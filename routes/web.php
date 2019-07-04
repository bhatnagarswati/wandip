<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::namespace ('Admin')->group(function () {
    Route::get('admin/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('admin/login', 'LoginController@login')->name('admin.login');
    Route::get('admin/logout', 'LoginController@logout')->name('admin.logout');
});
Route::group(['prefix' => 'admin', 'middleware' => ['employee'], 'as' => 'admin.'], function () {
    Route::namespace ('Admin')->group(function () {
        Route::group(['middleware' => ['role:admin|superadmin|clerk, guard:employee']], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::namespace ('Products')->group(function () {
                Route::resource('products', 'ProductController');
                Route::get('remove-image-product', 'ProductController@removeImage')->name('product.remove.image');
                Route::get('remove-image-thumb', 'ProductController@removeThumbnail')->name('product.remove.thumb');
            });
            Route::namespace ('Customers')->group(function () {
                Route::resource('customers', 'CustomerController');
                Route::resource('customers.addresses', 'CustomerAddressController');
            });
            Route::namespace ('Categories')->group(function () {
                Route::resource('categories', 'CategoryController');
                Route::get('remove-image-category', 'CategoryController@removeImage')->name('category.remove.image');
            });
            Route::namespace ('Orders')->group(function () {
                Route::resource('orders', 'OrderController');
                Route::resource('order-statuses', 'OrderStatusController');
                Route::get('orders/{id}/invoice', 'OrderController@generateInvoice')->name('orders.invoice.generate');
            });

            Route::namespace ('Routers')->group(function () {
                Route::resource('routers', 'RouterController');
            });
            Route::namespace ('Banners')->group(function () {
                Route::resource('banners', 'BannerController');
            });
            Route::namespace ('Pages')->group(function () {
                Route::resource('pages', 'PageController');
            });

            Route::namespace ('Servicers')->group(function () {
                Route::resource('servicers', 'ServicerController');
            });
            Route::namespace ('Stores')->group(function () {
                Route::resource('stores', 'StoreController');
            });
            Route::namespace ('Pumps')->group(function () {
                Route::resource('pumps', 'PumpController');
            });
            Route::namespace ('Drivers')->group(function () {
                Route::resource('drivers', 'DriverController');
            });

            // Blogs
            Route::namespace ('Blogs')->group(function () {
                Route::resource('blogs', 'BlogController');
            });

            Route::namespace ('RouteRequests')->group(function () {
                Route::resource('requests', 'RouteRequestController');
            });

            Route::get('/route/{id}/requests', 'RouteRequests\RouteRequestController@index');

            Route::get('/requests/{id}/cancel-request', 'RouteRequests\RouteRequestController@cancelRequest');
            Route::get('/requests/{id}/activate-request', 'RouteRequests\RouteRequestController@activateRequest');


            Route::resource('addresses', 'Addresses\AddressController');
            Route::resource('countries', 'Countries\CountryController');
            Route::resource('countries.provinces', 'Provinces\ProvinceController');
            Route::resource('countries.provinces.cities', 'Cities\CityController');
            Route::resource('couriers', 'Couriers\CourierController');
            Route::resource('attributes', 'Attributes\AttributeController');
            Route::resource('attributes.values', 'Attributes\AttributeValueController');
            Route::resource('brands', 'Brands\BrandController');
        });
        Route::group(['middleware' => ['role:admin|superadmin, guard:employee']], function () {
            Route::resource('employees', 'EmployeeController');
            Route::get('employees/{id}/profile', 'EmployeeController@getProfile')->name('employee.profile');
            Route::put('employees/{id}/profile', 'EmployeeController@updateProfile')->name('employee.profile.update');
            Route::resource('roles', 'Roles\RoleController');
            Route::resource('permissions', 'Permissions\PermissionController');
        });
    });
});

/**
 * Servicer routes
 */
Route::namespace ('Servicer')->group(function () {
    Route::get('servicer/login', 'LoginController@showLoginForm')->name('servicer.login');
    Route::post('servicer/login', 'LoginController@login')->name('servicer.login');
    Route::get('servicer/logout', 'LoginController@logout')->name('servicer.logout');

    Route::get('servicer/{id}/confirmotp', 'RegisterController@confirmotp');
   
});
Route::group(['prefix' => 'servicer', 'middleware' => ['servicer'], 'as' => 'servicer.'], function () {
    Route::namespace ('Servicer')->group(function () {

        Route::group(['middleware' => 'servicer'], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
            

            Route::namespace ('Products')->group(function () {
                Route::resource('products', 'ProductController');
                Route::get('remove-image-product', 'ProductController@removeImage')->name('product.remove.image');
                Route::get('remove-image-thumb', 'ProductController@removeThumbnail')->name('product.remove.thumb');
            });
            Route::namespace ('Customers')->group(function () {
                Route::resource('customers', 'CustomerController');
                Route::resource('customers.addresses', 'CustomerAddressController');
            });
            Route::namespace ('Categories')->group(function () {
                Route::resource('categories', 'CategoryController');
                Route::get('remove-image-category', 'CategoryController@removeImage')->name('category.remove.image');
            });
            Route::namespace ('Orders')->group(function () {
                Route::resource('orders', 'OrderController');
                Route::resource('order-statuses', 'OrderStatusController');
                Route::get('orders/{id}/invoice', 'OrderController@generateInvoice')->name('orders.invoice.generate');
            });

            Route::namespace ('Stores')->group(function () {
                Route::resource('stores', 'StoreController');
            });
            Route::namespace ('Pumps')->group(function () {
                Route::resource('pumps', 'PumpController');
            });
            Route::namespace ('Drivers')->group(function () {
                Route::resource('drivers', 'DriverController');
            });

            Route::namespace ('Routers')->group(function () {
                Route::resource('routers', 'RouterController');
            });

            Route::namespace ('RouteRequests')->group(function () {
                Route::resource('requests', 'RouteRequestController');
            });

            Route::get('/route/{id}/requests', 'RouteRequests\RouteRequestController@index');

            Route::get('/requests/{id}/cancel-request', 'RouteRequests\RouteRequestController@cancelRequest');
            Route::get('/requests/{id}/activate-request', 'RouteRequests\RouteRequestController@activateRequest');


            Route::resource('addresses', 'Addresses\AddressController');
            Route::resource('countries', 'Countries\CountryController');
            Route::resource('countries.provinces', 'Provinces\ProvinceController');
            Route::resource('countries.provinces.cities', 'Cities\CityController');
            Route::resource('couriers', 'Couriers\CourierController');
            Route::resource('attributes', 'Attributes\AttributeController');
            Route::resource('attributes.values', 'Attributes\AttributeValueController');
            Route::resource('brands', 'Brands\BrandController');
            Route::resource('employees', 'EmployeeController');

            Route::get('/profile/{id}', 'ServicerController@getProfile')->name('employee.profile');
            Route::post('/profile/{id}', 'ServicerController@updateProfile')->name('employee.profile.update');

            // Stripe connect success url
            Route::get('/stripeconnect', 'ServicerController@stripeConnect');
            Route::get('/stripecancel', 'ServicerController@stripeCancel');

            Route::get('employees/{id}/profile', 'EmployeeController@getProfile')->name('employee.profile');
            Route::put('employees/{id}/profile', 'EmployeeController@updateProfile')->name('employee.profile.update');
            Route::resource('roles', 'Roles\RoleController');
            Route::resource('permissions', 'Permissions\PermissionController');
        });
    });
});
/**
 * Frontend routes
 */
Auth::routes();
Route::namespace ('Auth')->group(function () {
    Route::get('cart/login', 'CartLoginController@showLoginForm')->name('cart.login');
    Route::post('cart/login', 'CartLoginController@login')->name('cart.login');
    Route::get('logout', 'LoginController@logout');    

    Route::post('servicer_register', 'RegisterController@servicer_register');
    Route::get('servicer/{id}/confirmotp', 'RegisterController@confirmotp');
    Route::get('otpConfirm', 'RegisterController@otpConfirm');
    Route::post('verifyOtp', 'RegisterController@verifyOtp');
    Route::post('resendOtp', 'RegisterController@resendOtp');

    Route::get('signup/completed', 'RegisterController@signupSuccess');
    
    
});
Route::namespace ('Front')->group(function () {

    Route::get('/', 'HomeController@index')->name('welcome');
    Route::get('/home', 'HomeController@home')->name('front.home');
    Route::post('/setlocation', 'HomeController@setlocation');
    Route::get('/contact-us', 'ContactController@index');
    Route::post('/sendquery', 'ContactController@contactQuery');
    Route::get('auth/reset_password', 'HomeController@resetPassword');
    Route::post('auth/change_password', 'HomeController@changePassword');
    Route::get('/shop', 'ShopController@shop')->name('home');
    Route::post('/shop', 'ShopController@shop')->name('home');



    Route::get('/about-us', 'HomeController@aboutUs');
    Route::get('/mobile-apps', 'HomeController@mobileApps');

    // Blogs
    Route::get('blogs', 'BlogController@index');
    Route::get('/blog/{id}/{title}', 'BlogController@blogDetail');

    // Stations
    Route::get('stations', 'PumpController@index');
    Route::get('/stations/{id}/{title}', 'PumpController@pumpDetail');

    // Routes Section
    Route::get('def-routes', 'RouteController@index');
    Route::post('def-routes', 'RouteController@index');
    Route::get('/def-routes/{id}/info', 'RouteController@routeDetail');
    Route::post('submit_route_req', 'RouteController@submitRouteReq');
    Route::post('cancel_route_req', 'RouteController@cancelRouteReq');

    Route::get('checkCart', 'CartController@checkCartItems');
    Route::post('checkCart', 'CartController@checkCartItems');
    Route::post('clearCart', 'CartController@clearCartItems');
    Route::get('/product/reviews/{product}', 'ProductController@allRatingsReviews');

    Route::group(['middleware' => ['auth', 'web']], function () {

        Route::namespace ('Payments')->group(function () {
            Route::get('bank-transfer', 'BankTransferController@index')->name('bank-transfer.index');
            Route::post('bank-transfer', 'BankTransferController@store')->name('bank-transfer.store');
        });

        Route::namespace ('Addresses')->group(function () {
            Route::resource('country.state', 'CountryStateController');
            Route::resource('state.city', 'StateCityController');
        });

        Route::get('accounts', 'AccountsController@index')->name('accounts');
        Route::get('accounts/addresses', 'AccountsController@addresses');
        Route::get('accounts/payments', 'AccountsController@payments');
        Route::get('accounts/orders', 'AccountsController@orders');
        Route::get('accounts/requests', 'AccountsController@dashboardRequests');


        Route::post('accounts/saveCards', 'AccountsController@saveCards');
        Route::post('accounts/deleteCards', 'AccountsController@deleteCards');
        Route::post('accounts/updateInfo', 'AccountsController@updateUserInfo');

        Route::get('checkout', 'CheckoutController@index')->name('checkout.index');
        Route::post('checkout', 'CheckoutController@store')->name('checkout.store');
        Route::get('checkout/execute', 'CheckoutController@executePayPalPayment')->name('checkout.execute');
        Route::post('checkout/execute', 'CheckoutController@charge')->name('checkout.execute');
        Route::get('checkout/cancel', 'CheckoutController@cancel')->name('checkout.cancel');
        Route::get('checkout/success', 'CheckoutController@checkoutSuccess')->name('checkout.success');

        Route::resource('customer.address', 'CustomerAddressController');

        
        Route::resource('customer.address', 'CustomerAddressController');

        Route::get('getRatings/{id}', 'ProductController@getRatings');

        Route::get('/{id}/ratings/{product}', 'RatingController@index');
        Route::post('/{id}/submitReview/{product}', 'RatingController@submitReview');
        
    });


    Route::resource('cart', 'CartController');
    Route::get("category/{slug}", 'CategoryController@getCategory')->name('front.category.slug');
    Route::get("search", 'ProductController@search')->name('search.product');
    Route::get("{product}", 'ProductController@show')->name('front.get.product');
});

// Set locale for multi language
Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, \Config::get('app.locales'))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});

