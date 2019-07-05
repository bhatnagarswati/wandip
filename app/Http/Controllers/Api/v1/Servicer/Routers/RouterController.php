<?php

namespace App\Http\Controllers\Api\v1\Servicer\Routers;

use App\Http\Controllers\Controller;
use App\v1\Drivers\Driver;
use App\v1\RouteRequests\RouteRequest;
use App\v1\RouterInformations\RouterInformation;
use App\v1\Routers\Router;
use App\v1\Servicers\Servicer;
use App\v1\Stores\Store;
use Illuminate\Http\Request;
use DB;

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
            'languageType' => 'required|string',
        ]);

        $language = $request->input('languageType');
        // Pagination
        $per_page = 10;
        if ($request->input('page') == 0) {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        $response['routes'] = [];
        //Search data for contents
        $data = Router::with(['routeInfo', 'driver', 'servicer'])->where(['servicerId' => $this->userId, 'languageType' => $language]);
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
                $routeinfo['routeLocations'] = $route->routeInfo;

                $response['routes'][] = $routeinfo;
            }

        }

        // Return Response array
        $this->success("All Routes", $response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addRoute(Request $request)
    {

        // validate incoming request
        $this->validation($request->all(), [
            'deliveryDate' => 'required|date|date_format:Y-m-d',
            'locations' => 'required',
            'departureTime' => 'required',
            'volumeContained' => 'required|string',
            'price' => 'required|string',
            'notifyUsers' => 'required|string',
            'mass_unit' => 'required',
            'languageType' => 'required|string',
        ]);

        $createData = array(
            'servicerId' => $this->userId,
            'driverId' => $request->input('driverId'),
            'deliveryDate' => $request->input('deliveryDate'),
            'departureTime' => $request->input('departureTime'),
            //'timeNote' => $request->input('timeNote'),
            //'arrivalTime' => $request->input('departureTime'),
            'volumeContained' => $request->input('volumeContained'),
            'price' => $request->input('price'),
            'priceUnit' => $request->input('mass_unit'),
            'notifyUsers' => $request->input('notifyUsers'),
            'languageType' => $request->input('languageType'),
            'status' => 1,
        );

        $router_res = Router::create($createData);
        // Store locations in Route information table
        $routeId = $router_res->id;

        $locations = [];

        $locations = $request->input('locations');

        if (!empty($locations)) {
            $i = 1;
            $location_arr = "";
            foreach ($locations as $key => $location) {

                $location_arr = array(
                    'routerId' => $routeId,
                    'location' => $location['location'],
                    'city' => $location['city'],
                    'locationLat' => $location['locationLat'],
                    'locationLong' => $location['locationLong'],
                    'sortOrder' => $i,

                );
                RouterInformation::create($location_arr);

                $i++;
            }

        }
        $response = $this->getRoute($routeId, 'info', $request);
        $this->success("Route Added", $response);
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
        $routeInfo = Router::with(['routeInfo', 'driver', 'servicer'])->findOrFail($routeId);
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
                    $destinationStatus = $this->checkRouteMarkStatus($value->id);
                    if ($destinationStatus) {
                        $value->destinationStatus = $destinationStatus->markedStatus; // 1 Reached on the destination, 0 Not reached yet.
                    } else {
                        $value->destinationStatus = 0; // 1 Reached on the destination, 0 Not reached yet.
                    }

                    $value->destinationMarkedInfo = date('Y-m-d H:m:s'); // Driver Marked Info
                    $routeinfo['routeLocations'][] = $value;

                }

            }
        }

        $this->success('Route Info', $routeinfo);

    }
    public function checkRouteMarkStatus($locationid = 0)
    {

        $markedData = RouteRequest::where(['requestedRoute' => $locationid, 'markedStatus' => 1])->first();
        return $markedData;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateRoute(Request $request)
    {

        $id = $request->input('routeId');
        $router = Router::with(['routeInfo', 'driver', 'servicer'])->findOrFail($id);
        // validate incoming request
        $this->validation($request->all(), [

            'deliveryDate' => 'required|date|date_format:Y-m-d',
            'locations' => 'required',
            'departureTime' => 'required',
            'volumeContained' => 'required|string',
            'price' => 'required|string',
            'notifyUsers' => 'required|string',
            'mass_unit' => 'required',
            'languageType' => 'required',
        ]);

        $updateData = array(
            'driverId' => $request->input('driverId'),
            'deliveryDate' => $request->input('deliveryDate'),
            'departureTime' => $request->input('departureTime'),
            //'timeNote' => $request->input('timeNote'),
            'volumeContained' => $request->input('volumeContained'),
            'price' => $request->input('price'),
            'priceUnit' => $request->input('mass_unit'),
            'notifyUsers' => $request->input('notifyUsers'),
            'languageType' => $request->input('languageType'),
        );

        $router_res = Router::where('id', $id)->update($updateData);
        $existingRoutes = RouterInformation::where('routerId', $id)->pluck('id')->toArray();

        $locations = [];
        $locations = $request->input('locations');
        $alllocations = $request->input('locations');
        if (!empty($locations)) {

            $toUpdate = array_column($alllocations, 'id');
            $toDelete = array_diff($existingRoutes, $toUpdate);
            if (!empty($toDelete)) {
                RouterInformation::destroy($toDelete);
            }

            //Update Rooute information
            $i = 1;
            $location_arr = "";
            foreach ($locations as $key => $location) {

                if (isset($location['id'])) {
                    $location_arr = array(
                        'routerId' => $id,
                        'location' => $location['location'],
                        'city' => $location['city'],
                        'locationLat' => $location['locationLat'],
                        'locationLong' => $location['locationLong'],
                        'sortOrder' => $i,
                    );
                    RouterInformation::where('id', $location['id'])->update($location_arr);
                } else {
                    if (!empty($location['location'])) {
                        $location_arr = array(
                            'routerId' => $id,
                            'location' => $location['location'],
                            'city' => $location['city'],
                            'locationLat' => $location['locationLat'] != null ? $location['locationLat'] : "32.94994",
                            'locationLong' => $location['locationLong'] != null ? $location['locationLong'] : "76.3323",
                            'sortOrder' => $i,
                        );
                        RouterInformation::create($location_arr);
                    }
                }
                $i++;
            }

        }
        $response = $this->getRoute($id, 'info', $request);
        $this->success("Route Update", $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteRoute(Request $request)
    {

        $routeId = $request->input('routeId');
        $this->validation($request->all(), [
            'routeId' => 'required',
        ]);
        Router::destroy($routeId);
        $delRoutesinfo = RouterInformation::where('routerId', $routeId)->pluck('id')->toArray();
        RouterInformation::destroy($delRoutesinfo);
        $this->success("Route deleted!", "");

    }
}
