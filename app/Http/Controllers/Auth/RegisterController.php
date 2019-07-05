<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Shop\Countries\Country;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Customers\Requests\RegisterCustomerRequest;
use App\Shop\Servicers\Repositories\Interfaces\ServicerRepositoryInterface;
use App\Shop\Servicers\Requests\RegisterServicerRequest;
use App\Shop\Servicers\Servicer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

require_once base_path() . '/vendor/twilio/sdk/Twilio/autoload.php';

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/accounts';

    private $customerRepo;
    private $servicerRepo;

    /**
     * Create a new controller instance.
     * @param CustomerRepositoryInterface $customerRepository
     * @param ServicerRepositoryInterface $servicerRepository
     */
    public function __construct(CustomerRepositoryInterface $customerRepository, ServicerRepositoryInterface $servicerRepository)
    {
        $this->middleware('guest');
        $this->customerRepo = $customerRepository;
        $this->servicerRepo = $servicerRepository;
    }

    public function showRegistrationForm()
    {

        $allcountries = Country::where('status', 1)->get();
        return view('auth.register', compact('allcountries'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Customer
     */
    protected function create(array $data)
    {

        return $this->customerRepo->createCustomer($data);
    }
    protected function servicer_create(array $data)
    {
        $email = $data['servicer_email'];
        $data['name'] = $data['servicer_name'];
        $data['email'] = $data['servicer_email'];
        $data['phone'] = $data['servicer_phone'];
        return $this->servicerRepo->createServicer($data);

    }

    /**
     * @param RegisterCustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterCustomerRequest $request)
    {

        $registerData = $request->except('_method', '_token');
        $phonecode = $registerData['countryCode'];
        $phoneno = $registerData['phone_number'];
        // Send Otp for confirmation
        $six_digit_random_otp = mt_rand(100000, 999999);
        $phone_number = $phonecode . $phoneno;
        $twillio_response = $this->sendConfirmationOtp($phone_number, $six_digit_random_otp);
        if (@$twillio_response->sid) {

            $registerData['twillio_code'] = $six_digit_random_otp;
            $registerData['countryCode'] = $phonecode;
            $registerData['twillio_status'] = 1;
            $registerData['role_id'] = 2;
            $customer = $this->create($registerData);
            $email = $request->input('email');
            $this->stripeCheckAndUpdate($email);
            Auth::login($customer);
            return redirect("/otpConfirm");

        } else {
            //$validator = "Phone number is not valid.";
            return redirect()->back()->withInput()->withErrors(array('servicer_phone' => $twillio_response));
        }


        
    }

    public function servicer_register(RegisterServicerRequest $request)
    {

        $registerData = $request->except('_method', '_token');
        $phonecode = $registerData['servicer_countryCode'];
        $phoneno = $registerData['servicer_phone'];

        // Send Otp for confirmation
        $six_digit_random_otp = mt_rand(100000, 999999);
        $phone_number = $phonecode . $phoneno;
        $twillio_response = $this->sendConfirmationOtp($phone_number, $six_digit_random_otp);
        if (@$twillio_response->sid) {

            $registerData['twillio_code'] = $six_digit_random_otp;
            $registerData['countryCode'] = $phonecode;
            $registerData['twillio_status'] = 1;
            $registerData['role_id'] = 3;

            $servicer = $this->servicer_create($registerData);
            $servicerId = $servicer->id;
            return redirect("/servicer/$servicerId/confirmotp");

        } else {
            //$validator = "Phone number is not valid.";
            return redirect()->back()->withInput()->withErrors(array('servicer_phone' => $twillio_response));
        }

    }

    public function stripeCheckAndUpdate($email = '')
    {

        $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $customerinfo = Customer::where(['email' => $email])->first();

        if (!empty($customerinfo->stripe_id)) {
            return (string) @$customerinfo->stripe_id;
        } else {
            $customer = \Stripe\Customer::create([
                'email' => @$email,
            ]);

            if ($customer['id']) {
                $customerinfo->stripe_id = $customer['id'];
                if ($customerinfo->save()) {
                    return (string) @$customer['id'];
                }
            }
        }

    }

    /**
     * OTP Confirmation

     * @param  user_id
     * @return response
     */

    public function sendConfirmationOtp($phone_number, $otpcode = "")
    {

        // Your Account SID and Auth Token from twilio.com/console
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $client = new Client($sid, $token);
        try {
            //code...
            $response = $client->messages->create(
                // the number you'd like to send the message to
                $phone_number,
                array(
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => env('TWILIO_NO'),
                    // the body of the text message you'd like to send
                    'body' => 'Your AdBlue verification code is:' . $otpcode,
                )
            );

        } catch (\Throwable $th) {
            //throw $th;
            $response = $th->getMessage();
        }

        return $response;

    }

    public function confirmotp(Request $request, $id)
    {
        $userinfo = Servicer::findOrFail($id);
        $userType = "servicer";
        return view('auth.confirm_otp', compact('userinfo', 'userType'));
    }
    public function otpConfirm()
    {

        if (Auth::user()) {
                $id = Auth::id();
                $userinfo = Customer::findOrFail($id);
                $userType = "customer";
                return view('auth.customer_confirm_otp', compact('userinfo', 'userType'));
        }else{

            return redirect('/');
        }
       
    }

    public function verifyOtp(Request $request)
    {

        $confirm_otp = trim($request->input('otp_code'));
        $userid = trim($request->input('uid'));
        $user_type = trim($request->input('u_type'));
        $response = [];

        if ($user_type == 'servicer') {
            $servicer = Servicer::where(['id' => $userid, 'twillio_status' => 1])->first();
            if ($servicer) {
                if ($servicer->twillio_code == $confirm_otp) {

                    $servicer->twillio_code = null;
                    $servicer->twillio_status = 0;
                    $servicer->save();
                    $response['status'] = true;
                    $response['message'] = 'Verified successfully';

                } else {
                    $response['status'] = false;
                    $response['message'] = 'Invalid code.';

                }
            } else {
                $response['status'] = false;
                $response['message'] = 'Oops!, User not valid.';
            }
        } else if ($user_type == 'customer') {

            $customer = Customer::where('id',  $userid)->first();
            if ($customer) {
                if ($customer->twillio_code == $confirm_otp) {

                    $customer->twillio_code = null;
                    $customer->twillio_status = 0;
                    $customer->save();
                    $response['status'] = true;
                    $response['message'] = 'Verified successfully';
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Invalid code.';
                }

            } else {
                $response['status'] = false;
                $response['message'] = 'Oops!,  User not valid.';
            }

        }

        return json_encode($response);

    }

    public function resendOtp(Request $request)
    {

        
        $userid = trim($request->input('uid'));
        $user_type = trim($request->input('u_type'));

        if ($user_type == 'servicer') {
            $servicer = Servicer::where('id',  $userid)->first();
            if ($servicer) {
                $six_digit_random_otp = mt_rand(100000, 999999);
                $phoneno = $servicer->countryCode . "" . $servicer->phone;
                $twillio_response = $this->sendConfirmationOtp($phoneno, $six_digit_random_otp);
                if (@$twillio_response->sid) {
                    $servicer->twillio_code = $six_digit_random_otp;
                    $servicer->twillio_status = 1;
                    $servicer->save();
                    $response['status'] = true;
                    $response['message'] = 'Otp sent successfully';

                } else {

                    $response['status'] = false;
                    $response['message'] = $twillio_response;
                }
            } else {
                $response['status'] = false;
                $response['message'] = 'Oops!,  User not valid.';
            }
        } else if ($user_type == 'customer') {
            $customer = Customer::where('id',  $userid)->first();
            if ($customer) {
                $six_digit_random_otp = mt_rand(100000, 999999);
                $phoneno = $customer->countryCode . "" . $customer->phone_number;
                $twillio_response = $this->sendConfirmationOtp($phoneno, $six_digit_random_otp);
                if (@$twillio_response->sid) {
                    $customer->twillio_code = $six_digit_random_otp;
                    $customer->twillio_status = 1;
                    $customer->save();
                    $response['status'] = true;
                    $response['message'] = 'Otp sent successfully';
                } else {
                    $response['status'] = false;
                    $response['message'] = $twillio_response;
                }

            } else {
                $response['status'] = false;
                $response['message'] = 'Oops!,  User not valid.';
            }

        }

        return json_encode($response);
    }


    public function signupSuccess(Request $request){

                return view('auth.servicer_success', compact('userinfo', 'userType'));
    }

    
    
}
