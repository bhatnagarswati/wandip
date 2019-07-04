<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['namespace' => 'Api'], function () {
    Route::group(['namespace' => 'v1'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
        Route::post('forgotPassword', 'AuthController@forgotPassword');
        Route::post('sendConfirmationOtp', 'AuthController@sendConfirmationOtp');

        Route::middleware('APIToken')->group(function () {

            Route::get('logout', 'AuthController@logout');
            Route::post('verifyOtp', 'AuthController@verifyOtp');
            Route::post('resendOtp', 'AuthController@resendOtp');
            Route::post('updateQuickBlockId', 'AuthController@updateQuickBlockId');

			// Service Provider Api's
            Route::group(['namespace' => 'Servicer'], function () {
                Route::post('servicerHome', 'Products\ProductController@getHome');
                Route::get('getProfile', 'ServicerController@getProfile');
                Route::post('updateProfile', 'ServicerController@updateProfile');
                Route::post('updatePassword', 'ServicerController@updatePassword');
                Route::post('updatePushNotify', 'ServicerController@updatePushNotification');
                

                // Products
                Route::post('storeProducts', 'Products\ProductController@allStoreProducts');
                Route::post('productsOpts', 'Products\ProductController@productsOpt');
                Route::post('addProduct', 'Products\ProductController@addProduct');
                Route::post('updateProduct', 'Products\ProductController@update');
                Route::post('getProduct', 'Products\ProductController@getProduct');
                Route::post('deleteProduct', 'Products\ProductController@deleteProduct');
                Route::post('removeThumbnail', 'Products\ProductController@removeThumbnail');
                Route::post('allProductReviews', 'Products\ProductController@getProductReviews');
                
                // Stores
                Route::post('allStores', 'Stores\StoreController@allStores');
                Route::post('addStore', 'Stores\StoreController@addStore');
                Route::post('editStore', 'Stores\StoreController@editStore');
                Route::post('updateStore', 'Stores\StoreController@updateStore');
                Route::post('deleteStore', 'Stores\StoreController@deleteStore');
                Route::post('storeInfo', 'Stores\StoreController@storeInfo');

                // Stores
                Route::post('allPumps', 'Pumps\PumpController@allPumps');
                Route::get('servicerStores', 'Pumps\PumpController@servicerStores');
                Route::post('addPump', 'Pumps\PumpController@addPump');
                Route::post('getPump', 'Pumps\PumpController@getPumpInfo');
                Route::post('updatePump', 'Pumps\PumpController@updatePump');
                Route::post('deletePump', 'Pumps\PumpController@deletePump');

                // Drivers
                Route::post('allDrivers', 'Drivers\DriverController@allDrivers');
                Route::post('addDriver', 'Drivers\DriverController@listNewDriver');
                Route::post('updateDriver', 'Drivers\DriverController@updateDriver');
                Route::post('driverProfile', 'Drivers\DriverController@driverInfo');
                Route::post('deleteDriver', 'Drivers\DriverController@deleteDriver');

                // Routes
                Route::post('allRoutes', 'Routers\RouterController@allRoutes');
                Route::post('addRoute', 'Routers\RouterController@addRoute');
                Route::post('updateRoute', 'Routers\RouterController@updateRoute');
                Route::post('deleteRoute', 'Routers\RouterController@deleteRoute');
                Route::post('getRouteInfo', 'Routers\RouterController@getRouteInfo');

                // Route Requests
                Route::post('getServicerCustomersRequest', 'RouteRequests\RouteRequestController@getAllRequestsOnRoute');

                // Brands
                Route::post('allBrands', 'Brands\BrandController@allBrands');
                Route::post('addBrand', 'Brands\BrandController@addBrand');
                Route::post('updateBrand', 'Brands\BrandController@updateBrand');
 
                // Orders
                Route::post('allOrders', 'Orders\OrderController@allOrders');
                
                

            });

            // Driver Api's
            Route::group(['namespace' => 'Driver'], function () {

                Route::get('getDriverProfile', 'DriverController@getProfile');
                Route::post('updateDriverProfile', 'DriverController@updateProfile');
                Route::post('updateDriverPushNotify', 'DriverController@updatePushNotification');

                // Routes listing
                Route::post('allDriverRoutes', 'Routers\RouterController@allRoutes');
                Route::post('getDriverRouteInfo', 'Routers\RouterController@getRouteInfo');
                Route::post('getCustomerRequests', 'RouteRequests\RouteRequestController@getAllRequestsOnRoute');
                Route::post('markDelivered', 'RouteRequests\RouteRequestController@markDelivered');
                

            });


            // Customer Api's
            Route::group(['namespace' => 'Customer'], function () {

                Route::get('getCustomerProfile', 'CustomerController@getProfile');
                Route::post('updateCustomerProfile', 'CustomerController@updateProfile');
                Route::post('updateCustomerPassword', 'CustomerController@updatePassword');
                Route::post('updateCustomerPushNotify', 'CustomerController@updatePushNotification');
                

                Route::post('customerHome', 'Products\ProductController@getCustomerHome');
                Route::post('allProducts', 'Products\ProductController@allProducts');
                Route::post('getProductInfo', 'Products\ProductController@getProductInfo');
                Route::post('productReviews', 'Products\ProductController@getProductReviews');
                
                // Routes section
                Route::post('customerAllRoutes', 'Routers\RouteController@customerAllRoutes');
                Route::post('routeDetail', 'Routers\RouteController@getRouteDetail');
                Route::post('submitRouteRequest', 'Routers\RouteController@submitRouteReq');
                Route::post('cancelRouteRequest', 'Routers\RouteController@cancelRouteReq');

                // Pumps
                Route::post('customerAllPumps', 'Pumps\PumpController@allPumps');
                Route::post('pumpDetail', 'Pumps\PumpController@pumpDetail');
                Route::post('relatedProducts', 'Pumps\PumpController@storeProducts');

                // Cart
                Route::post('addToCart', 'Cart\CartController@addItemsToCart');
                Route::post('updateCart', 'Cart\CartController@updateCart');
                Route::post('removeCartItem', 'Cart\CartController@removeCartItem');
                
                Route::post('getCart', 'Cart\CartController@getCart');
                //Route::post('deleteCart', 'Cart\CartController@deleteCart');
                

                 // Orders
                 Route::post('customerOrders', 'Orders\OrderController@customerOrders');
                 Route::post('addRating', 'Ratings\RatingController@submitReview');
            });
            


        });


    });
});
