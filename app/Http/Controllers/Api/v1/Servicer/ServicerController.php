<?php

namespace App\Http\Controllers\Api\v1\Servicer;


use App\v1\Servicers\Repositories\ServicerRepository;
use App\v1\Servicers\Repositories\Interfaces\ServicerRepositoryInterface;
use App\v1\Roles\Repositories\RoleRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Shop\Servicers\Servicer;
use App\Http\Requests;
use Illuminate\Http\Request;

class ServicerController extends Controller
{
    
 	public $successStatus = 200;
    public $userId;
    public $user_type;
    
    public function __construct(Request $request){
	
		$this->userId = $request->header('userId') ? $request->header('userId') : "";
		$this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }
 

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile($type = 'api' )
    {

        $serviceProvider = Servicer::findOrFail($this->userId);
        $serviceProvider->profilePic = config('constants.service_provider_pull_path'). $serviceProvider->profilePic;

        unset($serviceProvider->created_at);
        unset($serviceProvider->updated_at);
        unset($serviceProvider->verification_token);
        unset($serviceProvider->deleted_at);
        unset($serviceProvider->role_id);

        if($type != "api")
        {
			return $serviceProvider; 

		}else{
			$this->success("Service provider info.",  $serviceProvider);        
		}
    }

    /**
     * @param UpdateServicerRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {


        $servicer = Servicer::findOrFail($this->userId);
      
        $this->validation($request->all(),[
             'name' => 'required|string|max:50',
             'phone' => 'required|max:12',
        ]);
 
         if($request->hasFile('profilePic'))
         {
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
          //$servicer->email = $request->input('email');
          $servicer->phone = $request->input('phone');
          $servicer->save();

          $serviceproviderProfile =  $this->getProfile('info');
          $this->success("Profile updated.",  $serviceproviderProfile);
    }

    

    public function updatePassword(Request $request)
    {

        $servicer = Servicer::findOrFail($this->userId);
        $this->validation($request->all(),[
             'old_password' => 'required',
             'password' => 'required|min:6',
             'password_confirmation' => 'required_with:password|same:password|min:6'
         ]);	
 
        $oldpassword  = $request->input('old_password');
        if (!Hash::check(@$oldpassword,  $servicer->password)) {
	        $this->error("Wrong old password.",  ""); 
	    }
           
	    if ($request->has('password') && !empty($request->input('password'))) {
	            $servicer->password = Hash::make($request->input('password'));
	    }
        $servicer->save();

        $this->success("Password changed successfully.",  "");        
    }


    public function updatePushNotification(Request $request)
    {

        $servicer = Servicer::findOrFail($this->userId);
        $this->validation($request->all(),[
             'push_notify' => 'required',
        ]);	
 
        $servicer->pushNotification = $request->input('push_notify');
        $servicer->save();

        $this->success("Push Notification setting changed successfully.",  "");        
    }
 



}
