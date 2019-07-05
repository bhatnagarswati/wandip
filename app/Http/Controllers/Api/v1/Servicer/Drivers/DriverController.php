<?php

namespace App\Http\Controllers\Api\v1\Servicer\Drivers;

use App\Http\Controllers\Controller;
use App\v1\Drivers\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public $successStatus = 200;
    public $userId;
    public $user_type;

    public function __construct(Request $request)
    {

        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    public function allDrivers(Request $request)
    {

        // Pagination
        $per_page = 10;
        if ($request->input('page') == "") {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        $response['drivers'] = [];
        //Search data for contents
        $data = Driver::where('servicerId', $this->userId);
        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $data->where(function ($query) use ($keyword) {
                $query->where('firstName', 'LIKE', "%$keyword%")
                    ->orWhere('lastName', 'LIKE', "%$keyword%")
                    ->orWhere('address', 'LIKE', "%$keyword%");
            });
        }

        // All Drivers Count
        $drivers_count = $data->count();
        $data = $data->skip($skip)->take($take)->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $response['drivers'][] = [
                    'driverId' => $value->driverId,
                    'driverEmail' => $value->driverEmail,
                    'firstName' => (string) @$value->firstName,
                    'lastName' => (string) @$value->lastName,
                    'contactNumber' => (string) @$value->contactNumber,
                    'address' => $value->address,
                    'licenceNumber' => $value->licenceNumber != null ? $value->licenceNumber : "",
                    'driverPic' => config('constants.driver_pull_path') . $value->driverPic,
                    'idProof' => config('constants.driver_id_proof_pull_path') . $value->idProof,
                ];
            }
        }
        $response['drivers_count'] = $drivers_count;

        // Return Response array
        $this->success("All Drivers", $response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function listNewDriver(Request $request)
    {
       
        // validate incoming request
        $this->validation($request->all(), [

            'email' => 'required|email|unique:customers|unique:servicers|unique:drivers,driverEmail',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'driverLat' => 'required|string',
            'driverLong' => 'required|string',
            'contactNumber' => 'required|string',
            'address' => 'required|string',
            'licenceNumber' => 'required|string',
        ]);

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

            $proof_file = $request->file('idProof');
            $idProof = str_random(10) . '.' . $proof_file->getClientOriginalExtension();
            //Upload File
            $idproof_destinationPath = config('constants.driver_idproof');
            $proof_file->move($idproof_destinationPath, $idProof);
        }

        $createData = array(
            'servicerId' => $this->userId,
            'driverEmail' => strtolower($request->input('email')),
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
            'status' => 1,
        );

        $res = Driver::create($createData);
        $driverInfo = [];
        if ($res) {
            $driverInfo = $this->driverInfo($res->driverId, 'info', $request);
        }
        $this->success('Driver added!', $driverInfo);
    }

    /**
     *  Get Driver Info
     * @param type $driverId
     * @param type $type
     * @param Request $request
     * @return array
     */
    public function driverInfo($driverId = 0, $type = 'api', Request $request)
    {
        if ($type == 'api') {
            $this->validation($request->all(), [
                'driverId' => 'required',
            ]);
            $driverId = $request->input('driverId');

        }
        $driverInfo = [];
        $driverInfo = Driver::findOrFail($driverId);
        if ($driverInfo) {

            $driverInfo->driverPic = config('constants.driver_pull_path') . $driverInfo->driverPic;
            $driverInfo->idProof = config('constants.driver_id_proof_pull_path') . $driverInfo->idProof;
            unset($driverInfo->password);
            unset($driverInfo->servicerId);
            unset($driverInfo->deleted_at);
            unset($driverInfo->updated_at);
            unset($driverInfo->created_at);
        }

        if ($type == 'api') {
            $this->success('Driver Info', $driverInfo);
        } else {
            return $driverInfo;
        }

    }

    /**
     * Update Driver profile
     * @param Request $request
     */
    public function updateDriver(Request $request)
    {

        if (!empty($request->input('password'))) {
            $this->validation($request->all(), [
                'password' => 'required',
                'confirmPassword' => 'required|same:password',
            ]);
        }

        $this->validation($request->all(), [
            'driverId' => 'required',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'contactNumber' => 'required|string',
            'address' => 'required|string',
            'licenceNumber' => 'required|string',
        ]);

        $driverId = $request->input('driverId');
        $driver = Driver::findOrFail($driverId);

        if ($request->hasFile('driverPic')) {

            $file = $request->file('driverPic');
            $driverPic = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.driver_pic');
            $file->move($destinationPath, $driverPic);
            $driver->driverPic = $driverPic;

        }
        if ($request->hasFile('idProof')) {

            $proofFile = $request->file('idProof');
            $idProof = str_random(10) . '.' . $proofFile->getClientOriginalExtension();
            //Upload File
            $idProof_destinationPath = config('constants.driver_idproof');
            $proofFile->move($idProof_destinationPath, $idProof);
            $driver->idProof = $idProof;

        }

        $driver->firstName = $request->input('firstName');
        $driver->lastName = $request->input('lastName');

        if (!empty($request->input('password'))) {
            $driver->password = Hash::make($request->input('password'));
        }
        $driver->contactNumber = $request->input('contactNumber');
        $driver->address = $request->input('address');
        $driver->licenceNumber = $request->input('licenceNumber');

        $driver->save();

        $driverInfo = $this->driverInfo($driverId, 'info', $request);
        $this->success('Driver info updated!', $driverInfo);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteDriver(Request $request)
    {
        $driverId = $request->input('driverId');
        $this->validation($request->all(), [
            'driverId' => 'required',
        ]);
        Driver::destroy($driverId);
        $this->success('Driver deleted!', "");

    }

}
