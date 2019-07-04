@extends('layouts.front.app')

@section('og')
<meta property="og:type" content="product" />
<meta property="og:title" content="{{ $product->name }}" />
<meta property="og:description" content="{{ strip_tags($product->description) }}" />
@if(!is_null($product->cover))
<meta property="og:image" content="{{ $product->cover }}" />
@endif
@endsection

@section('content')


<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{  __('common.product_page_title')  }}</h1>
        </div>
    </div>
</section>

<section class="product_comn_main product_detail_main">
    <div class="container">
        <div class="row">
            
            <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i>
                        {{ __('common.product_breadcrumb_hometitle') }}</a></li>
                @if(isset($category))
                <li> <a href="{{ route('front.category.slug', $category->slug) }}">| {{ $category->name }} </a></li>
                @endif
                <li class="active"> | {{ @$product->name }}</li>
            </ol>
         </div>
        </div>
    </div>
    @include('layouts.front.product')
</section>
@endsection