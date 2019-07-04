@extends('layouts.front.app')
@section('content')
<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{ __('common.contact_page_title') }}</h1>
        </div>
    </div>
</section>
<section class="contact_us_main">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="contact_us_com contact_us_adress about_text">
                    <h3> {{ __('common.contact_page_information') }}</h3>

                    <div class="adress_bx">
                        <ul>
                            <li><span class="fa_icon_bx"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                <h4> {{ __('common.contact_page_address') }} </h4>
                                <p>43 Lorem ipsum , ipsum 85133 Brescia Italy</p>
                            </li>
                            <li><span class="fa_icon_bx"><i class="fa fa-mobile" aria-hidden="true"></i></span>
                                <h4> {{ __('common.contact_page_phone') }}</h4>
                                <p><a href="tel:1234567890">(123) 456 - 7890</a></p>
                            </li>
                            <li><span class="fa_icon_bx"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                                <h4>{{ __('common.contact_page_email') }}</h4>
                                <p><a href="mailto:Info@djidrones.com">Info@djidrones.com</a></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-7 offset-sm-1">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                <div class="contact_form contact_us_com">
                    <form method="POST" action="{{ url('/sendquery') }}">
                        {{ csrf_field() }}
                        <h3>{{ __('common.contact_page_getintouch') }}</h3>
                        <div class="contact_form_inn">
                            <div class="flex-row form-group">
                                <div class="sub-form-group {{ $errors->has('firstName') ? 'has-error' : ''}}">
                                    <label class="title_label">{{ __('common.contact_page_firstname') }} <sup
                                            class="color_primatry">*</sup></label>
                                    <input type="text" required="required" name="firstName"
                                        class="form-control firstup" />
                                </div>
                                <div class="sub-form-group {{ $errors->has('lastName') ? 'has-error' : ''}}">
                                    <label class="title_label">{{ __('common.contact_page_lastname') }}<sup
                                            class="color_primatry firstup">*</sup></label>
                                    <input type="text" required="required" name="lastName" class="form-control" />
                                </div>
                            </div>
                            <div class="flex-row form-group {{ $errors->has('uEmail') ? 'has-error' : ''}}">
                                <div class="sub-form-group">
                                    <label class="title_label">{{ __('common.contact_page_youremail') }}<sup
                                            class="color_primatry">*</sup></label>
                                    <input type="email" required="required" name="uEmail" class="form-control" />
                                </div>
                                <div class="sub-form-group {{ $errors->has('phoneNumber') ? 'has-error' : ''}}">
                                    <label class="title_label">{{ __('common.contact_page_phoneno') }} <sup
                                            class="color_primatry">*</sup></label>
                                    <input type="number" required="required" name="phoneNumber"
                                        class="form-control numinp" />
                                </div>
                            </div>
                            <div class=" form-group {{ $errors->has('uQurey') ? 'has-error' : ''}}">
                                <label class="title_label">{{ __('common.contact_page_message') }}<sup
                                        class="color_primatry">*</sup></label>
                                <textarea required="required" class="form-control form-textarea firstup"
                                    name="uQurey"></textarea>
                            </div>
                            <div class=" submit_btn_outer">
                                <button type='submit'
                                    class="btn large_btn creative_btn blue-btn">{{ __('common.contact_page_submit') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="contact_map_bx">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2797.100463743879!2d10.151142215820169!3d45.48792163988095!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478174606a3bb14b%3A0xa37bbed74c7ccc18!2sResnova+S.R.L.!5e0!3m2!1sen!2sin!4v1550725466757"
        width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>

@endsection


@section('js')
<script>
$(document).ready(function() {
    document.querySelector(".numinp").addEventListener("keypress", function(evt) {
        if (evt.which != 43 && evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
});
</script>

@endsection