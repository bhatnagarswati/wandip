@extends('layouts.front.app')

@section('og')
<meta property="og:type" content="category" />
<meta property="og:title" content="{{ $category->name }}" />
<meta property="og:description" content="{{ $category->description }}" />
@if(!is_null($category->cover))
<meta property="og:image" content="{{ asset("storage/$category->cover") }}" />
@endif
@endsection

@section('content')
<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{  __('common.category_page_title') }} {{ $category->name }}</h1>
        </div>
    </div>
</section>

<div class="container">
   
    <!-- <div class="col-md-3">
            @include('front.categories.sidebar-category')
        </div> -->

    <div class="row col-md-12">
        <h2 style="margin:30px 0px;">{{ $category->name }}</h2>
        <br />
        <div class="row col-md-12">
            <div class="category-image pull-left col-md-4 no-padding">
                @if(isset($category->cover))
                <img src="{{ asset("storage/app/public/$category->cover") }}" alt="{{ $category->name }}"  class="img-responsive" />
                @else
                <img src="https://placehold.it/1200x200" alt="{{ $category->cover }}" class="img-responsive" />
                @endif
            </div>
            <div class="col-md-8">{!! $category->description !!} </div>
        </div>
    </div>
    <hr>
    <div class="row products_list_outer">
        @include('front.products.category-products-list', ['products' => $products])
    </div>
</div>

@endsection