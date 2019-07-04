@extends('layouts.front.app')

@section('content')
 
<section class="banner banner_inn" style="background-image: url('{{  asset('public/images/login_bg.jpg') }}')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"
            data-aos-duration="600">
            <h1>{{  __('common.reg_title') }}</h1>
        </div>
    </div>
</section>
<!--------------------------------------------------------------->
<section class="login_coman login_main">
    <div class="container">
        <div class="contact_form contact_us_com">

            <div class="acount_head login_head">
                <h3>{{  __('common.reg_create_an_acc') }} </h3>
            </div>
            <div class="contact_form_inn">
                <ul class="nav nav-tabs tab_head" id="pop_up" role="tablist">
                    <li>
                        <a @if(old('tab') == 'customer')  class="active" @elseif(old('tab') == '') class="active"  @endif data-toggle="tab" href="#customer" role="tab" aria-controls="home"
                            aria-selected="true">{{ __('common.reg_customer_tab') }}</a>
                    </li>
                    <li>
                        <a @if(old('tab') == 'service_provider')  class="active"  @endif  data-toggle="tab" href="#service_provider" role="tab" aria-controls="profile"
                            aria-selected="false">{{ __('common.reg_servicer_tab') }}</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade @if(old('tab') == 'customer')  show active  @elseif(old('tab') == '')  show active  @endif" id="customer" role="tabpanel" aria-labelledby="home-tab">

                        <form id='customer_form' class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
  
                            <input style="display:none">
                            <input type="password" style="display:none">
                            <input type="text"  name="tab" value="customer"  style="display:none">
                            <div class="form-group flex-row">
                                <div class="sub-form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="title_label">Name <sup class="color_primatry">*</sup></label>
                                    <input id="name" type="text" class="form-control" name="name"
                                        value="{{ old('name') }}" autofocus>
                                    @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="sub-form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="title_label">Email <sup class="color_primatry">*</sup></label>
                                    <input id="email" type="email" autocomplete='off' class="form-control" name="email"
                                        value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group flex-row">
                                <div class="sub-form-group {{ $errors->has('countryCode') ? ' has-error' : '' }}">
                                    <label class="title_label">Country code <sup class="color_primatry">*</sup></label>

                                    <select id="countryCode"  name="countryCode" class="form-control arrow_down select2">
                                    <option value="">Select code</option>
                                        @if(!empty($allcountries))
                                                @foreach($allcountries as $country)
                                                    <option @if(old('countryCode') == '+'.$country->phonecode)  selected="selected" @endif  value="+{{ $country->phonecode }}">{{ $country->iso }} +{{ $country->phonecode }}</option>
                                                    @endforeach
                                                    @endif

                                        </select>
                                    @if ($errors->has('countryCode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('countryCode') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="sub-form-group {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                    <label class="title_label">Phone Number <sup class="color_primatry">*</sup></label>
                                    <input id="phone_number" type="number" class="form-control" name="phone_number"
                                        value="{{ old('phone_number') }}">
                                    @if ($errors->has('phone_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group flex-row">
                                <div class="sub-form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="title_label">Password <sup class="color_primatry">*</sup></label>
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="sub-form-group">
                                    <label class="title_label">Confirm Password <sup
                                            class="color_primatry">*</sup></label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation">
                                </div>
                            </div>

                            <div class=" submit_btn_outer">
                                <button type="submit" class="btn large_btn creative_btn blue-btn">Register</button>
                            </div>

                        </form>

                    </div>

                    <div class="tab-pane fade @if(old('tab') == 'service_provider')  show active @endif"" id="service_provider" role="tabpanel" aria-labelledby="home-tab">
                        <form id='servicer_form' class="form-horizontal" role="form" autocomplete="off" method="POST"
                            action="{{ url('/servicer_register') }}">
                            {{ csrf_field() }}
                            <input type="email" style="display:none">
                            <input type="password" style="display:none">
 
                            <input type="text"  name="tab" value="service_provider"  style="display:none">
                            <div class="form-group flex-row ">
                                <div class="sub-form-group {{ $errors->has('servicer_name') ? ' has-error' : '' }}">
                                    <label class="title_label">Name <sup class="color_primatry">*</sup></label>
                                    <input id="servicer_name" type="text" class="form-control" name="servicer_name" value="{{ old('servicer_name') }}"  autofocus>
                                    @if ($errors->has('servicer_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('servicer_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="sub-form-group">
                                    <label for="servicer_email" class="title_label">Email <sup
                                            class="color_primatry">*</sup></label>
                                            <input id="servicer_email" type="email" autocomplete='off' class="form-control" name="servicer_email"
                                        value="{{ old('servicer_email') }}">
                                    @if ($errors->has('servicer_email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('servicer_email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>



                            <div class="form-group flex-row">
                                <div class="sub-form-group {{ $errors->has('servicer_countryCode') ? ' has-error' : '' }}">
                                    <label class="title_label">Country code <sup class="color_primatry">*</sup></label>
                                    <select id="servicer_countryCode"  name="servicer_countryCode" class="form-control arrow_down select2">
                                    <option value="">Select code</option>
                                        @if(!empty($allcountries))
                                                @foreach($allcountries as $country)
                                                    <option @if(old('servicer_countryCode') == '+'.$country->phonecode)  selected="selected" @endif value="+{{ $country->phonecode }}">{{ $country->iso }} +{{ $country->phonecode }}</option>
                                                    @endforeach
                                                    @endif

                                        </select>
                                    @if ($errors->has('servicer_countryCode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('servicer_countryCode') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="sub-form-group {{ $errors->has('servicer_phone') ? ' has-error' : '' }}">
                                    <label class="title_label">Phone Number <sup class="color_primatry">*</sup></label>
                                    <input type="number" name="servicer_phone" value="{{  old('servicer_phone')  }}" class="form-control" />
                                    @if ($errors->has('servicer_phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('servicer_phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group flex-row {{ $errors->has('servicer_password') ? ' has-error' : '' }}">
                                <div class="sub-form-group">
                                    <label class="title_label">Password <sup class="color_primatry">*</sup></label>
                                    <input type="password" name="servicer_password" class="form-control" />
                                    @if ($errors->has('servicer_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('servicer_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="sub-form-group">
                                    <label class="title_label">Confirm Password <sup
                                            class="color_primatry">*</sup></label>
                                    <input type="password" name="servicer_password_confirmation" class="form-control" />
                                </div>
                            </div>

                            <div class=" submit_btn_outer">
                                <button type='submit' class="btn large_btn creative_btn blue-btn">Register</button>
                            </div>

                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>
 
<!--------------------------------------------------------------->

@endsection
@section('js')
<script>
 //redirect to specific tab
 $(document).ready(function () {
    $('ul#pop_up a[href="#{{ old('tab') }}"]').trigger('click');
    $('ul#pop_up a[href="#{{ old('tab') }}"]').tab('show');
 });
</script>

@endsection
