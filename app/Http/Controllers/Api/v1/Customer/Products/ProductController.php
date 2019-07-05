<?php

namespace App\Http\Controllers\Api\v1\Customer\Products;

use App\Http\Controllers\Controller;
use App\v1\Attributes\Repositories\AttributeRepositoryInterface;
use App\v1\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\v1\Banners\Banner;
use App\v1\Brands\Repositories\BrandRepositoryInterface;
use App\v1\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\v1\Drivers\Driver;
use App\v1\Servicers\Servicer;
use App\v1\ProductAttributes\ProductAttribute;
use App\v1\Products\Product;
use App\v1\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\v1\Products\Repositories\ProductRepository;
use App\v1\Products\Transformations\ProductTransformable;
use App\v1\Pumps\Pump;
use App\v1\Routers\Router;
use App\v1\Stores\Store;
use App\v1\Ratings\ProductReviews;
use App\v1\Tools\UploadableTrait;
use Config;
use DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    use ProductTransformable,
        UploadableTrait;

    public $successStatus = 200;
    public $userId = "";
    public $user_type = "";

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepo;

    /**
     * @var AttributeValueRepositoryInterface
     */
    private $attributeValueRepository;

    /**
     * @var ProductAttribute
     */
    private $productAttribute;

    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepo;

    /**
     * ProductController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeValueRepositoryInterface $attributeValueRepository
     * @param ProductAttribute $productAttribute
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueRepositoryInterface $attributeValueRepository,
        ProductAttribute $productAttribute,
        BrandRepositoryInterface $brandRepository, Request $request
    ) {
        $this->productRepo = $productRepository;
        $this->categoryRepo = $categoryRepository;
        $this->attributeRepo = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->productAttribute = $productAttribute;
        $this->brandRepo = $brandRepository;
        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    /**
     * Api for Customer Home screen
     * @param Request $request
     *
     */
    public function getCustomerHome(Request $request)
    {

        $this->validation($request->all(),
            [
                'languageType' => 'required',
                'userLat' => 'required',
                'userLong' => 'required',
            ]);

        $languageType = $request->input('languageType');
        $userLatitude = $request->input('userLat');
        $userLongitude = $request->input('userLong');

        $qry = "SELECT s.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((storeLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((storeLat*pi()/180)) * cos(((" . $userLongitude . "- storeLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM stores s WHERE languageType =  '$languageType' AND  storeLat IS NOT NULL AND isActive = 1 order by distance ASC";
        $stores = DB::select($qry);

        $storesId = [];
        if ($stores) {
            $storesId = array_column($stores, 'id');
        }
        if (!empty($storesId)) {
            $list = $this->productRepo->listProducts('id')->where('languageType', $languageType)->where('status', 1)->whereIn('store_id', $storesId);
        } else {
            $list = $this->productRepo->listProducts('id')->where('languageType', $languageType)->where('status', 1);

        }

        $allproducts = [];
        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();

        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $value->rating = $this->getAvgRatings($value->id);
                $allproducts[] = $value;
            }

        }

        $pumps = [];

        $qry = "SELECT p.* , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((pumpLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((pumpLat*pi()/180)) * cos(((" . $userLongitude . "- pumpLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM pumps p WHERE languageType =  '$languageType' AND  pumpLat IS NOT NULL AND status = 1 order by distance ASC LIMIT 0, 5";
        $allpumps = DB::select($qry);

        if (!empty($allpumps)) {
            foreach ($allpumps as $pump) {
                $pumpinfo['pumpId'] = $pump->pumpId;
                $pumpinfo['pumpTitle'] = $pump->pumpTitle;
                $pumpinfo['pumpDescription'] = $pump->pumpDescription;
                $pumpinfo['pumpAddress'] = $pump->pumpAddress;
                $pumpinfo['pumpPrice'] = $pump->pumpPrice;
                $pumpinfo['pumpMassUnit'] = $pump->pumpMassUnit != null ? $pump->pumpMassUnit : "";
                $pumpinfo['pumpPic'] = config('constants.pump_pull_path') . $pump->pumpPic;
                $pumps[] = $pumpinfo;
            }
        }

        // Routes
        $routes = [];
        $allroutes = Router::with(['routeInfo', 'driver', 'servicer'])->where(['languageType' => $languageType, 'status' => 1])->orderBy('deliveryDate', 'desc')->get()->take(2);
        if (!empty($allroutes)) {
            foreach ($allroutes as $route) {
                $routeinfo['routeId'] = $route->id;
                $routeinfo['driverName'] = $route->driver->firstName;
                $routeinfo['servicerName'] = $route->servicer->name;
                $routeinfo['deliveryDate'] = $route->deliveryDate;
                $routeinfo['departureTime'] = $route->departureTime;
                $routeinfo['volumeContained'] = $route->volumeContained;
                $routeinfo['price'] = $route->price;
                $routeinfo['priceUnit'] = $route->priceUnit;
                $routeinfo['notifyUsers'] = $route->notifyUsers;
                $routeinfo['routeLocations'] = $route->routeInfo;
                $routes[] = $routeinfo;
            }

        }

        // Banners
        $banners = [];
        $allbanners = Banner::where(['bannerType' => 'app', 'status' => 1])->orderBy('sortOrder', 'asc')->get();
        if (!empty($allbanners)) {
            foreach ($allbanners as $banner) {
                $bannerInfo['bannerid'] = $banner->id;
                $bannerInfo['bannerImage'] = config('constants.banner_pull_path') . $banner->bannerImage;
                $banners[] = $bannerInfo;
            }
        }

        // All Store count
        $stores_count = Store::where(['languageType' => $languageType, 'isActive' => 1])->count();
        // All Pump count
        $pumps_count = Pump::with('stores')->where(['languageType' => $languageType, 'pumps.status' => 1])->count();

        // Drivers Count
        $drivers_count = Driver::where(['status' => 1])->count();

        // Routes Count
        $routes_count = Router::where(['languageType' => $languageType, 'status' => 1])->count();

        $response = [
            'banners' => $banners,
            'products' => $allproducts,
            'pumps' => $pumps,
            'routes' => $routes,
            'stores_count' => $stores_count,
            'pumps_count' => $pumps_count,
            'routes_count' => $routes_count,
            'drivers_count' => $drivers_count,

        ];

        $this->success("Customer homepage data.", $response, $this->successStatus);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allProducts(Request $request)
    {

        $this->validation($request->all(),
            [
                'languageType' => 'required',
                'userLat' => 'required',
                'userLong' => 'required',
            ]);

        $languageType = $request->input('languageType');
        $userLatitude = $request->input('userLat');
        $userLongitude = $request->input('userLong');

        $per_page = 10;
        if ($request->input('page') == 0) {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        $qry = "SELECT s.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((storeLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((storeLat*pi()/180)) * cos(((" . $userLongitude . "- storeLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM stores s WHERE languageType =  '$languageType' AND  storeLat IS NOT NULL AND isActive = 1 order by distance  DESC";
        $stores = DB::select($qry);

        $storesId = [];
        $orderstoresId = "";
        if ($stores) {
            $storesId = array_column($stores, 'id');
            $orderstoresId = implode(",", $storesId);
        }

        $data = Product::where(['status' => 1, 'languageType' => $languageType]);

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
            if ($request->input('filter_search') == 'yes') {
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
        $data->whereIn('store_id', $storesId);
        $products_count = $data->count();
        $list = $data->orderBy('name', 'ASC')->orderByRaw(DB::raw("FIELD(store_id, $orderstoresId)"))->skip($skip)->take($take)->get();

        $allproducts = [];
        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();

        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $value->rating = $this->getAvgRatings($value->id);
                $allproducts[] = $value;
            }

        }

        $response = ['products' => $allproducts, 'products_count' => $products_count];
        $this->success("All Products.", $response, $this->successStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductInfo(Request $request)
    {

        $this->validation($request->all(),
            [
                'productId' => 'required',

            ]);

        $id = $request->input('productId');

        $product = $this->productRepo->findProductById($id);
        $store = $product->store()->first();
        @$sellerInfo = Servicer::where('id', $store->servicerId)->first();
        @$store->service_provider = @$sellerInfo->name;
        $product->store = $store;
        @$product->productReviews = $this->getFullRatings($product->id);       
        $existedproductAttributes = $product->attributes()->select('id')->get();
        @$product->productSizes = [];
        if (!empty($existedproductAttributes)) {
            $existedAttributes = [];
            foreach ($existedproductAttributes as $pa) {
                foreach ($pa->attributesValues as $item) {
                    $product_attribute_id = $item->pivot->product_attribute_id;
                    $existedAttributes[] = array(
                        "attr_id" => $product_attribute_id,
                        "attr_value" => $item->value,
                    );
                }
            }
            @$product->productSizes = $existedAttributes;
        }
        if ($product) {
            $this->success("Product info.", $product);
        } else {
            $this->error("Product not found.", []);
        }

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


    private function getFullRatings($productId = 0)
    {

        $productReviews = ProductReviews::select('*')->where('productId', $productId)->get();
        $prodReviews = ProductReviews::with('customer')->select('*')->where('productId', $productId)->limit(3)->get()->toArray();
        if (!empty($productReviews)) {

            $star5 = [];
            $star4 = [];
            $star3 = [];
            $star2 = [];
            $star1 = [];
            $totalRating = [];
            $totalReviews = [];

            foreach ($productReviews as $reviews) {

                if ($reviews->customerRating == 5) {
                    $star5[] = $reviews->customerRating;
                }

                if ($reviews->customerRating == 4) {
                    $star4[] = $reviews->customerRating;
                }

                if ($reviews->customerRating == 3) {
                    $star3[] = $reviews->customerRating;
                }

                if ($reviews->customerRating == 2) {
                    $star2[] = $reviews->customerRating;
                }

                if ($reviews->customerRating == 1) {
                    $star1[] = $reviews->customerRating;
                }

                $totalRating[] = $reviews->customerRating;
                $totalReviews[] = $reviews;
            }

            // Total Star Rating
            $total = array_sum($totalRating);
            $totalAvg = $total / 5;

            // Five Star Rating
            $star5Avg = 0;
            if (!empty($star5)) {
                $star5Avg = (count($star5) * 100) / count($totalRating);
            }
            // Four Star Rating
            $star4Avg = 0;
            if (!empty($star4)) {
                $star4Avg = (count($star4) * 100) / count($totalRating);
            }
            // Three Star Rating
            $star3Avg = 0;
            if (!empty($star3)) {
                $star3Avg = (count($star3) * 100) / count($totalRating);
            }
            // Two Star Rating
            $star2Avg = 0;
            if (!empty($star2)) {
                $star2Avg = (count($star2) * 100) / count($totalRating);
            }
            // One Star Rating
            $star1Avg = 0;
            if (!empty($star1)) {
                $star1Avg = (count($star1) * 100) / count($totalRating);
            }

            $response = [];
            $response['totalAvg'] = number_format((float)$totalAvg, 1, '.', '');    
            $response['totalReviewsCount'] = count($productReviews);
            $response['reviews'] = $prodReviews;
            $response['fivestar_totalAvg'] =  number_format((float)$star5Avg, 1, '.', '');  
            $response['fivestar_totalCount'] = count($star5);

            $response['fourstar_totalAvg'] = number_format((float)$star4Avg, 1, '.', ''); 
            $response['fourstar_totalCount'] = count($star4);

            $response['threestar_totalAvg'] = number_format((float)$star3Avg, 1, '.', ''); 
            $response['threestar_totalCount'] = count($star3);

            $response['twostar_totalAvg'] = number_format((float)$star2Avg, 1, '.', ''); 
            $response['twostar_totalCount'] = count($star2);

            $response['onestar_totalAvg'] =  number_format((float)$star1Avg, 1, '.', '');  
            $response['onestar_totalCount'] = count($star1);
            return $response;
        } else {
            return [];
        }

    }


    public function getProductReviews(Request $request)
    {

        $this->validation($request->all(),
        [
            'productId' => 'required',
        ]);


        $productId = $request->input('productId');
        $per_page = 10;
        if ($request->input('page') == 0) {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        $productReviews = ProductReviews::select('*')->where('productId', $productId)->get();
        $prodReviews = ProductReviews::with('customer')->select('*')->where('productId', $productId)->orderBy("id")->skip($skip)->take($take)->get()->toArray();
        if (!empty($productReviews)) {

            $star5 = [];
            $star4 = [];
            $star3 = [];
            $star2 = [];
            $star1 = [];
            $totalRating = [];
            $totalReviews = [];

            foreach ($productReviews as $reviews) {

                if ($reviews->customerRating == 5) {
                    $star5[] = $reviews->customerRating;
                }

                if ($reviews->customerRating == 4) {
                    $star4[] = $reviews->customerRating;
                }

                if ($reviews->customerRating == 3) {
                    $star3[] = $reviews->customerRating;
                }

                if ($reviews->customerRating == 2) {
                    $star2[] = $reviews->customerRating;
                }

                if ($reviews->customerRating == 1) {
                    $star1[] = $reviews->customerRating;
                }

                $totalRating[] = $reviews->customerRating;
                $totalReviews[] = $reviews;
            }

            // Total Star Rating
            $total = array_sum($totalRating);
            $totalAvg = $total / 5;

            // Five Star Rating
            $star5Avg = 0;
            if (!empty($star5)) {
                $star5Avg = (count($star5) * 100) / count($totalRating);
            }
            // Four Star Rating
            $star4Avg = 0;
            if (!empty($star4)) {
                $star4Avg = (count($star4) * 100) / count($totalRating);
            }
            // Three Star Rating
            $star3Avg = 0;
            if (!empty($star3)) {
                $star3Avg = (count($star3) * 100) / count($totalRating);
            }
            // Two Star Rating
            $star2Avg = 0;
            if (!empty($star2)) {
                $star2Avg = (count($star2) * 100) / count($totalRating);
            }
            // One Star Rating
            $star1Avg = 0;
            if (!empty($star1)) {
                $star1Avg = (count($star1) * 100) / count($totalRating);
            }

            $response = [];
            $response['totalAvg'] = number_format((float)$totalAvg, 1, '.', '');    
            $response['totalReviewsCount'] = count($productReviews);
            $response['reviews'] = $prodReviews;
            $response['fivestar_totalAvg'] =  number_format((float)$star5Avg, 1, '.', '');  
            $response['fivestar_totalCount'] = count($star5);

            $response['fourstar_totalAvg'] = number_format((float)$star4Avg, 1, '.', ''); 
            $response['fourstar_totalCount'] = count($star4);

            $response['threestar_totalAvg'] = number_format((float)$star3Avg, 1, '.', ''); 
            $response['threestar_totalCount'] = count($star3);

            $response['twostar_totalAvg'] = number_format((float)$star2Avg, 1, '.', ''); 
            $response['twostar_totalCount'] = count($star2);

            $response['onestar_totalAvg'] =  number_format((float)$star1Avg, 1, '.', '');  
            $response['onestar_totalCount'] = count($star1);
            return $this->success('All reviews' , $response);
        } else {
            return $this->error('No reviews' , "");;
        }

    }


}
