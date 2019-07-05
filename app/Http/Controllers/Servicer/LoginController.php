<?php

namespace App\Http\Controllers\Servicer;

use App\Shop\Admins\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Shop\Servicers\Servicer;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/servicer';


    /**
     * Shows the admin login form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (auth()->guard('servicer')->check()) {
            return redirect()->route('servicer.dashboard');
        }

        return view('auth.servicer.login');
    }

    /**
     * Login the servicer
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
		
        if (auth()->guard('servicer')->attempt($details)) {

            $email = $details['email'];
            @$twilio_status  = $this->checkOtpstatus($email);
            if(@$twilio_status->twillio_status == 1){
                $servicerId = $twilio_status->id;
                return redirect("/servicer/$servicerId/confirmotp")->with('message', "Otp verification pending. Please verify your phone number.");
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
		
        return $this->sendFailedLoginResponse($request);
    }
    public function logout()
    {
        auth()->guard('servicer')->logout();
        return redirect('/servicer/login');
    }

    public function checkOtpstatus($email = '')
    {
      
        $servicerinfo = Servicer::where(['email' => strtolower($email)])->first();
        if (!empty($servicerinfo)) {
            return @$servicerinfo;
        }else{
            return 0;
        }  
    }
}
