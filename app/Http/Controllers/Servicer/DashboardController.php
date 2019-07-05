<?php

namespace App\Http\Controllers\Servicer;

use App\Http\Controllers\Controller;
use App\Shop\Stores\Store;
use App\Shop\Pumps\Pump;
use App\Shop\Drivers\Driver;
use App\Shop\Products\Product;
use Auth;
use Lang;
use DB;
use Session;

class DashboardController extends Controller
{
    public function index()
    {


    	$servicerId  = Auth::guard('servicer')->user()->id;
    	
    	// All Store count
		$stores_count = Store::where(['servicerId' => $servicerId, 'isActive' => 1])->count();

		// All Pump count
		$pumps_count = Pump::with('stores')->where(['pumps.servicerId' => $servicerId, 'pumps.status' => 1])->count();
		
		// All Drivers
		$drivers_count = Driver::where(['servicerId' => $servicerId, 'status' => 1])->count();
	
		// Get all stores of logged in service providers
		$storeIds = Store::where(['servicerId' => $servicerId, 'isActive' => 1])->pluck('id');

		// Get all products of all stores
		$products_count = Product::whereIn('store_id', $storeIds)->count();


		$all_drivers = Driver::where(['servicerId' => $servicerId, 'status' => 1])->orderBy('created_at', 'desc')->get()->take(8);

        return view('servicer.dashboard', [
	        		'all_drivers' => $all_drivers,
	        		'products_count' => $products_count,
	        		'stores_count' => $stores_count,
	        		'pumps_count' => $pumps_count,
	        		'drivers_count' => $drivers_count,
        		]
    	);
    }
}
