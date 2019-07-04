<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('public/images/favicon.ico') }}" type="image/x-icon">
    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('public/css/admin.min.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/public/bower_components/Ionicons/css/ionicons.min.css') }}">
    @yield('css')
    <meta name="theme-color" content="#ffffff">
</head>

<body class="hold-transition skin-purple sidebar-mini">
    <noscript>
        <p class="alert alert-danger">
            You need to turn on your javascript. Some functionality will not work if this is disabled.
            <a href="https://www.enable-javascript.com/" target="_blank">Read more</a>
        </p>
    </noscript>
    <!-- Site wrapper -->
    <div class="wrapper">
        @include('layouts.admin.header', ['user' => $admin])
        @include('layouts.admin.sidebar', ['user' => $admin])
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content-header ">
                <h1 class="top_title">
                    @if(isset($page_title)) {{  $page_title }} @else {{ __('admin/dashboard.welcome_message') }} @endif
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home </a></li>
                    @foreach($segments = Request::segments() as $index => $segment)
                    @if (is_numeric($segment))
                    @continue
                    @endif
                    <li> <a
                            href="{{  url(implode(array_slice($segments , 0 , $index+1 ), '/')) }}">{{ title_case($segment) }}</a>
                    </li>
                    @endforeach
                </ol>
            </section>
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        @include('layouts.admin.footer')
        @include('layouts.admin.control-sidebar')
    </div>
    <!-- ./wrapper -->

    <script src="{{ asset('public/js/admin.min.js') }}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?libraries=places,geometry&language=en&key={{ env('GOOGLE_KEY') }}">
    </script>
    <script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
    <script src="{{ asset('public/js/scripts.js?v=0.2') }}"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    @yield('js')
</body>

</html>