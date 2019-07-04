@extends('layouts.front.app')

@section('content')

@if(!$products->isEmpty())


<section class="cart cart-grey cart_main">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="breadcrumb-cart">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i>
                                {{  __('common.ck_breadcrumb_home') }}</a> | </li>
                        <li><a href="{{ route('cart.index') }}"><i class="fa fa-cart-plus"></i>
                                {{  __('common.ck_breadcrumb_cart') }} </a> | </li>
                        <li class="active"> {{  __('common.ck_breadcrumb_checkout') }} </li>
                    </ol>
                </div>
                <div class="cart_left  cart_step_2">
                    <div class="cart_title_bx white_bg cart_row">
                        <div class="cart_title_bx_lt">
                            <h3>{{  __('common.ck_welcome') }}</h3>
                            <p>{{$customer->name}}</p>

                            @include('layouts.errors-and-messages')

                        </div>
                        <!-- <div class="cart_title_bx_rt">
                            <a href="#" class="btn cart_title_btn normal_btn">Change</a>
                        </div> -->
                    </div>
                    @if(count($addresses) > 0)
                    <div class="accordion cart_steps_main" id="order_step">

                        @if(isset($addresses))
                        <div class="card white_bg cart_row">
                            <div class="card-header " id="headingOne" data-toggle="collapse" data-target="#collapse1"
                                aria-expanded="true" aria-controls="collapseOne">
                                <h3><i class="fa fa-home"></i> {{  __('common.ck_delivery_add') }}</h3>

                            </div>

                            <div id="collapse1" class="collapse show" data-parent="#order_step">
                                <div class="card-body">
                                    <div class="card_body_row ">
                                        <div class="radio_step">

                                            <h5>{{  __('common.ck_billing_add') }}</h5>
                                            <hr>
                                            @foreach($addresses as $key => $address)


                                            <div class="custom_radio abs-right">
                                                <label>
                                                    <!--  <input type="radio" name="radio" checked> -->
                                                    <input type="radio" value="{{ $address->id }}"
                                                        name="billing_address" @if($billingAddress->id == $address->id)
                                                    checked="checked" @endif>

                                                    <span class="check_text"></span>
                                                    <div class="radio_content">
                                                        <p>{{ $address->alias }}</p>
                                                        <p> {{ $address->address_1 }} {{ $address->address_2 }} <br />
                                                            @if(!is_null($address->province))
                                                            {{ $address->city }} {{ $address->province->name }} <br />
                                                            @endif
                                                            {{ $address->city }} {{ $address->state_code }} <br>
                                                            {{ $address->country->name }} {{ $address->zip }}

                                                        </p>
                                                    </div>
                                                </label>
                                                @if($billingAddress->id == $address->id)
                                                <label class="sm_billing" for="sameDeliveryAddress">
                                                    <input type="checkbox" id="sameDeliveryAddress" checked="checked">
                                                    {{  __('common.ck_same_as_billing') }}
                                                </label>
                                                @endif
                                            </div>
                                            @endforeach



                                            <a href="{{ url('/accounts/addresses')  }}" class="edit_btn primary_color">
                                                {{  __('common.ck_manage_address') }}</a>
                                            <!-- <a href="#" class="btn blue-btn creative_btn ">Deliver Here</a> -->

                                        </div>
                                    </div>
                                    <div class="card_body_row" style="display: none" id="sameDeliveryAddressRow">

                                        <h5>{{  __('common.ck_samedelivery_label') }}</h5>
                                        <hr>
                                        <div class="radio_step">
                                            @foreach($addresses as $key => $address)
                                            <div class="custom_radio">
                                                <label>
                                                    <input type="radio" value="{{ $address->id }}"
                                                        name="delivery_address" @if(old('')==$address->id)
                                                    checked="checked" @endif>

                                                    <span class="check_text"></span>
                                                    <div class="radio_content">
                                                        <p>{{ $address->alias }}</p>
                                                        <p>{{ $address->address_1 }} {{ $address->address_2 }} <br />
                                                            @if(!is_null($address->province))
                                                            {{ $address->city }} {{ $address->province->name }} <br />
                                                            @endif
                                                            {{ $address->city }} {{ $address->state_code }} <br>
                                                            {{ $address->country->name }} {{ $address->zip }}
                                                        </p>
                                                    </div>
                                                </label>
                                            </div>

                                            @endforeach
                                            <!--<a href="#" class="edit_btn primary_color">Edit</a>-->
                                        </div>
                                    </div>

                                    <div class="place_order_row order_summary_btns" style="margin-bottom:20px;">
                                        <a href="javascript::void(0)" data-toggle="collapse" aria-expanded="true"
                                            aria-controls="collapseOne" data-target="#collapse2"
                                            class="btn blue-btn creative_btn">
                                            {{  __('common.ck_continue') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endif
                        <div class="card white_bg cart_row">
                            <div class="card-header collapsed" id="headingOne" data-toggle="collapse"
                                data-target="#collapse2" aria-expanded="true" aria-controls="collapseOne">
                                <h3><i class="fa fa-cart-plus"></i> {{  __('common.ck_order_summery') }}</h3>
                            </div>

                            <div id="collapse2" class="collapse " data-parent="#order_step">
                                <div class="card-body">
                                    <div class="card_body_row">

                                        <div class="order_sumary_product">

                                            @include('front.products.checkout-list', compact('products'))


                                            <div class="order_date_outer">
                                                <!-- <div class="order_date_comn order_date_lt">
                                                    <p>Ordered On <strong>Wed, Aug 15th ‘18</strong></p>
                                                </div>
                                                <div class="order_date_comn order_date_rt">
                                                    <p>Order total <strong>$220</strong></p>
                                                </div> -->
                                            </div>
                                        </div>




                                        <div class="place_order_row order_summary_btns">
                                            <a href="javascript::void(0)" data-toggle="collapse" aria-expanded="true"
                                                aria-controls="collapseOne" data-target="#collapse3"
                                                class="btn blue-btn creative_btn">{{  __('common.ck_continue') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card white_bg cart_row">
                            <div class="card-header collapsed" id="headingOne" data-toggle="collapse"
                                data-target="#collapse3" aria-expanded="true" aria-controls="collapseOne">
                                <h3><i class="fa fa-credit-card"> </i> {{  __('common.ck_payment_opt') }}</h3>
                            </div>

                            <div id="collapse3" class="collapse " data-parent="#order_step">

                                <div class="card-body">
                                    <div class="card_body_row ">
                                        <div class="radio_step">
                                            <h5>{{  __('common.ck_payments') }}</h5>
                                            <a href="{{ url('/accounts/payments')  }}" target="_blank"
                                                class="edit_btn primary_color">
                                                {{  __('common.ck_manage_payments') }}</a>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-body">
                                    @if(!is_null($rates))
                                    <div class="card_body_row" style='display:none;'>
                                        <input type="radio" checked name="rate" data-fee="{{ $rates->amount }}"
                                            value="{{ $rates->object_id }}">
                                    </div>
                                    @endif

                                    @if(isset($stripe_customer_cards) && !empty($stripe_customer_cards))
                                    <div class="card_body_row">
                                        <div class="card_box_row">
                                            @if(isset($payments) && !empty($payments))
                                            @foreach($payments as $payment)
                                            @include('layouts.front.payment-options', compact('payment', 'total',
                                            'shipment', 'stripe_customer_cards'))
                                            @endforeach
                                            @else
                                            <p class="alert alert-danger">{{  __('common.ck_no_payment_method_set') }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>

                                    @else
                                    <div class="card_body_row">
                                        <div class="card_box_row ">
                                            <p class="alert alert-danger col-md-12"><a
                                                    href="{{ url('/accounts/payments')  }}"
                                                    target="_blank">{{  __('common.ck_no_payment_method_set') }}</a></p>
                                        </div>
                                    </div>
                                    @endif


                                </div>
                            </div>
                        </div>

                    </div>
                    @else
                    <p class="alert alert-danger"><a
                            href="{{ route('customer.address.create', [$customer->id]) }}">{{  __('common.ck_no_address_found') }}</a>
                    </p>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="cart_right_bxx white_bg">
                    <div class="cart_head">
                        <h3>{{  __('common.ck_right_price_detail') }}</h3>
                    </div>
                    <div class="order_place_bx">
                        <ul>
                            <li>{{  __('common.ck_right_price_subtotal') }}<bdi
                                    class="order_price">{{config('cart.currency')}}
                                    {{ number_format($subtotal, 2, '.', ',') }}</bdi></li>
                            <li>{{  __('common.ck_right_delivery_charges') }}<bdi
                                    class="order_price">{{config('cart.currency')}} <span
                                        id="shippingFee">{{ number_format(0, 2) }}</span></bdi></li>
                            <li>{{ __('common.cart_tax') }} <bdi class="order_price">{{config('cart.currency')}}
                                    {{ number_format($tax, 2) }}</bdi></li>
                            <li class="total_price">{{  __('common.ck_right_amount_payable') }}<bdi
                                    class="order_price">{{config('cart.currency')}}
                                    <span id="grandTotal"
                                        data-total="{{ $total }}">{{ number_format($total, 2, '.', ',') }}</span></bdi>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@else

<section class="cart-grey cart empty_cart cart_coman_style">
    <div class="container">
        <div class="white_bg cart_innr">
            <div class="cart_head">
                <h3><i class="fa fa-cart-plus"></i> {{  __('common.ck_shopping_cart') }}</h3>
            </div>
            <div class="empty-cart-cent text-center">
                <img src="{{ asset('public/images/empty-cart.png') }} " class="empty-img" alt="empty-cart.png" />
                <p class="empty-text"><strong>{{  __('common.ck_shopping_cart_empty') }}<a
                            href="{{ route('home') }}">{{  __('common.ck_shopping_shopnow') }}</a></strong>
                </p>
            </div>
        </div>
    </div>
</section>


@endif


<div class="mainloader overlay-fixed" id="order_overlay-loader" style="display: none;">
    <img src="{{ url('/') }}/public/images/main_loader.png" alt="main_loader.png">
</div>

@endsection