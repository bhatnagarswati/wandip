@extends('layouts.front.app')

@section('content')

<section class="banner banner_inn"  style="background-image: url('{{ asset('public/images/login_bg.jpg') }}')">
        <div class="container">
             <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"  data-aos-duration="600">
                <h1>{{  __('common.updatepass_page_title') }}</h1>
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
                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                    <div class="acount_head login_head">
                     <h3>{{  __('common.updatepass_page_subtitle') }}</h3>
                    </div>
                    <div class="contact_form_inn">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                          <label class="title_label">{{  __('common.updatepass_page_email') }}  <sup class="color_primatry">*</sup></label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif

                          </div>


                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="title_label">{{ __('common.updatepass_page_password') }}<sup class="color_primatry">*</sup></label>
                            <input id="password" type="password" class="form-control" name="password" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif

                        </div>


                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="title_label">{{ __('common.updatepass_page_confirm_password') }}<sup class="color_primatry">*</sup></label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif

                        </div>

                        <div class=" submit_btn_outer">
                               <button type="submit" class="btn large_btn creative_btn blue-btn">{{  __('common.updatepass_page_button') }}  </button>
                        </div>

                  </form>
              </div>
        </div>
    </section>


@endsection
