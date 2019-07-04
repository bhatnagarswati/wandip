<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('public/images/favicon.ico') }}" type="image/x-icon">
    <title>{{ config('app.name', 'AdBlue') }}</title>
    <meta name="description" content="adblue petrolling app">
    <meta name="tags" content="adblue">
    <meta name="author" content="">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/flexslider.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/style_main.css') }}">
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" rel="stylesheet"> 
 
    <!-- <link href="{{ asset('public/css/style.min.css') }}" rel="stylesheet"> -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    @yield('css')
    <meta property="og:url" content="{{ request()->url() }}" />
    @yield('og')
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>
<div class="mainloader">
    <img src="{{ asset('public/images/main_loader.png') }}" alt="main_loader.png" />
</div>

<body>
    <noscript>
        <p class="alert alert-danger">
            You need to turn on your javascript. Some functionality will not work if this is disabled.
            <a href="https://www.enable-javascript.com/" target="_blank">Read more</a>
        </p>
    </noscript>

    <header>
        <div class="header_top">
            <div class="container">

                <div class="header_top_rt">

                    <ul>
                        <li> <b>Your Location:</b> &nbsp; <span id="location"></span> </li>
                       
                        <li><span class="top_icon"><i class="fa fa-sign-in" aria-hidden="true"></i></span><a target="_blank"
                                href="{{ url('/servicer') }}"></i>Service Provider {{ __('home.menu_login') }}</a></li>

                        @if(auth()->check())
                        <li><a href="{{ route('accounts') }}" aria-hidden="true"><i
                                    class="fa fa-home"></i> {{ __('home.menu_myacoount') }}</a></li>
                        <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i>
                                {{ __('home.menu_logout') }}</a></li>
                        @else
                        <li><span class="top_icon"><i class="fa fa-sign-in" aria-hidden="true"></i></span><a
                                href="{{ route('login') }}"></i> Customer {{ __('home.menu_login') }}</a></li>
                        <li><span class="top_icon"><i class="fa fa-user-circle-o" aria-hidden="true"></i><a
                                    href="{{ route('register') }}"> {{ __('home.menu_register') }}</a></li>
                        @endif
                        <li id="cart" class="menubar-cart">
                            <span class="top_icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span><a
                                href="{{ route('cart.index') }}" title="View Cart"
                                class="awemenu-icon menu-shopping-cart">
                                <span class="cart-number">{{ __('home.menu_cart') }} ({{ $cartCount }})</span>
                            </a>
                        </li>
                        <li class="lang"><select class="form-control arrow_down changeLangSelect">
                                <option value="en" @if ( Config::get('app.locale')=='en' ) {{ 'selected' }} @endif>
                                    English</a></option>
                                <option value="it" @if ( Config::get('app.locale')=='it' ) {{ 'selected' }} @endif>
                                    Italian</a></option>
                            </select></li>
                        <li><a href="javascript::void(0)" class="search_btn_main"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
              
            </div>
        </div>
        <div class="header_bottom">
            <div class="container">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('public/images/logo.png') }}"
                            alt="logo.png" /></a>
                    <a class="navbar_brand_skicky" href="{{ url('/') }}"><img
                            src="{{ asset('public/images/logo.png') }}" alt="logo.png" /></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item  @if(request()->segment(1) == 'home') active @endif">
                                <a class="nav-link" href="{{ url('/home') }}"> {{ __('home.menu_home') }}</a>
                            </li>
                            <li class="nav-item @if(request()->segment(1) == 'about-us') active @endif">
                                <a class="nav-link" href="{{ url('/about-us') }}">{{ __('home.menu_aboutus') }}</a>
                            </li>
                            <li class="nav-item @if(request()->segment(1) == 'stations') active @endif">
                                <a class="nav-link" href="{{ url('/stations') }}">{{ __('home.menu_adbluestations') }}
                                </a>
                            </li>
                            <li class="nav-item @if(request()->segment(1) == 'shop') active @endif">
                                <a class="nav-link" href="{{ url('/shop') }}">{{ __('home.menu_products') }} </a>
                            </li>
                            <li class="nav-item @if(request()->segment(1) == 'def-routes') active @endif">
                                <a class="nav-link" href="{{ url('/def-routes') }}">{{ __('home.menu_routes') }} </a>
                            </li>

                            <li class="nav-item @if(request()->segment(1) == 'contact-us') active @endif">
                                <a class="nav-link" href="{{ url('/contact-us') }}">{{ __('home.menu_contact') }}</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
 

    @yield('content')

    @include('layouts.front.footer')

    
    <!--  search bar pop-->
    <div class="search_pop">
    <form action="{{route('search.product')}}" method="GET">
            <div class="search_field">
                <input required="required" class="form-control search_text" name="q" placeholder="Search..." />
                <button  type="submit" class=" search_btn"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
        </form>
        <a href="#" class="close_btn"></a>
    </div>


    <a href="javascript:void(0);" id="top_arrow"></a>
 
    <!-- jQuery first, then Bootstrap JS. -->
    <script src="{{ asset('public/js/front/jquery.min.js') }} "></script>
    <script src="{{ asset('public/js/front/popper.min.js') }}"></script>
    <script src="{{ asset('public/js/front/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/js/front/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('public/js/front/owl.carousel.js') }}"></script>
    <script src="{{ asset('public/js/front/wow.js') }}"></script>
    <script src="{{ asset('public/js/front/aos.js') }}"></script>
    <script src="{{ asset('public/js/front/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('public/js/front/slick.min.js') }}"></script>
    <script src="{{ asset('public/js/front/jquery.flexslider.js') }}"></script>
    <script src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_KEY') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('public/js/front/front_custom.js') }}"></script>
    <script>
    (function(jQuery) {
       
        jQuery('.changeLangSelect').on('change', function() {
            let selectedLang = jQuery(this).val();
            window.location.href = "<?php echo url('/') ?>/setlocale/" + selectedLang;
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showLocation);
        } else {
            jQuery('#location').html('Geolocation is not supported by this browser.');
        }

       // askCustomer();

    })(jQuery);

    /* function askCustomer(){
        alert('asds')
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showLocation);
        } else {
            jQuery('#location').html('Geolocation is not supported by this browser.');
        }
    } */

    function showLocation(position) {
        let latitude = position.coords.latitude;
        let longitude = position.coords.longitude;
        jQuery.ajax({
            type: 'POST',
            url: "<?php echo url('/') ?>/setlocation",
            data: 'latitude=' + latitude + '&longitude=' + longitude + '&_token=<?php echo csrf_token() ?>',
            success: function(msg) {
                if (msg) {
                    jQuery("#location").html(msg);
                } else {
                    jQuery("#location").html(   'Not Available');
                }
            }
        });
    }
    </script>
    @yield('js')
</body>

</html>