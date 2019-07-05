<?php

namespace App\Http\Controllers\Admin\RouteRequests;

use App\Http\Controllers\Controller;
use App\Shop\Drivers\Driver;
use App\Shop\Products\Product;
use App\Shop\RouteRequests\RouteRequest;
use App\Shop\Routers\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteRequestController extends Controller
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

        
        $routeid = 0;
        if($request->route('id')){
            $routeid = $request->route('id');
            
        }
        if (!empty($keyword)) {
            $route_requests = RouteRequest::where('requestedAddress', 'LIKE', "%$keyword%")
                ->orWhere('driverId', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $route_requests = RouteRequest::with(['driverinfo', 'servicerinfo', 'customerinfo'])->where('routeId', $routeid)->orderBy('routeId')->latest()->paginate($perPage);
        }
        return view('admin.route-requests.index', compact('route_requests'));
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
        $route_request = RouteRequest::with(['driverinfo', 'servicerinfo', 'customerinfo'])->findOrFail($id);
        return view('admin.route-requests.show', compact('route_request'));
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
        $routes = RouteRequest::with(['routeInfo', 'driver', 'servicer'])->findOrFail($id);
        $weight_units = Product::MASS_UNIT;
        $drivers = Driver::where(['servicerId' => Auth::guard('servicer')->user()->id, 'status' => 1])->get();
        return view('admin.route-requests.edit', compact('routes', 'weight_units', 'drivers'));
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

        $delRequest = RouteRequest::findOrFail($id);
        $routeId = $delRequest->routeId;
        RouteRequest::destroy($id);
        return redirect('admin/route/'.$routeId.'/requests')->with('message', 'Request deleted!');
    }
    /**
     * Cancel the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancelRequest($id)
    {

        $cancelrequest = RouteRequest::findOrFail($id);
        $cancelrequest->status = 0;
        $cancelrequest->save();

        return redirect('admin/route/'.$cancelrequest->routeId.'/requests')->with('message', 'Request cancelled successfully!');
    }

    public function activateRequest($id)
    {

        $activaterequest = RouteRequest::findOrFail($id);
        $activaterequest->status = 1;
        $activaterequest->save();

        return redirect('admin/route/'.$activaterequest->routeId.'/requests')->with('message', 'Request activated successfully!');
    }
}
