@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
    @if($customers)
    <div class="box">
        <div class="box-body">
            <h2>{{ __('admin/customers.top_heading')  }}</h2>

            <div class="card-body no-datatable_serach">
            
                @include('layouts.search', ['route' => route('admin.customers.index')])

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td class="col-md-2">{{ __('admin/customers.customer_id')  }}</td>
                        <td class="col-md-2">{{ __('admin/customers.customer_name')  }}</td>
                        <td class="col-md-2">{{ __('admin/customers.customer_email')  }}</td>
                        <td class="col-md-2">{{ __('admin/customers.customer_status')  }}</td>
                        <td class="col-md-2">{{ __('admin/customers.customer_phone_number')  }}</td>
                        <td class="col-md-4">{{ __('admin/customers.customer_action')  }}</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer['id'] }}</td>
                        <td>{{ $customer['name'] }}</td>
                        <td>{{ $customer['email'] }}</td>
                        <td>@include('layouts.status', ['status' => $customer['status']])</td>
                        <td>{{ $customer['phone_number'] }}</td>
                        <td>
                            <form action="{{ route('admin.customers.destroy', $customer['id']) }}" method="post"
                                class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="delete">
                                <div class="btn-group">
                                    <a href="{{ route('admin.customers.show', $customer['id']) }}"
                                        class="btn btn-info btn-sm"><i class="fa fa-eye"></i> {{ __('admin/common.action_show') }}</a> &nbsp;&nbsp;
                                    <a href="{{ route('admin.customers.edit', $customer['id']) }}"
                                        class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> {{ __('admin/common.action_edit') }}</a> &nbsp;&nbsp;
                                    <button onclick="return confirm('Are you sure?')" type="submit"
                                        class="btn btn-danger btn-sm"><i class="fa fa-times"></i> {{ __('admin/common.action_delete') }}</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $customers->links() }}

            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    @endif

</section>
<!-- /.content -->
@endsection