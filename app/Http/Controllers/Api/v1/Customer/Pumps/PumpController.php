<?php
namespace App\Http\Controllers\Api\v1\Customer\Pumps;

use App\Http\Controllers\Controller;
use App\v1\Products\Product;
use App\v1\Products\Transformations\ProductTransformable;
use App\v1\Pumps\Pump;
use App\v1\Ratings\ProductReviews;
use App\v1\Tools\UploadableTrait;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PumpController extends Controller
{

    use ProductTransformable,
        UploadableTrait;

    public $successStatus = 200;
    public $userId = "";
    public $user_type = "";

    use ProductTransformable;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function __construct(Request $request)
    {

        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    /**
     * Stations Listing page
     *
     *
     */
    public function allPumps(Request $request)
    {

        $this->validation($request->all(), [
            'languageType' => 'required',
            'userLat' => 'required',
            'userLong' => 'required',
        ]);

        $limit = 10;
        if ($request->input('page') != 0) {
            $pageno = $request->input('page');
        } else {
            $pageno = 1;
        }
        $start_from = ($pageno - 1) * $limit;
        $search_key = $request->input('search_key');

        /*  $distance_sql = "";
        if (!empty($miles_keyword)) {
        if ($miles_keyword != "all") {
        $distance_sql = " HAVING distance <= $miles_keyword ";
        }
        } */

        // Selected Language
        $languageType = $request->input('languageType');
        // User Lat Long pumpTitle pumpDescription pumpAddress
        $userLatitude = $request->input('userLat');
        $userLongitude = $request->input('userLong');

        if (!empty($search_key)) {

            $search_sql = "";
            $search_sql = " AND pumpTitle LIKE   '%$search_key%' OR pumpDescription LIKE   '%$search_key%' OR pumpAddress LIKE   '%$search_key%'  ";

            $qry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType' AND pumpLat IS NOT NULL  $search_sql order by distance ASC LIMIT $start_from, $limit";
            $pumps = DB::select($qry);

            // For Custom Pagination
            $pagiqry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType' AND  pumpLat IS NOT NULL   $search_sql  order by distance ASC";
            $pagi_pumps = DB::select($pagiqry);

        } else {

            $qry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType' AND  pumpLat IS NOT NULL order by distance ASC LIMIT $start_from, $limit";
            $pumps = DB::select($qry);

            // For Custom Pagination
            $pagiqry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType' AND  pumpLat IS NOT NULL order by distance ASC";
            $pagi_pumps = DB::select($pagiqry);
        }

        $count = count($pagi_pumps);
        $response = [];
        $allpumps = [];
        if (!empty($pumps)) {
            foreach ($pumps as $pump) {
                $pump->pumpPic = config('constants.pump_pull_path') . $pump->pumpPic;
                $allpumps[] = $pump;
            }

        }

        $response['pumps'] = $allpumps;
        $response['pumps_count'] = $count;

        return $this->success('All pumps', $response);
    }

    /**
     * Station Detail Pages
     *
     */
    public function pumpDetail(Request $request)
    {

        $this->validation($request->all(), [
            'languageType' => 'required',
            'pumpId' => 'required',
        ]);

        $languageType = $request->input('languageType');
        $pumpid = $request->input('pumpId');
        $pump = Pump::where('pumpId', $pumpid)->firstOrFail();
        $products = [];
        if ($pump) {

            $products = [];
            if ($pump) {
                $data = Product::where(['store_id' => $pump->storeId, 'status' => 1, 'languageType' => $languageType])->orderBy('created_at', 'DESC')->take(8)->get();
                foreach ($data as $key => $value) {

                    $product = $this->transformProduct($value);
                    $product->rating = $this->getAvgRatings($value->id);
                    $products[] = $product;
                }
            }
            $pump->pumpPic = config('constants.pump_pull_path') . $pump->pumpPic;
            $response['pumps'] = $pump;
            $response['related_products'] = $products;

            $this->success('Pump Info', $response);
        } else {
            $this->success('Pump not found', null);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeProducts(Request $request)
    {

        $this->validation($request->all(),
            [
                'languageType' => 'required',
                'storeId' => 'required',
            ]);

        $languageType = $request->input('languageType');
        $storeId = $request->input('storeId');

        $per_page = 10;
        if ($request->input('page') == "") {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }
 
        $data = Product::where(['languageType' => $languageType, 'status' => 1]);

        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $data->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('slug', 'LIKE', "%$keyword%")
                    ->orWhere('description', 'LIKE', "%$keyword%")
                ;
            });
        }
        $bycategories = [];
        $bybrands = [];
        $byservice = [];
        $minprice = 1;
        $maxprice = 5000;
        if ($request->has('filter_search')) {
            if($request->input('filter_search') == 'yes'){
                $minprice = $request->input('minprice');
                $maxprice = $request->input('maxprice');
                $bycategories = $request->input('bycategories');
                $bybrands = $request->input('bybrands');
                $byservice = $request->input('byservice');

                if (!empty($bycategories)) {

                    $bycategories = explode(",", $bycategories);
                    $cat_products = DB::table('category_product')->whereIn('category_id', $bycategories)->distinct('product_id')->pluck('product_id')->toArray();
                    if (!empty($cat_products)) {
                        $data->whereIn('id', $cat_products);
                    }
                }
                if (!empty($bybrands)) {
                    $bybrands = explode(",", $bybrands);
                    $data->whereIn('brand_id', $bybrands);
                }
                if (!empty($byservice)) {
                    $byservice = explode(",", $byservice);
                    $data->whereIn('serviceOfferedType', $byservice);
                }
                if (!empty($minprice) && !empty($maxprice)) {
                    $data->whereBetween('price', [$minprice, $maxprice]);
                }
            }
        }
        $data->where('store_id', $storeId);
        $products_count = $data->count();
        $list = $data->orderBy("id")->skip($skip)->take($take)->get();

        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();

        $allproducts = [];
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $value->rating = $this->getAvgRatings($value->id);
                $allproducts[] = $value;
            }

        }
        $response = ['products' => $allproducts, 'products_count' => $products_count];
        $this->success("Store all products.", $response, $this->successStatus);
    }


    private function getAvgRatings($productId = 0)
    {
        $productReviews = ProductReviews::select('*')->where('productId', $productId)->get();
        if (!empty($productReviews)) {
            $totalRating = [];
            foreach ($productReviews as $reviews) {
                $totalRating[] = $reviews->customerRating;
                $totalReviews[] = $reviews;
            }
            // Total Star Rating
            $total = array_sum($totalRating);
            $totalAvg = $total / 5;
              return number_format((float)$totalAvg, 1, '.', '');
        } else {
            return 0;
        }

    }
 
     
}
