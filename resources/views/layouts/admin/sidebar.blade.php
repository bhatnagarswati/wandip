<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('public/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $user->name }}</p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">{{ __('admin/sidebar.sidemenu_dashboard') }}</li>
            <li><a href="{{ route('admin.dashboard') }}"> <i class="fa fa-home"></i><span>
                        {{ __('admin/sidebar.sidemenu_home') }} </span></a></li>


            <li class="treeview @if(request()->segment(2) == 'banners') active @endif">
                <a href="#">
                    <i class="fa fa-camera"></i> <span>{{ __('admin/sidebar.sidemenu_banners') }} </span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.banners.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listbanners') }}</a></li>
                    <li><a href="{{ route('admin.banners.create') }}"><i
                                class="fa fa-plus"></i>{{ __('admin/sidebar.sidemenu_addbanner') }}</a></li>
                </ul>
            </li>


            <li class="treeview @if(request()->segment(2) == 'pages') active @endif">
                <a href="#">
                    <i class="fa fa-file"></i> <span>{{ __('admin/sidebar.sidemenu_pages') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.pages.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listpages') }} </a></li>
                    <li><a href="{{ route('admin.pages.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_addpage') }}</a></li>
                </ul>
            </li>

            <li class="treeview @if(request()->segment(2) == 'blogs') active @endif">
                <a href="#">
                    <i class="fa fa-file"></i> <span>{{ __('admin/sidebar.sidemenu_blogs') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.blogs.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listblogs') }} </a></li>
                    <li><a href="{{ route('admin.blogs.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_addblog') }}</a></li>
                </ul>
            </li>

            <li class="treeview @if(request()->segment(2) == 'teams') active @endif">
                <a href="#">
                    <i class="fa fa-file"></i> <span>{{ __('admin/sidebar.sidemenu_teams') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.teams.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listteams') }} </a></li>
                    <li><a href="{{ route('admin.teams.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_addteams') }}</a></li>
                </ul>
            </li>


            <li class="header">{{ __('admin/sidebar.sidemenu_users') }}</li>

            <li class="treeview @if(request()->segment(2) == 'servicers') active @endif">
                <a href="#">
                    <i class="fa fa-id-card"></i> <span>{{ __('admin/sidebar.sidemenu_serviceprovider') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.servicers.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listservicer') }}</a></li>
                    <li><a href="{{ route('admin.servicers.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createservicer') }}</a></li>
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
                        <a href="{{ route('admin.customers.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listcustomers') }}</a></li>
                    <li style="display:none;"><a href="{{ route('admin.customers.create') }}"><i class="fa fa-plus"></i>
                            Create customer</a></li>
                    <li style="display:none;" class="@if(request()->segment(2) == 'addresses') active @endif">
                        <a href="#"><i class="fa fa-map-marker"></i> Addresses
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.addresses.index') }}"><i class="fa fa-circle-o"></i> List
                                    addresses</a></li>
                            <li><a href="{{ route('admin.addresses.create') }}"><i class="fa fa-plus"></i> Create
                                    address</a></li>
                        </ul>
                    </li>
                </ul>
            </li>


            <li class="treeview @if(request()->segment(2) == 'drivers') active @endif">
                <a href="#">
                    <i class="fa fa-id-card-o"></i> <span> {{ __('admin/sidebar.sidemenu_drivers') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>


                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.drivers.index') }}"><i
                                class="fa fa-circle-o"></i>{{ __('admin/sidebar.sidemenu_listdrivers') }}</a></li>
                </ul>
            </li>




            @if($user->hasRole('admin|superadmin'))
            <li style="display:none;" class="treeview @if(request()->segment(2) == 'employees' || request()->segment(2) == 'roles' || request()->segment(2) == 'permissions') active @endif">
                <a href="#">
                    <i class="fa fa-star"></i> <span>{{ __('admin/sidebar.sidemenu_employees') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.employees.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listemployees') }}</a></li>
                    <li style="display:none;"><a href="{{ route('admin.employees.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createemployees') }}</a></li>
                    <li style="display:none;" class="@if(request()->segment(2) == 'roles') active @endif">
                        <a href="#">
                            <i class="fa fa-star-o"></i> <span>Roles</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.roles.index') }}"><i class="fa fa-circle-o"></i> List roles</a>
                            </li>
                        </ul>
                    </li>
                    <li style="display:none;" class="@if(request()->segment(2) == 'permissions') active @endif">
                        <a href="#">
                            <i class="fa fa-star-o"></i> <span>Permissions</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.permissions.index') }}"><i class="fa fa-circle-o"></i> List permissions</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif

            <li class="header">{{ __('admin/sidebar.sidemenu_config') }}</li>

            <li class="treeview @if(request()->segment(2) == 'stores') active @endif">
                <a href="#">
                    <i class="fa fa-building"></i> <span>{{ __('admin/sidebar.sidemenu_stores') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.stores.index') }}"><i
                                class="fa fa-circle-o"></i>{{ __('admin/sidebar.sidemenu_liststores') }}</a></li>
                    <li><a href="{{ route('admin.stores.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createstores') }}</a></li>
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
                    <li><a href="{{ route('admin.pumps.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listpumps') }}</a></li>
                    <li><a href="{{ route('admin.pumps.create') }}"><i class="fa fa-plus"></i>
                            {{ __('admin/sidebar.sidemenu_createpumps') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'categories') active @endif">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>{{ __('admin/sidebar.sidemenu_categories') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.categories.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listcategories') }}</a></li>
                    <li><a href="{{ route('admin.categories.create') }}"><i
                                class="fa fa-plus"></i>{{ __('admin/sidebar.sidemenu_createcategories') }}</a></li>
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
                    <li><a href="{{ route('admin.brands.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listbrands') }}</a></li>
                    <li><a href="{{ route('admin.brands.create') }}"><i class="fa fa-plus"></i>
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
                    @if($user->hasPermission('view-product'))<li><a href="{{ route('admin.products.index') }}"><i
                                class="fa fa-circle-o"></i> {{ __('admin/sidebar.sidemenu_listproducts') }}</a></li>
                    @endif
                    @if($user->hasPermission('create-product'))<li><a href="{{ route('admin.products.create') }}"><i
                                class="fa fa-plus"></i> {{ __('admin/sidebar.sidemenu_createproducts') }}</a></li>@endif
                    <li style="display: none;" class="@if(request()->segment(2) == 'attributes') active @endif">
                        <a href="#">
                            <i class="fa fa-gear"></i> <span>Attributes</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.attributes.index') }}"><i class="fa fa-circle-o"></i> List
                                    attributes</a></li>
                            <li><a href="{{ route('admin.attributes.create') }}"><i class="fa fa-plus"></i> Create
                                    attribute</a></li>
                        </ul>
                    </li>
                </ul>

            </li>
            <li
                class="treeview @if(request()->segment(2) == 'routers' || request()->segment(4) == 'requests') active @endif">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>{{ __('admin/sidebar.sidemenu_routes') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.routers.index') }}"><i class="fa fa-circle-o"></i>
                            {{ __('admin/sidebar.sidemenu_listroutes') }}</a></li>
                    <li><a href="{{ route('admin.routers.create') }}"><i
                                class="fa fa-plus"></i>{{ __('admin/sidebar.sidemenu_createroutes') }}</a></li>
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
                    <li><a href="{{ route('admin.requests.index') }}"><i class="fa fa-circle-o"></i>
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
                    <li><a href="{{ route('admin.orders.index') }}"><i class="fa fa-circle-o"></i> {{ __('admin/sidebar.sidemenu_listorders') }}</a></li>
                </ul>
            </li>
            <li style="display:none;" class="treeview @if(request()->segment(2) == 'order-statuses') active @endif">
                <a href="#">
                    <i class="fa fa-anchor"></i> <span>{{ __('admin/sidebar.sidemenu_order_status')  }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.order-statuses.index') }}"><i class="fa fa-circle-o"></i> {{ __('admin/sidebar.sidemenu_listorder_status') }}</a></li>
                    <li><a href="{{ route('admin.order-statuses.create') }}"><i class="fa fa-plus"></i>{{ __('admin/sidebar.sidemenu_createorder_status') }}</a></li>
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
                    <li><a href="{{ route('admin.couriers.index') }}"><i class="fa fa-circle-o"></i> List couriers</a>
                    </li>
                    <li><a href="{{ route('admin.couriers.create') }}"><i class="fa fa-plus"></i> Create courier</a>
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
                    <li><a href="{{ route('admin.countries.index') }}"><i class="fa fa-circle-o"></i> List</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- =============================================== -->