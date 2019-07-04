@extends('layouts.front.app')
@section('content')
<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{ __('common.routes_page_title') }}</h1>
        </div>
    </div>
</section>


<section class="route_comn route_detail_main">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="route_detail_bx route_detail_map">
                    <div class="route_detail_map_bx ">
                        <div id='map_convas' style="height:400px;"></div>
                    </div>
                    <div class="step_detail_bx route_step_bx">
                        <ul>
                            @php
                            $first_location = "__('common.routes_first_location')";
                            $last_location = "__('common.routes_last_location')";
                            if(!empty(@$route_info->routeLocations)){
                            $n = 1;
                            $activeno = 1;
                            foreach($route_info->routeLocations as $key => $info){
                            if($n == 1){
                            $first_location = $info->location;
                            }
                            if($n == count($route_info->routeLocations)){
                            $last_location = $info->location;
                            }

                            if($info->markedStatus == 1){
                            $activeno = $n + 1;
                            }
                            @endphp
                            <li
                                class="@php if(@$info->markedStatus == 1){ echo 'completed'; } else if($n == $activeno) { echo 'active'; } @endphp ">
                                <span class="dot_icon"></span>
                                <p>{{ $info->location }} </p>
                                <!--  <small>0{{ $n }}:00 am</small> -->
                            </li>
                            @php
                            $n++;
                            }
                            }
                            @endphp
                        </ul>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class=" step_detail_bx_rt  about_text">
                    <h3>{{ @$route_info->servicerName }}</h3>
                    <div class="comn_style_route date_time_bx">
                        <div class="date_time_sub date_time_lt">
                            <p><strong>{{ date("dS M Y", strtotime(@$route_info->deliveryDate)) }}</strong></p>
                        </div>
                        <div class="date_time_sub date_time_rt">
                            <p><strong>${{ @$route_info->price }}/{{ @$route_info->priceUnit }}</strong></p>
                        </div>
                    </div>
                    <div class="comn_style_route route_form_bx">
                        <div class="route_form_row route_form_form">
                            <div class="route_form_colum route_form_col_lt">
                                <div class="route_form_title route_form_to">
                                    <p><strong>{{ __('common.routes_label_from') }}</strong></p>
                                </div>
                                <div class="route_form_adress">
                                    <p>{{  $first_location  }}</p>
                                </div>
                            </div>

                            <div class="route_form_colum route_form_col_rt">

                                <div class="route_form_title route_form_to">
                                    <p><strong>{{ __('common.routes_label_to') }}</strong></p>
                                </div>
                                <div class="route_form_adress">
                                    <p>{{  $last_location  }}</p>
                                </div>

                            </div>
                        </div>

                        <div class="route_form_row route_form_form">
                            <div class="route_form_title">
                                <p><strong>{{ __('common.routes_label_at') }}</strong></p>
                            </div>
                            <div class="route_form_adress">
                                <p>{{ @$route_info->departureTime }}</p>
                            </div>
                        </div>
                    </div>



                    <div class="comn_style_route route_form_distance ">
                        <ul>
                            <li>
                                <div class="route_form_title">
                                    <p><strong>{{ __('common.route_distance_to_cover')  }}</strong></p>
                                </div>
                                <div class="route_form_adress">
                                    <p id='total_distance_to_cover'> </p>
                                </div>
                            </li>
                            <li>
                                <div class="route_form_title">
                                    <p><strong>{{ __('common.route_volume_contained')  }}</strong></p>
                                </div>
                                <div class="route_form_adress">
                                    <p>{{ @$route_info->volumeContained }} {{ @$route_info->priceUnit }}</p>

                                </div>
                            </li>
                            <li>
                                <div class="route_form_title">
                                    <p><strong>{{ __('common.route_volume_remaining')  }}</strong></p>
                                </div>
                                <div class="route_form_adress">
                                    <p>{{ @$route_info->volumeRemaining }} {{ @$route_info->priceUnit }}</p>
                                </div>
                            </li>
                            <li class="driver">
                                <div class="route_form_title">
                                    <p><strong>{{ __('common.route_driver')  }}</strong></p>
                                </div>
                                <div class="route_form_adress">
                                    <p>{{ @$route_info->driverName }}</p>
                                </div>
                                <a href="#" class="driver_chat"><i class="fa fa-comments" aria-hidden="true"></i></a>
                            </li>
                            <li>
                                <div class="route_form_title">
                                    <p><strong>{{ __('common.route_notify_user')  }}</strong></p>
                                </div>
                                <div class="route_form_adress">
                                    <p>{{ __('common.route_within_text')  }} {{ @$route_info->notifyUsers }} miles</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="route_btn_outr">
                        @if(auth()->check())
                        @if(@$requests_status == 1)
                        @if(@$request_fulfilled_status == 1)
                        <a href="javascript::void(0);"
                            class="btn blue-btn creative_btn">{{ __('common.route_fulfilled_already')  }}</a>
                        @else
                        <a href="javascript::void(0);" class="btn blue-btn creative_btn" data-toggle="modal"
                            data-target="#cancel_request">{{ __('common.route_cancel_req_btn')  }}</a>
                        @endif
                        @elseif(@$requests_status == 2)
                        <a href="javascript::void(0);" class="btn blue-btn creative_btn"
                            data-toggle="modal">{{ __('common.route_cancelled_msg')  }}</a>
                        @else
                        <a href="javascript::void(0);" class="btn blue-btn creative_btn" data-toggle="modal"
                            data-target="#create_req">{{ __('common.route_create_req_btn')  }}</a>
                        @endif
                        @else
                        <a href="javascript::void(0);" class="btn blue-btn creative_btn" data-toggle="modal"
                            data-target="#login_req">{{ __('common.route_create_req_btn')  }}</a>

                        @endif
                        <!--  <a href="#" class="view_all_btn primary_color">View All Requests</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@section('js')

<script type="text/javascript">
var geocoder;
var map;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var locations = [
    <?php
if ($route_info->routeLocations) {
    $n = 1;
    foreach ($route_info->routeLocations as $key => $info) {?>['<?php echo $info->location; ?>',
        <?php echo $info->locationLat; ?>, <?php echo $info->locationLong; ?>, <?php echo $n; ?>],
    <?php
$n++;}
}?>

];

function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();


    var map = new google.maps.Map(document.getElementById('map_convas'), {
        zoom: 10,
        center: new google.maps.LatLng(<?php echo  @$route_info->driverLat ?>, <?php echo  @$route_info->driverLong ?>),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    directionsDisplay.setMap(map);


    // Show Drive current location
    var contentString = '<h6> {{ ucfirst(@$route_info->driverName) }} </h6>';
    var d_infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    var storeLocal = {
        lat: <?php echo  @$route_info->driverLat ?>,
        lng: <?php echo  @$route_info->driverLong ?>
    };
    var d_marker = new google.maps.Marker({
        position: storeLocal,
        icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
        map: map,
       
        title: 'Driver current location'
    });
    d_marker.addListener('click', function() {
        d_infowindow.open(map, d_marker);
    });
    // end

    // Location on map

    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    var request = {
        travelMode: google.maps.TravelMode.DRIVING
    };
    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));

        if (i == 0) request.origin = marker.getPosition();
        else if (i == locations.length - 1) request.destination = marker.getPosition();
        else {
            if (!request.waypoints) request.waypoints = [];
            request.waypoints.push({
                location: marker.getPosition(),
                stopover: true
            });
        }

    }
    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);

            var route = result.routes[0];
            var summaryPanel = document.getElementById('directions-panel');
            var  totalDistance = 0;;
            var  totalDuration = "";
            var  total_distance = "";
            var  total_duration = "";
            var approx_time = 0;
            // For each route, display summary information.
            var dis_km = 0;
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              total_distance += route.legs[i].distance.text + " / ";
              total_duration += route.legs[i].duration.text + " / ";
              let km = 1000;
              let mnt = 60;
              dis_km = route.legs[i].distance.value/parseInt(km);
              totalDistance = parseInt(totalDistance) + parseInt(dis_km);
              
              //tion 
              totalDuration = route.legs[i].duration.value;            
              var timemnt = secondsTimeSpanToHMS(totalDuration);
              var newDateObj = new Date();
              var oldDateObj = '<?php  echo date("G:i:s", strtotime($route_info->departureTime)) ; ?>';

              var newDateObj2 = new Date(oldDateObj);
              
/* 
              console.log(timemnt);
              console.log(totalDuration);
              console.log(oldDateObj);
              console.log(newDateObj2);
               */
              
             /// approx_time = newDateObj.setTime(oldDateObj.getTime() + (30 * 60 * 1000));
             // console.log('dasdsd' + approx_time);
            }
            //console.log(totalDistance);
            if(totalDistance != ""){
              //$('#total_distance_to_cover').html(totalDistance + "Duration"+ totalDuration);
              $('#total_distance_to_cover').html(totalDistance + " Km ");
            }else{
              $('#total_distance_to_cover').html('N/A');
            }

        }
    });
}
google.maps.event.addDomListener(window, "load", initialize);

function secondsTimeSpanToHMS(s) {
    var h = Math.floor(s/3600); //Get whole hours
    s -= h*3600;
    var m = Math.floor(s/60); //Get remaining minutes
    s -= m*60;
    return h+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s); //zero padding on minutes and seconds
}



</script>

@endsection

@if(auth()->check())

@if(@$requests_status == 1)

<!--------- Cancel Request for Route  end  --------->
<div class="modal fade popup create_req" id="cancel_request" req-ro="{{ @$route_info->routeId }}" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <h3>{{  htmlspecialchars_decode(__('common.route_cancel_confirm')) }}</h3>
            <div class="form-group">
                <input type="hidden" id="_token" style='visiblity' value="@php echo csrf_token() @endphp" />
                <input type="hidden" id="_base" value="@php echo url('/') @endphp" />

                <a href="javascript:void(0);" onclick="return cancel_req();"
                    class="btn blue-btn creative_btn route_request_btn">
                    {{  __('common.route_cancel_confirm_yes') }}</a>
                <a href="javascript:void(0);" class="btn blue-btn creative_btn route_request_btn"
                    data-dismiss="modal">{{  __('common.route_cancel_confirm_no') }}</a>

                <p id='showResponse' class='r_respo'></p>
            </div>
            <a href="javascript:void(0);" class="close_btn" data-dismiss="modal">x</a>
        </div>
    </div>
</div>
<!--------- Cacnel Request for Route  end  --------->
@else
<!------------------- Create Request for  Route PUP up Start --------------->
<div class="modal fade popup normal_pop create_req" id="create_req" tabindex="-1" req-ro="{{ @$route_info->routeId }}"
    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <h3>{{ __('common.route_req_popup_title') }} </h3>
            <div class="form_inputs">
                <div class="form-group">
                    <input type="text" class="form-control" disabled="disabled" placeholder="Name"
                        value="{{ @Auth::user()->name }}" />
                </div>
                <div class="form-group">
                    <textarea class="form-control textarea_control" id="requested_address"
                        placeholder="{{ __('common.route_req_popup_input_add') }} "></textarea>
                </div>
                <div class="form-group">
                    <select id="route_address" class="form-control">
                        <option value="">{{ __('common.route_req_popup_input_location') }} </option>
                        @php
                        if(!empty(@$route_info->routeLocations)){
                        foreach($route_info->routeLocations as $routeinformation) {
                        @endphp
                        <option value="{{ $routeinformation->id }}">{{ $routeinformation->location }} </option>
                        @php
                        }
                        }
                        @endphp
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="requested_qty"
                        placeholder="{{ __('common.route_req_popup_input_qty') }}"
                        onkeypress="return isNumberKey(event)" required="required" />
                </div>
                <div class="form-group">
                    <input type="hidden" id="_token" style='visiblity' value="@php echo csrf_token() @endphp" />
                    <input type="hidden" id="_base" value="@php echo url('/') @endphp" />
                    <a href="javascript:void(0)" class="btn blue-btn creative_btn"
                        onclick="return send_req();">{{ __('common.route_req_popup_input_btn') }}</a>

                    <p id='showResponse' class='r_respo'></p>
                </div>
            </div>
            <a href="javascript:void(0);" class="close_btn " data-dismiss="modal">x</a>
        </div>
    </div>
</div>
<!--------- Create Request for Route  end  --------->
@endif


@else

<!--------- Force to Login  PUP up Start  --------------->

<div class="modal fade popup normal_pop create_req" id="login_req" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <h3>{{  __('common.route_notloggedin') }}</h3>
            <div class="form-group">
                <a href="{{ url('/login') }}"
                    class="btn blue-btn creative_btn route_request_btn">{{  __('common.route_loginbtn') }}</a>
            </div>
            <a href="javascript:void(0);" class="close_btn" data-dismiss="modal">x</a>
        </div>
    </div>
</div>

<!--------- Force to Login  PUP up end  --------------->

@endif


@endsection