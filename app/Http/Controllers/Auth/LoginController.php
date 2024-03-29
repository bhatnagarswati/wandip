<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Shop\Admins\Requests\LoginRequest;
use App\Shop\Customers\Customer;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/accounts';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login the admin
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {

        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $details = $request->only('email', 'password');
        $details['status'] = 1;
        if (auth()->attempt($details)) {
            $email = $details['email'];
            $this->stripeCheckAndUpdate($email);
            @$twilio_status  = $this->checkOtpstatus($email);
            if(@$twilio_status == 1){
                return redirect("/otpConfirm")->with('message', "Otp verification pending. Please verify your phone number.");
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
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
    public function checkOtpstatus($email = '')
    {
      
        $customerinfo = Customer::where(['email' => strtolower($email)])->first();
        if (!empty($customerinfo)) {
            return @$customerinfo->twillio_status;
        }else{
            return 0;
        }  
    }
}
