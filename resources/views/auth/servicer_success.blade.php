@extends('layouts.front.app')

@section('content')

<section class="banner banner_inn" style="background-image: url('{{  asset('public/images/login_bg.jpg') }}')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{  __('common.signup_success_title') }}</h1>
        </div>
    </div>
</section>
 
<!--------------------------------------------------------------->

<section class="login_coman login_main">
    <div class="container">
        <div class="contact_form contact_us_com text-center">
                <p>{{  __('common.signup_success_message') }}</p>
                <br/>
                <a target="_blank" href="{{ url('/') }}/servicer" class="btn btn-primary blue-btn">{{  __('common.signup_success_button') }}</a>
        </div>
    </div>
</section>
<!--------------------------------------------------------------->

@endsection
 