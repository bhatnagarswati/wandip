<?php

namespace App\Http\Controllers\Api\v1\Customer\Routers;

use App\Http\Controllers\Controller;
use App\v1\Drivers\Driver;
use App\v1\RouteRequests\RouteRequest;
use App\v1\Routers\Router;
use App\v1\Servicers\Servicer;
use DB;
use Illuminate\Http\Request;

class RouteController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    /**
     * Routes Listing
     *
     *
     */

    public function customerAllRoutes(Request $request)
    {

        $this->validation($request->all(), [
            'languageType' => 'required',
            'userLat' => 'required',
            'userLong' => 'required',

        ]);

        // Pagination
        $per_page = 10;
        if ($request->input('page') == "") {
            $start = 0;
            $limit = $per_page;
        } else {
            $start = $per_page * $request->input('page');
            $limit = ((int) @$request->input('page') + 1) * 10;
        }

        $response['routes'] = [];

        $languageType = $request->input('languageType');
        $userLatitude = $request->input('userLat');
        $userLongitude = $request->input('userLong');

        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $routeIds = DB::table('routers')
                ->join('router_informations', 'routers.id', '=', 'router_informations.routerId')
                ->join('drivers', 'routers.driverId', '=', 'drivers.driverId')
                ->join('servicers', 'routers.servicerId', '=', 'servicers.id')
                ->where('router_informations.location', 'LIKE', "%$keyword%")
                ->orWhere('router_informations.city', 'LIKE', "%$keyword%")
                ->orWhere('drivers.firstName', 'LIKE', "%$keyword%")
                ->orWhere('drivers.lastName', 'LIKE', "%$keyword%")
                ->orWhere('servicers.name', 'LIKE', "%$keyword%")
                ->distinct('routerId')->value('router_informations.routerId');
            $ser_sql = " ";
            if (!empty($routeIds)) {
                $ser_sql = " AND r.id IN ('$routeIds') ";
            }

            $qry = "SELECT  r.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((ri.locationLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((ri.locationLat*pi()/180)) * cos(((" . $userLongitude . "- ri.locationLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM routers r INNER JOIN router_informations ri ON r.id = ri.routerId INNER JOIN servicers s ON  s.id  = r.servicerId WHERE r.status = 1 AND r.languageType = '$languageType'  $ser_sql  GROUP BY r.id ORDER BY distance ASC , r.deliveryDate DESC LIMIT $start, $limit";

            $routes = DB::select($qry);

            $qry_count = "SELECT  r.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((ri.locationLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((ri.locationLat*pi()/180)) * cos(((" . $userLongitude . "- ri.locationLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM routers r INNER JOIN router_informations ri ON r.id = ri.routerId INNER JOIN servicers s ON  s.id  = r.servicerId WHERE r.status = 1 AND r.languageType = '$languageType'  $ser_sql  GROUP BY r.id ORDER BY distance ASC , r.deliveryDate DESC";
            $routes_count = DB::select($qry_count);

        } else {

            $qry = "SELECT  r.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((ri.locationLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((ri.locationLat*pi()/180)) * cos(((" . $userLongitude . "- ri.locationLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM routers r INNER JOIN router_informations ri ON r.id = ri.routerId INNER JOIN servicers s ON  s.id  = r.servicerId WHERE r.status = 1 AND r.languageType = '$languageType' GROUP BY r.id ORDER BY distance ASC , r.deliveryDate DESC LIMIT $start, $limit";

            $routes = DB::select($qry);

            $qry_count = "SELECT  r.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((ri.locationLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((ri.locationLat*pi()/180)) * cos(((" . $userLongitude . "- ri.locationLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM routers r INNER JOIN router_informations ri ON r.id = ri.routerId INNER JOIN servicers s ON  s.id  = r.servicerId WHERE r.status = 1 AND r.languageType = '$languageType' GROUP BY r.id ORDER BY distance ASC , r.deliveryDate DESC";
            $routes_count = DB::select($qry_count);

        }

        if (!empty($routes)) {

            foreach ($routes as $route) {
                $routeInfo = $this->getRoute($route->id);
                $response['routes'][] = $routeInfo;
            }
        }

        $response['routes_count'] = count($routes_count);
        // Return Response array
        $this->success("All Routes", $response);
    }

    public function getRouteRequestCount($routeId = 0)
    {

        $request_count = RouteRequest::where(['routeId' => $routeId])->count();
        return $request_count;

    }

    public function getRoute($routeId = 0)
    {

        $routeinfo = [];
        $routeInfo = Router::where('status', 1)->with(['routeInfo', 'driver', 'servicer'])->findOrFail($routeId);


        $customer_requests = RouteRequest::with('routerInfo')->where(['routeId' => $routeId, "customerId" => $this->userId])->first();
        $requestLocation = "";
        if(@$customer_requests){
            $requestLocation = @$customer_requests->routerInfo->location;
        }


        // Get Remaining Oil status
        $volumeRemaining = $this->getConsumed($routeId);

        if ($routeInfo) {

            $locationInfo = [];
            foreach ($routeInfo->routeInfo as $info) {
                $info->destinationStatus = 0;
                $info->destinationMarkedInfo = date('Y-m-d H:m:s');
                $markedStatus = $this->checkRouteMarkStatus($info->id);
                if ($markedStatus) {
                    $info->destinationStatus = $markedStatus->markedStatus;
                    $info->destinationMarkedInfo = $markedStatus->requestedDate;
                }
                $locationInfo[] = $info;
            }

            $routeinfo['routeId'] = $routeInfo->id;
            $routeinfo['driverId'] = $routeInfo->driver->driverId;
            $routeinfo['driverQuickBlockId'] = $routeInfo->driver->quickBlockId != null ? $routeInfo->driver->quickBlockId : "";
            $routeinfo['driverName'] = $routeInfo->driver->firstName;
            $routeinfo['servicerId'] = $routeInfo->servicer->id;
            $routeinfo['servicerQuickBlockId'] = $routeInfo->servicer->quickBlockId != null ? $routeInfo->servicer->quickBlockId : "";
            $routeinfo['servicerName'] = $routeInfo->servicer->name;
            $routeinfo['deliveryDate'] = $routeInfo->deliveryDate;
            $routeinfo['departureTime'] = $routeInfo->departureTime;
            $routeinfo['volumeContained'] = $routeInfo->volumeContained;
            $routeinfo['volumeRemaining'] = $volumeRemaining;
            $routeinfo['price'] = $routeInfo->price;
            $routeinfo['priceUnit'] = $routeInfo->priceUnit;
            $routeinfo['notifyUsers'] = $routeInfo->notifyUsers;
            $routeinfo['languageType'] = $routeInfo->languageType;
            $routeinfo['requestedLocation'] = $requestLocation;
            $routeinfo['routeLocations'] = $locationInfo;
            
            
        }

        return (object) $routeinfo;

    }

    /**
     * Route Detail
     *
     *
     */
    public function getRouteDetail(Request $request)
    {

        $this->validation($request->all(), [
            'routeId' => 'required',
        ]);

        $routeId = $request->input('routeId');
        $route_info = $this->getRoute($routeId);
        $response = [];
        if (!empty($route_info)) {
            $route_info->requests_status = 0;
            $route_info->request_fulfilled_status = 0;
            if (!empty($this->userId)) {
                $customerId = $this->userId;
                $customer_requests = RouteRequest::where(['routeId' => $routeId, "customerId" => $customerId])->first();
                if ($customer_requests) {
                    if ($customer_requests->status == 0) {
                        $route_info->requests_status = 2; // route exist and cancelled by customer.
                    } else {
                        $route_info->requests_status = 1; // route request exist and customer can cancel the request
                    }
                    $route_info->request_fulfilled_status = $customer_requests->markedStatus;
                }
            }
            $response = $route_info;
            $this->success("Route information.", $response);

        } else {
            $this->success("Route not found.", $response);

        }

    }

    public function checkRouteMarkStatus($locationid = 0)
    {

        $markedData = RouteRequest::where(['requestedRoute' => $locationid, 'markedStatus' => 1])->first();
        return $markedData;

    }

    public function submitRouteReq(Request $request)
    {

        $this->validation($request->all(), [
            'languageType' => 'required',
            'routeId' => 'required',
            'requested_address' => 'required',
            'requested_qty' => 'required',
            'req_route' => 'required',
        ]);

        $routeId = $request->input('routeId');
        $routeinfo = $this->getRoute($routeId);

        $customer_requests = RouteRequest::where(['routeId' => $routeId, "customerId" => $this->userId])->first();
        if ($customer_requests) {
            $this->error("Oops! Can't process, As it's already requested for this route.", "");
        }
        if ($routeinfo) {

            $requested_qty = $request->input('requested_qty');
            $requested_address = $request->input('requested_address');
            $req_nearest_route = $request->input('req_route');
            $languageType = $request->input('languageType');

            $total_remaining = $routeinfo->volumeRemaining;
            if ($requested_qty != 0 && $requested_qty <= $total_remaining) {

                // Request Approved //
                $driverId = $routeinfo->driverId;
                // Calculate price as per quantity.
                $calculatd_price = $requested_qty * $routeinfo->price;

                $createData = array(
                    'routeId' => $routeId,
                    'customerId' => $this->userId,
                    'driverId' => $driverId,
                    'servicerId' => $routeinfo->servicerId,
                    'requestedAddress' => $requested_address,
                    'requestedRoute' => $req_nearest_route,
                    'requestedQty' => $requested_qty,
                    'requestedMassUnit' => $routeinfo->priceUnit,
                    'requestedUnitPrice' => $routeinfo->price,
                    'customerLat' => "",
                    'customerLong' => "",
                    'requestedDate' => date('Y-m-d H:s:m'),
                    'estimatedCalPrice' => $calculatd_price,
                    'status' => 1,
                    'markedStatus' => 0,
                    'languageType' => $languageType,
                );

                $res = RouteRequest::create($createData);
                if ($res) {
                    $this->success(__('common.route_req_route_success_msg'), "");
                } else {
                    $this->error(__('common.route_req_route_error_msg'), "");
                }
            } else {
                $this->error(__('common.route_req_route_insufficient_msg'), "");
            }
        } else {
            $this->error(__('common.route_req_route_notfound'), "");
        }
    }

    /**
     * Cancel Route request by customer
     *
     */

    public function cancelRouteReq(Request $request)
    {

        $this->validation($request->all(), [
            'routeId' => 'required',
        ]);
        $routeId = $request->input('routeId');
        $customerId = $this->userId;
        $request_exist = RouteRequest::where(['routeId' => $routeId, 'customerId' => $customerId])->first();
        if ($request_exist) {
            if ($request_exist->markedStatus == 1) {
                $this->success(__('common.route_fulfilled_already'), "");
            } else {
                $request_exist->status = 0;
                $request_exist->save();
                $this->success(__('common.route_cancelled_true'), "");
            }
        } else {
            $this->error(__('common.route_cancelled_false'), "");
        }
    }

    /**
     * Get Tanker Volume contained remaining
     *
     */

    public function getConsumed($routeId = 0)
    {

        $request_consumed = RouteRequest::where(['routeId' => $routeId])->sum('requestedQty');
        $volumeContained = Router::where(['id' => $routeId])->value('volumeContained');
        $total_remaining = $volumeContained;
        if ($request_consumed) {

            $total_remaining = $volumeContained - $request_consumed;
            return $total_remaining;

        } else {
            return $total_remaining;
        }
    }

}
