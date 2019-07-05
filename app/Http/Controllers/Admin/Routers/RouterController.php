<?php

namespace App\Http\Controllers\Admin\Routers;

use App\Http\Controllers\Controller;
use App\Shop\Drivers\Driver;
use App\Shop\Products\Product;
use App\Shop\RouterInformations\RouterInformation;
use App\Shop\Routers\Router;
use App\Shop\Servicers\Servicer;
use App\Shop\Stores\Store;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouterController extends Controller
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
            $drivers = Router::where('deliveryDate  ', 'LIKE', "%$keyword%")
                ->orWhere('departureTime', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $routes = Router::with(['routeInfo', 'driver', 'servicer'])->where('languageType', Config::get('app.locale'))->latest()->paginate($perPage);
        }

        return view('admin.routers.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

        $weight_units = Product::MASS_UNIT;
        $drivers = Driver::where(['status' => 1])->get();
        $servicers = Servicer::where(['status' => 1])->get();
        return view('admin.routers.create', compact('weight_units', 'drivers', 'servicers'));
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

            'deliveryDate' => 'required|date|date_format:Y-m-d',
            'locations' => 'required',
            'departureTime' => 'required',

            'volumeContained' => 'required|string',
            'price' => 'required|string',
            'notifyUsers' => 'required|string',
            'mass_unit' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $createData = array(
            'servicerId' => $request->input('servicerId'),
            'driverId' => $request->input('driverId'),
            'deliveryDate' => $request->input('deliveryDate'),
            'departureTime' => $request->input('departureTime'),
            //'timeNote' => $request->input('timeNote'),
            //'arrivalTime' => $request->input('arrivalTime'),
            'volumeContained' => $request->input('volumeContained'),
            'price' => $request->input('price'),
            'priceUnit' => $request->input('mass_unit'),
            'notifyUsers' => $request->input('notifyUsers'),
            'status' => $request->input('status'),
            'languageType' => Config::get('app.locale'),
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
                    'city' => $location['city'] != null ? $location['city'] : "",
                    'locationLat' => $location['locationLat'] != null ? $location['locationLat'] : "32.94994",
                    'locationLong' => $location['locationLong'] != null ? $location['locationLong'] : "76.3323",
                    'sortOrder' => $i,

                );
                RouterInformation::create($location_arr);

                $i++;
            }

        }

        return redirect('admin/routers')->with('message', 'Route added!');
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
        $route = Router::with(['routeInfo', 'driver', 'servicer'])->findOrFail($id);
        return view('admin.routers.show', compact('route'));
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
        $routes = Router::with(['routeInfo', 'driver', 'servicer'])->findOrFail($id);
        $weight_units = Product::MASS_UNIT;
        $drivers = Driver::where(['status' => 1])->get();
        $servicers = Servicer::where(['status' => 1])->get();
        return view('admin.routers.edit', compact('routes', 'weight_units', 'drivers', 'servicers'));
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

        $driver = Router::with(['routeInfo', 'driver', 'servicer'])->findOrFail($id);

        // validate incoming request
        $validator = Validator::make($request->all(), [

            'deliveryDate' => 'required|date|date_format:Y-m-d',
            'locations' => 'required',
            'departureTime' => 'required',
            /* 'arrivalTime' => 'required', */
            'volumeContained' => 'required|string',
            'price' => 'required|string',
            'notifyUsers' => 'required|string',
            'mass_unit' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $createData = array(
            'servicerId' => $request->input('servicerId'),
            'driverId' => $request->input('driverId'),
            'deliveryDate' => $request->input('deliveryDate'),
            'departureTime' => $request->input('departureTime'),
            //'timeNote' => $request->input('timeNote'),
            //'arrivalTime' => $request->input('arrivalTime'),
            'volumeContained' => $request->input('volumeContained'),
            'price' => $request->input('price'),
            'priceUnit' => $request->input('mass_unit'),
            'notifyUsers' => $request->input('notifyUsers'),
            'status' => $request->input('status'),
            'languageType' => Config::get('app.locale'),
        );

        $router_res = Router::where('id', $id)->update($createData);

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
                        'city' => $location['city'] != null ? $location['city'] : "",
                        'locationLat' => $location['locationLat'] != null ? $location['locationLat'] : "32.94994",
                        'locationLong' => $location['locationLong'] != null ? $location['locationLong'] : "76.3323",
                        'sortOrder' => $i,
                    );
                    RouterInformation::where('id', $location['id'])->update($location_arr);
                } else {
                    if (!empty($location['location'])) {
                        $location_arr = array(
                            'routerId' => $id,
                            'location' => $location['location'],
                            'city' => $location['city'] != null ? $location['city'] : "",
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

        return redirect('admin/routers')->with('message', 'Route updated!');
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
        Router::destroy($id);
        $delRoutesinfo = RouterInformation::where('routerId', $id)->pluck('id')->toArray();
        RouterInformation::destroy($delRoutesinfo);
        return redirect('admin/routers')->with('message', 'Route deleted!');
    }
}
