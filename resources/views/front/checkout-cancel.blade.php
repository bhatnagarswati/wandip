@extends('layouts.front.app')

@section('content')
<div class="container product-in-cart-list">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <p class="alert alert-warning"> {{ __('common.ck_order_cancel_1') }}<a href="{{ route('home') }}">
                    {{ __('common.ck_order_cancel_2') }}</a></p>
        </div>
    </div>
</div>
@endsection