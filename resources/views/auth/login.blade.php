@extends('layouts.front.app')

@section('content')
 
<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/login_bg.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000" data-aos-duration="600">
            <h1>{{ __('common.login_page_title') }}</h1>
        </div>
    </div>
</section>
    <section class="login_coman login_main">
    <div class="container">
        <div class="contact_form contact_us_com">
                <div class="acount_head login_head">
                    <h3>{{ __('common.login_page_subtitle') }}</h3>
                </div>
               
                    <div class="contact_form_inn">
                    <div class="col-md-12">@include('layouts.errors-and-messages')</div>
                    <form action="{{ route('login') }}" method="post" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="flex-row form-group">
                            <div class="sub-form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="title_label">{{ __('common.login_page_emailaddress') }}<sup class="color_primatry">*</sup></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="off" class="form-control input-group-addon1" placeholder="john.deo@example.com">
                            </div>
                            <div class="sub-form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="title_label">{{ __('common.login_page_password') }} <sup class="color_primatry">*</sup></label>
                                <input type="password" name="password" id="password" value="" autocomplete="off" class="form-control input-group-addon1" placeholder="xxxxxxx">
                            </div>
                        </div>
                        <div class=" submit_btn_outer">
                            <button  type='submit' class="btn large_btn creative_btn blue-btn">{{ __('common.login_page_signin') }}</button>
                        </div>
                        </form>	
                        <div class="text-center">
                            <a href="{{route('password.request')}}">{{ __('common.login_page_forgotpassword') }}</a>
                            <br>
                            <a href="{{route('register')}}" class="text-center">{{ __('common.login_page_registeropt') }}</a>
                        </div>
                    </div>
				
        </div>

       
    </div>
</section>





















    <!-- /.content -->
@endsection
