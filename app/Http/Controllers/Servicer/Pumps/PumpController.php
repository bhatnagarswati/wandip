<?php

namespace App\Http\Controllers\Servicer\Pumps;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Shop\Pumps\Pump;
use App\Shop\Stores\Store;
use Illuminate\Http\Request;
use App\Shop\Products\Product;
use Lang;
use DB;
use Session;
use Config; 

class PumpController extends Controller
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
            $pumps = Pump::with('stores')->where('pumpTitle', 'LIKE', "%$keyword%")
                ->orWhere('storeId', 'LIKE', "%$keyword%")
                ->orWhere('pumpDescription', 'LIKE', "%$keyword%")
                ->orWhere('pumpAddress', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $pumps = Pump::with('stores')->where(['pumps.servicerId' => Auth::guard('servicer')->user()->id, 'pumps.languageType' => Config::get('app.locale')])->latest()->paginate($perPage);
        }
	  
        return view('servicer.pumps.index', compact('pumps'));
    }

    /**
     * Show the form for creating a new resource.
     *	
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $storeids = Pump::pluck('storeId');
        $stores = Store::where(['isActive' => 1, 'servicerId' => Auth::guard('servicer')->user()->id])->whereNotIn('id', $storeids)->get();
        $mass_units =  Product::MASS_UNIT;
        return view('servicer.pumps.create' , compact('stores', 'mass_units'));
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
           'storeId' => 'required|string',
           'pumpTitle' => 'required|string',
           'pumpAddress' => 'required|string',
           'pumpDescription' => 'required|string', 
           'pumpMassUnit' => 'required|string',
           'pumpPrice' => 'required',
       ]);
        
       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       }
       $fileName = "";
        if ($request->hasFile('pumpPic')) {

              $file = $request->file('pumpPic');
              $fileName = str_random(10).'.'.$file->getClientOriginalExtension();
              //Upload File
              $destinationPath = config('constants.pump_pic');
              $file->move($destinationPath, $fileName);
        }
         
       $createData = array(
            'storeId' => $request->input('storeId'),
            'servicerId' => Auth::guard('servicer')->user()->id,
            'pumpTitle' => $request->input('pumpTitle'),
            'pumpAddress' => $request->input('pumpAddress'),
            'pumpDescription' => $request->input('pumpDescription'),
            'pumpLat' => $request->input('locationLat'),
            'pumpLong' => $request->input('locationLong'),
            'pumpPrice' => $request->input('pumpPrice'),
            'pumpMassUnit' => $request->input('pumpMassUnit'),
            'pumpPic' => $fileName,
            'status' => $request->input('status'),
            'languageType' =>  Config::get('app.locale'),
        );
        
        Pump::create($createData);
        return redirect('servicer/pumps')->with('flash_message', 'Pump added!');
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
        $pump = Pump::findOrFail($id);
        return view('servicer.pumps.show', compact('pump'));
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
       $pump = Pump::findOrFail($id);
       $stores = Store::where(['isActive' => 1, 'servicerId' => Auth::guard('servicer')->user()->id])->get();
       $mass_units =  Product::MASS_UNIT;
        return view('servicer.pumps.edit', compact('pump', 'stores','mass_units'));
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
        
       
        $pump = Pump::findOrFail($id);
       
        // validate incoming request
       $validator = Validator::make($request->all(), [
           'storeId' => 'required|string',
           'pumpTitle' => 'required|string',
           'pumpDescription' => 'required|string',
           'pumpAddress' => 'required|string',
           'pumpMassUnit' => 'required|string',
       ]);
        
       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       }

        $fileName = "";
        if ($request->hasFile('pumpPic')) {

              $file = $request->file('pumpPic');
              $fileName = str_random(10).'.'.$file->getClientOriginalExtension();
              //Upload File
              $destinationPath = config('constants.pump_pic');
              $file->move($destinationPath, $fileName);
              $pump->pumpPic =  $fileName;
        }

        $pump->storeId = $request->input('storeId');
        $pump->pumpTitle =  $request->input('pumpTitle');
        $pump->pumpDescription =  $request->input('pumpDescription');
        $pump->pumpAddress =  $request->input('pumpAddress');
        $pump->pumpMassUnit =  $request->input('pumpMassUnit');
        $pump->pumpLat =  $request->input('locationLat');
        $pump->pumpLong =  $request->input('locationLong');
        $pump->pumpPrice =  $request->input('pumpPrice');
        $pump->status =  $request->input('status');
        $pump->languageType =  Config::get('app.locale'); 
        $pump->save();

        return redirect('servicer/pumps')->with('flash_message', 'Pump updated!');
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
        Pump::destroy($id);

        return redirect('servicer/pumps')->with('flash_message', 'Pump deleted!');
    }
}
