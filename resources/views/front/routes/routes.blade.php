@extends('layouts.front.app')
@section('content')
<section class="banner banner_inn"  style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
        <div class="container">
             <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"  data-aos-duration="600">
                <h1>{{ __('common.routes_page_title') }}</h1>
              </div>
        </div>
    </section>

    <section class="adblue_station_main">
      <div class="container">

      <div class="filter_block">
        <div class="dropdown filter_btn_outr">
            <button class="btn" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-filter" aria-hidden="true"></i>{{ __('common.routes_filter_button_text') }}</button>
            <div class="dropdown-menu" >
              <form method="post" action="{{ url('/def-routes') }}">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                  <div class="form_group box-shaw">
                      <label>{{ __('common.routes_search_location') }}</label>
                      <input type="text" name="search_location" required class="form-control autocomplete" /> 
                  </div>
                  <div class="form_group box-shaw">
                    <label>{{ __('common.routes_search_date') }}</label>
                    <input type="text" id="datepicker" name="search_date"  required class="form-control">
                  </div>
                <div class="btn_outr">
                    <button type="submit" class="blue-btn creative_btn btn apply" >{{ __('common.routes_button_apply') }}</button>
                </div>
              </form>
            </div>
        </div>
    </div>


    <div class="route_list_outer col-sm-12">
          @if(!empty($allroutes))
          @foreach($allroutes as $route)

            <div class="route_list_bx row">
               <!-- <div class="col-md-3 station_place_outr">
                  <figure class="bg_cover station_place" style="background-image: url('{{ asset('public/images/station_img_1.jpg')}} ')"></figure>
                </div> -->
                <div class="col-md-8">
                  <div class="route_list_detail">
                      <h3>{{ @$route->servicerName }}</h3>
                 <div class="comn_style_route route_form_bx">
                    <div class="route_form_row route_form_form">
                       <div class="route_form_colum route_form_col_lt">
                          <div class="route_form_title route_form_to">
                            <p><strong>{{ __('common.routes_label_from') }}</strong></p>
                           </div>
                           <div class="route_form_adress">

                           @php

                            $first_location = "__('common.routes_first_location')";
                            $last_location = "__('common.routes_last_location')";

                            if(!empty(@$route->routeLocations)){
                                    $i=1;
                                    foreach($route->routeLocations as $info){
                                        if($i == 1){
                                            $first_location = $info->location;
                                        }
                                        if($i == count($route->routeLocations)){
                                            $last_location =  $info->location;
                                        }
                                        $i++;
                                    }
                            }
                           @endphp
                            <p>
                                {{  $first_location }}
                            </p>
                           </div>
                        </div>
                        <div class="route_form_colum route_form_col_lt">
                          <div class="route_form_title route_form_to">
                            <p><strong>{{ __('common.routes_label_to') }}</strong></p>
                           </div>
                           <div class="route_form_adress">
                            <p>{{  $last_location }}</p>
                           </div>
                        </div>
                       <div class="route_form_colum route_form_col_rt">
                         <div class="route_form_title">
                            <p><strong>{{ __('common.routes_label_at') }}</strong></p>
                           </div>
                           <div class="route_form_adress">
                            <p>{{ @$route->departureTime }}</p>
                           </div>
                        </div>
                      </div>

                        <div class="route_form_row route_form_form">

                      <!--  <div class="route_form_colum route_form_col_rt">
                         <div class="route_form_title">
                            <p><strong>at</strong></p>
                           </div>
                           <div class="route_form_adress">
                            <p>10:00pm (Approx.)</p>
                           </div>
                        </div> -->
                      </div>
                  </div>

                  </div>
                </div>
                <div class="col-md-4">
                     <div class="route_list_detail">
                     <div class="comn_style_route route_form_bx route_list_time">
                    <div class="route_form_row route_form_form">
                       <div class="route_form_colum route_form_col_lt">
                          <div class="route_form_title route_form_to">
                            <p><strong>{{ __('common.routes_label_date') }}</strong></p>
                           </div>
                           <div class="route_form_adress">
                               <p><strong class="primary_color">{{ date("dS M Y", strtotime(@$route->deliveryDate)) }}</strong></p>
                           </div>
                        </div>
                      </div>
                    <div class="route_form_row route_form_form">
                       <div class="route_form_colum route_form_col_lt">
                          <div class="route_form_title route_form_to">
                            <p><strong>{{ __('common.routes_label_price') }}</strong></p>
                           </div>
                           <div class="route_form_adress">
                            <p><strong class="primary_color">${{ @$route->price }}/{{ @$route->priceUnit }}</strong></p>
                           </div>
                        </div>
                      </div>
                     <a href="{{ url('/def-routes/'.$route->routeId.'/info')  }}" class="btn blue-btn creative_btn ">{{ __('common.routes_create_request_btn') }}</a>
                  </div>
                </div>
                </div>

              </div>

            @endforeach
            @else
                    <div class=" col-md-12"> {{ __('common.routes_no_route_found') }}</div>
            @endif
            <div class='clearfix'></div>
               <div class="col-md-12 text-center">
               {{ @$pagination->links() }}
          </div>
        </div>
    </section>
@endsection

@section('js')

<script>
// date piker
$( function() {
  $( "#datepicker" ).datepicker();

} );


function initialize() {
    var elements = document.body.querySelectorAll(".autocomplete");
    
    for (var i = 0, element; element = elements[i++];) {
        var autocomplete = new google.maps.places.Autocomplete(element);
        autocomplete.inputId = element.getAttribute('data_val');
        autocomplete.setFields(
            ['address_components', 'geometry', 'icon', 'name']);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
         }); 
        }
}
google.maps.event.addDomListener(window, 'load', initialize); 

</script>

@endsection