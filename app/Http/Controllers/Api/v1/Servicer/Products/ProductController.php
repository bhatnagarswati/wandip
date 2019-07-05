<?php

namespace App\Http\Controllers\Api\v1\Servicer\Products;

use App\Http\Controllers\Controller;
use App\v1\Attributes\Repositories\AttributeRepositoryInterface;
use App\v1\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\v1\Banners\Banner;
use App\v1\Brands\Repositories\BrandRepositoryInterface;
use App\v1\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\v1\Drivers\Driver;
use App\v1\ProductAttributes\ProductAttribute;
use App\v1\Products\Product;
use App\v1\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\v1\Products\Repositories\ProductRepository;
use App\v1\Products\Requests\CreateProductRequest;
use App\v1\Products\Requests\UpdateProductRequest;
use App\v1\Products\Transformations\ProductTransformable;
use App\v1\Pumps\Pump;
use App\v1\Routers\Router;
use App\v1\Stores\Store;
use App\v1\Tools\UploadableTrait;
use Config;
use App\v1\Ratings\ProductReviews;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
     * Api for Servicer Home screen
     * @param Request $request
     *
     */
    public function getHome(Request $request)
    {

        $this->validation($request->all(),
            [
                'languageType' => 'required',
            ]);

        $languageType = $request->input('languageType');
        $storeIds = Store::where(['servicerId' => $this->userId, 'isActive' => 1])->pluck('id');
        $data = Product::where(['languageType' => $languageType]);
        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $data->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('slug', 'LIKE', "%$keyword%")
                    ->orWhere('description', 'LIKE', "%$keyword%")
                ;
            });
        }
        $data->whereIn('store_id', $storeIds);
        $products_count = $data->count();
        $list = $data->take(5)->get();

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
        $allpumps = Pump::where(['servicerId' => $this->userId, 'languageType' => $languageType, 'status' => 1])->orderBy('pumpId',
            'desc')->take(5)->get();
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
        $allroutes = Router::with(['routeInfo', 'driver', 'servicer'])->where(['servicerId' => $this->userId, 'languageType' => $languageType])->orderBy('id', 'desc')->get()->take(2);

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
        if (!empty($allroutes)) {
            foreach ($allbanners as $banner) {
                $bannerInfo['bannerid'] = $banner->id;
                $bannerInfo['bannerImage'] = config('constants.banner_pull_path') . $banner->bannerImage;
                $banners[] = $bannerInfo;
            }
        }

        // All Store count
        $stores_count = Store::where(['servicerId' => $this->userId, 'languageType' => $languageType, 'isActive' => 1])->count();
        // All Pump count
        $pumps_count = Pump::with('stores')->where(['pumps.servicerId' => $this->userId, 'languageType' => $languageType, 'pumps.status' => 1])->count();

        // Drivers Count
        $drivers_count = Driver::where(['servicerId' => $this->userId, 'status' => 1])->count();

        // Routes Count
        $routes_count = Router::where(['servicerId' => $this->userId, 'languageType' => $languageType, 'status' => 1])->count();

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

        $this->success("Home Sections.", $response, $this->successStatus);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allStoreProducts(Request $request)
    {

        $this->validation($request->all(),
            [
                'languageType' => 'required',
            ]);

        $languageType = $request->input('languageType');

        $per_page = 10;
        if ($request->input('page') == 0) {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        $storeIds = Store::where(['servicerId' => $this->userId, 'languageType' => $languageType, 'isActive' => 1])->pluck('id');

        $data = Product::where(['languageType' => $languageType]);


        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $data->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('slug', 'LIKE', "%$keyword%")
                    ->orWhere('description', 'LIKE', "%$keyword%");
            });
        }


        $bycategories = [];
        $bybrands = [];
        $byservice = [];
        $minprice = 1;
        $maxprice = 5000;
        if ($request->has('filter_search')) {
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
        $data->whereIn('store_id', $storeIds);
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
        $this->success("All Products.", $response, $this->successStatus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productsOpt(Request $request)
    {

        $this->validation($request->all(),
        [
            'languageType' => 'required',
        ]);
        $languageType = $request->input('languageType');
       
        $categories = $this->categoryRepo->listCategories('name', 'asc')->where('languageType',  $languageType)->where('status', '1')->toTree();

        // Product attributes for Sizes.
        $attr_values = $this->attributeRepo->listAttributes();
        $productAttributes = [];
        foreach($attr_values as $attribute){
            if($attribute->id == 1){
                foreach($attribute->values as $attr){             
                    $productAttributes[] = $attr;
                }
            }
        }

        $allinfo = [
            'categories' => $categories,
            'brands' => $this->brandRepo->listBrands(['*'], 'name', 'asc'),
            'weight_units' => Product::MASS_UNIT,
			'attributes' => $this->attributeRepo->listAttributes(),
			'productAttributes' => $productAttributes,
            'stores' => Store::where('servicerId', $this->userId)->select('id as storeId', 'storeTitle')->get(),
        ];
        $this->success("Product options.", $allinfo, $this->successStatus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function addProduct(CreateProductRequest $request)
    {

        // Validate incoming request

        $this->validation($request->all(),
            [
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|string',
                'brand_id' => 'required',
                'store_id' => 'required',
                'mass_unit' => 'required',
				'weight' => 'required',
				'productSizes' => 'required',
				/* 'serviceOfferedType' => 'required', */
                'languageType' => 'required',
            ]);

        $data = $request->all();

        $final_categories = "";        
        $categories = $request->input('categories');
        if(!empty($categories)){
                $final_categories = explode("," , $categories);
        }
 
        $productSizes = "";
        $req_productsizes =  $request->input('productSizes');
        if(!empty($req_productsizes)){
                $productSizes = explode("," , $req_productsizes);
        }

        
        $data['quantity'] = "100000000";
        $data['slug'] = str_slug($request->input('name'));
        $data['status'] = 1;
        $data['languageType'] = $request->input('languageType');

        if ($request->hasFile('cover') && $request->file('cover') instanceof UploadedFile) {
            $data['cover'] = $this->productRepo->saveCoverImage($request->file('cover'));
        }

        // Add Sku dynamically.
        $data['sku'] = date("Ymds");
        $pprice = $data['price'];
        
       

        unset($data['productSizes']);
        unset($data['attribute']);

        $product = $this->productRepo->createProduct($data);
        // Add Attributes
        if (!empty($productSizes)) {
            foreach ($productSizes as $sizes) {
                $attributeData = [];
                $attributeData['attributeValue'] = $sizes;
                $attributeData['productAttributeQuantity'] = ($sizes * 1000);
                // will not use product
                $attributeData['productAttributePrice'] = ($sizes * $pprice);
                $attributeData['default'] = 0;
                $this->saveProductCombinations($attributeData, $product);
            }
        }

        $productRepo = new ProductRepository($product);
        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
        }

        if (!empty($final_categories)) {
            $productRepo->syncCategories($final_categories);
        } else {
            $productRepo->detachCategories();
        }

        // Update product for sku
        $update_product_sku = date("Ymds") . 's' . $data['store_id'] . $data['mass_unit'] . 'p' . $product->id . 'b' . $data['brand_id'];
        $skudata['sku'] = $update_product_sku;
        $productRepo->updateProduct($skudata);
        $updatedProduct = $this->productRepo->findProductById($product->id);
       
        $this->success("Product Created successfully", $updatedProduct);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getProduct(Request $request)
    {

        $this->validation($request->all(),
            [
                'productId' => 'required',

            ]);

        $id = $request->input('productId');
        $product = $this->productRepo->findProductById($id);
        $product->productReviews = $this->getFullRatings($id);
        if ($product) {
            $this->success("Product info.", $product);
        } else {
            $this->error("Product not found.", []);
        }

    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\v1\Products\Exceptions\ProductUpdateErrorException
     */
    public function update(Request $request)
    {

        $this->validation($request->all(),
            [
                'productId' => 'required',
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|string',
                'brand_id' => 'required',
				'store_id' => 'required',
				'productSizes' => 'required',
                'weight' => 'required',
				'mass_unit' => 'required',
				'serviceOfferedType' => 'required',
                'languageType' => 'required',
            ]);

        $id = $request->input('productId');

        $data = $request->except('categories', 'image', 'productId');	
        
        $final_categories = "";        
        $categories = $request->input('categories');
        if(!empty($categories)){
                $final_categories = explode("," , $categories);
        }

        $final_productsizes = "";
        $productSizes = "";
        $final_productsizes =  $request->input('productSizes');
        if(!empty($final_productsizes)){
                $productSizes = explode("," , $final_productsizes);
        }
           
		$product = $this->productRepo->findProductById($id);
		
        $productRepo = new ProductRepository($product);
        $existedproductAttributes = $product->attributes()->select('id')->get()->toArray();
 
        $existedAttributes = "";
        if (!empty($productSizes)) {
            $pprice = $data['price'];
            $existedAttributes = array_column($existedproductAttributes, 'id');
            $existingresults = @array_intersect(@$existedAttributes, @$productSizes);
            $resultstodelete = @array_diff(@$existedAttributes, @$productSizes);
            $resultstoadd = @array_diff(@$productSizes, @$existedAttributes);
 
            // Add New Attribues
            if (!empty(@$resultstoadd)) {
                foreach ($resultstoadd as $sizes) {

                    $attributeData = [];
                    $attributeData['attributeValue'] = $sizes;
                    $attributeData['productAttributeQuantity'] = ($sizes * 1000);
                    // will not use product
                    $attributeData['productAttributePrice'] = ($sizes * $pprice);
                    $attributeData['default'] = 0;
                    $this->saveProductCombinations($attributeData, $product);
                }
            }
            // Delete Attribues
            if (!empty(@$resultstodelete)) {
                $this->removeProductCombination($resultstodelete, $id);
            }
        }
		
		unset($data['productSizes']);
		unset($data['attribute']);

        $data['slug'] = str_slug($request->input('name'));
        $data['status'] = 1;
        $data['languageType'] = $request->input('languageType');
        $update_product_sku = date("Ymds") . 's' . $data['store_id'] . $data['mass_unit'] . 'p' . $id . 'b' . $data['brand_id'];
        $data['sku'] = $update_product_sku;

        if ($request->hasFile('cover')) {
            $data['cover'] = $productRepo->saveCoverImage($request->file('cover'));
        }

        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
        }

        if (!empty($final_categories)) {
            $productRepo->syncCategories($final_categories);
        } else {
            $productRepo->detachCategories();
        }
        $productRepo->updateProduct($data);
        $updatedProduct = $this->productRepo->findProductById($id);

        $this->success("Product Updated successfully", $updatedProduct);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function deleteProduct(Request $request)
    {

        $this->validation($request->all(),
            [
                'productId' => 'required',

            ]);

        $id = $request->input('productId');

        $product = $this->productRepo->findProductById($id);
        $product->categories()->sync([]);
        $productAttr = $product->attributes();

        $productAttr->each(function ($pa) {
            DB::table('attribute_value_product_attribute')->where('product_attribute_id',
                $pa->id)->delete();
        });

        $productAttr->where('product_id', $product->id)->delete();
        $productRepo = new ProductRepository($product);
        $productRepo->removeProduct();

        $this->success("Product deleted successfully", "");

    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $this->productRepo->deleteFile($request->only('product', 'image'), 'uploads');
        return redirect()->back()->with('message', 'Image delete successful');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeThumbnail(Request $request)
    {

        $this->validation($request->all(),
            [
                'imageSrc' => 'required|string',
            ]);

        $imageSrc = $request->input('imageSrc');
        $path = asset('storage/app/public/') . '/';
        $imgTodel = str_replace($path, "", $imageSrc);
        @unlink($_SERVER['DOCUMENT_ROOT'] . '/storage/app/public/' . $imgTodel);
        $this->productRepo->deleteThumb($imgTodel);
        $this->success('Image delete successful', "");
    }

   /**
     * @param Request $fields
     * @param Product $product
     * @return boolean
     */
    private function saveProductCombinations($fields, Product $product): bool
    {

        $quantity = $fields['productAttributeQuantity'];
        $price = $fields['productAttributePrice'];

        $sale_price = null;
        if (isset($fields['sale_price'])) {
            $sale_price = $fields['sale_price'];
        }

        $attributeValues = $fields['attributeValue'];
        $productRepo = new ProductRepository($product);

        $hasDefault = $productRepo->listProductAttributes()->where('default', 1)->count();

        $default = 0;
        if (isset($fields['default'])) {
            $default = $fields['default'];
        }

        if ($default == 1 && $hasDefault > 0) {
            $default = 0;
        }

        $productAttribute = $productRepo->saveProductAttributes(
            new ProductAttribute(compact('quantity', 'price', 'sale_price', 'default'))
        );

        // save the combinations
        return collect($attributeValues)->each(function ($attributeValueId) use ($productRepo, $productAttribute) {
            $attribute = $this->attributeValueRepository->find($attributeValueId);
            return $productRepo->saveCombination($productAttribute, $attribute);
        })->count();
    }

    /**
     *
     * @param  $arrtibuteId (int)
     * @param  $productId (int)
     * @return boolean
     *
     */
    private function removeProductCombination($attributeId = [], $productId = 0)
    {
        $product = $this->productRepo->findProductById($productId);
        $productAttributes = $product->attributes()->get();
        if (!empty($attributeId)) {
            foreach ($attributeId as $attrId) {
                $pa = $productAttributes->where('id', $attrId)->first();
                $pa->attributesValues()->detach();
                $pa->delete();
            }
        }
        return true;

    }
    /**
     * @param array $data
     *
     * @return
     */
    private function validateFields(array $data)
    {
        $validator = Validator::make($data,
            [
                'productAttributeQuantity' => 'required',
            ]);

        if ($validator->fails()) {
            return $validator;
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
