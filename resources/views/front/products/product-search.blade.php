@extends('layouts.front.app')

@section('content')

<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{  __('common.search_page_title')  }}</h1>
        </div>
    </div>
</section>

<section class="product_comn_main product_main">
    <div class="container">
        <div class="row">
            <!--  <div class="col-md-3">
            @include('front.categories.sidebar-category')
        </div> -->
            <div class="col-md-12">
                <div class="row products_list_outer">


                    @if(@$products->count() > 0)
                    @include('front.products.search-products-list', ['products' => $products])
                    @else
                    <p class="alert col-md-12 alert-warning">{{ __('common.search_page_noproducts') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection