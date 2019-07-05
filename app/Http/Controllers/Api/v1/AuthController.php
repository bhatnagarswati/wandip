<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Shop\Customers\Customer;
use App\Shop\Drivers\Driver;
use App\Shop\Servicers\Servicer;
use App\v1\Token;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Input;
use Mail;
use Twilio\Rest\Client;
use URL;

require_once base_path() . '/vendor/twilio/sdk/Twilio/autoload.php';

class AuthController extends Controller
{

    public $successStatus = 200;
    public $unauthorizedStatus = 401;
    public $user_type = "";
    private $apiToken;

    public function __construct(Request $request)
    {
        // Unique Token
        $this->apiToken = uniqid(base64_encode(str_random(20)));
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
        $this->userId = $request->header('userId') ? $request->header('userId') : "";
    }

    /**
     * login Api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $login_type = $request->input('login_type');
        if ($login_type == 'facebook' || $login_type == 'gmail') {
            $this->socailLogin($request);

        }

        $this->validation(
            $request->all(),
            [
                "email" => "required|email",
                "password" => "required",
                "login_type" => "required",
                "deviceType" => "required",
                "deviceToken" => "required",
                "userLat" => "required",
                "userLong" => "required",
            ]
        );

        if ($this->user_type == 'servicer') {

            // Service provider Login  Process
            $username = strtolower($request->input('email'));
            $password = $request->input('password');
            $userinfo = $userinfo = Servicer::select(
                'id as userId',
                'name',
                'email',
                'password',
                'countryCode',
                'phone',
                'profilePic',
                'deviceToken',
                'status',
                'twillio_code',
                'twillio_status',
                'quickBlockId',
                'pushNotification'
            )->where(['email' => $username])->first();

            $dbpass = @$userinfo->password;
            if (!empty($userinfo) && Hash::check(@$password, $dbpass) && $userinfo->status == 1) {

                $token = Token::where(['user_id' => $userinfo->userId, 'user_type' => $this->user_type])->first();
                if (empty($token)) {
                    $token = new Token;
                }

                $expire_date_time = date("Y-m-d H:i:s", strtotime("+1 hours"));
                $token->user_id = $userinfo->userId;
                $token->user_type = $this->user_type;
                $token->access_token = $this->apiToken;
                $token->token_status = 1;
                $token->expires_in = $expire_date_time;
                $token->save();

                $userinfo->deviceType = $request->input('deviceType');
                $userinfo->deviceToken = $request->input('deviceToken');
                $userinfo->save();
                $userinfo->profilePic = Config::get('constants.service_provider_pull_path') . $userinfo->profilePic;
                $userinfo->deviceType = $userinfo->deviceType != null ? $userinfo->deviceType : "";
                $userinfo->deviceToken = $userinfo->deviceToken != null ? $userinfo->deviceToken : "";
                $userinfo->twillio_status = $userinfo->twillio_status != null ? $userinfo->twillio_status : "";
                $userinfo->twillio_code = $userinfo->twillio_code != null ? $userinfo->twillio_code : "";
                $userinfo->quickBlockId = $userinfo->quickBlockId != null ? $userinfo->quickBlockId : "";
                
                // Check Otp verification
                if (!empty($userinfo->twillio_status) && $userinfo->twillio_status == 1) {
                    $this->success("Verification pending yet.", $userinfo, $this->successStatus, $this->apiToken);
                }

                $this->success("Login Successfuly.", $userinfo, $this->successStatus, $this->apiToken);
            } else {
                if (Hash::check(@$password, $dbpass) && $userinfo->status == 0) {
                    $this->error("Account verification is in processing.");
                } else {
                    $this->error("Invalid login details.");
                }
            }
        } else if ($this->user_type == 'driver') {

            // Driver Login Process
            $username = strtolower($request->input('email'));
            $password = $request->input('password');
            $userinfo = $userinfo = Driver::select(
                'driverId',
                'firstName',
                'lastName',
                'driverEmail',
                'password',
                'contactNumber',
                'address',
                'licenceNumber',
                'driverPic',
                'idProof',
                'driverLat',
                'driverLong',
                'deviceType',
                'quickBlockId',
                'deviceToken',
                'status'
            )->where(['driverEmail' => $username])->first();
            $dbpass = @$userinfo->password;
            if (!empty($userinfo) && Hash::check(@$password, $dbpass) && $userinfo->status == 1) {

                $token = Token::where(['user_id' => $userinfo->driverId, 'user_type' => $this->user_type])->first();
                if (empty($token)) {
                    $token = new Token;
                }

                $expire_date_time = date("Y-m-d H:i:s", strtotime("+1 hours"));
                $token->user_id = $userinfo->driverId;
                $token->user_type = $this->user_type;
                $token->access_token = $this->apiToken;
                $token->token_status = 1;
                $token->expires_in = $expire_date_time;
                $token->save();

                $userinfo->deviceType = $request->input('deviceType');
                $userinfo->deviceToken = $request->input('deviceToken');
                $userinfo->driverLat = $request->input('userLat');
                $userinfo->driverLong = $request->input('userLong');
                $userinfo->save();

                $userinfo->driverPic = Config::get('constants.driver_pull_path') . $userinfo->driverPic;
                $userinfo->idProof = Config::get('constants.driver_id_proof_pull_path') . $userinfo->idProof;
                $userinfo->deviceType = $userinfo->deviceType != null ? $userinfo->deviceType : "";
                $userinfo->deviceToken = $userinfo->deviceToken != null ? $userinfo->deviceToken : "";
                $userinfo->driverLat = $userinfo->driverLat != null ? $userinfo->driverLat : "";
                $userinfo->driverLong = $userinfo->driverLong != null ? $userinfo->driverLong : "";
                $userinfo->quickBlockId = $userinfo->quickBlockId != null ? $userinfo->quickBlockId : "";

                $this->success("Login Successfuly.", $userinfo, $this->successStatus, $this->apiToken);

            } else {
                $this->error("Invalid login details.");
            }

        } else if ($this->user_type == 'customer') {

            // Customer Login Process
            $username = strtolower($request->input('email'));
            $password = $request->input('password');
            $userinfo = $userinfo = Customer::select(
                'id as userId',
                'name',
                'email',
                'password',
                'countryCode',
                'phone_number',
                'profilePic',
                'deviceType',
                'deviceToken',
                'pushNotification',
                'customerLat',
                'customerLong',
                'twillio_code',
                'twillio_status',
                'stripe_id',
                'quickBlockId',
                'status'
            )->where(['email' => $username])->first();
            $dbpass = @$userinfo->password;
            if (!empty($userinfo) && Hash::check(@$password, $dbpass) && $userinfo->status == 1) {

                $token = Token::where(['user_id' => $userinfo->userId, 'user_type' => $this->user_type])->first();
                if (empty($token)) {
                    $token = new Token;
                }

                $expire_date_time = date("Y-m-d H:i:s", strtotime("+1 hours"));
                $token->user_id = $userinfo->userId;
                $token->user_type = $this->user_type;
                $token->access_token = $this->apiToken;
                $token->token_status = 1;
                $token->expires_in = $expire_date_time;
                $token->save();

                $userinfo->deviceType = $request->input('deviceType');
                $userinfo->deviceToken = $request->input('deviceToken');
                $userinfo->customerLat = $request->input('userLat');
                $userinfo->customerLong = $request->input('userLong');
                $userinfo->save();

                // Create Customer on stripe
                $reg_email = $userinfo->email;
                $stripeId = $this->stripeCheckAndUpdate($reg_email);
                if ($stripeId) {
                    $userinfo->stripe_id = $stripeId;
                }

                $userinfo->profilePic = Config::get('constants.customer_pull_path') . $userinfo->profilePic;
                $userinfo->deviceType = $userinfo->deviceType != null ? $userinfo->deviceType : "";
                $userinfo->deviceToken = $userinfo->deviceToken != null ? $userinfo->deviceToken : "";
                $userinfo->customerLat = $userinfo->customerLat != null ? $userinfo->customerLat : "";
                $userinfo->customerLong = $userinfo->customerLong != null ? $userinfo->customerLong : "";
                $userinfo->twillio_status = $userinfo->twillio_status != null ? $userinfo->twillio_status : "";
                $userinfo->twillio_code = $userinfo->twillio_code != null ? $userinfo->twillio_code : "";
                $userinfo->quickBlockId = $userinfo->quickBlockId != null ? $userinfo->quickBlockId : "";

                // Check Otp verification
                if (!empty($userinfo->twillio_status) && $userinfo->twillio_status == 1) {
                    $this->success("Verification pending yet.", $userinfo, $this->successStatus, $this->apiToken);
                }

                $this->success("Login Successfuly.", $userinfo, $this->successStatus, $this->apiToken);

            } else {
                $this->error("Invalid login details.");
            }

        } else {
            $this->error('Unauthorised.');
        }
    }

    /**
     * Customer Social login , Sign up
     *
     * @return response
     */
    public function socailLogin(Request $request)
    {

        $this->validation(
            $request->all(),
            [
                "email" => "required|email",
                "social_id" => "required",
                "login_type" => "required",
                "deviceType" => "required",
                "deviceToken" => "required",
                "userLat" => "required",
                "userLong" => "required",
            ]
        );

        $username = strtolower($request->input('email'));
        $userinfo = $userinfo = Customer::select(
            'id as userId',
            'name',
            'email',
            'password',
            'countryCode',
            'phone_number',
            'profilePic',
            'deviceType',
            'deviceToken',
            'pushNotification',
            'customerLat',
            'customerLong',
            'stripe_id',
            'status'
        )->where(['email' => $username])->first();

        if (!empty($userinfo)) {

            //$response['stripe_customer_id'] = @$this->stripeCheckAndUpdate(@$get_data->email);

            $token = Token::where(['user_id' => $userinfo->userId, 'user_type' => $this->user_type])->first();
            if (empty($token)) {
                $token = new Token;
            }
            $expire_date_time = date("Y-m-d H:i:s", strtotime("+1 hours"));
            $token->user_id = $userinfo->userId;
            $token->user_type = $this->user_type;
            $token->access_token = $this->apiToken;
            $token->token_status = 1;
            $token->expires_in = $expire_date_time;
            $token->save();

            $userinfo->deviceType = $request->input('deviceType');
            $userinfo->deviceToken = $request->input('deviceToken');
            $userinfo->loginType = $request->input('login_type');
            $userinfo->customerLat = $request->input('userLat');
            $userinfo->customerLong = $request->input('userLong');
            $userinfo->save();

            // Create Customer on stripe
            $reg_email = $userinfo->email;
            $stripeId = $this->stripeCheckAndUpdate($reg_email);
            if ($stripeId) {
                $userinfo->stripe_id = $stripeId;
            }

            $userinfo->deviceType = $userinfo->deviceType != null ? $userinfo->deviceType : "";
            $userinfo->deviceToken = $userinfo->deviceToken != null ? $userinfo->deviceToken : "";
            $userinfo->customerLat = $userinfo->customerLat != null ? $userinfo->customerLat : "";
            $userinfo->customerLong = $userinfo->customerLong != null ? $userinfo->customerLong : "";

            $this->success("Login successfuly.", $userinfo, $this->successStatus, $this->apiToken);

        } else {

            $this->validation($request->all(), [
                "email" => "required|email|unique:customers|unique:servicers|unique:drivers,driverEmail",
            ]);

            $input = $request->all();
            $signup = new Customer;
            $signup->name = $input['name'];
            $signup->email = strtolower($input['email']); // Exceptional case when email not cmmoing from social login. User need to update there email when login.
            $signup->social_id = $input['social_id'];
            $signup->profilePic = $input['profilePic'] != null ? $input['profilePic'] : "";
            $signup->phone_number = $input['phone'] != null ? $input['phone'] : "";
            $signup->role_id = 2;
            $signup->status = 1;
            $signup->deviceType = $input['deviceType'];
            $signup->deviceToken = $input['deviceToken'];
            $signup->save();

            // Create Customer on stripe
            $reg_email = $signup->email;
            $stripeId = $this->stripeCheckAndUpdate($reg_email);
            if ($stripeId) {
                $signup->stripe_id = $stripeId;
            }

            // Genrate Token
            $token = new Token;
            $expire_date_time = date("Y-m-d H:i:s", strtotime("7 days"));
            $token->user_id = $signup->id;
            $token->user_type = $this->user_type;
            $token->access_token = $this->apiToken;
            $token->token_status = 1;
            $token->expires_in = $expire_date_time;
            $token->save();

            $signup->userId = $signup->id;

            unset($signup->id);
            unset($signup->role_id);
            unset($signup->created_at);

            $this->success(
                'Login Successfully.',
                $signup,
                $this->successStatus,
                $this->apiToken
            );

        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {

        $input = $request->all();
        $fileName = "";
        $fullFilepath = "";

        $user_type = $this->user_type;
        if ($user_type == 'servicer') {

            $this->validation(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:servicers|unique:customers|unique:drivers,driverEmail',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password',
                    'phone' => 'required',
                    'countryCode' => 'required',
                    'deviceType' => 'required',
                    'deviceToken' => 'required',
                    'login_type' => 'required',
                ]
            );

            // Send Otp for confirmation
            $six_digit_random_otp = mt_rand(100000, 999999);
            $phoneno = $input['countryCode'] . $input['phone'];
            $twillio_response = $this->sendConfirmationOtp($phoneno, $six_digit_random_otp);
            if (@$twillio_response->sid) {

                if ($request->hasFile('profilePic')) {
                    $file = $request->file('profilePic');
                    $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
                    //Upload File
                    $destinationPath = config('constants.service_provider_pic');
                    $file->move($destinationPath, $fileName);
                    $fullFilepath = config('constants.service_provider_pull_path') . $fileName;
                }

                $signup = new Servicer;
                $signup->name = $input['name'];
                $signup->email = strtolower($input['email']);
                $signup->password = bcrypt($input['password']);
                $signup->countryCode = $input['countryCode'];
                $signup->phone = $input['phone'];
                $signup->role_id = 3;
                $signup->status = 0;
                $signup->deviceType = $input['deviceType'];
                $signup->deviceToken = $input['deviceToken'];
                $signup->profilePic = $fileName;
                $signup->twillio_code = $six_digit_random_otp;
                $signup->twillio_status = 1;
                $signup->save();

            } else {
                $this->error('Otp not sent.', $twillio_response);
            }

            // Genrate Token
            $token = new Token;
            $expire_date_time = date("Y-m-d H:i:s", strtotime("7 days"));
            $token->user_id = $signup->id;
            $token->user_type = $user_type;
            $token->access_token = $this->apiToken;
            $token->token_status = 1;
            $token->expires_in = $expire_date_time;
            $token->save();
            $signup->userId = $signup->id;
            $signup->profilePic = $fullFilepath;

            unset($signup->id);
            unset($signup->role_id);
            unset($signup->created_at);
            unset($signup->created_at);

            $this->success(
                'Register Successfully.',
                $signup,
                $this->successStatus,
                $this->apiToken
            );
        } else if ($user_type == 'customer') {

            $login_type = $request->input('login_type');
            if ($login_type == 'facebook' || $login_type == 'gmail') {
                $this->socailLogin($request);

            }

            $this->validation(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:customers|unique:servicers|unique:drivers,driverEmail',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password',
                    'phone' => 'required',
                    'countryCode' => 'required',
                    'deviceType' => 'required',
                    'deviceToken' => 'required',
                    'login_type' => 'required',
                ]
            );

            // Send Otp for confirmation
            $six_digit_random_otp = mt_rand(100000, 999999);
            $phoneno = $input['countryCode'] . $input['phone'];
            $twillio_response = $this->sendConfirmationOtp($phoneno, $six_digit_random_otp);

            if (@$twillio_response->sid) {

                if ($request->hasFile('profilePic')) {
                    $file = $request->file('profilePic');
                    $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
                    //Upload File
                    $destinationPath = config('constants.customer_pic');
                    $file->move($destinationPath, $fileName);
                    $fullFilepath = config('constants.customer_pull_path') . $fileName;
                }

                $signup = new Customer;
                $signup->name = $input['name'];
                $signup->email = strtolower($input['email']);
                $signup->password = bcrypt($input['password']);
                $signup->phone_number = $input['phone'];
                $signup->countryCode = $input['countryCode'];
                $signup->role_id = 2;
                $signup->status = 1;
                $signup->deviceType = $input['deviceType'];
                $signup->deviceToken = $input['deviceToken'];
                $signup->profilePic = $fileName;
                $signup->twillio_code = $six_digit_random_otp;
                $signup->twillio_status = 1;
                $signup->save();

            } else {
                $this->error('Otp not sent.', $twillio_response);
            }

            // Create Customer on stripe
            $reg_email = $signup->email;
            $stripeId = $this->stripeCheckAndUpdate($reg_email);
            if ($stripeId) {
                $signup->stripe_id = $stripeId;
            }

            // Genrate Token
            $token = new Token;
            $expire_date_time = date("Y-m-d H:i:s", strtotime("7 days"));
            $token->user_id = $signup->id;
            $token->user_type = $user_type;
            $token->access_token = $this->apiToken;
            $token->token_status = 1;
            $token->expires_in = $expire_date_time;
            $token->save();
            $signup->userId = $signup->id;
            $signup->profilePic = $fullFilepath;

            unset($signup->id);
            unset($signup->role_id);
            unset($signup->created_at);
            unset($signup->created_at);

            $this->success(
                'Register Successfully. Confirmation otp sent for confirmation.',
                $signup,
                $this->successStatus,
                $this->apiToken
            );

        } else {
            $this->error('User Type not valid.');
        }
    }

    /**
     * Forgot password
     *
     * @param  email
     * @return response
     */
    public function forgotPassword(Request $request)
    {
        $this->validation($request->all(), [
            "email" => "required",
        ]);

        $user_type = $this->user_type;
        if ($user_type == 'servicer') {
            $userinfo = Servicer::where('email', strtolower($request->input('email')))->first();
            if ($userinfo) {
                $pool = 'abcdefghijklmnopqrstuvwxyz0123456789';
                $verify_token = sha1(substr(str_shuffle(str_repeat($pool, 8)), 0, 8));
                $userinfo->verification_token = (string) $verify_token;
                if ($userinfo->save()) {
                    $pass_url = url('/auth/reset_password?u=' . $userinfo->id . '&t=' . $verify_token . '&ut=' . $user_type);
                    $send = Mail::send(
                        'emails.forgot.forgot_password',
                        ['email' => $userinfo->email, 'username' => $userinfo->name, 'pass_url' => $pass_url],
                        function ($m) use ($userinfo) {
                            $m->from(env('MAIL_FROM_ADDRESS'), 'AdBlue');

                            $m->to($userinfo->email, $userinfo->name)->subject('Adblue : Reset Password');
                        }
                    );
                    $this->success(
                        "Verification email has been sent. Please check your inbox.",
                        "",
                        $this->successStatus
                    );
                } else {
                    $this->error("Please try again.", "", $this->successStatus);
                }
            } else {
                $this->error("Email does not exist. Please enter valid email.", "", $this->successStatus);
            }
        } else if ($user_type == 'customer') {
            $userinfo = Customer::where('email', strtolower($request->input('email')))->first();
            if ($userinfo) {
                $pool = 'abcdefghijklmnopqrstuvwxyz0123456789';
                $verify_token = sha1(substr(str_shuffle(str_repeat($pool, 8)), 0, 8));
                $userinfo->verification_token = (string) $verify_token;
                if ($userinfo->save()) {
                    $pass_url = url('/auth/reset_password?u=' . $userinfo->id . '&t=' . $verify_token . '&ut=' . $user_type);
                    $send = Mail::send(
                        'emails.forgot.forgot_password',
                        ['email' => $userinfo->email, 'username' => $userinfo->name, 'pass_url' => $pass_url],
                        function ($m) use ($userinfo) {
                            $m->from(env('MAIL_FROM_ADDRESS'), 'AdBlue');

                            $m->to($userinfo->email, $userinfo->name)->subject('Adblue : Reset Password');
                        }
                    );
                    $this->success(
                        "Verification email has been sent. Please check your inbox.",
                        "",
                        $this->successStatus
                    );
                } else {
                    $this->error("Please try again.", "", $this->successStatus);
                }
            } else {
                $this->error("Email does not exist. Please enter valid email.", "", $this->successStatus);
            }
        } else {
            $this->error("User type not valid.", "");
        }
    }

    /**
     * Logout
     *
     * @param  user_id
     * @return response
     */
    public function logout()
    {
        $user_type = $this->user_type;
        if ($user_type == 'servicer') {
            $servicer = Servicer::where('id', $this->userId)->first();
            if ($servicer) {
                $servicer->deviceType = '';
                $servicer->deviceToken = '';
                if ($servicer->save()) {
                    $token = Token::where(['user_id' => $this->userId, 'user_type' => $this->user_type])->first();
                    if ($token) {
                        $token->token_status = 0;
                        $token->save();
                    }
                    $this->success('Logout successfully.', '');
                } else {
                    $this->error('Something went wrong.');
                }
            } else {
                $this->error('User does not exists.');
            }
        } else if ($user_type == 'driver') {

            $driver = Driver::where('driverId', $this->userId)->first();
            if ($driver) {
                $driver->deviceType = '';
                $driver->deviceToken = '';
                if ($driver->save()) {
                    $token = Token::where(['user_id' => $this->userId, 'user_type' => $this->user_type])->first();
                    if ($token) {
                        $token->token_status = 0;
                        $token->save();
                    }
                    $this->success('Logout successfully.', '');
                } else {
                    $this->error('Something went wrong.');
                }
            } else {
                $this->error('User does not exists.');
            }

        } else if ($user_type == 'customer') {

            $customer = Customer::where('id', $this->userId)->first();
            if ($customer) {
                $customer->deviceType = '';
                $customer->deviceToken = '';
                if ($customer->save()) {
                    $token = Token::where(['user_id' => $this->userId, 'user_type' => $this->user_type])->first();
                    if ($token) {
                        $token->token_status = 0;
                        $token->save();
                    }
                    $this->success('Logout successfully.', '');
                } else {
                    $this->error('Something went wrong.');
                }
            } else {
                $this->error('User does not exists.');
            }

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

    /***
     * Verify OTP
     *
     */

    public function verifyOtp(Request $request)
    {

        $this->validation(
            $request->all(),
            [
                'confirm_otp' => 'required',
            ]
        );

        $confirm_otp = trim($request->input('confirm_otp'));
        $user_type = $this->user_type;
        if ($user_type == 'servicer') {
            $servicer = Servicer::where('id', $this->userId)->first();
            if ($servicer) {
                if ($servicer->twillio_code == $confirm_otp) {

                    $servicer->twillio_code = null;
                    $servicer->twillio_status = 0;
                    $servicer->save();
                    $this->success('Verified successfully.', '');

                } else {
                    $this->error("Invalid code.");
                }
            } else {
                $this->error('User does not exists.');
            }
        } else if ($user_type == 'customer') {

            $customer = Customer::where('id', $this->userId)->first();
            if ($customer) {
                if ($customer->twillio_code == $confirm_otp) {

                    $customer->twillio_code = null;
                    $customer->twillio_status = 0;
                    $customer->save();
                    $this->success('Verified successfully.', '');
                } else {
                    $this->error("Invalid code.");
                }

            } else {
                $this->error('User does not exists.');
            }

        }

    }

    public function resendOtp()
    {

        $user_type = $this->user_type;
        if ($user_type == 'servicer') {
            $servicer = Servicer::where('id', $this->userId)->first();
            if ($servicer) {

                $six_digit_random_otp = mt_rand(100000, 999999);
                $phoneno = $servicer->countryCode . "" . $servicer->phone;
                $twillio_response = $this->sendConfirmationOtp($phoneno, $six_digit_random_otp);
                if (@$twillio_response->sid) {
                    $servicer->twillio_code = $six_digit_random_otp;
                    $servicer->twillio_status = 1;
                    $servicer->save();
                    $this->success('Otp sent successfully.', '');
                } else {
                    $this->error(" Otp not sent.", $twillio_response);
                }
            } else {
                $this->error('User does not exists.');
            }
        } else if ($user_type == 'customer') {
            $customer = Customer::where('id', $this->userId)->first();
            if ($customer) {
                $six_digit_random_otp = mt_rand(100000, 999999);
                $phoneno = $customer->countryCode . "" . $customer->phone_number;
                $twillio_response = $this->sendConfirmationOtp($phoneno, $six_digit_random_otp);
                if (@$twillio_response->sid) {
                    $customer->twillio_code = $six_digit_random_otp;
                    $customer->twillio_status = 1;
                    $customer->save();
                    $this->success('Otp sent successfully.', '');
                } else {
                    $this->error(" Otp not sent.", $twillio_response);
                }

            } else {
                $this->error('User does not exists.');
            }

        }

    }

    public function updateQuickBlockId(Request $request)
    {

        $this->validation($request->all(), [
            'quickBlockId' => 'required',
        ]);

        $user_type = $this->user_type;
        if ($user_type == 'servicer') {
            $servicer = Servicer::where('id', $this->userId)->first();
            if ($servicer) {
                $servicer->quickBlockId = $request->input('quickBlockId');
                $servicer->save();
                $this->success('Quick blocks id updated successfully.', '');
            } else {
                $this->error('User does not exists.');
            }
        } else if ($user_type == 'customer') {
            $customer = Customer::where('id', $this->userId)->first();
            if ($customer) {
                $customer->quickBlockId = $request->input('quickBlockId');
                $customer->save();
                $this->success('Quick blocks id updated successfully.', '');
            } else {
                $this->error('User does not exists.');
            }

        } else if ($user_type == 'driver') {
            $driver = Driver::where('driverId', $this->userId)->first();
            if ($driver) {
                $driver->quickBlockId = $request->input('quickBlockId');
                $driver->save();
                $this->success('Quick blocks id updated successfully.', '');
            } else {
                $this->error('User does not exists.');
            }
        } else {
            $this->error('Not a valid request.');
        }
    }

}
