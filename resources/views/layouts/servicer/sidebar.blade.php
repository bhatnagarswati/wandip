<!-- =============================================== -->

<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">

            @php 

                                                            
                $userImage =  url('/')."/public/img/no-image.png";
                if($user->profilePic != ""){
                    $userImage  = config('constants.service_provider_pull_path').$user->profilePic ; 
                }
                @endphp
                <img src="{{  $userImage }}" class="img-circle"
                    alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $user->name }}</p>
                <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">{{ __('admin/sidebar.sidemenu_dashboard') }}</li>
            <li><a href="{{ route('servicer.dashboard') }}"> <i class="fa fa-home"></i><span>
                        {{ __('admin/sidebar.sidemenu_home') }} </span></a></li>


            <li class="treeview @if(request()->segment(2) == 'drivers') active @endif">
                <a href="#">
                    <i class="fa fa-folder"></i> <span> {{ __('admin/sidebar.sidemenu_drivers') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.drivers.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listdrivers') }}</a></li>
                    <li><a href="{{ route('servicer.drivers.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createdrivers') }}</a></li>
                </ul>
            </li>

            <li class="treeview @if(request()->segment(2) == 'customers') active @endif">
                <a href="#">
                    <i class="fa fa-id-card"></i> <span>{{ __('admin/sidebar.sidemenu_customers') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ route('servicer.customers.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listcustomers') }}</a></li>
                    <li style="display:none;"><a href="{{ route('servicer.customers.create') }}"><i
                                class="fa fa-plus"></i>
                            Create customer</a></li>
                    <li style="display:none;" class="@if(request()->segment(2) == 'addresses') active @endif">
                        <a href="#"><i class="fa fa-map-marker"></i> Addresses
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('servicer.addresses.index') }}"><i class="fa fa-circle-o"></i> List
                                    addresses</a></li>
                            <li><a href="{{ route('servicer.addresses.create') }}"><i class="fa fa-plus"></i> Create
                                    address</a></li>
                        </ul>
                    </li>
                </ul>
            </li>


            <li class="header">SELL</li>
            <li class="treeview @if(request()->segment(2) == 'stores') active @endif">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>{{ __('admin/sidebar.sidemenu_stores') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.stores.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_liststores') }}</a></li>
                    <li><a href="{{ route('servicer.stores.create') }}"><i
                                class="fa fa-plus"></i>{{ __('admin/sidebar.sidemenu_createstores') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'pumps') active @endif">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>{{ __('admin/sidebar.sidemenu_pumps') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.pumps.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listpumps') }}</a></li>
                    <li><a href="{{ route('servicer.pumps.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createpumps') }}</a></li>
                </ul>
            </li>
            <li class="@if(request()->segment(2) == 'brands') active @endif">
                <a href="#">
                    <i class="fa fa-tag"></i> <span>{{ __('admin/sidebar.sidemenu_brands') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.brands.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listbrands') }}</a></li>
                    <li><a href="{{ route('servicer.brands.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createbrands') }}</a></li>
                </ul>
            </li>
            <li
                class="treeview @if(request()->segment(2) == 'products' || request()->segment(2) == 'attributes') active @endif">
                <a href="#">
                    <i class="fa fa-gift"></i> <span>{{ __('admin/sidebar.sidemenu_products') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li>
                        <a href="{{ route('servicer.products.index') }}">
                            <i class="fa fa-circle-o"></i> {{ __('admin/sidebar.sidemenu_listproducts') }}</a>

                    </li>

                    <li><a href="{{ route('servicer.products.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createproducts') }}</a></li>


                </ul>
            </li>

            <li
                class="treeview @if(request()->segment(2) == 'routers'  || request()->segment(4) == 'requests') active @endif">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>{{ __('admin/sidebar.sidemenu_routes') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.routers.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listroutes') }}</a></li>
                    <li><a href="{{ route('servicer.routers.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createroutes') }}</a></li>
                </ul>
            </li>

            <li style="display:none;" class="@if(request()->segment(2) == 'requests') active @endif">
                <a href="#">
                    <i class="fa fa-tag"></i> <span>{{ __('admin/sidebar.sidemenu_routes_requests') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.requests.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listroutes_requests') }}</a></li>

                </ul>
            </li>


            <li class="header">{{ __('admin/sidebar.sidemenu_orders_label') }} </li>
            <li class="treeview @if(request()->segment(2) == 'orders') active @endif">
                <a href="#">
                    <i class="fa fa-money"></i> <span>{{ __('admin/sidebar.sidemenu_orders') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.orders.index') }}"><i class="fa fa-circle-o"></i> {{ __('admin/sidebar.sidemenu_listorders') }}</a></li>
                </ul>
            </li>

            <li style="display:none;" class="treeview @if(request()->segment(2) == 'order-statuses') active @endif">
                <a href="#">
                    <i class="fa fa-anchor"></i> <span>Order Statuses</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.order-statuses.index') }}"><i class="fa fa-circle-o"></i> List order
                            statuses</a></li>
                    <li><a href="{{ route('servicer.order-statuses.create') }}"><i class="fa fa-plus"></i> Create order
                            status</a></li>
                </ul>
            </li>
            <li style="display:none;" class="header">DELIVERY</li>
            <li style="display:none;" class="treeview @if(request()->segment(2) == 'couriers') active @endif">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Couriers</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.couriers.index') }}"><i class="fa fa-circle-o"></i> List
                            couriers</a></li>
                    <li><a href="{{ route('servicer.couriers.create') }}"><i class="fa fa-plus"></i> Create courier</a>
                    </li>
                </ul>
            </li>

            <li style="display:none;"
                class="treeview @if(request()->segment(2) == 'countries' || request()->segment(2) == 'provinces') active @endif">
                <a href="#">
                    <i class="fa fa-flag"></i> <span>Countries</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('servicer.countries.index') }}"><i class="fa fa-circle-o"></i> List</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- =============================================== -->