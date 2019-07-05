<?php

namespace App\Http\Controllers\Api\v1\Servicer;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Shop\Servicers\Servicer;
use Validator;
use Auth;
use Lang;
use DB;
use Session;

class CommonController extends Controller
{

 	public $successStatus = 200;
    public $userId;
    public $user_type;
    
    public function __construct(Request $request){
	
		$this->userId = $request->header('userId') ? $request->header('userId') : "";
		$this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    


}