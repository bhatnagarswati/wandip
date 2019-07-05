<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Shop\Stores\Store;
use App\Shop\Pumps\Pump;
use App\Shop\Drivers\Driver;
use App\Shop\Products\Product;
use App\Shop\Servicers\Servicer;
use Auth;
use Lang;
use DB;
use Session;


class DashboardController extends Controller
{
    public function index()
    {

    	// All Service providers
    	$service_providers = Servicer::where(['status' => 1 ])->orderBy('created_at', 'desc')->get()->take(8);

    	$service_providers_count = Servicer::where(['status' => 1 ])->count();

    	// All Store count
		$stores = Store::where(['isActive' => 1])->orderBy('created_at', 'desc')->get()->take(8);

		$stores_count = Store::where(['isActive' => 1])->count();
		// All Pump count
		$pumps_count = Pump::with('stores')->where(['pumps.status' => 1])->count();
		// All Drivers
		$drivers_count = Driver::where(['status' => 1])->count();
		// Get all stores of logged in service providers
		$storeIds = Store::where(['isActive' => 1])->pluck('id');
		// Get all products of all stores
		$products_count = Product::whereIn('store_id', $storeIds)->count();
		
		$all_drivers = Driver::where(['status' => 1])->orderBy('created_at', 'desc')->get()->take(8);

        return view('admin.dashboard' , [
	        		'service_providers' => $service_providers,
	        		'service_providers_count' => $service_providers_count,
	        		'stores' => $stores,
	        		'all_drivers' => $all_drivers,
	        		'products_count' => $products_count,
	        		'stores_count' => $stores_count,
	        		'pumps_count' => $pumps_count,
	        		'drivers_count' => $drivers_count,
        		]);
    }

    static function getNotificationsCount(){

    	//Service Provider Approved requests
    	$approve_requests = Servicer::where(['status' => 0 ])->count();
    	return  [ 'approve_requests' => $approve_requests ];

    }
}	
