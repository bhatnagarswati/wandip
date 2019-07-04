@extends('layouts.front.app')

@section('content')
<div class="container product-in-cart-list">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <h3>{{  __('common.ck_order_success') }}</h3>
            <p class="alert alert-success">

                {{  __('common.ck_order_success_line_first') }}<br />
                {{  __('common.ck_order_success_line_second') }} <br />
                {{  __('common.ck_order_success_line_third') }} <br />

            </p>
            <p class="text-right"><a class="btn blue-btn creative_btn"
                    href="{{ route('home') }}">{{  __('common.ck_order_success_continue') }} </a> </p>
            <br />
        </div>
    </div>
</div>
@endsection