<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Ratings\ProductReviews;
use App\Shop\Stores\Store;
use Config;

class ProductController extends Controller
{
    use ProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        if (request()->has('q') && request()->input('q') != '') {
            $list = $this->productRepo->searchProduct(request()->input('q'))->where('languageType', Config::get('app.locale'));
        } else {
            $list = $this->productRepo->listProducts()->where('languageType', Config::get('app.locale'));
        }

        $products = $list->where('status', 1)->map(function (Product $item) {
            return $this->transformProduct($item);
        });

        return view('front.products.product-search', [
            'products' => $this->productRepo->paginateArrayResults($products->all(), 16),
        ]);
    }

    /**
     * Get the product
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $slug)
    {
        $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        $images = $product->images()->get();
        $category = $product->categories()->first();
        $brand = $product->brand()->first();
        $store = $product->store()->first();
        @$sellerInfo = Store::with('service_provider')->where('id', $product->store_id)->first();
        $productAttributes = $product->attributes;
        $reviews = [];
        //rating and reviews
        $ratingAndReviews = $this->getRatings($product->id);
        if ($ratingAndReviews) {
            $reviews = $ratingAndReviews;
        }

        return view('front.products.product', compact(
            'product',
            'images',
            'productAttributes',
            'category',
            'brand',
            'sellerInfo',
            'store',
            'reviews',
            'combos'
        ));
    }

    public function getRatings($productId = 0)
    {

        $productReviews = ProductReviews::with('customer')->select('*')->where('productId', $productId)->get();
        $prodReviews = ProductReviews::with('customer')->select('*')->where('productId', $productId)->limit(3)->get();
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
            $response['totalAvg'] = $totalAvg;
            $response['totalReviewsCount'] = count($productReviews);
            $response['reviews'] = $prodReviews;
            $response['fivestar_totalAvg'] = round($star5Avg, 2);
            $response['fivestar_totalCount'] = count($star5);

            $response['fourstar_totalAvg'] = round($star4Avg, 2);
            $response['fourstar_totalCount'] = count($star4);

            $response['threestar_totalAvg'] = round($star3Avg, 2);
            $response['threestar_totalCount'] = count($star3);

            $response['twostar_totalAvg'] = round($star2Avg, 2);
            $response['twostar_totalCount'] = count($star2);

            $response['onestar_totalAvg'] = round($star1Avg, 2);
            $response['onestar_totalCount'] = count($star1);
            return $response;
        } else {
            return false;
        }

    }




    public function allRatingsReviews($slug = "")
    {

        $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        $productReviews = ProductReviews::with('customer', 'product')->select('*')->where('productId',  $product->id)->get();
        $prodReviews = ProductReviews::with('customer', 'product')->select('*')->where('productId',  $product->id)->get();
       

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
            $response['totalAvg'] = $totalAvg;
            $response['totalReviewsCount'] = count($productReviews);
            $response['reviews'] = $prodReviews;
            $response['fivestar_totalAvg'] = round($star5Avg, 2);
            $response['fivestar_totalCount'] = count($star5);

            $response['fourstar_totalAvg'] = round($star4Avg, 2);
            $response['fourstar_totalCount'] = count($star4);

            $response['threestar_totalAvg'] = round($star3Avg, 2);
            $response['threestar_totalCount'] = count($star3);

            $response['twostar_totalAvg'] = round($star2Avg, 2);
            $response['twostar_totalCount'] = count($star2);

            $response['onestar_totalAvg'] = round($star1Avg, 2);
            $response['onestar_totalCount'] = count($star1);

            return view('front.rating.allreviews', compact(
                'product',
                'response'                 
            ));
 
        }
 


}
