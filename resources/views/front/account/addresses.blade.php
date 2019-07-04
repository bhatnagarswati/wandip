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
                            <h3>{{ __('common.ac_page_manage_add')  }}</h3>
                            <div class="accordion dashboard_accorndng" id="order_step">
                                <div class="card cart_row">
                                    <div class="card-header collapsed" id="headingOne" data-toggle="collapse"
                                        data-target="#collapse1" aria-expanded="true" aria-controls="collapseOne">
                                        <h3>{{ __('common.ac_page_addnew_address')  }}</h3>
                                    </div>

                                    <div id="collapse1" class="collapse " data-parent="#order_step">
                                        <div class="card-body">
                                            <form action="{{ route('customer.address.store', $customer->id) }}"
                                                method="post" class="form" enctype="multipart/form-data">
                                                <input type="hidden" name="status" value="1">
                                                {{ csrf_field() }}
                                                <div class="form-group flex-row">
                                                    <div class="sub-form-group">
                                                        <input type="text" name="alias" id="alias"
                                                            placeholder="{{ __('common.ac_page_addnew_alias') }}" class="form-control"
                                                            value="{{ old('alias') }}">
                                                    </div>
                                                    <div class="sub-form-group">

                                                        <input type="text" name="phone" id="phone"
                                                            placeholder="{{ __('common.ac_page_addnew_phoneno') }}" class="form-control"
                                                            value="{{ old('phone') }}">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <textarea name="address_1" id="address_1"
                                                        placeholder="{{ __('common.ac_page_addnew_address1') }}"
                                                        class="form-control form-textarea">{{ old('address_1') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="address_2" id="address_2" placeholder="{{ __('common.ac_page_addnew_address2') }}"
                                                        class="form-control form-textarea">{{ old('address_2') }}</textarea>
                                                </div>

                                                <div class="form-group flex-row">
                                                    <div class="sub-form-group">
                                                        <input type="text" name="zip" id="zip" placeholder="{{ __('common.ac_page_addnew_zipcode') }}"
                                                            class="form-control" value="{{ old('zip') }}">
                                                    </div>
                                                    <div class="sub-form-group">
                                                        <select name="country_id" id="country_id"
                                                            class="form-control select2 down_arrow">
                                                            @foreach($countries as $country)
                                                            <option @if(env('SHOP_COUNTRY_ID')==$country->id)
                                                                selected="selected" @endif
                                                                value="{{ $country->id }}">{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="form-group flex-row">
                                                    <div class="sub-form-group">
                                                        <div id="cities" class="form-group" style="display: none;">
                                                        </div>

                                                    </div>
                                                    <div class="sub-form-group">
                                                        <div id="provinces" class="form-group" style="display: none;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group ">
                                                    <button type="submit"
                                                        class="btn large_btn blue-btn grey_bg_btn creative_btn">{{ __('common.ac_page_addnew_saveadd_btn') }}</button>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!------------------->
                            @if(!$addresses->isEmpty())

                            @foreach($addresses as $address)

                            <div class="adress_list_outr">
                                <div class="adress_print_bx">
                                    <div class="adress_head">
                                        <div class="name_tile">
                                            <p><strong>{{$address->alias}}</strong></p>
                                        </div>
                                        <div class="number_tile">
                                            <p><strong><a href="tel:{{$address->phone}}">{{$address->phone}}</a></strong>
                                            </p>
                                        </div>
                                    </div>
                                    <p>
                                        {{$address->address_1}},
                                        {{$address->city}}
                                        @if(isset($address->province)) {{$address->province->name}} , @endif
                                        @if(isset($address->state_code)) {{ $address->state_code}}, @endif
                                        {{$address->country->name}} ,
                                        {{$address->zip}}

                                    </p>


                                    <form method="post"
                                        action="{{ route('customer.address.destroy', [auth()->user()->id, $address->id]) }}"
                                        >

                                        <div class="addes_card_bx adress_print_bx-add" >
                                         
                                            <a href="{{ route('customer.address.edit', [auth()->user()->id, $address->id]) }}" class="menu_btn edit_menu_btn primary_color"><i class="fa fa-pencil"></i> {{ __('common.ac_page_add_edit') }}</a>
                                            <input type="hidden" name="_method" value="delete">
                                            {{ csrf_field() }}
                                            <button onclick="return confirm('Are you sure?')" type="submit" class="menu_btn delete_menu_btn"><i class="fa fa-trash"></i></button> 
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @endforeach

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



@section('css')
<link href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css') }}"
    rel="stylesheet" />
@endsection

@section('js')
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') }}"></script>
<script type="text/javascript">
function findProvinceOrState(countryId) {
    $.ajax({
        url: '/api/v1/country/' + countryId + '/province',
        contentType: 'json',
        success: function(res) {
            if (res.data.length > 0) {
                let html = '<label for="province_id">Provinces </label>';
                html += '<select name="province_id" id="province_id" class="form-control select2">';
                $(res.data).each(function(idx, v) {
                    html += '<option value="' + v.id + '">' + v.name + '</option>';
                });
                html += '</select>';

                $('#provinces').html(html).show();
                $('.select2').select2();

                findCity(countryId, 1);

                $('#province_id').change(function() {
                    var provinceId = $(this).val();
                    findCity(countryId, provinceId);
                });
            } else {
                $('#provinces').hide().html('');
                $('#cities').hide().html('');
            }
        }
    });
}

function findCity(countryId, provinceOrStateId) {
    $.ajax({
        url: '/api/v1/country/' + countryId + '/province/' + provinceOrStateId + '/city',
        contentType: 'json',
        success: function(data) {
            let html = '<label for="city_id">City </label>';
            html += '<select name="city_id" id="city_id" class="form-control select2">';
            $(data.data).each(function(idx, v) {
                html += '<option value="' + v.id + '">' + v.name + '</option>';
            });
            html += '</select>';

            $('#cities').html(html).show();
            $('.select2').select2();
        },
        errors: function(data) {
            console.log(data);
        }
    });
}

function findUsStates() {
    $.ajax({
        url: '/country/' + countryId + '/state',
        contentType: 'json',
        success: function(res) {
            if (res.data.length > 0) {
                let html = '<label for="state_code">States </label>';
                html += '<select name="state_code" id="state_code" class="form-control select2">';
                $(res.data).each(function(idx, v) {
                    html += '<option value="' + v.state_code + '">' + v.state + '</option>';
                });
                html += '</select>';

                $('#provinces').html(html).show();
                $('.select2').select2();

                findUsCities('AK');

                $('#state_code').change(function() {
                    let state_code = $(this).val();
                    findUsCities(state_code);
                });
            } else {
                $('#provinces').hide().html('');
                $('#cities').hide().html('');
            }
        }
    });
}

function findUsCities(state_code) {
    $.ajax({
        url: '/state/' + state_code + '/city',
        contentType: 'json',
        success: function(res) {
            if (res.data.length > 0) {
                let html = '<label for="city">City </label>';
                html += '<select name="city" id="city" class="form-control select2">';
                $(res.data).each(function(idx, v) {
                    html += '<option value="' + v.name + '">' + v.name + '</option>';
                });
                html += '</select>';

                $('#cities').html(html).show();
                $('.select2').select2();

                $('#state_code').change(function() {
                    let state_code = $(this).val();
                    findUsCities(state_code);
                });
            } else {
                $('#provinces').hide().html('');
                $('#cities').hide().html('');
            }
        }
    });
}

let countryId = +"{{ env('SHOP_COUNTRY_ID') }}";

$(document).ready(function() {

    if (countryId === 226) {
        findUsStates(countryId);
    } else {
        findProvinceOrState(countryId);
    }

    $('#country_id').on('change', function() {
        countryId = +$(this).val();
        if (countryId === 226) {
            findUsStates(countryId);
        } else {
            findProvinceOrState(countryId);
        }

    });

    $('#city_id').on('change', function() {
        cityId = $(this).val();
        findProvinceOrState(countryId);
    });

    $('#province_id').on('change', function() {
        provinceId = $(this).val();
        findProvinceOrState(countryId);
    });
});
</script>
@endsection