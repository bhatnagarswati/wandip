<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Session;
use Validator;
use Mail;

class ContactController
{

    public function __construct()
    {

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {

        return view('front.contact-us');
    }

    /**
     * Send Contact Page query to Admin
     *
     * @return response
     */
    public function contactQuery(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "firstName" => "required|string",
            "lastName" => "required|string",
            "uEmail" => "required|email",
            "phoneNumber" => "required",
            "uQurey" => "required|string",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }    
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $uEmail = $request->input('uEmail');
        $phoneNumber = $request->input('phoneNumber');
        $uQuery = $request->input('uQurey');

        $userinfo = array(
           'firstName'=>  $firstName,
           'lastName'=>  $lastName,
           'uEmail'=>  $uEmail,
           'phoneNumber'=>  $phoneNumber,
           'uQuery'=>  $uQuery,
        );
        $send = Mail::send('emails.inquiry.contact', ['firstName' =>  $firstName,  'lastName'=>$lastName,  'uEmail'=>$uEmail,  'phoneNumber'=>$phoneNumber,  'uQuery'=>$uQuery],
            function ($m) use ($userinfo) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Adblue');
                $m->to($userinfo['uEmail'], "Admin")->subject('Adblue : Contact us query.');
            }
        );
       
        return redirect('/contact-us')->with('status', 'Thank you for contacting with us!! We will get back to you shortly.');;
    }

}

