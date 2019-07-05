<?php

namespace App\Http\Controllers\Admin\Servicers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Shop\Servicers\Servicer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lang;
use Session;

class ServicerController extends Controller {

   public function __construct()
   {
      
   }

   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\View\View
    */
   public function index(Request $request)
   {
      $keyword = $request->get('search');
      $perPage = 25;

      if(!empty($keyword))
      {
	 $servicers = Servicer::where('name', 'LIKE', "%$keyword%")
			 ->orWhere('email', 'LIKE', "%$keyword%")
			 ->orWhere('phone', 'LIKE', "%$keyword%")
			 ->orWhere('password', 'LIKE', "%$keyword%")
			 ->orWhere('role_id', 'LIKE', "%$keyword%")
			 ->orWhere('profilePic', 'LIKE', "%$keyword%")
			 ->orWhere('deviceType', 'LIKE', "%$keyword%")
			 ->orWhere('deviceToken', 'LIKE', "%$keyword%")
			 ->orWhere('remember_token', 'LIKE', "%$keyword%")
			 ->orWhere('status', 'LIKE', "%$keyword%")
			 ->latest()->paginate($perPage);
      } else
      {
	 $servicers = Servicer::latest()->paginate($perPage);
      }
      $this->data['servicers'] = $servicers;


      return view('admin.servicers.index', $this->data);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\View\View
    */
   public function create()
   {
      return view('admin.servicers.create');
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    *
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    */
   public function store(Request $request)
   {

      $fileName = "";
      if($request->hasFile('profilePic'))
      {

      	 $file = $request->file('profilePic');
      	 $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
      	 //Upload File
      	 $destinationPath = config('constants.service_provider_pic');
      	 $file->move($destinationPath, $fileName);
      }
      // validate incoming request
      $validator = Validator::make($request->all(), [
  		  'email' => 'required|email|unique:servicers',
  		  'name' => 'required|string|max:50',
  		  'phone' => 'required|max:12',
  		  'password' => 'required|min:6',
  		  'password_confirmation' => 'required_with:password|same:password|min:6'
      ]);

      if($validator->fails())
      {
	       return redirect()->back()->withErrors($validator)->withInput();
      }

      $requestData = $request->all();
      $createData = array(
	  'name' => $request->input('name'),
	  'email' => $request->input('email'),
	  'phone' => $request->input('phone'),
	  'password' => Hash::make($request->input('password')),
	  'role_id' => 2,
	  'profilePic' => $fileName,
	  'status' => $request->input('isActive'),
      );
      Servicer::create($createData);
      return redirect('admin/servicers')->with('flash_message', 'Servicer added!');
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    *
    * @return \Illuminate\View\View
    */
   public function show($id)
   {
      $servicer = Servicer::findOrFail($id);

      return view('admin.servicers.show', compact('servicer'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    *
    * @return \Illuminate\View\View
    */
   public function edit($id)
   {
      $servicer = Servicer::findOrFail($id);
      return view('admin.servicers.edit', compact('servicer'));
   }

   /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param  int  $id
    *
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    */
   public function update(Request $request, $id)
   {

      $requestData = $request->all();
      $servicer = Servicer::findOrFail($id);
      if($request->hasFile('profilePic'))
      {
      	 $file = $request->file('profilePic');
      	 $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
      	 //Upload File
      	 $destinationPath = config('constants.service_provider_pic');
      	 $file->move($destinationPath, $fileName);

      	 $servicer->profilePic = $fileName;
      }

      if(!empty($request->input('password')))
      {
    	 // validate incoming request
    	 $validator = Validator::make($request->all(), [
    		 'email' => 'required|email',
		     'name' => 'required|string|max:50',
		     'phone' => 'required|max:12',
		     'password' => 'required|min:6',
		     'password_confirmation' => 'required_with:password|same:password|min:6'
	     ]);
	      $servicer->password = bcrypt($request->input('password'));
      } else
      {
    	 // validate incoming request
    	 $validator = Validator::make($request->all(), [
    		     'email' => 'required|email',
    		     'name' => 'required|string|max:50',
    		     'phone' => 'required|max:12',
    	 ]);
      }

      if($validator->fails())
      {
	       return redirect()->back()->withErrors($validator)->withInput();
      }

      $servicer->name = $request->input('name');
      $servicer->email = $request->input('email');
      $servicer->phone = $request->input('phone');
      $servicer->status = $request->input('isActive');
      $servicer->save();

      return redirect('admin/servicers')->with('flash_message', 'Servicer updated!');
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    *
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    */
   public function destroy($id)
   {
      Servicer::destroy($id);
      return redirect('admin/servicers')->with('flash_message', 'Servicer deleted!');
   }

}
