
@if(isset($payment['name']))
<form action="{{ route('checkout.execute') }}" method="post" class="stripe_step" id="stripeForm">

    
    @foreach($stripe_customer_cards as $card)
    <div class="radio_step visa-card pull-left">
    <div class="custom_radio">
    <label>
        <input type="radio" @if($loop->iteration  == 1) checked='checked' @endif name="payment_card" value='{{ $card->id }}'>
        <span class="check_text"></span>
        <div class="radio_content">
            <h5> {{ __('common.ck_added_cardtype')  }}
                {{ $card->brand }} </h5>
            <h5> {{ __('common.ck_added_expiry')  }}
                {{ $card->exp_month }} / {{ $card->exp_year }}</h5>
            <h5> {{ __('common.ck_added_lastdigits')  }}
                {{ $card->last4 }} </h5>
            <h5> {{ __('common.ck_added_nameoncard')  }}
                {{ $card->name }} </h5>
        </div>
    </label>
    </div>
    </div>
    @endforeach
    <div class="clearfix"></div>
    <input type="hidden" name="payment" value="{{ config('stripe.name') }}">
    <input type="hidden" name="stripeToken" value="{{ csrf_token() }}">
    <input type="hidden" class="billing_address" name="billing_address" value="">
    <input type="hidden" class="delivery_address_id" name="delivery_address" value="">
    <input type="hidden" class="courier" name="courier" value="">
    {{ csrf_field() }}

    <button id="paywithstripe" class="btn blue-btn creative_btn pull-right">Pay with {{ ucwords($payment['name']) }} <i
            class="fa fa-cc-stripe"></i></button>
</form>
@endif
  
@section('js')
<script src="{{ url('https://checkout.stripe.com/checkout.js') }}"></script>
<script type="text/javascript">
function setTotal(total, shippingCost) {
    let computed = +shippingCost + parseFloat(total);
    $('#total').html(computed.toFixed(2));
}

function setShippingFee(cost) {
    el = '#shippingFee';
    $(el).html(cost);
    $('#shippingFeeC').val(cost);
}

function setCourierDetails(courierId) {
    $('.courier_id').val(courierId);
}

$(document).ready(function() {

    let clicked = false;

    $('#sameDeliveryAddress').on('change', function() {
        clicked = !clicked;
        if (clicked) {
            $('#sameDeliveryAddressRow').show();
        } else {
            $('#sameDeliveryAddressRow').hide();
        }
    });

    let billingAddress = 'input[name="billing_address"]';
    $(billingAddress).on('change', function() {
        let chosenAddressId = $(this).val();
        $('.address_id').val(chosenAddressId);
        $('.delivery_address_id').val(chosenAddressId);
    });

    let deliveryAddress = 'input[name="delivery_address"]';
    $(deliveryAddress).on('change', function() {
        let chosenDeliveryAddressId = $(this).val();
        $('.delivery_address_id').val(chosenDeliveryAddressId);
    });

    let courier = 'input[name="courier"]';
    $(courier).on('change', function() {
        let shippingCost = $(this).data('cost');
        let total = $('#total').data('total');

        setCourierDetails($(this).val());
        setShippingFee(shippingCost);
        setTotal(total, shippingCost);
    });


    let billingAddressId = $('input[name="billing_address"]').val();
    $('.billing_address').val(billingAddressId);

    let courierRadioBtn = $('input[name="rate"]');
    courierRadioBtn.click(function() {
        $('.rate').val($(this).val())
    });


    if ($(courier).is(':checked')) {
        let shippingCost = $(courier + ':checked').data('cost');
        let courierId = $(courier + ':checked').val();
        let total = $('#total').data('total');

        setShippingFee(shippingCost);
        setCourierDetails(courierId);
        setTotal(total, shippingCost);
    }

    /* let handler = StripeCheckout.configure({
        key: "{{ config('stripe.key') }}",
        locale: 'auto',
        token: function(token) {
            // You can access the token ID with `token.id`.
            // Get the token ID to your server-side code for use.
            console.log(token.id);
            $('input[name="stripeToken"]').val(token.id);
            $('#stripeForm').submit();
        }
    }); */

   /*  document.getElementById('paywithstripe').addEventListener('click', function(e) {
        let total = parseFloat("{{ $total }}");
        let shipping = parseFloat($('#shippingFeeC').val());
        let amount = total + shipping;
        // Open Checkout with further options:
        handler.open({
            name: "{{ config('stripe.name') }}",
            description: "{{ config('stripe.description') }}",
            currency: "{{ config('cart.currency') }}",
            amount: amount * 100,
            email: "{{ $customer->email }}"
        });
        e.preventDefault();
    }); */

    // Close Checkout on page navigation:
    /* window.addEventListener('popstate', function() {
        handler.close();
    }); */
});
</script>
@endsection