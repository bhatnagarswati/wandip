<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\Shop\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Orders\Order;
use App\Shop\Servicers\Servicer;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\Orders\Repositories\OrderRepository;
use App\Shop\Orders\Transformers\OrderTransformable;
use App\Shop\Provinces\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Shop\RouteRequests\RouteRequest;
use Auth;
use App\Shop\Ratings\ProductReviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;

class AccountsController extends Controller
{
    use OrderTransformable;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @var CourierRepositoryInterface
     */
    private $courierRepo;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepo;

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepo;

    /**
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepo;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    private $user;

    /**
     * AccountsController constructor.
     *
     * @param CourierRepositoryInterface $courierRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param CountryRepositoryInterface  $countryRepository
     * @param CityRepositoryInterface     $cityRepository
     * @param ProvinceRepositoryInterface $provinceRepository
     */
    public function __construct(
        CourierRepositoryInterface $courierRepository,
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        CountryRepositoryInterface $countryRepository,
        CityRepositoryInterface $cityRepository,
        ProvinceRepositoryInterface $provinceRepository
    ) {
        $this->customerRepo = $customerRepository;
        $this->courierRepo = $courierRepository;
        $this->orderRepo = $orderRepository;
        $this->countryRepo = $countryRepository;
        $this->provinceRepo = $provinceRepository;
        $this->cityRepo = $cityRepository;
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();
            if (@$this->user->twillio_status == 1) {
                return redirect("/otpConfirm")->with('message', "Otp verification pending. Please verify your phone number.");
            }
            return $next($request);
        });

    }

    public function index()
    {
        $customer = $this->customerRepo->findCustomerById(auth()->user()->id);

        $customerRepo = new CustomerRepository($customer);
        $orders = $customerRepo->findOrders(['*'], 'created_at');

        $orders->transform(function (Order $order) {
            return $this->transformOrder($order);
        });

        $addresses = $customerRepo->findAddresses();

        return view('front.account.accounts', [
            'customer' => $customer,
            'countries' => $this->countryRepo->listCountries(),
        ]);
    }

    public function addresses()
    {
        $customer = $this->customerRepo->findCustomerById(auth()->user()->id);
        $customerRepo = new CustomerRepository($customer);
        $orders = $customerRepo->findOrders(['*'], 'created_at');

        $orders->transform(function (Order $order) {
            return $this->transformOrder($order);
        });

        $addresses = $customerRepo->findAddresses();

        return view('front.account.addresses', [
            'customer' => $customer,
            'countries' => $this->countryRepo->listCountries(),
            'cities' => $this->cityRepo->listCities(),
            'provinces' => $this->provinceRepo->listProvinces(),
            'orders' => $this->customerRepo->paginateArrayResults($orders->toArray(), 15),
            'addresses' => $addresses,
        ]);
    }
    public function orders()
    {
        $customer = $this->customerRepo->findCustomerById(auth()->user()->id);
        $customerRepo = new CustomerRepository($customer);
        $orders = $customerRepo->findOrders(['*'], 'created_at');

        $orders->transform(function (Order $order) {
            
            $orderRepo = new OrderRepository($order);
            $serviceProviderId = $order->servicerId;
            $servicerInfo = Servicer::where('id' , $serviceProviderId )->first();
            if($servicerInfo){
                $order->service_provider =  $servicerInfo->name;
            }

            $items = $orderRepo->listOrderedProducts();
            $allitems =  [];
            if(!empty($items)){
                foreach($items as $item){
                    $item->rating = 0;
                    $reviews = ProductReviews::where(['customerId' => auth()->user()->id , 'productId' => $item->id])->first();
                    if(!is_null($reviews)){
                        $item->rating = $reviews->customerRating;
                    }
                    $allitems = $item;

                }

            }
            $order->products = $allitems;
            return $this->transformOrder($order);
        });

        $addresses = $customerRepo->findAddresses();

        return view('front.account.orders', [
            'customer' => $customer,
            'orders' => $this->customerRepo->paginateArrayResults($orders->toArray(), 15),
            'addresses' => $addresses,
        ]);
    }
    public function dashboardRequests()
    {

        $customer = $this->customerRepo->findCustomerById(auth()->user()->id);

        $perPage = 15;
        $pending_requests = RouteRequest::with('driverinfo')->where(['customerId' => auth()->user()->id, 'markedStatus' => 0])->latest()->paginate($perPage);
        $delivered_requests = RouteRequest::with('driverinfo')->where(['customerId' => auth()->user()->id, 'markedStatus' => 1])->latest()->paginate($perPage);

        return view('front.account.requests', [
            'customer' => $customer,
            'pending_requests' => $pending_requests,
            'delivered_requests' => $delivered_requests,
        ]);
    }

    public function payments()
    {
        $customer = $this->customerRepo->findCustomerById(auth()->user()->id);
        $customerRepo = new CustomerRepository($customer);
        $addresses = $customerRepo->findAddresses();
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripe_customer = [];
        $stripe_customer = \Stripe\Customer::retrieve("$customer->stripe_id");
        return view('front.account.payments', [
            'customer' => $customer,
            'stripe_customer' => $stripe_customer,
            'addresses' => $addresses,
        ]);
    }

    public function saveCards(Request $request)
    {

        $customerinfo = $this->customerRepo->findCustomerById(auth()->user()->id);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = \Stripe\Customer::retrieve("$customerinfo->stripe_id");

        // validate incoming request
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string',
            'exp_month' => 'required|string',
            'exp_year' => 'required|string',
            'card_holdername' => 'required|string',
            'card_cvc' => 'required|string',
        ]);

        $card_number = $request->input('card_number');
        $exp_month = $request->input('exp_month');
        $exp_year = $request->input('exp_year');
        $card_holdername = $request->input('card_holdername');
        $card_cvc = $request->input('card_cvc');
        $existed_cards = [];
        if ($customer->sources) {
            if (!empty($customer->sources->data)) {
                $existed_cards = array_column($customer->sources->data, 'fingerprint');
            }
        }
        try {
            $response = \Stripe\Token::create(array(
                "card" => array(
                    "number" => $card_number,
                    "exp_month" => $exp_month,
                    "exp_year" => $exp_year,
                    "cvc" => $card_cvc,
                    "name" => $card_holdername,
                )));

            $response_fingerprint = $response->card->fingerprint;
            if (!empty($existed_cards)) {
                if (in_array($response_fingerprint, $existed_cards)) {
                    return redirect('accounts/payments')->withInput($request->all())->with('message', 'Card already exist!');
                } else {
                    $new_cardtoken = $response->id;
                    $res = $customer->sources->create(['source' => $new_cardtoken]);
                    return redirect('accounts/payments')->with('message', 'Card added!');
                }
            } else {
                $new_cardtoken = $response->id;
                $res = $customer->sources->create(['source' => $new_cardtoken]);
                return redirect('accounts/payments')->with('message', 'Card added!');
            }

        } catch (\Stripe\Error\Base $e) {
            // Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err = $body['error'];
            return redirect('accounts/payments')->withInput($request->all())->with('message', $err['message']);

        }
    }

    public function deleteCards(Request $request)
    {

        $cardid = $request->input('cardid');
        $customerinfo = $this->customerRepo->findCustomerById(auth()->user()->id);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = \Stripe\Customer::retrieve("$customerinfo->stripe_id");
        try {
            $customer->sources->retrieve($cardid)->delete();
            return redirect('accounts/payments')->with('message', 'Card deleted!');
        } catch (\Stripe\Error\Base $e) {
            // Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err = $body['error'];
            return redirect('accounts/payments')->withInput($request->all())->with('message', $err['message']);
        }

    }

    // Update user information

    public function updateUserInfo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'phone_number' => 'required',
            'countryCode' => 'required',
        ]);
        
        $customer = $this->customerRepo->findCustomerById(auth()->user()->id);
        if ($request->hasFile('userProfilepic')) {


            $pictoDelete = config('constants.customer_pic')."/".$customer->profilePic;
            if(file_exists($pictoDelete)){
                File::delete($pictoDelete);
            }
            $file = $request->file('userProfilepic');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.customer_pic');
            $file->move($destinationPath, $fileName);
            $customer->profilePic = $fileName;
        }


       
        $customer->name = $request->input('customer_name');
        $customer->countryCode = $request->input('countryCode');
        $customer->phone_number = $request->input('phone_number');
        $customer->save();

        return redirect("/accounts")->with('message', "Profile updated successfully!!");

    }

 
}
