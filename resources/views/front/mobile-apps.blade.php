@extends('layouts.front.app')
 
@section('content')
 
<section class="banner banner_inn"  style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
        <div class="container">
             <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"  data-aos-duration="600">
                <h1>{{  __('common.apps_page_title')  }}</h1>
              </div>
        </div>
    </section>
    
    <section class="about_us_page">
                <div class="container">
                <div class="col-md-12">
                   <!-- <div class="about_comn about_us_sec about_text" data-aos="fade-up" data-aos-easing="ease" data-aos-delay="400"  data-aos-duration="600">
                      <div class="about_comn about_banner " data-aos="fade-up" data-aos-easing="ease" data-aos-delay="400"  data-aos-duration="600">
                      <figure class="banner_figure" style="background-image: url('{{ config('constants.page_pull_path').@$apps->pagePic}}')"></figure>
                    </div> -->
                     @php echo html_entity_decode(stripslashes(@$apps->fullDescription)) ? @$apps->fullDescription:"" @endphp
                  </div>
                  </div>
        </div>
    </section>
@endsection
 