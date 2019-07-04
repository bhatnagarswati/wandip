@extends('layouts.front.app')
@section('content')

<section class="dashboard_main rating_review_main cart-grey">
    <div class="container">
        <div class="cart_head white_bg">
            <h3>{{ __('common.rating_page_title')  }}</h3>

             @include('layouts.errors-and-messages')

        </div>
         
         {{ csrf_field() }}
        <div class="dashboard_main_row row">
            <div class="col-md-3">
                <div class="comn_height dashboard_sidebar_rating white_bg">
                    <div class="rating_product_bx">
                        <figure><img src="{{ asset('storage/app/public/'.$product->cover) }} " alt="product rating" /></figure>
                        <div class="rating_product_detail">
                        <h3>{{ $product->name }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="comn_height dashboard_colm_rt dashboard_rating_rt white_bg">
                <div class="review_rating_bx about_text ">
                    <div class="review-head">
                        <h3>{{  __('common.p_rating_reviews') }}</h3>
                        @if(!empty($response['reviews']))
                        <div class="rating-bx-main">
                            <div class="rating-bx-lt">
                                <h4>{{ $response['totalAvg'] }} <i class="fa fa-star" aria-hidden="true"></i></h4>
                                <p>{{ $response['totalReviewsCount'] }} {{ __('common.product_rating_label') }}</p>

                            </div>
                            <div class="rating-bx-rt">
                                <div class="progress-list">
                                    <span>5 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-success"
                                            style="width:{{ $response['fivestar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $response['fivestar_totalCount'] }}</span>
                                </div>
                                <div class="progress-list">
                                    <span>4 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-success"
                                            style="width:{{ $response['fourstar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $response['fourstar_totalCount'] }}</span>
                                </div>
                                <div class="progress-list">
                                    <span>3 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-success"
                                            style="width:{{ $response['threestar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $response['threestar_totalCount'] }}</span>
                                </div>
                                <div class="progress-list">
                                    <span>2 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning"
                                            style="width:{{ $response['twostar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $response['twostar_totalCount'] }}</span>
                                </div>
                                <div class="progress-list">
                                    <span>1 <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger"
                                            style="width:{{ $response['onestar_totalAvg'] }}%;"></div>
                                    </div>
                                    <span class="review-text">{{ $response['onestar_totalCount'] }}</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="rating-bx-main">

                            <h6>{{ __('common.product_norating') }}</h6>

                        </div>
                        @endif
                    </div>

                    @if(!empty($response['reviews']))

                    @foreach($response['reviews'] as $review)
                    <div class="comment-list">
                        <h4>{{ $review->reviewTitle }}</h4>
                        <p>{{ $review->reviewDescription }}</p>

                        <br/>
                        <h6><b>By: {{ $review->customer->name }} </b></h6>
                    </div>
                    @endforeach
                     
                    @endif

                </div>
 
                </div>
            </div>
        
        </div>
      
    </div>
</section>
<!--our team-->


@endsection