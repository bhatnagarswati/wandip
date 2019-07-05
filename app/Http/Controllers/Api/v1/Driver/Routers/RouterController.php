<?php

namespace App\Http\Controllers\Api\v1\Driver\Routers;

use App\Http\Controllers\Controller;
use App\v1\Drivers\Driver;
use App\v1\RouterInformations\RouterInformation;
use App\v1\Routers\Router;
use App\v1\RouteRequests\RouteRequest;
use App\v1\Servicers\Servicer;
use App\v1\Stores\Store;
use Illuminate\Http\Request;

class RouterController extends Controller
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

    public function allRoutes(Request $request)
    {


        $this->validation($request->all(), [
            'languageType' => 'required',
            
        ]);


        $language = $request->input('languageType');
        // Pagination
        $per_page = 10;
        if ($request->input('page') == "") {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        $response['routes'] = [];
        //Search data for contents
        $data = Router::with(['routeInfo', 'driver', 'servicer'])->where(['driverId' => $this->userId, 'languageType' => $language]);
        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $routeIds =  DB::table('routers')
            ->join('router_informations', 'routers.id', '=', 'router_informations.routerId')
            ->join('drivers', 'routers.driverId', '=', 'drivers.driverId')
            ->join('servicers', 'routers.servicerId', '=', 'servicers.id')
            ->where('router_informations.location', 'LIKE', "%$keyword%")
            ->orWhere('router_informations.city', 'LIKE', "%$keyword%")
            ->orWhere('drivers.firstName', 'LIKE', "%$keyword%")
            ->orWhere('drivers.lastName', 'LIKE', "%$keyword%")
            ->orWhere('servicers.name', 'LIKE', "%$keyword%")
            ->distinct('routerId')->pluck('router_informations.routerId');

            if(!empty($routeIds)){
                $data->whereIn('id', $routeIds);
            }
        }
        // All Routes Count
        $response['routes_count'] = $data->skip($skip)->take($take)->count();
        $data = $data->orderBy('id', 'desc')->skip($skip)->take($take)->get();

        // Routes
        if (!empty($data)) {
            foreach ($data as $route) {

                $routeRequests = $this->getRouteRequestCount($route->id);
                $routeinfo['routeId'] = $route->id;
                $routeinfo['driverId'] = $route->driverId;
                $routeinfo['driverName'] = $route->driver->firstName;
                $routeinfo['servicerName'] = $route->servicer->name;
                $routeinfo['deliveryDate'] = $route->deliveryDate;
                $routeinfo['departureTime'] = $route->departureTime;
                $routeinfo['volumeContained'] = $route->volumeContained;
                $routeinfo['price'] = $route->price;
                $routeinfo['priceUnit'] = $route->priceUnit;
                $routeinfo['notifyUsers'] = $route->notifyUsers;
                $routeinfo['languageType'] = $route->languageType;
                $routeinfo['routeRequestsCount'] = $routeRequests;
                $routeinfo['routeLocations'] = $route->routeInfo;

                $response['routes'][] = $routeinfo;
            }
        }

        // Return Response array
        $this->success("All Routes", $response);
    }
  

    function getRouteRequestCount($routeId = 0){

        $request_count = RouteRequest::where(['routeId' => $routeId])->count();
        return $request_count;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function getRoute($routeId = 0, $type = 'api', Request $request)
    {
        if ($type == 'api') {
            $this->validation($request->all(), [
                'routeId' => 'required',
            ]);
            $routeId = $request->input('routeId');

        }
        $routeinfo = [];
        $routeInfo = Router::with(['routeInfo', 'driver', 'servicer'])->findOrFail($routeId);
        if ($routeInfo) {
            $routeinfo['routeId'] = $routeInfo->id;
            $routeinfo['driverName'] = $routeInfo->driver->firstName;
            $routeinfo['servicerName'] = $routeInfo->servicer->name;
            $routeinfo['deliveryDate'] = $routeInfo->deliveryDate;
            $routeinfo['departureTime'] = $routeInfo->departureTime;
            $routeinfo['volumeContained'] = $routeInfo->volumeContained;
            $routeinfo['price'] = $routeInfo->price;
            $routeinfo['priceUnit'] = $routeInfo->priceUnit;
            $routeinfo['notifyUsers'] = $routeInfo->notifyUsers;
            $routeinfo['languageType'] = $routeInfo->languageType;
            $routeinfo['routeLocations'] = $routeInfo->routeInfo;

        }

        if ($type == 'api') {
            $this->success('Route Info', $routeinfo);
        } else {
            return $routeinfo;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function getRouteInfo(Request $request)
    {

        $this->validation($request->all(), [
            'routeId' => 'required',
        ]);
        $routeId = $request->input('routeId');
         

        $routeinfo = [];
        $routeInfo = Router::with(['routeInfo', 'driver', 'servicer'])->where(['id' => $routeId, 'driverId' => $this->userId])->first();
        if ($routeInfo) {
            $routeinfo['routeId'] = $routeInfo->id;
            $routeinfo['driverName'] = $routeInfo->driver->firstName;
            $routeinfo['driverLat'] = $routeInfo->driver->driverLat;
            $routeinfo['driverLong'] = $routeInfo->driver->driverLong;
            $routeinfo['servicerName'] = $routeInfo->servicer->name;
            $routeinfo['deliveryDate'] = $routeInfo->deliveryDate;
            $routeinfo['departureTime'] = $routeInfo->departureTime;
            $routeinfo['volumeContained'] = $routeInfo->volumeContained;
            $routeinfo['price'] = $routeInfo->price;
            $routeinfo['priceUnit'] = $routeInfo->priceUnit;
            $routeinfo['notifyUsers'] = $routeInfo->notifyUsers;
            $routeinfo['languageType'] = $routeInfo->languageType;

            $routeinfo['routeLocations'] = [];
            if ($routeInfo->routeInfo) {
                foreach ($routeInfo->routeInfo as $key => $value) {
                    $value->destinationStatus = $this->getRouteReqStatus($value->routerId, $value->id); // 1 Reached on the destination, 0 Not reached yet.
                    $value->destinationMarkedInfo = date('Y-m-d H:m:s'); // Driver Marked Info
                    $routeinfo['routeLocations'][] = $value;
                }
            }
            $this->success('Route Info', $routeinfo);
        }else{
            $this->error('Route not found', NULL);
        }
        

    }

    // Get Requests exist for a location or not
    function getRouteReqStatus($routeId  = 0, $locationId = 0){

        $requests = RouteRequest::where(['routeId' => $routeId, 'requestedRoute' => $locationId])->first();
        $responseStatus = "";
        if($requests){
            $responseStatus = 1;
        }else{
            $responseStatus = 0;
        }
        return $responseStatus;
    }

   
   
}
