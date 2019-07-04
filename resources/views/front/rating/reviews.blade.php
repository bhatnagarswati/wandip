@extends('layouts.front.app')
@section('content')

<section class="dashboard_main rating_review_main cart-grey">
    <div class="container">
        <div class="cart_head white_bg">
            <h3>{{ __('common.rating_page_title')  }}</h3>

            @include('layouts.errors-and-messages')

        </div>
        <form method="post" action="{{ url('/') }}/{{ $orderId }}/submitReview/{{ $product->slug }}" >
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
                    <div class="rating_star_bx">
                        <h3>{{ __('common.rating_star_top') }}</h3>
                        <div class="rating_star">
                            <fieldset class="rating">
                                <input type="radio" id="star5" name="rating" value="5" /><label class="full" for="star5"
                                    title="Awesome - 5 stars"></label>
                                <!-- <input type="radio" id="star4half" name="rating" value="4 and a half" /><label
                                    class="half" for="star4half" title="Pretty good - 4.5 stars"></label> -->
                                <input type="radio" id="star4" name="rating" value="4" /><label class="full" for="star4"
                                    title="Pretty good - 4 stars"></label>
                            <!--     <input type="radio" id="star3half" name="rating" value="3 and a half" /><label
                                    class="half" for="star3half" title="Meh - 3.5 stars"></label> -->
                                <input type="radio" id="star3" name="rating" value="3" /><label class="full" for="star3"
                                    title="Meh - 3 stars"></label>
                               <!--  <input type="radio" id="star2half" name="rating" value="2 and a half" /><label
                                    class="half" for="star2half" title="Kinda bad - 2.5 stars"></label> -->
                                <input type="radio" id="star2" name="rating" value="2" /><label class="full" for="star2"
                                    title="Kinda bad - 2 stars"></label>
                               <!--  <input type="radio" id="star1half" name="rating" value="1 and a half" /><label
                                    class="half" for="star1half" title="Meh - 1.5 stars"></label> -->
                                <input type="radio" id="star1" name="rating" value="1" /><label class="full" for="star1"
                                    title="Sucks big time - 1 star"></label>
                                <!-- <input type="radio" id="starhalf" name="rating" value="half" /><label class="half"
                                    for="starhalf" title="Sucks big time - 0.5 stars"></label> -->
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="comn_height dashboard_colm_rt dashboard_rating_rt white_bg">
                    <h3>{{ __('common.rating_right_review') }}</h3>
                    <div class="dashboard_form rating_form">                    
                            <div class="form-group">
                                <input type="text" name="rating_title" class="form-control" value="{{ old('rating_title') }}" placeholder="{{ __('common.rating_title') }}" />
                            </div>
                            <div class="form-group">
                                <textarea class="form-control form-textarea" name="user_review" placeholder="{{ __('common.rating_review') }}">{{ old('user_review') }}</textarea>
                            </div>
                            <div class="rating_btn">

                                <button type="submit" class="btn large_btn blue-btn creative_btn">{{ __('common.rating_submit')  }}</button>
                            </div>
                    </div>
                </div>
            </div>
        
        </div>
        </form>
    </div>
</section>
<!--our team-->


@endsection