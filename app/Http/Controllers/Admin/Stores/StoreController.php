<?php

namespace App\Http\Controllers\Admin\Stores;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Shop\Stores\Store;
use App\Shop\Servicers\Servicer;
use Illuminate\Http\Request;
use Lang;
use Session;
use Config;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $stores = Store::where('storeTitle', 'LIKE', "%$keyword%")
                ->orWhere('storeDescription', 'LIKE', "%$keyword%")
                ->orWhere('storeLocation', 'LIKE', "%$keyword%")
                ->orWhere('storePic', 'LIKE', "%$keyword%")
                ->orWhere('servicerId', 'LIKE', "%$keyword%")
                ->orWhere('isActive', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $stores = Store::where('languageType', Config::get('app.locale'))->latest()->paginate($perPage);
        }
	 
        return view('admin.stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

        $servicers = Servicer::where(['status' => 1])->get();
        return view('admin.stores.create' ,  compact('servicers'));
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
        
        $requestData = $request->all();
 
        // validate incoming request
       $validator = Validator::make($request->all(), [
           'storeTitle' => 'required|string',
           'storeDescription' => 'required|string',
           'storeLocation' => 'required|string',
       ]);
        
       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       }
       
        $fileName = "";
        if ($request->hasFile('storePic')) {

              $file = $request->file('storePic');
              $fileName = str_random(10).'.'.$file->getClientOriginalExtension();
              //Upload File
              $destinationPath = config('constants.store_pic');
              $file->move($destinationPath, $fileName);
        }     
       
       $adminid = Auth::guard('employee')->user()->id; 
       $createData = array(
            'storeTitle' => $request->input('storeTitle'),
            'storeDescription' => $request->input('storeDescription'),
            'storeLocation' => $request->input('storeLocation'),
            'storeLat' => $request->input('locationLat'),
            'storeLong' => $request->input('locationLong'),
            'storePic' => $fileName,
            'servicerId' => $request->input('servicerId'),
            'isActive' => $request->input('isActive'),
            'languageType' =>  Config::get('app.locale'),
        );
        
        Store::create($createData);
        return redirect('admin/stores')->with('message', 'Store added!');
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
        $store = Store::findOrFail($id);

        return view('admin.stores.show', compact('store'));
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
        $store = Store::findOrFail($id);
        $servicers = Servicer::where(['status' => 1])->get();
        return view('admin.stores.edit', compact('store', 'servicers'));
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
        
        $store = Store::findOrFail($id);


        
        // validate incoming request
       $validator = Validator::make($request->all(), [
           'storeTitle' => 'required|string',
           'storeDescription' => 'required|string',
           'storeLocation' => 'required|string',
       ]);
        
       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       }
       $fileName = "";
        if ($request->hasFile('storePic')) {

              $file = $request->file('storePic');
              $fileName = str_random(10).'.'.$file->getClientOriginalExtension();
              //Upload File
              $destinationPath = config('constants.store_pic');
              $file->move($destinationPath, $fileName);
              $store->storePic =  $fileName;
        }
        $store->storeTitle =  $request->input('storeTitle');
        $store->storeDescription =  $request->input('storeDescription');
        $store->storeLocation =  $request->input('storeLocation');
        $store->storeLat =  $request->input('locationLat');
        $store->storeLong =  $request->input('locationLong');
        $store->isActive =  $request->input('isActive');
        $store->servicerId =  $request->input('servicerId');
        $store->languageType =  Config::get('app.locale');
        $store->save();

        return redirect('admin/stores')->with('message', 'Store updated!');
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
        Store::destroy($id);

        return redirect('admin/stores')->with('message', 'Store deleted!');
    }
}
