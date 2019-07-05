<?php

namespace App\Http\Controllers\Servicer\Stores;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Shop\Stores\Store;
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
		     ->Where('servicerId', '=', Auth::guard('employee')->user()->id)
		     ->orWhere('storeDescription', 'LIKE', "%$keyword%")
		     ->orWhere('storeLocation', 'LIKE', "%$keyword%")
		     ->orWhere('storePic', 'LIKE', "%$keyword%")
		     ->orWhere('isActive', 'LIKE', "%$keyword%")
		     ->latest()->paginate($perPage);
        } else {
            $stores = Store::where(['servicerId' => Auth::guard('servicer')->user()->id, 'languageType' => Config::get('app.locale') ])->latest()->paginate($perPage);
        }

        return view('servicer.stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('servicer.stores.create');
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
        $createData = array(
            'storeTitle' => $request->input('storeTitle'),
            'storeDescription' => $request->input('storeDescription'),
            'storeLocation' => $request->input('storeLocation'),
            'storeLat' => $request->input('locationLat'),
            'storeLong' => $request->input('locationLong'),
            'storePic' => $fileName,
            'servicerId' => Auth::guard('servicer')->user()->id,
            'isActive' => $request->input('isActive'),
            'languageType' => Config::get('app.locale'),
        );
        
        Store::create($createData);
        return redirect('servicer/stores')->with('flash_message', 'Store added!');
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

        return view('servicer.stores.show', compact('store'));
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

        return view('servicer.stores.edit', compact('store'));
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


        $fileName = "";
        if ($request->hasFile('storePic')) {

              $file = $request->file('storePic');
              $fileName = str_random(10).'.'.$file->getClientOriginalExtension();
              //Upload File
              $destinationPath = config('constants.store_pic');
              $file->move($destinationPath, $fileName);
              $store->storePic =  $fileName;
        }
        // validate incoming request
       $validator = Validator::make($request->all(), [
           'storeTitle' => 'required|string',
           'storeDescription' => 'required|string',
           'storeLocation' => 'required|string',
       ]);
        
       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       }

        $store->storeTitle =  $request->input('storeTitle');
        $store->storeDescription =  $request->input('storeDescription');
        $store->storeLocation =  $request->input('storeLocation');
        $store->storeLat =  $request->input('locationLat');
        $store->storeLong =  $request->input('locationLong');
        $store->isActive =  $request->input('isActive');
        $store->languageType =   Config::get('app.locale');
        
        $store->save();

        return redirect('servicer/stores')->with('flash_message', 'Store updated!');
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
        return redirect('servicer/stores')->with('flash_message', 'Store deleted!');
    }
}
