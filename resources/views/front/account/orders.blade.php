@extends('layouts.front.app')

@section('content')

<section class="dashboard_main rating_review_main cart-grey">
    <div class="container">
        <div class="dashboard_main_row">

            @include('layouts.front.account-sidebar')

            <!---- right panel start --->
            <div class="dashboard_content_main">
                <div class="comn_height dashboard_colm_rt white_bg">
                    <div class="box-body">
                        @include('layouts.errors-and-messages')
                    </div>
                    <div class="col_sm_12 col_xl_10 ">
                        <div class="my_order_main">
                            <h3>{{ __('common.ac_page_myorders')  }}</h3>
                            <div class="order_list_main">



                                <!--------------------->
                                @if(!$orders->isEmpty())
                                @foreach ($orders as $order)

                                <div class="modal fade" id="order_modal_{{$order['id']}}" tabindex="-1" role="dialog"
                                    aria-labelledby="MyOrders">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Reference
                                                    #{{$order['reference']}}</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                            </div>
                                            <div class="modal-body">
                                                <table class="table">
                                                    <thead>
                                                        <th>Txn Id</th>
                                                        <th>Address</th>
                                                        <th>Payment Method</th>
                                                        <th>Status</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $order['txn_id'] }}
                                                            <td>
                                                                <address>
                                                                    <strong>{{$order['address']->alias}}</strong><br />
                                                                    {{$order['address']->address_1}}
                                                                    {{$order['address']->address_2}}<br>
                                                                </address>
                                                            </td>
                                                            <td> {{$order['payment']}} </td>

                                                            </td>
                                                            <td>
                                                                <p class="text-center"
                                                                    style="color: #ffffff; background-color: {{ $order['status']->color }}">
                                                                    {{ $order['status']->name }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="bg-warning">Subtotal</td>
                                                            <td class="bg-warning">{{ $order['total_products'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="bg-warning">Tax</td>
                                                            <td class="bg-warning">{{ $order['tax'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="bg-warning">Delivery Charges</td>
                                                            <td class="bg-warning">{{ $order['total_shipping'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="bg-success text-bold">Order Total</td>
                                                            <td class="bg-success text-bold">{{ $order['total'] }}</td>
                                                        </tr>
                                                        @if($order['total_paid'] != $order['total'])
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="bg-danger text-bold">Total paid</td>
                                                            <td class="bg-danger text-bold">{{ $order['total_paid'] }}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="order_sumary_product">
                                    <div class="final_order_list">

                                        <div class="order_sumary_title">
                                            <p class="primary_color"> <strong> <a data-toggle="modal"
                                                        data-target="#order_modal_{{$order['id']}}" title="Show order"
                                                        href="javascript::void(0)"> Order Id
                                                        #{{$order['id']}} </a></strong>
                                            </p>
                                        </div>


                                        <!-- List all ordered product -->
                                        @if(!empty($order['products']))

                                        @foreach($order['products'] as $product)
                                        <div class="cart_list_row">
                                            <div class="cart_product_bx cart_added_cart">
                                                <div class="product_img_bx">
                                                    <img src="{{ asset('storage/app/public/'.$product['cover'] ) }}"
                                                        alt="No image" />
                                                </div>
                                            </div>
                                            <div class="product_list_detail_comn product_list_detail ">

                                                <p><a target="_blank"
                                                        href="{{ url('/'.$product['slug'] ) }}">{{ $product['name']  }}</a>
                                                </p>
                                                <bdi class="seller">Seller: {{ $order['service_provider'] }}</bdi>
                                                <bdi class="seller">Size: {{ $product['product_attribute_value'] }}
                                                    {{ $product['mass_unit'] }}</bdi>
                                                <h3 class="price_hd"> {{ config('cart.currency') }}
                                                    {{ $product['price'] * $product['product_attribute_value'] * $product['quantity']  }}
                                                </h3>

                                            </div>
                                            <div class="product_delever_bx text-right">
                                                <p class="text-center"
                                                    style="color: #ffffff; background-color: {{ $order['status']->color }}">
                                                    {{ $order['status']->name }} </p>

                                                @if($order['order_status_id'] == 2)
                                                <div class="order_date_comn order_date_lt text-left">
                                                    Delivered Date: <br />
                                                    {{ date('M d, Y h:i a', strtotime($order['updated_at'])) }}
                                                </div>

                                                @if($product['rating'] != 0)
                                                    
                                                        <div class="rating_star_bx">
                                                            <div class="rating_star" style="pointer-events:none">
                                                                <fieldset class="rating">
                                                                    <input type="radio" id="star5" name="rating"
                                                                        value="5" @if($product['rating'] == 5) checked @endif /><label class="full" for="star5"
                                                                        title="Awesome - 5 stars"></label>

                                                                    <input type="radio" id="star4" name="rating"
                                                                        value="4" @if($product['rating'] == 4) checked @endif /><label class="full" for="star4"
                                                                        title="Pretty good - 4 stars"></label>

                                                                    <input type="radio" id="star3" name="rating"
                                                                        value="3"  @if($product['rating'] == 3) checked @endif /><label class="full" for="star3"
                                                                        title="Meh - 3 stars"></label>

                                                                    <input type="radio" id="star2" name="rating"
                                                                        value="2" @if($product['rating'] == 2) checked @endif /><label class="full" for="star2"
                                                                        title="Kinda bad - 2 stars"></label>

                                                                    <input type="radio" id="star1" name="rating"
                                                                        value="1" @if($product['rating'] == 1) checked @endif /><label class="full" for="star1"
                                                                        title="Sucks big time - 1 star"></label>

                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                @else

                                                <div class="order_date_comn order_date_lt text-left">
                                                    <a class="btn blue-btn"
                                                        href="{{ url('/').'/'.$order['id'].'/ratings/'.$product['slug'] }}">Write
                                                        a review </a>
                                                </div>
                                                @endif
                                                @endif

                                            </div>
                                        </div>
                                        @endforeach
                                        @endif


                                    </div>
                                    <div class="order_date_outer">
                                        <div class="order_date_comn order_date_lt">
                                            Oredered Date:
                                            {{ date('M d, Y h:i a', strtotime($order['created_at'])) }}
                                        </div>
                                        <div class="order_date_comn order_date_rt">
                                            <p> Order total <strong> <span
                                                        class="label @if($order['total'] != $order['total_paid']) label-danger @else label-success @endif">
                                                        {{ config('cart.currency') }}
                                                        {{ $order['total'] }} </span> </strong></p>
                                        </div>
                                    </div>
                                </div>


                                @endforeach

                                {{ $orders->links() }}
                                @else
                                <p class="alert alert-warning">{{ __('common.ac_page_no_orders_yet')  }} <a
                                        href="{{ route('home') }}">{{ __('common.ac_page_shop_now')  }}</a>
                                </p>
                                @endif
                                <!--------------------->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!---- right panel end --->

        </div>
    </div>
</section>

@endsection