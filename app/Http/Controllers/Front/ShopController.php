<?php

namespace App\Http\Controllers\Front;

use App\Shop\Attributes\Repositories\AttributeRepositoryInterface;
use App\Shop\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\Shop\Brands\Repositories\BrandRepository;
use App\Shop\Brands\Repositories\BrandRepositoryInterface;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\ProductAttributes\ProductAttribute;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Tools\UploadableTrait;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopController
{

    use ProductTransformable,
        UploadableTrait;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

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
     * HomeController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */

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
        ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository, AttributeRepositoryInterface $attributeRepository, AttributeValueRepositoryInterface $attributeValueRepository, ProductAttribute $productAttribute, BrandRepositoryInterface $brandRepository) {

        $this->productRepo = $productRepository;
        $this->categoryRepo = $categoryRepository;
        $this->attributeRepo = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->productAttribute = $productAttribute;
        $this->brandRepo = $brandRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    // Shop Page
    public function shop(Request $request)
    {

        if ($request->session()->has('sessionUserLat') && $request->session()->has('sessionUserLong')) {
            $userLatitude = $request->session()->get('sessionUserLat');
            $userLongitude = $request->session()->get('sessionUserLong');
        } else {
            $userLatitude = env('DEFAULT_LAT');
            $userLongitude = env('DEFAULT_LONG');
        }

        $limit = 15;
        if ($request->input('page') != "") {
            $pageno = $request->input('page');
        } else {
            $pageno = 1;
        }
        $start_from = ($pageno - 1) * $limit;

       
        // Selected Language
        $languageType = Config::get('app.locale');

        $qry = "SELECT s.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((storeLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((storeLat*pi()/180)) * cos(((" . $userLongitude . "- storeLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM stores s WHERE languageType =  '$languageType' AND  storeLat IS NOT NULL AND isActive = 1 order by distance ASC";
        $stores = DB::select($qry);

        $storeIds = [];
        if ($stores) {
            $storeIds = array_column($stores, 'id');
        }

        $data = Product::where(['languageType' => $languageType]);

        $bycategories = [];
        $bybrands = [];
        $byservice = [];
        $sortOrder = "sort_asc";
        $minprice = 1;
        $maxprice = 5000;
        if ($request->has('filter_search')) {
            $minprice = $request->input('minval');
            $maxprice = $request->input('maxval');
            $bycategories = $request->input('bycategories');
            $bybrands = $request->input('bybrands');
            $byservice = $request->input('byservice');
            $sortOrder = $request->input('shop_sort_filter');

            if (!empty($bycategories)) {
                $cat_products = DB::table('category_product')->whereIn('category_id', $bycategories)->distinct('product_id')->pluck('product_id')->toArray();
                if (!empty($cat_products)) {
                    $data->whereIn('id', $cat_products);
                }
            }
            if (!empty($bybrands)) {
                $data->whereIn('brand_id', $bybrands);
            }
            if (!empty($byservice)) {
                $data->whereIn('serviceOfferedType', $byservice);
            }
            if (!empty($minprice) && !empty($maxprice)) {
                $data->whereBetween('price', [$minprice, $maxprice]);
            }

        }

        $data->whereIn('store_id', $storeIds);
        $products_count = $data->count();

        if ($sortOrder != "") {
  
            if ($sortOrder == 'sort_asc') {
                $data->orderBy('name', 'ASC');
            } else if ($sortOrder == 'sort_desc') {
                $data->orderBy('name', 'DESC');

            } else if ($sortOrder == 'price_asc') {
                $data->orderBy('price', 'ASC');

            } else if ($sortOrder == 'price_desc') {
                $data->orderBy('price', 'DESC');
            } else {
                $data->orderBy('id', 'DESC');
            }

        }else{
            $data->orderBy('name', 'ASC');
        }
        
        $list = $data->skip($start_from)->take($limit)->get();
         
        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();

        $pagination = new LengthAwarePaginator($products, $products_count, $limit, $pageno);
        $pagination->setPath(request()->url());

        // Load Categories
        $categories = $this->categoryRepo->rootCategories('created_at', 'desc');
        // Load Brands
        $brands = $this->brandRepo->listBrands(['*'], 'name', 'asc')->where('languageType', Config::get('app.locale'))->where('status', '1')->all();

        return view('front.index', compact('products', 'pagination', 'products_count', 'categories', 'brands', 'bycategories', 'bybrands', 'byservice', 'sortOrder', 'minprice', 'maxprice'));

    }

}
