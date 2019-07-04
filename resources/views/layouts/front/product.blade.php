<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="product_detait_product product_detail_slider">
                <div id="slider" class="flexslider">
                    <ul class="slides">
                        <li>
                            @if(isset($product->cover))
                            <img class="img-responsive img-thumbnail"
                                src="{{ asset("storage/app/public/$product->cover") }}" alt="{{ $product->name }}" />
                            @else
                            <img class="img-responsive img-thumbnail" src="{{ asset("https://placehold.it/180x180") }}"
                                alt="{{ $product->name }}" />
                            @endif
                        </li>
                        @if(isset($images) && !$images->isEmpty())
                        @foreach($images as $image)
                        <li>
                            <img src="{{ asset("storage/app/public/$image->src") }}" alt="{{ $product->name }}" />
                        </li>
                        @endforeach
                        @endif

                    </ul>
                </div>
                <div id="carousel" class="flexslider">
                    <ul class="slides">
                        <li>
                            @if(isset($product->cover))
                            <img class="img-responsive img-thumbnail"
                                src="{{ asset("storage/app/public/$product->cover") }}" alt="{{ $product->name }}" />
                            @else
                            <img class="img-responsive img-thumbnail" src="{{ asset("https://placehold.it/180x180") }}"
                                alt="{{ $product->name }}" />
                            @endif
                        </li>
                        @if(isset($images) && !$images->isEmpty())
                        @foreach($images as $image)
                        <li>
                            <img src="{{ asset("storage/app/public/$image->src") }}" alt="{{ $product->name }}" />
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>

            </div>
            <div class="like_btn_outer">
                <!-- <a href="#"><img src="{{ asset('public/images/like_btns.png') }}" alt="like_btns.png" /></a> -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="product_detail_text about_text ">
                <h3>{{ $product->name }} </h3>

                <p class="primary_color"><strong>{{  __('common.p_brand') }}: {{ @$brand->name }}</strong></p>
                <p class="primary_color"><strong>{{  __('common.p_seller') }}:
                        {{ @$sellerInfo->service_provider->name }}</strong></p>
                <div class="description"> {!! $product->description !!}</div>

                <div class="station_detail_adress">
                    <div class="station_detail_adss_com product_detail_adress_lt">
                        <ul class="station_list price_bx">
                            <li>
                                <h3>{{ config('cart.currency') }} <span id="final_amonut">{{ $product->price }}</span>
                                    <span id="final_per_unit"> / {{ ucfirst($product->mass_unit) }} </span>
                                </h3>
                            </li>
                        </ul>
                    </div>
                    <input type='hidden' id="pamt" value="{{ $product->price }}">
                </div>
                @include('layouts.errors-and-messages')
                <form action="{{ route('cart.store') }}" id="cart_form" class="pro-form-inline" method="post">
                    {{ csrf_field() }}
                    <div class="description_comn description_bx">

                        <div class="description_list">
                            <ul>
                                @if(isset($productAttributes) && !$productAttributes->isEmpty())
                                <li>
                                    <div class="description_text_bx">
                                        <label>{{  __('common.p_product_sizes') }}:</label>
                                        <select name="productSizes" id="productAttribute" class="form-control select2">
                                            <option value="">{{  __('common.p_select_size') }}</option>
                                            @foreach($productAttributes as $productAttribute)
                                            <option value="{{ $productAttribute->id }}">
                                                @foreach($productAttribute->attributesValues as $value)
                                                {{ ucwords($value->value) }} {{ ucfirst($product->mass_unit) }}
                                                @endforeach
                                                <!-- @if(!is_null($productAttribute->price))
                                                ( {{ config('cart.currency_symbol') }} {{ $productAttribute->price }})
                                                @endif -->
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                @endif
                                <li>
                                    <div class="description_text_bx">
                                        <label>{{  __('common.p_qty') }}:</label>
                                        <div class="pt_Quantity">
                                            <input type="number" min="1" value="1"
                                                placeholder="{{  __('common.p_qty') }}:" max="100" step="1"
                                                class="form-control" name="quantity" id="p_quantity"
                                                value="{{ old('quantity') }}" data-inc="1">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="service_bx">
                            <label>{{  __('common.p_type_of_service') }}: </label>
                            <div class="custom_checkbox">
                                <label><span class="check_text"> @php
                                        echo ucfirst(str_replace('_', ' ', $product->serviceOfferedType));

                                        @endphp
                                    </span>

                                </label>

                                @if( $product->serviceOfferedType == 'home_delivery')
                                <label><span class="check_text">
                                        {{  __('common.p_delivery_charges') }}: <b>
                                            @php
                                            echo config('cart.currency') . " ". ucfirst(str_replace('_', ' ',
                                            $product->delivery_charges));
                                            @endphp </b>
                                    </span>
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!------------------------------->
                    <div class="form-group">
                        <br />
                        <input type="hidden" name="product" id="pid" value="{{ $product->id }}" />
                        <button type="submit" id="addTocart" class="btn blue-btn"><i class="fa fa-cart-plus"></i>
                            {{  __('common.p_add_to_cart') }}
                        </button>
                    </div>
                </form>
                <!------------------------------->

                <div class="description_comn location_bxx_main">
                    <div class="location_bxx location_head">
                        <p><strong> {{  __('common.p_location_of_store') }}:</strong> <span class="fa_icon_bx">
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
                                </svg></span></p>
                    </div>
                    <div class="location_bxx location_text">
                        <p>{{ @$store->storeLocation }}</p>
                    </div>


                </div>

                <div class="review_rating_bx about_text ">
                    <div class="review-head">
                        <h3>{{  __('common.p_rating_reviews') }}</h3>

                        @if(!empty($reviews['reviews']))
                        <div class="rating-bx-main">
                            <div class="rating-bx-lt">
                                <h4>{{ $reviews['totalAvg'] }} <i class="fa fa-star" aria-hidden="true"></i></h4>
                                <p>{{ $reviews['totalReviewsCount'] }} {{ __('common.product_rating_label') }}</p>

                            </div>
                            <div class="rating-bx-rt">
                                <div class="progress-list">
                                    <span>5 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-success"
                                            style="width:{{ $reviews['fivestar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $reviews['fivestar_totalCount'] }}</span>
                                </div>
                                <div class="progress-list">
                                    <span>4 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-success"
                                            style="width:{{ $reviews['fourstar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $reviews['fourstar_totalCount'] }}</span>
                                </div>
                                <div class="progress-list">
                                    <span>3 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-success"
                                            style="width:{{ $reviews['threestar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $reviews['threestar_totalCount'] }}</span>
                                </div>
                                <div class="progress-list">
                                    <span>2 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning"
                                            style="width:{{ $reviews['twostar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $reviews['twostar_totalCount'] }}</span>
                                </div>
                                <div class="progress-list">
                                    <span>1 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger"
                                            style="width:{{ $reviews['onestar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $reviews['onestar_totalCount'] }}</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="rating-bx-main">

                            <h6>{{ __('common.product_norating') }}</h6>

                        </div>
                        @endif
                    </div>

                    @if(!empty($reviews['reviews']))

                    @foreach($reviews['reviews'] as $review)
                    <div class="comment-list">
                        <h4>{{ $review->reviewTitle }}</h4>
                        <p>{{ $review->reviewDescription }}</p>

                        <br/>
                        <h6><b>By: {{ $review->customer->name }} </b></h6>
                    </div>
                    @endforeach
                    <div class="comment-list copyright-list">
                        <p><a href="{{ url('/').'/product/reviews/'.$product->slug }}"><u> {{ __('common.product_review_left') }}
                                    {{ $reviews['totalReviewsCount'] }} {{ __('common.product_review_right') }} </u></a>
                        </p>
                    </div>
                    @endif

                </div>


            </div>
        </div>
    </div>

    <div class="modal fade popup" id="cart_check" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <h3>{{ __('common.p_cart_popup_title') }}</h3>
                <div class="form-group">
                    <p>{{ __('common.p_cart_popup_content') }} <strong><span id="old_servicer"></span></strong>.
                        {{ __('common.p_cart_popup_content2') }} <strong><span id="new_servicer"></span></strong> ?
                    </p>
                </div>
                <div class="form-group">
                    <input type="hidden" id="_token" style='visiblity' value="@php echo csrf_token() @endphp" />
                    <input type="hidden" id="_base" value="@php echo url('/') @endphp" />
                    <a href="javascript:void(0);" onclick="return clearcart();"
                        class="btn blue-btn creative_btn route_request_btn">
                        {{  __('common.route_cancel_confirm_yes') }}</a>
                    <a href="javascript:void(0);" class="btn blue-btn creative_btn route_request_btn"
                        data-dismiss="modal">{{  __('common.route_cancel_confirm_no') }}</a>


                </div>
                <a href="javascript:void(0);" class="close_btn" data-dismiss="modal">x</a>
            </div>
        </div>
    </div>


    @section('js')
    <script type="text/javascript">
    $(document).ready(function() {
        calAmt();
    });
    $('#productAttribute').on('change', function() {
        $('#productAttribute').removeClass('red_error');
        calAmt();
    });

    $("#p_quantity").bind('keyup change click', function(e) {
        if ($(this).val() != "") {
            if (!$(this).data("previousValue") ||
                $(this).data("previousValue") != $(this).val()
            ) {
                //console.log("changed");
                calAmt();
                $(this).data("previousValue", $(this).val());
            }
        }
    });
    $("#p_quantity").each(function() {
        $(this).data("previousValue", $(this).val());
    });



    $('#addTocart').on('click', function(e) {
        e.preventDefault();
        var sizes = $('#productAttribute').val();
        if (sizes == "") {
            $('#productAttribute').addClass('red_error');
            return false;
        } else {
            $('#productAttribute').removeClass('red_error');
        }
        var product_id = $('#pid').val();
        var security_token = $('#_token').val();
        var base_url = $('#_base').val();
        $.ajax({
                method: "POST",
                dataType: "json",
                url: base_url + "/checkCart",
                data: {
                    'product_id': product_id,
                    '_token': security_token
                }
            })
            .done(function(res) {
                if (res.status == false) {
                    $('#old_servicer').html(res.old_servicer);
                    $('#new_servicer').html(res.new_servicer);
                    $('#cart_check').modal('show');
                    return false;
                } else {
                    $('#cart_form').unbind('submit').submit();
                }
            });

    });

    function clearcart() {

        var security_token = $('#_token').val();
        var base_url = $('#_base').val();
        $.ajax({
                method: "POST",
                dataType: "json",
                url: base_url + "/clearCart",
                data: {
                    '_token': security_token
                }
            })
            .done(function(res) {
                if (res.status == true) {
                    $('#cart_check').modal('hide');
                } else {
                    console.log('query error');
                    $('#cart_check').modal('hide');
                }
                $('#cart_form').unbind('submit').submit();
            });

    }
    </script>
    @endsection