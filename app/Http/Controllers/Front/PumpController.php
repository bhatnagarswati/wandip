<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests;
use App\Shop\Pumps\Pump;
use App\Shop\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Shop\Products\Transformations\ProductTransformable;
use Config;
use DB;

class PumpController
{

    use ProductTransformable;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    /**
     * Stations Listing page
     *
     *
     */
    public function index(Request $request)
    {
        $title_keyword = $request->get('t');
        $address_keyword = $request->get('a');
        $miles_keyword = $request->get('m');

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
        
        $distance_sql = "";
        if (!empty($miles_keyword)) {
            if ($miles_keyword != "all") {
                $distance_sql = " HAVING distance <= $miles_keyword ";
            }
        }
        // Selected Language
        $languageType = Config::get('app.locale');

        if (!empty($title_keyword) || !empty($address_keyword) || !empty($miles_keyword)) {

            $search_sql = "";
            if (!empty($title_keyword) && !empty($address_keyword)) {
                $search_sql = " AND pumpTitle LIKE   '%$title_keyword%' OR pumpDescription LIKE   '%$title_keyword%' OR pumpAddress LIKE   '%$address_keyword%'  ";
            } else if (!empty($title_keyword)) {
                $search_sql = " AND pumpTitle LIKE   '%$title_keyword%' OR pumpDescription LIKE   '%$title_keyword%' ";
            } else if (empty($title_keyword) && !empty($address_keyword)) {
                $search_sql = " AND pumpAddress LIKE   '%$address_keyword%' ";
            }

            $qry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType' AND status = 1 AND pumpLat IS NOT NULL  $search_sql  $distance_sql order by distance ASC LIMIT $start_from, $limit";

            $pumps = DB::select($qry);

            // For Custom Pagination
            $pagiqry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType' AND status = 1 AND  pumpLat IS NOT NULL   $search_sql  $distance_sql  order by distance ASC";
            $pagi_pumps = DB::select($pagiqry);

            $pagination = new LengthAwarePaginator($pagi_pumps, count($pagi_pumps), $limit, $pageno);
            $pagination->setPath(request()->url());

        } else {

            $qry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType'  AND status = 1 AND  pumpLat IS NOT NULL order by distance ASC LIMIT $start_from, $limit";
            $pumps = DB::select($qry);

            // For Custom Pagination
            $pagiqry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType' AND status = 1 AND  pumpLat IS NOT NULL order by distance ASC";
            $pagi_pumps = DB::select($pagiqry);

            $pagination = new LengthAwarePaginator($pagi_pumps, count($pagi_pumps), $limit, $pageno);
            $pagination->setPath(request()->url());

        }

        return view('front.stations.pumps', compact('pumps', 'pagination'));
    }

    /**
     * Station Detail Pages
     *
     *
     */
    public function pumpDetail($id = "")
    {
        $pump = Pump::findOrFail($id);
        $languageType = Config::get('app.locale');
        $products = [];
        if($pump){
            $data = Product::where(['store_id' => $pump->storeId, 'status' => 1, 'languageType' => $languageType])->orderBy('created_at', 'DESC')->take(8)->get();
            foreach($data as $key => $value) {
                $products[] = $this->transformProduct($value);
            }
        }
      
        return view('front.stations.pump_detail', compact('pump', 'products'));
    }

}
