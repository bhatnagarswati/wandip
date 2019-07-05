<?php

namespace App\Http\Controllers\Servicer;

use App\Http\Controllers\Controller;
use App\Shop\Admins\Requests\UpdateServicerRequest;
use App\Shop\Servicers\Repositories\Interfaces\ServicerRepositoryInterface;
use App\Shop\Servicers\Repositories\ServicerRepository;
use App\Shop\Servicers\Servicer;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ServicerController extends Controller
{

    /**
     * ServicerController constructor.
     *
     * @param ServicerRepositoryInterface $servicerRepository
     */
    public function __construct()
    {

    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile($id = null)
    {
        if ($id == null) {
            return view('layouts.errors.404');
        }
        $employee = Servicer::findOrFail($id);
        return view('servicer.employees.profile', ['employee' => $employee]);
    }

    /**
     * @param UpdateServicerRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request, $id)
    {

        $servicer = Servicer::findOrFail($id);

        if ($servicer->email != $request->input('email')) {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:servicers',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'phone' => 'required|max:12',
            /*'password' => 'required|min:6',
        'password_confirmation' => 'required_with:password|same:password|min:6'*/
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('profilePic')) {
            $file = $request->file('profilePic');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.service_provider_pic');
            $file->move($destinationPath, $fileName);

            $servicer->profilePic = $fileName;
        }

        if ($request->has('password') && !empty($request->input('password'))) {
            $servicer->password = Hash::make($request->input('password'));

        }

        $servicer->name = $request->input('name');
        $servicer->email = $request->input('email');
        $servicer->phone = $request->input('phone');
        $servicer->save();

        return redirect('servicer/profile/' . $id)->with('message', 'Profile updated!');
    }

    public function stripeConnect(Request $request)
    {

        $servicerId = Auth::guard('servicer')->user()->id;
        if ($request->input('state') != $servicerId) {
            return view('layouts.errors.404');
        }

        //dd( $request->all());
        // Fetch the user's token from Stripe
        $stripe_connectcode = $request->input('code');
        $token_request_body = array(
            'client_secret' => env('STRIPE_SECRET'),
            'grant_type' => 'authorization_code',
            'client_id' => env('STRIPE_CLIENTID'),
            'code' => $stripe_connectcode,
        );

        $req = curl_init(env('STRIPE_TOKEN_URI'));
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POST, true);
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
        $resp = json_decode(curl_exec($req), true);

        $servicer = Servicer::findOrFail($servicerId);

        if (!isset($resp['error'])) {

            // Save connect id into database
            $connectId = $resp['stripe_user_id'];
            $servicer->stripeConnectId = $connectId;
            $servicer->save();
            return redirect('servicer/dashboard')->with('message', 'Congratulations!! Your account has been connected successfully!');

        } else {
            return redirect('servicer/dashboard')->with('error', 'Error occured, Please try again.');
        }

    }
    public function stripeCancel(Request $request)
    {

        $servicerId = Auth::guard('servicer')->user()->id;
        return redirect('servicer/dashboard')->with('message', 'Your account connect request has been successfully!');
    }
}
