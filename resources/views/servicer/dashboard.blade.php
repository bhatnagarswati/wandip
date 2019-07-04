@extends('layouts.servicer.app')

@section('content')
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{  __('dashboard.servicer_welcome_msg') }}</h3>

            <div class="box-tools pull-right">


            </div>
        </div>
        <div class="box-body">

            <section class="content">
                <div class="row">
                    @include('layouts.errors-and-messages')
                </div>
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('dashboard.servicer_driver_text') }}</span>
                                <span class="info-box-number">{{ $drivers_count }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-id-card"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('dashboard.servicer_store_text') }}</span>
                                <span class="info-box-number">{{ $stores_count }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix visible-sm-block"></div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-braille"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('dashboard.servicer_pump_text') }}</span>
                                <span class="info-box-number">{{ $pumps_count }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-cart-plus"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('dashboard.servicer_products_text') }}</span>
                                <span class="info-box-number">{{ $products_count }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                <div class="row listing_box_dashboard">

                    <div class="col-md-6">
                        <!-- USERS LIST -->
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ __('dashboard.servicer_drivers_recent') }}</h3>

                                <div class="box-tools pull-right">
                                    <span class="label label-danger">{{ count($all_drivers) }}
                                        {{ __('dashboard.servicer_drivers_new')  }}</span>
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <ul class="users-list clearfix">


                                    @if (!empty($all_drivers))
                                    @foreach($all_drivers as $driver)

                                    @php $driverpic = url('/')."/public/img/no-image.png";  @endphp
                                    @if(isset($driver->driverPic))
                                    @if($driver->driverPic != "")
                                    @php $driverpic = config('constants.driver_pull_path').$driver->driverPic; @endphp
                                    @endif
                                    @endif


                                    <li>
                                        <img class="ser_img" src="{{ $driverpic }}" alt="User Image">
                                        <a class="users-list-name"
                                            href="{{ url('servicer/drivers/'.$driver->driverId ) }}">{{ $driver->firstName }}</a>
                                        <span class="users-list-date">
                                            {{ date('d M Y', strtotime($driver->created_at)) }}
                                        </span>
                                    </li>

                                    @endforeach
                                    @endif

                                </ul>
                                <!-- /.users-list -->
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer text-center">
                                <a href="{{ url('servicer/drivers') }}"
                                    class="uppercase">{{ __('dashboard.servicer_drivers_viewall') }}</a>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!--/.box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6" style="display:none;">
                        <!-- USERS LIST -->
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">Latest Orders</h3>

                                <div class="box-tools pull-right">
                                    <span class="label label-danger">8 New Users</span>
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <ul class="users-list clearfix">
                                    <li>
                                        <img src="{{ asset('/public/bower_components/dist/img/user2-160x160.jpg')}}"
                                            alt="User Image">
                                        <a class="users-list-name" href="#">Alexander</a>
                                        <span class="users-list-date">13 Jan</span>
                                    </li>
                                    <li>
                                        <img src="{{ asset('/public/bower_components/dist/img/user5-128x128.jpg')}}"
                                            alt="User Image">
                                        <a class="users-list-name" href="#">Sarah</a>
                                        <span class="users-list-date">14 Jan</span>
                                    </li>
                                    <li>
                                        <img src="{{ asset('/public/bower_components/dist/img/user4-128x128.jpg') }}"
                                            alt="User Image">
                                        <a class="users-list-name" href="#">Nora</a>
                                        <span class="users-list-date">15 Jan</span>
                                    </li>
                                    <li>
                                        <img src="{{ asset('/public/bower_components/dist/img/user3-128x128.jpg')}}"
                                            alt="User Image">
                                        <a class="users-list-name" href="#">Nadia</a>
                                        <span class="users-list-date">15 Jan</span>
                                    </li>
                                    <li>
                                        <img src="{{ asset('/public/bower_components/dist/img/user1-128x128.jpg')}}"
                                            alt="User Image">
                                        <a class="users-list-name" href="#">Alexander Pierce</a>
                                        <span class="users-list-date">Today</span>
                                    </li>
                                    <li>
                                        <img src="{{ asset('/public/bower_components/dist/img/user8-128x128.jpg')}}"
                                            alt="User Image">
                                        <a class="users-list-name" href="#">Norman</a>
                                        <span class="users-list-date">Yesterday</span>
                                    </li>
                                    <li>
                                        <img src="{{ asset('/public/bower_components/dist/img/user7-128x128.jpg') }}"
                                            alt="User Image">
                                        <a class="users-list-name" href="#">Jane</a>
                                        <span class="users-list-date">12 Jan</span>
                                    </li>
                                    <li>
                                        <img src="{{ asset('/public/bower_components/dist/img/user6-128x128.jpg')}}"
                                            alt="User Image">
                                        <a class="users-list-name" href="#">John</a>
                                        <span class="users-list-date">12 Jan</span>
                                    </li>

                                </ul>
                                <!-- /.users-list -->
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer text-center">
                                <a href="javascript:void(0)" class="uppercase">View All Users</a>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!--/.box -->
                    </div>
                </div>
                <!-- /.row -->

            </section>
        </div>

    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection