@extends('layouts.front.app')
@section('content')

    <section class="banner banner_inn"  style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
        <div class="container">
             <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"  data-aos-duration="600">
                <h1>{{  __('common.about_page_title')  }}</h1>
              </div>
        </div>
    </section>
    
    <section class="about_us_page">
      <div class="container">
                  <div class="about_comn about_us_sec about_text" data-aos="fade-up" data-aos-easing="ease" data-aos-delay="400"  data-aos-duration="600">
                      <div class="about_comn about_banner " data-aos="fade-up" data-aos-easing="ease" data-aos-delay="400"  data-aos-duration="600">
                      <figure class="banner_figure" style="background-image: url('{{ config('constants.page_pull_path').@$aboutPage->pagePic}}')"></figure>
                    </div>
                     @php echo html_entity_decode(stripslashes(@$aboutPage->fullDescription)) ? @$aboutPage->fullDescription:"" @endphp
                  </div>
        </div>
    </section>
    
<!--our team-->

    <section class="our_team_main bg_grey" style="background-image: url('{{ asset('public/images/our_product_bg.png') }}')">
       <div class="container"> 
           <div class="section_head">
             <h2> @php echo html_entity_decode(stripslashes(__('common.about_page_ourteam'))) @endphp </h2>
           </div>
         <div class="row">
          @foreach($team_members as $team)
              <div class="col-md-3">
               <div class="team_bx">
                   <div class="team_pro_bx">
                     <figure class="banner_figure" style="background-image: url('{{ config('constants.team_pull_path').$team->image}}')"></figure>
                       <div class="sm_icon_bx">
                         <ul>
                         @if(!empty($team->facebook_link))
                           <li><a href="{{ $team->facebook_link }}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                           @endif
                           @if(!empty($team->twitter_link))
                           <li><a href="{{ $team->twitter_link }}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                           @endif
                           @if(!empty($team->linkedin_link))
                           <li><a href="{{ $team->linkedin_link }}"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                           @endif
                           </ul>
                       </div>
                   </div>
                   <div class="team_about">
                     <h3>{{ $team->name }}</h3>
                       <p>{!! $team->description !!}</p>
                   </div>
                  </div>
             </div>
             @endforeach
           </div>
        </div>
    </section>
    
  
@endsection
 