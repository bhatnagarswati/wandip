@extends('layouts.front.app')

@section('content')

<!--Cart-->
@if(!$cartItems->isEmpty())
<section class="cart cart-grey cart_main  product-in-cart-list">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="breadcrumb-cart">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i>{{  __('common.ck_breadcrumb_home') }}</a> | </li>
                        <li class="active">{{  __('common.ck_breadcrumb_cart') }}</li>
                    </ol>
                </div>
                <div class="cart_left white_bg cart_added">
                    <div class="cart_head">
                        <div class="box-body">
                            @include('layouts.errors-and-messages')
                        </div>
                        <h3><i class="fa fa-cart-plus"></i> {{ __('common.cart_heading') }}</h3>
                    </div>
                    <div class="cart_list_main">
                        @foreach($cartItems as $cartItem)
                        <div class="cart_list_row">
                            <div class="cart_product_bx cart_added_cart">
                                <div class="product_img_bx">
                                    <a href="{{ route('front.get.product', [$cartItem->product->slug]) }}"
                                        class="hover-border">
                                        @if(isset($cartItem->cover))
                                        <img src="{{$cartItem->cover}}" alt="{{ $cartItem->name }}"
                                            class="img-responsive img-thumbnail">
                                        @else
                                        <img src="https://placehold.it/120x120" alt=""
                                            class="img-responsive img-thumbnail">
                                        @endif
                                    </a>
                                </div>
                                <div class="text-center">
                                    <form action="{{ route('cart.update', $cartItem->rowId) }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="put">
                                        <div class="pt_Quantity custom_inscrease_btn">
                                            <div class="input-group">
                                                <input type="text" name="quantity" min="1" max="100" step="1"
                                                    class="form-control" value="{{ $cartItem->qty }}" data-inc="1">
                                            </div>
                                        </div>

                                        <div class="mt-3 text-center">
                                            <button class="btn cart-qty-update-btn blue-btn btn-sm">{{ __('common.cart_update_btn') }}</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="product_list_detail_comn product_list_detail ">
                                <p>
                                    <a
                                        href="{{ route('front.get.product', [$cartItem->product->slug]) }}">{{ $cartItem->name }}</a>
                                </p>
                                <bdi class="seller">{{ __('common.cart_product_seller')  }}: {{ $cartItem->servicer }}</bdi>

                                @if($cartItem->options->has('combination'))
                                @foreach($cartItem->options->combination as $option)
                                <small class="label label-primary">{{$option['value']}}</small>
                                @endforeach
                                @endif

                                <div class="product-description">
                                    {!! strlen($cartItem->product->description) > 15 ? substr($cartItem->product->description, 0, 250)."..." : $cartItem->product->description  !!}
                                </div>
                            </div>
                            <div class="product_delever_bx">
                                <form action="{{ route('cart.destroy', $cartItem->rowId) }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" class="remove_btn" name="_method" value="delete">
                                    <button onclick="return confirm('Are you sure?')" class="btn btn-danger"><i
                                            class="fa fa-times"></i></button>
                                </form>

                                <h3 class="price_hd">{{config('cart.currency')}}
                                    {{ number_format($cartItem->price, 2) }}</h3>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="place_order_row">
                        <a href="{{ route('home') }}" class="view_all_btn primary_color">{{ __('common.cart_continue_shopping')  }}</a>
                        <a href="{{ route('checkout.index') }}" class="btn blue-btn creative_btn">{{ __('common.cart_place_order')  }}</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="cart_right_bxx white_bg">
                    <div class="cart_head">
                        <h3>{{ __('common.cart_price_details')  }}</h3>
                    </div>
                    <div class="order_place_bx">
                        <ul>

                            <li>{{ __('common.ck_right_price_subtotal')  }}<bdi class="order_price">{{config('cart.currency')}}
                                    {{ number_format($subtotal, 2, '.', ',') }}</bdi>
                            </li>

                            @if(isset($shippingFee) && $shippingFee != 0)
                            <li>
                            {{ __('common.ck_right_delivery_charges')  }}<bdi class="order_price">{{config('cart.currency')}}
                                    {{ $shippingFee }}</bdi></li>
                            @endif
                            <li>{{ __('common.cart_tax') }} <bdi class="order_price">{{config('cart.currency')}}   {{ number_format($tax, 2) }}</bdi></li>
                            <li class="total_price">{{ __('common.cart_total_amt_pay') }}<bdi
                                    class="order_price">{{config('cart.currency')}}
                                    {{ number_format($total, 2, '.', ',') }}</bdi></li>
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
                <p class="empty-text"><strong>{{  __('common.ck_shopping_cart_empty') }} <a href="{{ route('home') }}">{{  __('common.ck_shopping_shopnow') }}</a></strong>
                </p>
            </div>
        </div>
    </div>
</section>

@endif


 
@endsection
@section('css')
<style type="text/css">
/* .product-description {
    padding: 10px 0;
}

.product-description p {
    line-height: 18px;
    font-size: 14px;
} */
</style>
@endsection