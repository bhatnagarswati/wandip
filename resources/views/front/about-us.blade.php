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
              <div class="col-md-3">
               <div class="team_bx">
                   <div class="team_pro_bx">
                     <figure class="banner_figure" style="background-image: url('{{ asset('public/images/our_team_1.jpg')}}')"></figure>
                       <div class="sm_icon_bx">
                         <ul>
                           <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                           <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                           <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                           </ul>
                       </div>
                   </div>
                   <div class="team_about">
                     <h3>Rose Merry</h3>
                       <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse purus enim, elementum ut interdum ut..</p>
                   </div>
                  </div>
             </div>
             
              <div class="col-md-3">
               <div class="team_bx">
                   <div class="team_pro_bx">
                     <figure class="banner_figure" style="background-image: url('{{ asset('public/images/our_team_2.jpg')}}')"></figure>
                       <div class="sm_icon_bx">
                         <ul>
                           <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                           <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                           <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                           </ul>
                       </div>
                   </div>
                   <div class="team_about">
                     <h3>Stephan</h3>
                       <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse purus enim, elementum ut interdum ut..</p>
                   </div>
                  </div>
             </div>
             
              <div class="col-md-3">
               <div class="team_bx">
                   <div class="team_pro_bx">
                     <figure class="banner_figure" style="background-image: url('{{ asset('public/images/our_team_3.jpg') }}')"></figure>
                       <div class="sm_icon_bx">
                         <ul>
                           <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                           <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                           <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                           </ul>
                       </div>
                   </div>
                   <div class="team_about">
                     <h3>Laura</h3>
                       <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse purus enim, elementum ut interdum ut..</p>
                   </div>
                  </div>
             </div>
             
              <div class="col-md-3">
               <div class="team_bx">
                   <div class="team_pro_bx">
                     <figure class="banner_figure" style="background-image: url('{{ asset('public/images/our_team_4.jpg')}}')"></figure>
                       <div class="sm_icon_bx">
                         <ul>
                           <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                           <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                           <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                           </ul>
                       </div>
                   </div>
                   <div class="team_about">
                     <h3>John Smith</h3>
                       <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse purus enim, elementum ut interdum ut..</p>
                   </div>
                  </div>
             </div>
           </div>
        </div>
    </section>
    
  
@endsection
 