<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\Shop\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Orders\Order;
use App\Shop\Ratings\ProductReviews;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\Orders\Repositories\OrderRepository;
use App\Shop\Orders\Transformers\OrderTransformable;
use App\Shop\Provinces\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Shop\RouteRequests\RouteRequest;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Shop\Products\Product;
use App\Shop\Stores\Store;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;

class RatingController extends Controller
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

    use ProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

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
        ProductRepositoryInterface $productRepository,
        ProvinceRepositoryInterface $provinceRepository
    ) {
        $this->customerRepo = $customerRepository;
        $this->courierRepo = $courierRepository;
        $this->orderRepo = $orderRepository;
        $this->countryRepo = $countryRepository;
        $this->provinceRepo = $provinceRepository;
        $this->cityRepo = $cityRepository;
        $this->productRepo = $productRepository;
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();
            if (@$this->user->twillio_status == 1) {
                return redirect("/otpConfirm")->with('message', "Otp verification pending. Please verify your phone number.");
            }
            return $next($request);
        });

    }

    public function index($orderid="", $product_slug ="")
    {
        $customerId = auth()->user()->id;
        $order = Order::where(['id' =>$orderid, 'customer_id' => $customerId])->first();
        $product = $this->productRepo->findProductBySlug(['slug' => $product_slug]);
        if(!empty($order) && !empty($product)){
            return view('front.rating.reviews', ['product' => $product, 'orderId' => $orderid]);
        }else{
            return view('layouts.errors.404');
        }
    }
  
    public function submitReview($orderid = "", Request $request , $product_slug =""){

        // validate incoming request
        $validator = Validator::make($request->all(), [
            'rating' => 'required|string',
            'rating_title' => 'required|string',
            'user_review' => 'required|string'             
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }  
             
        $rating = $request->input('rating');
        $rating_title = $request->input('rating_title');
        $user_review = $request->input('user_review');

        $customerId = auth()->user()->id;
        $order = Order::where(['id' => $orderid, 'customer_id' => $customerId])->first();
        $product = $this->productRepo->findProductBySlug(['slug' => $product_slug]);

        if(!empty($order) && !empty($product)){
            $reviewData = array(
                'productId'=> $product->id,
                'customerId'=>  auth()->user()->id,
                'reviewTitle'=>  $rating_title,
                'reviewDescription'=>   $user_review,
                'customerRating'=>  $rating,
                'status'=>  1,
             );
             $res = ProductReviews::create($reviewData);
             if($res){
               // return redirect($orderid.'/ratings/'.$product_slug)->with('message', 'Thank you for your valuable feedback!!');
                return redirect('accounts/orders')->with('message', 'Thank you for your valuable feedback!!');
             }
        }else{
            return view('layouts.errors.404');
        }
        
        
    }

}
