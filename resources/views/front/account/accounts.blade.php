@extends('layouts.front.app')

@section('content')

<!------------------------------------------------->

<section class="dashboard_main rating_review_main cart-grey">
    <div class="container">
        <div class="dashboard_main_row">

            @include('layouts.front.account-sidebar')


            <div class="dashboard_content_main">
                <div class="box-body">
                    @include('layouts.errors-and-messages')
                </div>

                <div class="comn_height dashboard_colm_rt white_bg accountashboard">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="profile-inner input_grey">
                                <form method="post" enctype="multipart/form-data"
                                    action="{{ url('/') }}/accounts/updateInfo">
                                    {{  csrf_field() }}
                                    <div class="profile-row">
                                        <div class="head_h3">

                                            <h3>{{ __('common.ac_dashboard_title') }} </h3>
                                            <!--   <a href="#" class="edit_btn primary_color">Edit</a> -->
                                        </div>


                                        <div class="form-group">
                                            <div class="user-img edit-profile-main">
                                                <div class="logoContainer">
                                                    <figure>

                                                        @php

                                                        if(!empty($customer->profilePic)){
                                                        $profilepic =
                                                        config('constants.customer_pull_path').$customer->profilePic;
                                                        }else{
                                                        $profilepic = url('/')."/public/img/no-image.png";
                                                        }
                                                        @endphp

                                                        <img src="{{  $profilepic  }}">
                                                    </figure>
                                                </div>
                                                <div class="fileContainer sprite">
                                                    <p class="edit-btn-btn">{{ __('common.ac_dashboard_editpic') }}</p>
                                                    <span classs="no-font"></span>
                                                    <input type="hidden" name="action" value="userImage">
                                                    <input type="file" name="userProfilepic" id="file"
                                                        value="Choose File">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group flex-row">
                                            <div class="sub-form-group"><input type="text" name="customer_name"
                                                    value="{{$customer->name}}" class="form-control" /></div>


                                            <div class="sub-form-group"><input type="text" title="Can't edit"
                                                    value="{{$customer->email}}" disabled class="form-control" /></div>

                                        </div>
                                    </div>

                                    <div class="profile-row">
                                        <div class="head_h3">
                                            <h3>{{ __('common.ac_dashboard_mobileno') }} </h3>

                                        </div>


                                        <div class="form-group flex-row row-margin flex-start">
                                            <div class="sub-form-group col-md-2 ">

                                                @if(!empty($countries))
                                                <select name="countryCode" class="form-control">
                                                    @foreach($countries as $country)
                                                    <option @if($customer->countryCode == '+'.$country->phonecode)
                                                        selected='selected' @endif
                                                        value="+{{ $country->phonecode }}">{{ $country->iso }}
                                                        {{ '+'.$country->phonecode }} </option>
                                                    @endforeach
                                                </select>
                                                @endif
                                            </div>


                                            <div class="sub-form-group col-md-6 ">
                                                <input type="number" name="phone_number"
                                                    value='{{$customer->phone_number}}' class="numinp form-control "
                                                    placeholder="+xxx xxx xxxx" />
                                            </div>

                                        </div>

                                    </div>
                                    <div class="profile-row">
                                        <button type="submit"
                                            class="btn blue-btn creative_btn">{{ __('common.ac_dashboard_updateInfo') }}</button>
                                        <a href="#" class="change_pass_btn primary_color" data-toggle="modal"
                                            data-target="#personal_pop">{{  __('common.ac_dashboard_changepassword') }}
                                        </a>
                                    </div>

                            </div>
                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!------------------------------------------------->

<!-- Modal -->
<div class="modal fade popup normal_pop personal_pop animated" id="personal_pop" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="pop_inner row">
                <div class="col-md-6">
                    <div class="pop_lft_bx">
                        <h3>{{ __('common.ac_dashboard_popup_top_heading') }}</h3>
                        <ul>
                            <li>
                                <p>{{ __('common.ac_dashboard_popup_instruction_1') }}</p>
                            </li>
                            <li>
                                <p>{{ __('common.ac_dashboard_popup_instruction_2') }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pop_rt_bx input_grey">
                        <h3> {{ __('common.ac_dashboard_popup_right_heading') }} </h3>
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('common.ac_dashboard_popup_current_password') }}" />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('common.ac_dashboard_popup_new_password') }}" />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('common.ac_dashboard_popup_comfirm_new_password') }}" />
                            </div>
                            <div class="form-group">
                                <a href="javascript::void(0)"
                                    class="btn blue-btn creative_btn ">{{ __('common.ac_dashboard_popup_btn') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <a href="javascript:void(0);" class="close_btn " data-dismiss="modal">x</a>
        </div>
    </div>
</div>

@endsection


@section('js')
<script>
$(document).ready(function() {

    document.querySelector(".numinp").addEventListener("keypress", function(evt) {

        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }


    });
});
</script>

@endsection