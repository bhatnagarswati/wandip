<?php

namespace App\Http\Controllers\Api\v1\Customer\Ratings;

use App\Http\Controllers\Controller;
use App\Shop\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\Shop\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Orders\Order;
use App\Shop\Ratings\ProductReviews;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\Orders\Repositories\OrderRepository;
use App\Shop\Orders\Transformers\OrderTransformable;
use App\Shop\Provinces\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Shop\RouteRequests\RouteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;

class RatingController extends Controller
{
    use OrderTransformable;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @var CourierRepositoryInterface
     */
    private $courierRepo;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepo;

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepo;

    /**
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepo;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    private $user;

    use ProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;


    public $successStatus = 200;
    public $userId = "";
    public $user_type = "";

    use ProductTransformable;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

   

    /**
     * AccountsController constructor.
     *
     * @param CourierRepositoryInterface $courierRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param CountryRepositoryInterface  $countryRepository
     * @param CityRepositoryInterface     $cityRepository
     * @param ProvinceRepositoryInterface $provinceRepository
     */
    public function __construct(
        CourierRepositoryInterface $courierRepository,
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        CountryRepositoryInterface $countryRepository,
        CityRepositoryInterface $cityRepository,
        ProductRepositoryInterface $productRepository,
        ProvinceRepositoryInterface $provinceRepository,
        Request $request
    ) {
        $this->customerRepo = $customerRepository;
        $this->courierRepo = $courierRepository;
        $this->orderRepo = $orderRepository;
        $this->countryRepo = $countryRepository;
        $this->provinceRepo = $provinceRepository;
        $this->cityRepo = $cityRepository;
        $this->productRepo = $productRepository;
        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

     
    public function submitReview(Request $request){

        // validate incoming request
        $this->validation($request->all(), [
            'rating' => 'required',
            'rating_title' => 'required|string',
            'user_review' => 'required|string',
            'orderid' => 'required',             
            'product_id' => 'required'             
        ]);
       
        $rating = $request->input('rating');
        $rating_title = $request->input('rating_title');
        $user_review = $request->input('user_review');
        $product_id = $request->input('product_id');
        $orderid = $request->input('orderid');
        $customerId = $this->userId;
        $order = Order::where(['id' => $orderid, 'customer_id' => $customerId])->first();
        $reviewExist = ProductReviews::where(['productId' => $product_id, 'customerId' => $customerId])->first();
        $product = $this->productRepo->findProductById($product_id);
        if(!empty($order) && !empty($product)){

            if(!$reviewExist){
                $reviewData = array(
                    'productId'=> $product->id,
                    'customerId'=>  $this->userId ,
                    'reviewTitle'=>  addslashes($rating_title),
                    'reviewDescription'=> addslashes($user_review),
                    'customerRating'=>  $rating,
                    'status'=>  1,
                 );
                 $res = ProductReviews::create($reviewData);
                 if($res){
                    $this->success("Review added successfully." , "");
                 } 

            }else{
                $this->error("Review already given." , "");
            }
            
        }else{
            $this->error('No product found' , "");
        }    
    }

}
