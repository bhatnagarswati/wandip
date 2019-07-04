@extends('layouts.front.app')

@section('content')

<section class="dashboard_main rating_review_main cart-grey">
    <div class="container">
        <div class="dashboard_main_row">

            <!-------  Sidebar start -->
            @include('layouts.front.account-sidebar')
            <!-------  Sidebar end -->

            <!------- Right Side Panel start -->
            <div class="dashboard_content_main">
                <div class="box-body">
                    @include('layouts.errors-and-messages')
                </div>
                <div class="comn_height dashboard_colm_rt white_bg">
                    <div class="col_sm_12 col_xl_10 ">
                        <div class="manage_adress_main">
                            <h3>{{ __('common.ac_payments')  }}</h3>


                            <div class="accordion dashboard_accorndng" id="order_step">
                                <div class="card cart_row">
                                    <div class="card-header collapsed" id="headingOne" data-toggle="collapse"
                                        data-target="#collapse1" aria-expanded="true" aria-controls="collapseOne">
                                        <h3>{{ __('common.ac_payments_addnew')  }}</h3>
                                    </div>

                                    <div id="collapse1" class="collapse " data-parent="#order_step">
                                        <div class="card-body flex-row-payment">
                                            <form action="{{ url('/accounts/saveCards') }}" method="post" class="form"
                                                id="payment-form" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <div class="form-group flex-row ">
                                                    <div class="sub-form-group ">
                                                        <input type="number" onKeyPress="if(this.value.length==16) return false;" required="required" class="form-control"
                                                            name="card_number" value="{{ old('card_number') }}"
                                                            id="card_number"
                                                            placeholder="{{ __('common.ac_payments_card_number') }}">
                                                    </div>
                                                    <div
                                                        class="sub-form-group form-control small-form-group saal_wala_dabba">
                                                        <ul>
                                                            <li>
                                                                <p>{{ __('common.ac_payments_expiry') }}</p>
                                                            </li>
                                                            <li><select required="required" name="exp_month"
                                                                    class="form-control down_arrow">
                                                                    <option value="" selected>MM</option>
                                                                    @for($i = 1; $i <= 12; $i ++) <option
                                                                        value="@if($i <10){{0}}@endif{{$i}}">@if($i <
                                                                            10){{0}}@endif{{$i}}</option> @endfor
                                                                            </select> </li> <li><select
                                                                                required="required" name="exp_year"
                                                                                class=" form-control down_arrow">
                                                                                <option value="" selected>YYYY</option>
                                                                                @for($i = date('Y'); $i <= 2050; $i ++)
                                                                                    <option value="{{ $i }}">{{ $i }}
                                                                                    </option>
                                                                                    @endfor
                                                                            </select>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group flex-row ">
                                                    <div class="sub-form-group"><input type="text"
                                                            value="{{ old('card_holdername') }}" required="required"
                                                            name="card_holdername" id="card_holdername"
                                                            class="form-control"
                                                            placeholder="{{ __('common.ac_payments_nameoncard') }}">
                                                    </div>
                                                    <div class="sub-form-group small-form-group">
                                                        <input type="number" value="{{ old('card_cvc') }}"
                                                            required="required" class="form-control" name="card_cvc"
                                                            id="card_cvc" onKeyPress="if(this.value.length==3) return false;"
                                                            placeholder="{{ __('common.ac_payments_cvc') }}"></div>
                                                </div>


                                                <div class="form-group ">
                                                    <div class="payment-errors"></div>
                                                    <button type="submit" id="stripe-submit"
                                                        class="btn large_btn blue-btn grey_bg_btn creative_btn">{{ __('common.ac_payments_save_button') }}</button>
                                                    <!--    <button type="button"
                                                        class="btn primary_color cancel_btn">{{ __('common.ac_payments_cancel_button') }}</button> -->
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------->
                            @if(!empty($stripe_customer))
                            @if(!empty($stripe_customer->sources->data))


                            @php
                            // print_r($stripe_customer);
                            @endphp
                            @foreach($stripe_customer->sources->data as $card)
                            <div class="adress_list_outr">
                                <div class="adress_print_bx addes_card_bx">
                                    <div class="adress_head">
                                        <p>{{ $card->name }} , {{ $card->brand }} {{  $card->object }}</p>
                                    </div>
                                    <div class="addes_cart">
                                        <p><img src="{{ asset('public/images/card-added.png') }}" class="card_add"
                                                alt="card-added.png" />
                                            <span class="card_no">****</span> <span class="card_no">****</span> <span
                                                class="card_no">****</span> <span
                                                class="card_no">{{ $card->last4 }}</span>
                                        </p>
                                    </div>
                                    <div class="edit_btn adress_print_bx-add">
                                        <!-- <a href="#" class="menu_btn edit_menu_btn primary_color">Edit</a> -->
                                        <form action="{{ url('/accounts/deleteCards') }}" method="post" class="form-horizontal">
                                            {{ csrf_field() }}
                                            
                                            <input type="hidden" name="cardid" value="{{ $card->id }}">
                                            <button onclick="return confirm('{{ __('common.ac_card_del_confirm')  }}')"  type="submit" class="menu_btn delete_menu_btn"><i  class="fa fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            @else
                            <br />
                            <p class="alert alert-warning">{{ __('common.ac_page_no_address')  }}</p>
                            @endif
                            <!------------------->



                        </div>
                    </div>
                </div>
            </div>
            <!------- Right Side Panel end  -->

        </div>
    </div>
</section>


@endsection