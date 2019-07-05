<?php

namespace App\Http\Controllers\Api\v1\Servicer\RouteRequests;

use App\Http\Controllers\Controller;
use App\Shop\RouteRequests\RouteRequest;
use Illuminate\Http\Request;

class RouteRequestController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function getAllRequestsOnRoute(Request $request)
    {

        $this->validation($request->all(), [
            'routeId' => 'required',
            'routeType' => 'required',
        ]);

        $routeId = $request->input('routeId');
        $routeType = $request->input('routeType');

        // Pagination
        $per_page = 10;
        if ($request->input('page') == "") {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        // 0 for Pending Results
        if ($routeType == 0) {

            $response['pending_requests'] = [];
            // Pending Requests

            $data = RouteRequest::with(['driverinfo', 'servicerinfo', 'customerinfo'])->where(['servicerId' => $this->userId, 'routeId' => $routeId, 'status' => 1, 'markedStatus' => 0]);
            if (!empty($request->input('search_key'))) {
                $keyword = $request->input('search_key');
                $data->where(function ($query) use ($keyword) {
                    $query->where('requestedAddress', 'LIKE', "%$keyword%")
                        ->Where('requestedQty', '=', $this->userId);
                });
            }
            // Pending Request Count
            $response['pending_count'] = $data->skip($skip)->take($take)->count();
            $data = $data->orderBy('id', 'desc')->skip($skip)->take($take)->get();

            // Pending Route Requests
            if (!empty($data)) {
                foreach ($data as $route) {

                    $routeinfo['routeId'] = $route->routeId;
                    $routeinfo['routeRequestId'] = $route->id;
                    $routeinfo['driverId'] = $route->driverId;
                    $routeinfo['servicerId'] = $route->servicerId;
                    $routeinfo['customerId'] = $route->customerId;
                    $routeinfo['driverName'] = $route->driverinfo->firstName;
                    $routeinfo['servicerName'] = $route->servicerinfo->name;
                    $routeinfo['customerName'] = $route->customerinfo->name;
                    $routeinfo['requestedAddress'] = $route->requestedAddress;
                    $routeinfo['requestedQty'] = $route->requestedQty;
                    $routeinfo['requestedMassUnit'] = $route->requestedMassUnit;
                    $routeinfo['requestedUnitPrice'] = $route->requestedUnitPrice;
                    $routeinfo['status'] = $route->status;
                    $routeinfo['markedStatus '] = $route->markedStatus;
                    $routeinfo['requestMarkedDate '] = $route->requestedDate;
                    $routeinfo['languageType '] = $route->languageType;
                    $routeinfo['created_at '] = $route->created_at;
                    $response['pending_requests'][] = $routeinfo;
                }
            }

        }

        // 1 For delivered
        if ($routeType == 1) {

            $response['delivered_requests'] = [];

            // Delivered Requests
            $delivered_data = RouteRequest::with(['driverinfo', 'servicerinfo', 'customerinfo'])->where(['servicerId' => $this->userId, 'routeId' => $routeId, 'status' => 1, 'markedStatus' => 1]);
            if (!empty($request->input('search_key'))) {
                $keyword = $request->input('search_key');
                $delivered_data->where(function ($query) use ($keyword) {
                    $query->where('requestedAddress', 'LIKE', "%$keyword%")
                        ->Where('requestedQty', '=', $this->userId);
                });
            }
            // Delivered Count
            $response['delivered_count'] = $delivered_data->skip($skip)->take($take)->count();
            $delivered_data = $delivered_data->orderBy('id', 'desc')->skip($skip)->take($take)->get();

            // Delivered Route Requests
            if (!empty($delivered_data)) {
                foreach ($delivered_data as $route) {

                    $routeinfo['routeId'] = $route->routeId;
                    $routeinfo['routeRequestId'] = $route->id;
                    $routeinfo['driverId'] = $route->driverId;
                    $routeinfo['servicerId'] = $route->servicerId;
                    $routeinfo['customerId'] = $route->customerId;
                    $routeinfo['driverName'] = $route->driverinfo->firstName;
                    $routeinfo['servicerName'] = $route->servicerinfo->name;
                    $routeinfo['customerName'] = $route->customerinfo->name;
                    $routeinfo['requestedAddress'] = $route->requestedAddress;
                    $routeinfo['requestedQty'] = $route->requestedQty;
                    $routeinfo['requestedMassUnit'] = $route->requestedMassUnit;
                    $routeinfo['requestedUnitPrice'] = $route->requestedUnitPrice;
                    $routeinfo['status'] = $route->status;
                    $routeinfo['markedStatus '] = $route->markedStatus;
                    $routeinfo['requestMarkedDate '] = $route->requestedDate;
                    $routeinfo['languageType '] = $route->languageType;
                    $routeinfo['created_at '] = $route->created_at;
                    $response['delivered_requests'][] = $routeinfo;

                }
            }

        }

        // Return Response array
        $this->success("All Requests on Route", $response);

    }

    public function markDelivered(Request $request)
    {

        $this->validation($request->all(), [
            'requestId' => 'required',
            'driverId' => 'required',
        ]);
        $requestId = $request->input('requestId');
        $driverId = $request->input('driverId');
        $request_updated = RouteRequest::where(['id' => $requestId, 'servicerId' => $this->userId, 'driverId' => $driverId])->firstOrFail();
        if ($request_updated) {

            $request_updated->markedStatus = 1;
            $request_updated->requestedDate = date('Y-m-d H:m:s');
            $request_updated->save();
            $this->success("Request marked delivered successfully.", "");

        } else {
            $this->error("Request not found.", "");
        }

    }
}
