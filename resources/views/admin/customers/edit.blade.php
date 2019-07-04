@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="post" class="form">
                <div class="box-body col-md-7">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
                    <div class="form-group">
                        <label for="name">{{ __('admin/customers.customer_name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="{{ __('admin/customers.customer_name') }}" class="form-control" value="{!! $customer->name ?: old('name')  !!}">
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('admin/customers.customer_email') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" name="email" id="email" placeholder="{{ __('admin/customers.customer_email') }}" class="form-control" value="{!! $customer->email ?: old('email')  !!}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">{{ __('admin/customers.customer_password') }}  <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" placeholder="xxxxx" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status">{{ __('admin/customers.customer_status') }} </label>
                        <select name="status" id="status" class="form-control">
                            <option value="0" @if($customer->status == 0) selected="selected" @endif>{{ __('admin/customers.customer_disable') }}</option>
                            <option value="1" @if($customer->status == 1) selected="selected" @endif>{{ __('admin/customers.customer_enable') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">{{ __('admin/customers.customer_phone_number') }} <span class="text-danger">*</span></label>
                        <input type="text" name="phone_number" id="phone_number" placeholder="{{ __('admin/customers.customer_phone_number') }}" class="form-control" value="{!! $customer->phone_number ?: old('phone_number')  !!}">
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-default btn-sm">{{ __('admin/common.action_back') }}</a>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('admin/common.action_update') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
