<?php

namespace App\Http\Controllers\Front;

use App\Shop\Attributes\Repositories\AttributeRepositoryInterface;
use App\Shop\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\Shop\Banners\Banner;
use App\Shop\Brands\Repositories\BrandRepositoryInterface;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Customers\Customer;
use App\Shop\Pages\CmsPage;
use App\Shop\ProductAttributes\ProductAttribute;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Servicers\Servicer;
use App\Shop\Tools\UploadableTrait;
use Auth;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Validator;
use App\Shop\Teams\Team;

class HomeController
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    use ProductTransformable,
        UploadableTrait;

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

    public function index()
    {

        return view('front.welcome');
    }

    // Home Page
    public function home(Request $request)
    {

        // Banners
        $web_banners = Banner::where(['status' => 1, 'bannerType' => 'web'])->orderBy('sortOrder', 'ASC')->get();
        // About us Page
        $aboutData = $this->getCmsPage(1);
        // Our App Page
        $ourAppData = $this->getCmsPage(2);
        // Our Blog Page
        $blogData = $this->getCmsPage(3);

        // Producrs
        if ($request->session()->has('sessionUserLat') && $request->session()->has('sessionUserLong')) {
            $userLatitude = $request->session()->get('sessionUserLat');
            $userLongitude = $request->session()->get('sessionUserLong');
        } else {
            $userLatitude = env('DEFAULT_LAT');
            $userLongitude = env('DEFAULT_LONG');
        }
        // Selected Language
        $languageType = Config::get('app.locale');
        $storesId = [];

        if (!empty($userLongitude) && !empty($userLongitude)) {
            $qry = "SELECT s.id , (((acos(sin((" . $userLatitude . "*pi()/180)) * sin((storeLat*pi()/180))+cos((" . $userLatitude . "*pi()/180)) * cos((storeLat*pi()/180)) * cos(((" . $userLongitude . "- storeLong)*pi()/180))))*180/pi())*60*1.1515) as distance FROM stores s WHERE languageType =  '$languageType' AND  storeLat IS NOT NULL order by distance ASC LIMIT 0, 10";
            $stores = DB::select($qry);
            $storesId = [];
            if ($stores) {
                $storesId = array_column($stores, 'id');
            }
        }
        if (!empty($storesId)) {
            $list = $this->productRepo->listProducts('id')->where('languageType', $languageType)->whereIn('store_id', $storesId);
        } else {
            $list = $this->productRepo->listProducts('id')->where('languageType', $languageType);
        }
        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();

        return view('front.home', compact('cat1', 'cat2', 'web_banners', 'aboutData', 'ourAppData', 'blogData', 'products'));

    }

    // Shop Page
    public function shop()
    {
        $cat1 = $this->categoryRepo->findCategoryById(2);
        $cat2 = $this->categoryRepo->findCategoryById(3);

        return view('front.index', compact('cat1', 'cat2'));

    }

    // CMS Pages start

    //About us Page
    public function getCmsPage($pageId = 0)
    {
        //$res = CmsPage::where(['id' => $pageId, 'pageType' => 'website', 'languageType' => Config::get('app.locale')])->first();
        $res = CmsPage::where(['id' => $pageId, 'pageType' => 'website'])->first();
        return $res;
    }
    //About us Page
    public function aboutUs()
    {

        $pageId = 1;
        //$res = CmsPage::where(['id' => $pageId, 'pageType' => 'website', 'languageType' => Config::get('app.locale')])->first();
        $aboutPage = CmsPage::where(['id' => $pageId, 'pageType' => 'website'])->first();
        $team_members = Team::where('status', 1)->get();
        return view('front.about-us', compact('aboutPage'));
    }

    //About us Page
    public function mobileApps()
    {

        $pageId = 2;
        $apps = CmsPage::where(['id' => $pageId, 'pageType' => 'website'])->first();
        return view('front.mobile-apps', compact('apps'));
    }

    // CMS Pages End

    public function setlocation(Request $request)
    {
        $userLat = $request->input('latitude');
        $userLong = $request->input('longitude');

        if ($request->session()->has('sessionUserLat') && $request->session()->has('sessionUserLong')) {
            if (Auth::check()) {
                $userLatitude = Auth::user()->customerLat;
                $userLongitude = Auth::user()->customerLong;
                if (!empty($userLatitude) && !empty($userLongitude)) {

                    $request->session()->put('sessionUserLat', $userLatitude);
                    $request->session()->put('sessionUserLong', $userLongitude);
                }
            }
        } else {
            if (Auth::check()) {
                $userLatitude = Auth::user()->customerLat;
                $userLongitude = Auth::user()->customerLong;
                if (!empty($userLatitude) && !empty($userLongitude)) {
                    $request->session()->put('sessionUserLat', $userLatitude);
                    $request->session()->put('sessionUserLong', $userLongitude);
                } else {
                    $request->session()->put('sessionUserLat', $userLat);
                    $request->session()->put('sessionUserLong', $userLong);
                }
            } else {
                $request->session()->put('sessionUserLat', $userLat);
                $request->session()->put('sessionUserLong', $userLong);
            }

        }

        $location = "Coordinates Lat: " . $request->session()->get('sessionUserLat') . " Lang: " . $request->session()->get('sessionUserLong');
        echo @$location;

        /* ini_set('allow_url_fopen', 1);
    if (!empty($userLat) && !empty($userLong)) {

    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($userLat) . ',' . trim($userLong) . '&key=' . env("GOOGLE_KEY") . '&sensor=false';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $contents = curl_exec($ch);
    if (curl_errno($ch)) {
    echo curl_error($ch);
    $contents = '';
    } else {
    curl_close($ch);
    }

    if (!is_string($contents) || !strlen($contents)) {
    //echo "Failed to get contents.";
    $location = '';
    } else {

    @$data = json_decode($contents);

    $location = "Coordinates Lat: " . $userLat . " Lang: " . $userLong;
    }

    echo @$location;

    } */
    }

    /**
     * Reset password by confirming email for Apps
     *
     * @return response
     */
    public function resetPassword(Request $request)
    {

        $user = $request->input('u');
        $user_type = $request->input('ut');
        $usertoken = $request->input('t');

        if (!empty($user) && !empty($usertoken)) {
            if ($user_type == 'servicer') {
                $check_token = Servicer::where(['id' => $user, 'verification_token' => $usertoken])->first();
                if ($check_token) {
                    return view('emails.forgot.reset_password', compact('user', 'usertoken', 'user_type'));
                } else {
                    return view('emails.forgot.expire_link');
                }
            } else if ($user_type == 'customer') {
                $check_token = Customer::where(['id' => $user, 'verification_token' => $usertoken])->first();
                if ($check_token) {
                    return view('emails.forgot.reset_password', compact('user', 'usertoken', 'user_type'));
                } else {
                    return view('emails.forgot.expire_link');
                }
            } else {
                return view('layouts.errors.404');
            }

        } else {
            return view('errors.404');
        }
    }
    /**
     * Save Reset password by confirming email for Apps
     *
     * @return response
     */
    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "user" => "required",
            "usertoken" => "required",
            "user_type" => "required",
        ]);

        $user = $request->input('user');
        $usertoken = $request->input('usertoken');
        $user_type = $request->input('user_type');

        $newPassword = Hash::make($request->input('password'));
        if ($user_type == 'servicer') {
            $token_exist = Servicer::where(['id' => $user, 'verification_token' => $usertoken])->first();
            if ($token_exist) {
                $update = Servicer::where(['id' => $user, 'verification_token' => $usertoken])->update(['verification_token' => null, 'password' => $newPassword]);
                if ($update) {
                    return view('emails.forgot.thankyou', compact('user', 'usertoken'));
                } else {
                    Session::flash('message', 'Somthing went wrong, Please try again!');
                    Session::flash('alert-class', 'alert-danger');
                    return view('emails.forgot.resetpass', compact('user', 'usertoken', 'user_type'));
                }
            } else {
                return view('emails.forgot.alert');
            }
        } else if ($user_type == 'customer') {
            $token_exist = Customer::where(['id' => $user, 'verification_token' => $usertoken])->first();
            if ($token_exist) {
                $update = Customer::where(['id' => $user, 'verification_token' => $usertoken])->update(['verification_token' => null, 'password' => $newPassword]);
                if ($update) {
                    return view('emails.forgot.thankyou', compact('user', 'usertoken'));
                } else {
                    Session::flash('message', 'Somthing went wrong, Please try again!');
                    Session::flash('alert-class', 'alert-danger');
                    return view('emails.forgot.resetpass', compact('user', 'usertoken', 'user_type'));
                }
            } else {
                return view('emails.forgot.alert');
            }
        }

    }

}
