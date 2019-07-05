<?php

namespace App\Http\Controllers\Api\v1\Driver;

use App\Http\Controllers\Controller;
use App\Shop\Drivers\Driver;
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

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile($type = 'api')
    {

        $driverInfo = Driver::findOrFail($this->userId);
        $driverInfo->driverPic = config('constants.driver_pull_path') . $driverInfo->driverPic;
        $driverInfo->idProof = config('constants.driver_id_proof_pull_path') . $driverInfo->idProof;
        unset($driverInfo->created_at);
        unset($driverInfo->updated_at);
        unset($driverInfo->verification_token);
        unset($driverInfo->deleted_at);
        unset($driverInfo->role_id);

        if ($type != "api") {
            return $driverInfo;

        } else {
            $this->success("Driver info.", $driverInfo);
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

        $driver = Driver::findOrFail($this->userId);
        if ($driver) {
            $this->validation($request->all(), [
                'driverId' => 'required',
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'contactNumber' => 'required|string',
                'address' => 'required|string',
                'licenceNumber' => 'required|string',
            ]);

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
            $driver->contactNumber = $request->input('contactNumber');
            $driver->address = $request->input('address');
            $driver->licenceNumber = $request->input('licenceNumber');

            $driver->save();

            $driverInfo = $this->getProfile($this->userId, 'info', $request);
            $this->success("Profile updated.", $driverInfo);
        } else {

            $this->success("No profile found.", null);
        }

    }

    public function updatePassword(Request $request)
    {

        $servicer = Driver::findOrFail($this->userId);
        $this->validation($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
        ]);

        $oldpassword = $request->input('old_password');
        if (!Hash::check(@$oldpassword, $servicer->password)) {
            $this->success("Wrong old password.", "");
        }

        if ($request->has('password') && !empty($request->input('password'))) {
            $servicer->password = Hash::make($request->input('password'));
        }
        $servicer->save();

        $this->success("Password changed successfully.", "");
    }

    public function updatePushNotification(Request $request)
    {

        $driver = Driver::findOrFail($this->userId);
        if ($driver) {
            $this->validation($request->all(), [
                'push_notify' => 'required',
            ]);

            $driver->pushNotification = $request->input('push_notify');
            $driver->save();

            $this->success("Push Notification setting changed successfully.", "");
        } else {
            $this->success("Driver not found.", "");
        }
    }

     

}
