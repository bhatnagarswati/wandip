@extends('layouts.front.app')
@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')

<section class="banner homepage_banner">
    <div id="demo" class="carousel slide" data-ride="carousel">

      <!-- The slideshow -->
      <div class="carousel-inner">
       @php
        $n=1;
        if(!empty($web_banners)){
         foreach($web_banners as $item){
           @endphp
          <div class="carousel-item @php if( $n == 1){ @endphp active @php } @endphp " style="background-image: url('{{ config('constants.banner_pull_path').@$item->bannerImage }}')">
            <div class="container">
              <div class="banner_text" data-aos="fade-up" data-aos-easing="ease" data-aos-delay="1000" data-aos-duration="600">
              @php echo html_entity_decode(stripslashes(@$item->description)) ? @$item->description:"" @endphp
              </div>
            </div>
          </div>
       @php
        $n++; }
      }
      @endphp
     </div>
    </div>
  </section>

  <section class="about_us_main">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 col-md-5 offset-lg-1">
          <div class="about_comn about_banner data_text" data-text="a" data-aos="fade-up" data-aos-easing="ease"
            data-aos-delay="400" data-aos-duration="600">

            <figure class="banner_figure" style="background-image: url('{{ config('constants.page_pull_path').@$aboutData->pagePic}}' )"></figure>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="about_comn about_text" data-aos="fade-up" data-aos-easing="ease" data-aos-delay="400"
            data-aos-duration="600">
            <h2 data-aos="fade-up" data-aos-easing="ease" data-aos-delay="300" data-aos-duration="1000"> @php echo html_entity_decode(stripslashes(__('common.home_page_about'))) @endphp </h2>
            @php echo html_entity_decode(stripslashes(@$aboutData->shortDescription)) ? @$aboutData->shortDescription:"" @endphp
            <a href="{{ url('/about-us')  }}" class="btn blue-btn creative_btn">
              <span class="card__corner"></span> {{ __('common.home_page_readmore') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- our product-->

  <section class="our_project_main bg_grey" style="background-image: url('{{ asset('public/images/our_product_bg.png') }} '">
    <div class="container">
      <div class="section_head">
        <h2 data-aos="zoom-in" data-aos-easing="ease" data-aos-delay="400" data-aos-duration="500">@php echo html_entity_decode(stripslashes(__('common.home_page_prodcuts'))) @endphp</h2>
      </div>
      <div class="our_project_inn">
        <div class="loop owl-carousel owl-theme">

        @include('front.products.home-list', ['products' => $products])

        </div>
      </div>
    </div>
  </section>
  <!--    app section-->
  <section class="about_us_main app_block_main">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-4 offset-lg-1">
          <div class="about_comn app_mobile_bx" data-aos="fade-up" data-aos-easing="ease" data-aos-delay="200"
            data-aos-duration="800">
            <figure class="banner_figure" style="background-image: url('{{ config('constants.page_pull_path').@$ourAppData->pagePic}}'"></figure>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="about_comn about_text" data-aos="fade-up" data-aos-easing="ease" data-aos-delay="500"
            data-aos-duration="1000">
            <h2 data-aos="fade-up" data-aos-easing="ease" data-aos-delay="300" data-aos-duration="1000"> @php echo html_entity_decode(stripslashes(__('common.home_page_ourapp'))) @endphp</h2>
            @php echo html_entity_decode(stripslashes(@$ourAppData->shortDescription)) ? @$ourAppData->shortDescription:"" @endphp
            <a href="{{ url('/mobile-apps')  }}" class="btn blue-btn creative_btn">{{ __('common.home_page_download_app') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="our_blog_main">
    <div class="container">
      <div class="row center_row">
        <div class="col-lg-6 col-md-6">
          <div class="about_comn blog_figure" data-aos="fade-up" data-aos-easing="ease" data-aos-delay="500"
            data-aos-duration="600">
            <figure class="banner_figure" style="background-image: url('{{ config('constants.page_pull_path').@$blogData->pagePic}}')"></figure>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="about_comn about_text frame_border" data-aos="fade-up" data-aos-easing="ease" data-aos-delay="400"
            data-aos-duration="600">
            <h2 data-aos="fade-up" data-aos-easing="ease" data-aos-delay="300" data-aos-duration="1000"> @php echo html_entity_decode(stripslashes(__('common.home_page_blogs'))) @endphp</h2>
            @php echo html_entity_decode(stripslashes(@$blogData->shortDescription)) ? @$blogData->shortDescription:"" @endphp
            <a href="{{ url('/blogs')  }}" class="btn blue-btn creative_btn blue_bg_btn">{{ __('common.home_page_view_all') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
