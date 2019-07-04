@foreach($cartItems as $cartItem)
<div class="final_order_list">
    <div class="order_sumary_title">
        <p class="primary_color"><strong>Product Id : {{ $cartItem->product->id  }}</strong>
        </p>
    </div>
    <div class="cart_list_row">
        <div class="cart_product_bx cart_added_cart">
            <div class="product_img_bx">
                <a href="{{ route('front.get.product', [$cartItem->product->slug]) }}" class="hover-border">
                    @if(isset($cartItem->cover))
                    <img src="{{$cartItem->cover}}" alt="{{ $cartItem->name }}" class="img-responsive img-thumbnail">
                    @else
                    <img src="https://placehold.it/120x120" alt="" class="img-responsive img-thumbnail">
                    @endif
                </a>
            </div>
        </div>
        <div class="product_list_detail_comn product_list_detail ">
            <p><a href="{{ route('front.get.product', [$cartItem->product->slug]) }}">{{ $cartItem->name }}</a></p>
            <bdi class="seller">{{ __('common.ck_product_seller') }} {{ $cartItem->servicer }}</bdi>
            @if($cartItem->options->has('combination'))
            @foreach($cartItem->options->combination as $option)
            <small class="label label-primary label-succcess">{{$option['value']}}</small>
            @endforeach
            @endif

            <!-- {!! $cartItem->product->description !!} -->
            {!! strlen($cartItem->product->description) > 15 ? substr($cartItem->product->description, 0, 250)."..." : $cartItem->product->description  !!}


        </div>
        <div class="product_delever_bx">
            <form action="{{ route('cart.destroy', $cartItem->rowId) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="delete">
                <button onclick="return confirm('Are you sure?')" class="btn btn-danger"><i
                        class="fa fa-times"></i></button>
            </form>
            <h3 class="price_hd">{{config('cart.currency')}} {{ number_format($cartItem->price, 2) }}</h3>
            <bdi class="seller">Your item will be delivered soon</bdi>
        </div>
    </div>
</div>

@endforeach


<script type="text/javascript">
$(document).ready(function() {
    let courierRadioBtn = $('input[name="rate"]');
    courierRadioBtn.click(function() {
        $('#shippingFee').text($(this).data('fee'));
        let totalElement = $('span#grandTotal');
        let shippingFee = $(this).data('fee');
        let total = totalElement.data('total');
        let grandTotal = parseFloat(shippingFee) + parseFloat(total);
        totalElement.html(grandTotal.toFixed(2));
    });
    courierRadioBtn.prop("checked", true).trigger("click");
});
</script>