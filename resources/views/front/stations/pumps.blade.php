@extends('layouts.front.app')
@section('content')
<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{ __('common.stations_page_title') }}</h1>
        </div>
    </div>
</section>

<section class="adblue_station_main">
    <div class="container">
        <div class="user_form_detail aos-init aos-animate" data-aos="fade-left" data-aos-duration="1500">
            <form method="GET" action="{{ url('/stations') }}" accept-charset="UTF-8" role="search">
                <ul>
                    <li>
                        <input class="form-control" name="t" type="text" value="{{ Request::input('t') }}"
                            placeholder="{{  __('common.stations_station_name_search')  }}">
                    </li>
                    <!-- <li>
                   <select class="form-control arrow_down">
                     <option selected="">City</option>
                     <option>City1</option>
                     <option>City2</option>
                     <option>City3</option>
                     <option>City4</option>
                   </select>
               </li>
               <li>
                   <select class="form-control arrow_down">
                     <option selected="">State</option>
                     <option>State1</option>
                     <option>State2</option>
                     <option>State3</option>
                   </select>
               </li> -->
                    <li>
                        <input class="form-control" name="a" type="text" value="{{ Request::input('a') }}"
                            placeholder="{{  __('common.stations_address_search')  }}">
                    </li>
                    <li>
                        <select name="m" class="form-control arrow_down">
                            <option value="all" @if(Request::input('m')=='all' ) selected @endif>
                                {{  __('common.stations_miles_all')  }}</option>
                            <option value='5' @if(Request::input('m')=='5' ) selected @endif>
                                {{  __('common.stations_miles_5')  }}</option>
                            <option value='10' @if(Request::input('m')=='10' ) selected @endif>
                                {{  __('common.stations_miles_10')  }}</option>
                            <option value='20' @if(Request::input('m')=='20' ) selected @endif>
                                {{  __('common.stations_miles_20')  }}</option>
                            <option value='50' @if(Request::input('m')=='50' ) selected @endif>
                                {{  __('common.stations_miles_50')  }}</option>
                            <option value='100' @if(Request::input('m')=='100' ) selected @endif>
                                {{  __('common.stations_miles_100')  }}</option>
                        </select>
                    </li>
                    <li>
                        <button type="submit" class="btn submit_btn">{{  __('common.stations_byn_search')  }}</button>
                    </li>
                </ul>
            </form>
        </div>

        <div class="row">



            @if(!empty($pumps))
            @foreach($pumps as $pump)

            <div class="col-md-3">
                <div class="commn_station station_bx">
                <a  href="{{ url('/stations/'.$pump->pumpId.'/'.urlencode($pump->pumpTitle))  }}"> <figure class="product_figure "
                        style="background-image: url('{{ asset('public/uploads/pumps/'.$pump->pumpPic.'')}}')">
                       </figure> </a>
                    <div class="item_content">
                        <h3><a href="{{ url('/stations/'.$pump->pumpId.'/'.urlencode($pump->pumpTitle))  }}">{{  $pump->pumpTitle }}
                            </a></h3>
                        <ul class="station_list">
                            <li><span class="fa_icon_bx">
                                    <svg id="map" viewBox="0 0 306.078 381.602">
                                        <g>
                                            <path
                                                d="M152.961,0C68.641,0,0,68.641,0,152.961c0,9.598,0.879,19.277,2.719,28.719
                                            c0.082,0.558,0.402,2.242,1.043,5.121c2.316,10.32,5.758,20.48,10.238,30.16c16.48,38.801,52.719,98.398,132.238,162.238
                                            c2,1.602,4.403,2.402,6.801,2.402c2.402,0,4.801-0.801,6.801-2.402c79.441-63.84,115.762-123.438,132.238-162.238
                                            c4.48-9.68,7.922-19.762,10.242-30.16c0.641-2.879,0.961-4.563,1.039-5.121c1.762-9.442,2.719-19.121,2.719-28.719
                                            C305.922,68.641,237.281,0,152.961,0L152.961,0z M281.922,177.922c0,0.156-0.082,0.316-0.082,0.476
                                            c-0.078,0.403-0.32,1.602-0.719,3.442V182c-2,8.961-4.961,17.68-8.883,26.078c-0.078,0.082-0.078,0.242-0.16,0.32
                                            c-14.957,35.441-47.758,89.523-119.117,148.48C81.602,297.922,48.801,243.84,33.84,208.398c-0.078-0.078-0.078-0.238-0.16-0.32
                                            c-3.84-8.316-6.801-17.117-8.879-26.078v-0.16c-0.481-1.84-0.641-3.039-0.723-3.442c0-0.16-0.078-0.32-0.078-0.558
                                            c-1.602-8.238-2.398-16.559-2.398-24.961c0-72.399,58.957-131.359,131.359-131.359c72.398,0,131.359,58.96,131.359,131.359
                                            C284.32,161.359,283.52,169.762,281.922,177.922L281.922,177.922z M281.922,177.922" />
                                            <path
                                                d="M152.961,57.52c-53.52,0-97.121,43.601-97.121,97.121c0,53.519,43.601,97.121,97.121,97.121
                                            c53.52,0,97.117-43.602,97.117-97.121C250.078,101.121,206.48,57.52,152.961,57.52L152.961,57.52z M152.961,230.16
                                            c-41.68,0-75.52-33.922-75.52-75.519c0-41.602,33.918-75.52,75.52-75.52c41.598,0,75.52,33.918,75.52,75.52
                                            C228.48,196.238,194.641,230.16,152.961,230.16L152.961,230.16z M152.961,230.16" />
                                        </g>
                                    </svg></span>{{  $pump->pumpAddress }}</li>
                            <li><span class="fa_icon_bx">
                                    <svg id="pump" viewBox="0 0 37.5 40">
                                        <g>
                                            <defs>
                                                <rect id="SVGID_1_" width="37.5" height="40" />
                                            </defs>
                                            <clipPath id="SVGID_2_">
                                                <use xlink:href="#SVGID_1_" overflow="visible" />
                                            </clipPath>
                                            <path clip-path="url(#SVGID_2_)" fill="none" d="M8.203,14.063c0.647,0,1.172,0.524,1.172,1.172c0,0.647-0.525,1.172-1.172,1.172
                                            s-1.172-0.525-1.172-1.172C7.031,14.587,7.556,14.063,8.203,14.063" />
                                            <path clip-path="url(#SVGID_2_)" fill="none"
                                                d="M24.609,0H5.859C3.92,0,2.343,1.577,2.343,3.516v29.375H1.171
                                            C0.524,32.891,0,33.415,0,34.063v4.766C0,39.475,0.524,40,1.171,40h28.125c0.648,0,1.173-0.525,1.173-1.172v-4.766
                                            c0-0.647-0.524-1.172-1.173-1.172h-1.172v-2.506c3.972-0.571,7.032-3.994,7.032-8.119V18.75h1.172c0.647,0,1.172-0.525,1.172-1.172
                                            v-7.031c0-0.647-0.524-1.172-1.172-1.172h-1.172V6.83c0-2.45-0.954-4.754-2.687-6.487c-0.458-0.457-1.2-0.457-1.657,0
                                            c-0.458,0.458-0.458,1.2,0,1.658c1.29,1.29,2,3.005,2,4.829v2.545h-1.172c-0.647,0-1.172,0.525-1.172,1.172v7.031
                                            c0,0.647,0.524,1.172,1.172,1.172h1.172v3.516c0,2.83-2.017,5.197-4.688,5.741V3.516C28.124,1.577,26.547,0,24.609,0
                                             M32.813,11.719h2.344v4.687h-2.344V11.719z M28.124,37.656H2.343v-2.421h25.781V37.656z M4.688,32.891V3.516
                                            c0-0.646,0.526-1.172,1.172-1.172h18.75c0.646,0,1.172,0.526,1.172,1.172v29.375H4.688z" />
                                            <path clip-path="url(#SVGID_2_)" fill="none" d="M22.265,4.688H8.203c-0.647,0-1.172,0.524-1.172,1.172v4.687
                                            c0,0.647,0.525,1.172,1.172,1.172h14.062c0.648,0,1.172-0.525,1.172-1.172V5.86C23.437,5.212,22.913,4.688,22.265,4.688
                                             M21.094,9.375H9.375V7.031h11.719V9.375z" />
                                            <path clip-path="url(#SVGID_2_)" fill="none"
                                                d="M16.198,14.568c-0.218-0.317-0.579-0.506-0.964-0.506
                                            c-0.385,0-0.745,0.189-0.964,0.506c-0.049,0.071-1.212,1.758-2.393,3.795c-1.684,2.902-2.502,4.945-2.502,6.246
                                            c0,3.23,2.629,5.859,5.859,5.859c3.23,0,5.858-2.629,5.858-5.859c0-1.301-0.817-3.344-2.502-6.246
                                            C17.41,16.326,16.247,14.639,16.198,14.568 M15.234,28.125c-1.938,0-3.516-1.577-3.516-3.516c0-1.28,1.829-4.62,3.516-7.256
                                            c1.687,2.636,3.516,5.976,3.516,7.256C18.75,26.548,17.172,28.125,15.234,28.125" />
                                        </g>
                                    </svg>
                                </span><strong>${{  $pump->pumpPrice }}/{{  $pump->pumpMassUnit }}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
            <div class='clearfix'></div>
            <div class="col-md-12 text-center">
                {{ @$pagination->links() }}
            </div>
        </div>
</section>
@endsection