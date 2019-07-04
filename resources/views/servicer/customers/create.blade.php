@extends('layouts.servicer.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('servicer.customers.store') }}" method="post" class="form">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">{{ __('admin/customers.customer_name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('admin/customers.customer_email') }}  <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">{{ __('admin/customers.customer_password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" placeholder="xxxxx" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status">{{ __('admin/customers.customer_status') }} </label>
                        <select name="status" id="status" class="form-control">
                            <option value="0">{{ __('admin/customers.customer_disable') }}</option>
                            <option value="1">{{ __('admin/customers.customer_enable') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">{{ __('admin/customers.customer_phone_number') }} <span class="text-danger">*</span></label>
                        <input type="text" name="phone_number" id="phone_number" placeholder="{{ __('admin/customers.customer_phone_number') }}" class="form-control" value="{{ old('phone_number') }}">
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('servicer.products.index') }}" class="btn btn-default">{{ __('admin/common.action_back') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('admin/common.action_create') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
