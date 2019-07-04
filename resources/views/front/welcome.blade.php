<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<title>{{ config('app.name', 'AdBlue') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<!-- Favicon -->
<link rel="icon" href="{{ asset('public/images/favicon.ico') }}" type="image/x-icon">
<!-- CSS -->
<link href="{{ asset('public/css/comingsoon.css') }}" rel="stylesheet">
</head>
<body>



<!--main block start-->

    <div class="adblue_main">

      <div class="adblue_comman adblue_left" style="background-image: url( {{ asset('public/images/cooming_soon_bg.jpg') }}">   <!--Adblue left block start here-->

           <div class="adblue_lt_inn">

               <div class="logo_block">

                  <a href="{{ url('/') }}" class="site_logo"><img src="{{ asset('public/images/logo.png')}}" alt="logo.png" /></a>

                   <p>Buy online authentic products or replenish your passenger cars or light & heavy commercial vehicles in nearest station with Adblue. </p>

               </div>

               <div class="adblue_left_content">

                 

                   <div class="adblue_lt_list">

                       <div class="adblue_lis_colnm adblue_lis_colnm_lt">

                       <h3>For Customers</h3>

                       <ul class="adblue_list_content adblue_list_left">

                             <li>Regular updates when a new Adblue station opens in your city, state or country</li>

                             <li>Prompt mobile alerts when Adblue mobile station drives near your current location. </li>

                             <li>Order online Adblue products.  Home delivery available.</li>

                             <li>Regular Updates on new Adblue products</li>

                       </ul>

                           </div>

                       <div class="adblue_lis_colnm adblue_lis_colnm_rt">

                        <h3>For Service Providers</h3>

                        <ul class="adblue_list_content adblue_list_right">

                             <li>Register and sell your Adblue products </li>

                             <li>Promote your Adblue stations within country</li>

                             <li>Advertise current location of your mobile Adblue station wherever it drives to, for refilling cars.</li>

                             <li>Get online inquiries from customers for business growth.</li>

                       </ul>

                      </div>

                   </div>

             

               </div>

          </div>

        </div>

      <div class="adblue_comman adblue_right">

         <div class="adblue_rt_inner">

            <h1><img src="{{ asset('public/images/welcome.png')}}" alt="welcome.png" /></h1>

          <!--   <h3>We can let yu know whrn site is ready. Please drop <br />us your E-mail address. Keep in touch!</h3>-->

             <h3>As a registered member, we offer benefits to customers as well as licensed service providers.</h3>

             

             <div class="get_in_toch">

               <h4><span>Get in touch with us</span></h4>

                 <form>

                     <div class="get_touch_form">

                       <div class="form-group get_touch_group">

                           <div class="sub_form_row">

                              <input type="text"  class="form-control left" placeholder="Full Name"/>

                           </div>

                           <div class="sub_form_row">

                              <input type="text"  class="form-control right" placeholder="Email"/>

                           </div>

                       </div>

                         

                          <div class="form-group get_touch_group">

                           <div class="sub_form_row">

                              <input type="text"  class="form-control left" placeholder="Contact No."/>

                           </div>

                           <div class="sub_form_row">

                              <input type="text"  class="form-control right" placeholder="Location"/>

                           </div>

                       </div>

                         

                         <div class="form-group submit_outr">

                           <button class="submit_btn btn blue-btn">Submit</button>

                         </div>

                     </div>

                 </form>

             </div>

          </div>    

      </div>

    </div>         

</body>

</html>

