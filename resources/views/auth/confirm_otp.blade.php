@extends('layouts.front.app')

@section('content')

<section class="banner banner_inn" style="background-image: url('{{  asset('public/images/login_bg.jpg') }}')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{  __('common.otp_title') }}</h1>
        </div>
    </div>
</section>



<!--------------------------------------------------------------->

<section class="login_coman login_main">
    <div class="container">
        <div class="contact_form contact_us_com">
            <form method="POST" id="verify_form" req-ro="{{ $userinfo->id }}" action="{{ url('/verify_otp') }}">

                {{ csrf_field() }}
                <input type="hidden" id='_type' value="">
                <input type="hidden" id='_base' value="{{ url('/') }}">
                <input type="hidden" id='_utype' value="{{ $userType }}">
                <div class="acount_head login_head">
                    <!--     <p class="text-center">{{ __('common.otp_desc')  }} </p> -->
                    <div class="col-md-12">@include('layouts.errors-and-messages')</div>
                </div>
                <div class="contact_form_inn">
                    <div class="form-group">
                        <label class="title_label">{{ __('common.otp_otpcode')  }} <sup
                                class="color_primatry">*</sup></label>
                        <input type="number" pattern="\d{6}" maxlength="6" max="6"
                            placeholder="Please enter 6 digits code." required="required" name="opt_code" id="otp_code"
                            class="form-control numinp" />
                    </div>
                    <div class=" submit_btn_outer relative otp-relative">
                        <button type="button" id='otp_submit'
                            class="btn large_btn creative_btn blue-btn">{{ __('common.otp_submit')  }}</button>
                        <a href="javscipt::void(0);" id="otp_resend"
                            class="edit_btn primary_color">{{  __('common.otp_resend_otp') }}</a>

                        <div class="text-center" id="showResponse"></div>
                    </div>
                    <!-- <div class="back_btn"><a href="#" class="angle_btn">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <i class="fa fa-fighter-jet" aria-hidden="true"></i></a>Back To Login</div> -->
                </div>
            </form>
        </div>
    </div>
</section>
<!--------------------------------------------------------------->

@endsection
@section('js')
<script>
//redirect to specific tab

$('#otp_submit').on('click', function() {

    var s_token = $('#verify_form input[name=_token]').val();
    var b_url = $('#verify_form #_base').val();
    var utype = $('#verify_form #_utype').val();
    var uid = $('#verify_form').attr('req-ro');

    var otp_code = $('#verify_form #otp_code').val();
    $('#verify_form #otp_code').removeClass('red_error');
    if ($.trim(otp_code) == "") {
        $('#verify_form #otp_code').addClass('red_error');
        return false;
    }

    $.ajax({
            method: "POST",
            dataType: "json",
            url: b_url + "/verifyOtp",
            data: {
                'otp_code': otp_code,
                '_token': s_token,
                'uid': uid,
                'u_type': utype
            }
        })
        .done(function(res) {
            if (res.status == true) {

                $("#showResponse").removeAttr("style");
                $('#showResponse').css('color', "green");

                $('#showResponse').html(res.message);
                setTimeout(function() {
                    $('#showResponse').html("");
                    window.location.href = b_url + "/signup/completed";
                }, 2000);
            } else {
                $("#showResponse").removeAttr("style");
                $('#showResponse').css('color', "red");
                $('#showResponse').html(res.message);
            }
        });

});


$('#otp_resend').on('click', function() {

    var s_token = $('#verify_form input[name=_token]').val();
    var b_url = $('#verify_form #_base').val();
    var uid = $('#verify_form').attr('req-ro');
    var utype = $('#verify_form #_utype').val();
    $.ajax({
            method: "POST",
            dataType: "json",
            url: b_url + "/resendOtp",
            data: {
                '_token': s_token,
                'uid': uid,
                'u_type': utype
            }
        })
        .done(function(res) {
            if (res.status == true) {

                $("#showResponse").removeAttr("style");
                $('#showResponse').css('color', "green");

                $('#showResponse').html(res.message);
                setTimeout(function() {
                    $('#showResponse').html("");
                    location.reload();
                }, 3000);
            } else {
                $("#showResponse").removeAttr("style");
                $('#showResponse').css('color', "red");
                $('#showResponse').html(res.message);
            }
        });

});
</script>

@endsection