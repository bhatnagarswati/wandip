<?php

namespace App\Http\Controllers\Api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Shop\Customers\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    public $successStatus = 200;
    public $userId;
    public $user_type;

    public function __construct(Request $request)
    {

        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile($type = 'api')
    {

        $customer = Customer::findOrFail($this->userId);
        $customer->loginType = $customer->loginType <> null ? $customer->loginType : "";
        $customer->social_id = $customer->social_id <> null ? $customer->social_id : "";
        $customer->profilePic = config('constants.customer_pull_path') . $customer->profilePic;
      
        unset($customer->created_at);
        unset($customer->updated_at);
        unset($customer->verification_token);
        unset($customer->deleted_at);
        unset($customer->role_id);
          
        unset($customer->card_brand);
        unset($customer->card_last_four);
        unset($customer->trial_ends_at);
 

        if ($type != "api") {
            return $customer;

        } else {
            $this->success("Customer info.", $customer);
        }
    }

    /**
     * @param UpdateCustomerRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {

         
        $this->validation($request->all(), [
            'name' => 'required|string|max:50',
            'phone' => 'required|max:12',
        ]);
        $customer = Customer::findOrFail($this->userId);
        if ($request->hasFile('profilePic')) {
            $file = $request->file('profilePic');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.customer_pic');
            $file->move($destinationPath, $fileName);
            $customer->profilePic = $fileName;
        }

        $customer->name = $request->input('name');
        $customer->phone_number = $request->input('phone');
        $customer->save();

        $customerProfile = $this->getProfile('info');
        $this->success("Profile updated.", $customerProfile);
    }
    /**
     * Update password
     * 
     */
    public function updatePassword(Request $request)
    {

        $this->validation($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
        ]);
        $customer = Customer::findOrFail($this->userId);

        $oldpassword = $request->input('old_password');
        if (!Hash::check(@$oldpassword, $customer->password)) {
            $this->error("Wrong old password.", "");
        }

        if ($request->has('password') && !empty($request->input('password'))) {
            $customer->password = Hash::make($request->input('password'));
        }
        $customer->save();

        $this->success("Password changed successfully.", "");
    }


    /**
     * On/Off push notifications status
     * 
     */
    public function upudatePushNotification(Request $request)
    {
        $this->validation($request->all(), [
            'push_notify' => 'required',
        ]);
        $customer = Customer::findOrFail($this->userId);

        $customer->pushNotification = $request->input('push_notify');
        $customer->save();

        $this->success("Push Notification setting changed successfully.", "");
    }

     

}
