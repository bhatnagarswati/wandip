@extends('layouts.front.app')

@section('content')
<section class="banner banner_inn"  style="background-image: url('{{ asset('public/images/login_bg.jpg') }}')">
        <div class="container">
             <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"  data-aos-duration="600">
                <h1>{{  __('common.resetpass_page_title') }}</h1>
              </div>
        </div>
    </section>

    <section class="login_coman login_main">
      <div class="container">
                <div class="contact_form contact_us_com">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                    <div class="acount_head login_head">
                     <h3>{{  __('common.resetpass_page_subtitle') }}</h3>
                      <p>{{  __('common.resetpass_page_sub_desc') }} </p>
                    </div>
                    <div class="contact_form_inn">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                          <label class="title_label">{{  __('common.resetpass_page_email') }}  <sup class="color_primatry">*</sup></label>
                          <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                          @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                          </div>
                           <div class=" submit_btn_outer">
                               <button type="submit" class="btn large_btn creative_btn blue-btn">{{  __('common.resetpass_page_button') }}  </button>
                          </div>
                      <div class="back_btn"><a href="{{ url('/login') }}" class="angle_btn">
                          <i class="fa fa-angle-left" aria-hidden="true"></i>
                          <i class="fa fa-fighter-jet" aria-hidden="true"></i></a>{{  __('common.resetpass_page_backto_login') }} </div>
                    </div>
                  </form>
              </div>
        </div>
    </section>

@endsection
