@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"> </h3>

            <div class="box-tools pull-right">


            </div>
        </div>
        <div class="box-body">

            <section class="content">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('admin/dashboard.service_provider') }}</span>
                                <span class="info-box-number">{{ $service_providers_count }}</span>
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
                                <span class="info-box-text">{{ __('admin/dashboard.stores') }}</span>
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
                                <span class="info-box-text">{{ __('admin/dashboard.pumps') }}</span>
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
                                <span class="info-box-text">{{ __('admin/dashboard.products') }}</span>
                                <span class="info-box-number">{{ $products_count }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>


                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('admin/dashboard.drivers') }}</span>
                                <span class="info-box-number">{{ $drivers_count }}</span>
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
                                <h3 class="box-title">{{ __('admin/dashboard.latest_service_providers') }}</h3>

                                <div class="box-tools pull-right">
                                    <span class="label label-danger">{{ count($service_providers) }} New Members</span>
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


                                    @if (!empty($service_providers))
                                    @foreach($service_providers as $provider)
                                    @php 
                                     $providerPic = url('/')."/public/img/no-image.png";

                                     @endphp
                                    @if(isset($provider->profilePic))
                                    @if($provider->profilePic != "")
                                    @php 
                                                                        $providerPic = config('constants.service_provider_pull_path').$provider->profilePic; @endphp


                                    @endif
                                    @endif


                                    <li>
                                        <img class="ser_img" src="{{ $providerPic }}" alt="">
                                        <a class="users-list-name"
                                            href="{{ url('admin/servicers/'.$provider->id ) }}">{{ $provider->name }}</a>
                                        <span class="users-list-date">
                                            {{ date('d M Y', strtotime($provider->created_at)) }}
                                        </span>
                                    </li>

                                    @endforeach
                                    @endif

                                </ul>
                                <!-- /.users-list -->
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer text-center">
                                <a href="{{ url('admin/servicers') }}"
                                    class="uppercase">{{ __('admin/dashboard.latest_service_providers_link') }} </a>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!--/.box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <!-- USERS LIST -->
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ __('admin/dashboard.latest_stores') }}</h3>

                                <div class="box-tools pull-right">
                                    <span class="label label-danger">{{ count($stores) }} New Stores</span>
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


                                    @if (!empty($stores))
                                    @foreach($stores as $store)
                                    @php $storepic = url('/')."/public/img/no-image.png"; @endphp
                                    @if(isset($store->storePic))
                                    @if($store->storePic != "")
                                    @php $storepic = config('constants.store_pull_path').$store->storePic; @endphp
                                    @endif
                                    @endif


                                    <li>
                                        <img class="ser_img" src="{{ $storepic }}" alt="">
                                        <a class="users-list-name"
                                            href="{{ url('admin/stores/'.$store->id ) }}">{{ $store->storeTitle }}</a>
                                        <span class="users-list-date">
                                            {{ date('d M Y', strtotime($store->created_at)) }}
                                        </span>
                                    </li>

                                    @endforeach
                                    @endif

                                </ul>
                                <!-- /.users-list -->
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer text-center">
                                <a href="{{ url('admin/stores') }}"
                                    class="uppercase">{{ __('admin/dashboard.latest_stores_link') }} </a>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!--/.box -->
                    </div>
                </div>
                <!-- /.row -->

            </section>


        </div>
        <!-- /.box -->

</section>
<!-- /.content -->
@endsection