<?php

namespace App\Http\Controllers\Front;

use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\RouteRequests\RouteRequest;
use App\Shop\Routers\Router;
use Auth;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RouteController
{

    use ProductTransformable;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    /**
     * Rouets Listing
     *
     *
     */
    public function index(Request $request)
    {

        $title_keyword = $request->get('t');
        $address_keyword = $request->get('a');

        $limit = 15;
        if ($request->input('page') != "") {
            $pageno = $request->input('page');
        } else {
            $pageno = 1;
        }
        $start_from = ($pageno - 1) * $limit;

        // User Lat Long pumpTitle pumpDescription pumpAddress
        if ($request->session()->has('sessionUserLat') && $request->session()->has('sessionUserLong')) {
            $userLatitude = $request->session()->get('sessionUserLat');
            $userLongitude = $request->session()->get('sessionUserLong');
        } else {
            $userLatitude = env('DEFAULT_LAT');
            $userLongitude = env('DEFAULT_LONG');
        }
        // Selected Language
        $languageType = Config::get('app.locale');

        if (!empty($title_keyword) || !empty($address_keyword)) {

            /* $keyword = $request->input('search_key');
            $routeIds = DB::table('routers')
                ->join('router_informations', 'routers.id', '=', 'router_informations.routerId')
                ->join('drivers', 'routers.driverId', '=', 'drivers.driverId')
                ->join('servicers', 'routers.servicerId', '=', 'servicers.id')
                ->where('router_informations.location', 'LIKE', "%$address_keyword%")
                ->orWhere('router_informations.city', 'LIKE', "%$address_keyword%")
                ->orWhere('drivers.firstName', 'LIKE', "%$title_keyword%")
                ->orWhere('drivers.lastName', 'LIKE', "%$title_keyword%")
                ->orWhere('servicers.name', 'LIKE', "%$title_keyword%")
                ->distinct('routerId')->value('router_informations.routerId');
            $ser_sql = " "; 
            if (!empty($routeIds)) {
                $ser_sql = " AND r.id IN ('$routeIds') "; 
            }

            $qry = "SELECT  r.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((ri.locationLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((ri.locationLat*pi()/180)) * cos(((" . $userLongitude . "- ri.locationLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM routers r INNER JOIN router_informations ri ON r.id = ri.routerId INNER JOIN servicers s ON  s.id  = r.servicerId WHERE r.status = 1 AND r.languageType = '$languageType'  $ser_sql  GROUP BY r.id ORDER BY distance ASC , r.deliveryDate DESC LIMIT $start, $limit";

            $routes = DB::select($qry);

            $qry_count = "SELECT  r.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((ri.locationLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((ri.locationLat*pi()/180)) * cos(((" . $userLongitude . "- ri.locationLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM routers r INNER JOIN router_informations ri ON r.id = ri.routerId INNER JOIN servicers s ON  s.id  = r.servicerId WHERE r.status = 1 AND r.languageType = '$languageType'  $ser_sql  GROUP BY r.id ORDER BY distance ASC , r.deliveryDate DESC";
            $routes_count = DB::select($qry_count); */

        } else {

            //  routers router_informations servicers
            $qry = "SELECT  r.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((ri.locationLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((ri.locationLat*pi()/180)) * cos(((" . $userLongitude . "- ri.locationLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM routers r INNER JOIN router_informations ri ON r.id = ri.routerId INNER JOIN servicers s ON  s.id  = r.servicerId WHERE r.status = 1 AND r.languageType = '$languageType' GROUP BY r.id ORDER BY distance ASC , r.deliveryDate DESC LIMIT $start_from, $limit";
            $allroutes = [];
            $routes = DB::select($qry);
            if (!empty($routes)) {

                foreach ($routes as $route) {
                    $routeInfo = $this->getRoute($route->id);
                    $allroutes[] = $routeInfo;
                }
            }

            // For Custom Pagination
            $pagination_qry = "SELECT r.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((ri.locationLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((ri.locationLat*pi()/180)) * cos(((" . $userLongitude . "- ri.locationLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM routers r INNER JOIN router_informations ri ON r.id = ri.routerId  WHERE r.status = 1 AND r.languageType = '$languageType' GROUP BY r.id ORDER BY distance ASC , r.deliveryDate DESC";
            $pagination_routes = DB::select($pagination_qry);

            $pagination = new LengthAwarePaginator($pagination_routes, count($pagination_routes), $limit, $pageno);
            $pagination->setPath(request()->url());

        }

        return view('front.routes.routes', compact('allroutes', 'pagination'));
    }

    public function getRoute($routeId = 0)
    {

        $routeinfo = [];
        $routeInfo = Router::where('status', 1)->with(['routeInfo', 'driver', 'servicer'])->findOrFail($routeId);

        // Get Remaining Oil status
        $volumeRemaining = $this->getConsumed($routeId);

        if ($routeInfo) {

            $locationInfo = [];
            foreach ($routeInfo->routeInfo as $info) {
                $info->markedStatus = 0;
                $markedStatus = $this->checkRouteMarkStatus($info->id);
                if ($markedStatus) {
                    $info->markedStatus = 1;
                    if (!empty($locationInfo)) {
                        $locations = [];
                        foreach ($locationInfo as $location) {
                            $location->markedStatus = 1;
                            $locations[] = $location;
                        }
                        $locationInfo = $locations;
                    }
                }
                $locationInfo[] = $info;
            }

            $routeinfo['routeId'] = $routeInfo->id;
            $routeinfo['driverId'] = $routeInfo->driver->driverId;
            $routeinfo['driverName'] = $routeInfo->driver->firstName;
            $routeinfo['driverLat'] = $routeInfo->driver->driverLat;
            $routeinfo['driverLong'] = $routeInfo->driver->driverLong;
            $routeinfo['servicerId'] = $routeInfo->servicer->id;
            $routeinfo['servicerName'] = $routeInfo->servicer->name;
            $routeinfo['deliveryDate'] = $routeInfo->deliveryDate;
            $routeinfo['departureTime'] = $routeInfo->departureTime;
            $routeinfo['volumeContained'] = $routeInfo->volumeContained;
            $routeinfo['volumeRemaining'] = $volumeRemaining;
            $routeinfo['price'] = $routeInfo->price;
            $routeinfo['priceUnit'] = $routeInfo->priceUnit;
            $routeinfo['notifyUsers'] = $routeInfo->notifyUsers;
            $routeinfo['languageType'] = $routeInfo->languageType;
            $routeinfo['routeLocations'] = $locationInfo;
        }

        return (object) $routeinfo;

    }

    /**
     * Route Detail Page
     *
     *
     */
    public function routeDetail($id = "")
    {
        $route_info = $this->getRoute($id);
        if (!empty($route_info)) {

            $languageType = Config::get('app.locale');

            $requests_status = 0;
            $request_fulfilled_status = 0;
            if (Auth::user()) {
                $customerId = Auth::user()->id;
                $customer_requests = RouteRequest::where(['routeId' => $id, "customerId" => $customerId])->first();
                if ($customer_requests) {
                    if ($customer_requests->status == 0) {
                        $requests_status = 2; // route exist and cancelled by customer.
                    } else {
                        $requests_status = 1; // route request exist and customer can cancel the request
                    }
                    $request_fulfilled_status = $customer_requests->markedStatus;
                }
            }
            return view('front.routes.route_detail', compact('route_info', 'requests_status', 'request_fulfilled_status'));
        } else {
            $route_info = [];
            $requests_status = 0;
            return view('front.routes.route_detail', compact('route_info', 'requests_status'));
        }

    }

    public function checkRouteMarkStatus($locationid = 0)
    {

        $markedData = RouteRequest::where(['requestedRoute' => $locationid, 'markedStatus' => 1])->first();
        return $markedData;

    }

    public function submitRouteReq(Request $request)
    {

        $routeId = $request->input('routeId');
        $routeinfo = $this->getRoute($routeId);

        $request_exist = RouteRequest::where(['routeId' => $routeId, 'customerId' =>  Auth::user()->id])->first();
        if($request_exist){
            $response['status'] = false;
            $response['message'] = "Request already created for this route.";
            echo json_encode($response);
            die;
        }

        if ($routeinfo) {

            $requested_qty = $request->input('requested_qty');
            $requested_address = $request->input('r_address');
            $req_nearest_route = $request->input('req_route');

            $total_remaining = $routeinfo->volumeRemaining;
            if ($requested_qty != 0 && $requested_qty <= $total_remaining) {

                // Request Approved //
                $driverId = $routeinfo->driverId;
                // Calculate price as per quantity.
                $calculatd_price = $requested_qty * $routeinfo->price;


                if ($request->session()->has('sessionUserLat') && $request->session()->has('sessionUserLong')) {
                    $userLatitude = $request->session()->get('sessionUserLat');
                    $userLongitude = $request->session()->get('sessionUserLong');
                } else {
                    $userLatitude = env('DEFAULT_LAT');
                    $userLongitude = env('DEFAULT_LONG');
                }


                $createData = array(
                    'routeId' => $routeId,
                    'customerId' => Auth::user()->id,
                    'driverId' => $driverId,
                    'servicerId' => $routeinfo->servicerId,
                    'requestedAddress' => $requested_address,
                    'requestedRoute' => $req_nearest_route,
                    'requestedQty' => $requested_qty,
                    'requestedMassUnit' =>$routeinfo->priceUnit,
                    'requestedUnitPrice' =>$routeinfo->price,
                    'customerLat' => $userLatitude,
                    'customerLong' => $userLongitude,
                    'requestedDate' => date('Y-m-d H:s:m'),
                    'estimatedCalPrice' => $calculatd_price,
                    'status' => 1,
                    'markedStatus' => 0,
                    'languageType' => Config::get('app.locale'),
                );

                $res = RouteRequest::create($createData);
                if ($res) {
                    $response['status'] = true;
                    $response['message'] = __('common.route_req_route_success_msg');
                } else {
                    $response['status'] = false;
                    $response['message'] = __('common.route_req_route_error_msg');
                }
                echo json_encode($response);

            } else {

                $response['status'] = false;
                $response['message'] = __('common.route_req_route_insufficient_msg');
                echo json_encode($response);
            }

        } else {
            $response['status'] = false;
            $response['message'] = __('common.route_req_route_notfound');
            echo json_encode($response);
        }

    }

    /**
     * Cancel Route request by customer
     *
     */

    public function cancelRouteReq(Request $request)
    {

        $routeId = $request->input('routeId');
        $customerId = Auth::user()->id;
        $request_exist = RouteRequest::where(['routeId' => $routeId, 'customerId' => $customerId])->first();
        if ($request_exist) {

            if ($request_exist->markedStatus == 1) {
                $response['status'] = true;
                $response['message'] = __('common.route_fulfilled_already');
                echo json_encode($response);
            } else {
                $request_exist->status = 0;
                $request_exist->save();
                $response['status'] = true;
                $response['message'] = __('common.route_cancelled_true');
                echo json_encode($response);
            }

        } else {
            $response['status'] = false;
            $response['message'] = __('common.route_cancelled_false');
            echo json_encode($response);
        }

    }

    /**
     * Get Tanker Volume contained remaining
     *
     */

    public function getConsumed($routeId = 0)
    {

        $request_consumed = RouteRequest::where(['routeId' => $routeId, 'status' => 1])->sum('requestedQty');
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
