<?php

namespace App\Http\Controllers\Admin\Drivers;

use App\Http\Controllers\Controller;
use App\Shop\Drivers\Driver;
use App\Shop\Stores\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
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
            $drivers = Driver::where('firstName', 'LIKE', "%$keyword%")
                ->orWhere('lastName', 'LIKE', "%$keyword%")
                ->orWhere('address', 'LIKE', "%$keyword%")
                ->orWhere('licenceNumber', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $drivers = Driver::with('providers')->latest()->paginate($perPage);
        }

        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return redirect('admin/drivers')->with('message', 'Opps not found!');
        //return view('admin.drivers.create');
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

            'driverEmail' => 'required|email|unique:customers,email|unique:servicers,email|unique:drivers,driverEmail',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'contactNumber' => 'required|string',
            'address' => 'required|string',
            'licenceNumber' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $driverPic = "";
        $idProof = "";
        if ($request->hasFile('driverPic')) {

            $file = $request->file('driverPic');
            $driverPic = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.driver_pic');
            $file->move($destinationPath, $driverPic);
        }
        if ($request->hasFile('idProof')) {
            $file_proof = $request->file('idProof');
            $idProof = str_random(10) . '.' . $file_proof->getClientOriginalExtension();
            //Upload File
            $idproof_destinationPath = config('constants.driver_idproof');
            $file_proof->move($idproof_destinationPath, $idProof);
        }

        $createData = array(
            'servicerId' => Auth::guard('employee')->user()->id,
            'driverEmail' => strtolower($request->input('driverEmail')),
            'password' => Hash::make($request->input('password')),
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'contactNumber' => $request->input('contactNumber'),
            'address' => addslashes($request->input('address')),
            'driverLat' => $request->input('driverLat'),
            'driverLong' => $request->input('driverLong'),
            'licenceNumber' => $request->input('licenceNumber'),
            'driverPic' => $driverPic,
            'idProof' => $idProof,
            'status' => $request->input('status'),
        );

        Driver::create($createData);
        return redirect('admin/drivers')->with('message', 'Driver added!');
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
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.show', compact('driver'));
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
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.edit', compact('driver'));
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
        $driver = Driver::findOrFail($id);
        if ($request->hasFile('driverPic')) {

            $file = $request->file('driverPic');
            $driverPic = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.driver_pic');
            $file->move($destinationPath, $driverPic);
            $driver->driverPic = $driverPic;
        }
        if ($request->hasFile('idProof')) {

            $proof_file = $request->file('idProof');
            $idProof = str_random(10) . '.' . $proof_file->getClientOriginalExtension();
            //Upload File
            $idproof_destinationPath = config('constants.driver_idproof');
            $proof_file->move($idproof_destinationPath, $idProof);
            $driver->idProof = $idProof;
        }
        // validate incoming request
        if (!empty($request->input('password'))) {

            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'confirmPassword' => 'required|same:password',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
 
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'contactNumber' => 'required|string',
            'address' => 'required|string',
            'licenceNumber' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        if (!empty($request->input('password'))) {
            $driver->password = Hash::make($request->input('password'));
        }
        $driver->firstName = $request->input('firstName');
        $driver->lastName = $request->input('lastName');
        $driver->contactNumber = $request->input('contactNumber');
        $driver->address = $request->input('address');
        $driver->driverLat = $request->input('driverLat');
        $driver->driverLong = $request->input('driverLong');
        $driver->status = $request->input('status');
        $driver->save();

        return redirect('admin/drivers/'.$id.'/edit')->with('message', 'Driver updated!');
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
        Driver::destroy($id);
        return redirect('admin/drivers')->with('message', 'Driver deleted!');
    }
}
