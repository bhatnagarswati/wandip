@extends('layouts.front.app')

@section('og')
<meta property="og:type" content="home" />
<meta property="og:title" content="{{ config('app.name') }}" />
<meta property="og:description" content="{{ config('app.name') }}" />
@endsection

@section('content')

<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{  __('common.shop_page_title')  }}</h1>
        </div>
    </div>
</section>

<section class="product_comn_main product_main">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="product_sidebar product_sidebar_filter">
                    @include('front.products.sidebar-product', ['categories' => $categories, 'brands' => $brands])
                </div>
            </div>
            <div class="col-md-9">
                <div class="product_list_coman product_list_main">
                    <div class="sort_tolbar">
                        <div class="product_count">
                            @php
                            if($products_count > 1 ){
                            @endphp
                            <p>{{  $products_count }} {{ __('common.listproduct_multiple_products') }}</p>
                            @php
                            }else{
                            @endphp
                            <p>{{ $products_count }} {{ __('common.listproduct_single_product') }}</p>
                            @php
                            }
                            @endphp
                        </div>
                        <div class="product_sort">
                            <p>{{ __('common.listproduct_sortby') }}</p>
                            <select name='shop_sort_filter' id='shop_sort_filter' class="form-control arrow_down">
                                <option @php if(!empty($sortOrder)){ if($sortOrder=='sort_asc' ){ echo "selected" ;} }
                                    @endphp value="sort_asc" seleted> {{ __('common.listproduct_sortbyop1') }} </option>
                                <option @php if(!empty($sortOrder)){ if($sortOrder=='sort_desc' ){ echo "selected" ;} }
                                    @endphp value="sort_desc"> {{ __('common.listproduct_sortbyop2') }} </option>
                                <option @php if(!empty($sortOrder)){ if($sortOrder=='price_asc' ){ echo "selected" ;} }
                                    @endphp value="price_asc"> {{ __('common.listproduct_sortbyop3') }} </option>
                                <option @php if(!empty($sortOrder)){ if($sortOrder=='price_desc' ){ echo "selected" ;} }
                                    @endphp value="price_desc"> {{ __('common.listproduct_sortbyop4') }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="row products_list_outer">
                        @include('front.products.shop-list', ['products' => $products, 'pagination' => $pagination])
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
