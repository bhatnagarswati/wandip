@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-body">
                <h2>{{  __('admin/customers.top_heading') }}</h2>

                <div class="table-responsive">
                  <table class="table">
                     <tbody>
                        <tr>
                           <th>{{  __('admin/customers.customer_id') }}</th>
                           <td> {{ $customer->id }}</td>
                        </tr>
                        <tr>
                           <th> {{  __('admin/customers.customer_name') }} </th>
                           <td> {{ $customer->name }}   </td>
                        </tr>
                         
                        <tr>
                           <th> {{  __('admin/customers.customer_email') }} </th>
                           <td> {{ $customer->email }}   </td>
                        </tr>
                        <tr>
                           <th>{{  __('admin/customers.customer_push_notification') }} </th>
                           <td> @php if($customer->pushNotification == 1) { @endphp  {{ __('admin/customers.customer_push_notification_yes') }} @php }else { @endphp  {{ __('admin/customers.customer_push_notification_no') }}  @php }  @endphp   </td>
                        </tr>
                         
                        <tr>
                           <th> {{  __('admin/customers.customer_phone_number') }} </th>
                           <td> {{ $customer->phone_number }}   </td>
                        </tr>
                         
                       
                        
                     </tbody>
                  </table>
               </div>

            </div>
            <div class="box-body">
                <h2>{{  __('admin/customers.customer_addresses') }}</h2>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="col-md-2">{{ __('admin/customers.customer_address_alias')   }}</td>
                            <td class="col-md-2">{{ __('admin/customers.customer_address_address')   }}</td>
                            <td class="col-md-2">{{ __('admin/customers.customer_address_country')   }}</td>
                            <td class="col-md-2">{{ __('admin/customers.customer_address_zip')   }}</td>
                            <td class="col-md-2">{{ __('admin/customers.customer_status')   }}</td>
                            <td class="col-md-4">{{ __('admin/customers.customer_action')   }}</td>
                        </tr>
                        </tbody>
                        <tbody>
                        @foreach ($addresses as $address)
                            <tr>
                                <td>{{ $address->alias }}</td>
                                <td>{{ $address->address_1 }} {{ $address->address_2 }}</td>
                                <td>{{ $address->country->name }}</td>
                                <td>{{ $address->country->zip }}</td>
                                <td>@include('layouts.status', ['status' => $address->status])</td>
                                <td>
                                    <form action="{{ route('admin.addresses.destroy', $address->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                             <!-- <a href="{{ route('admin.customers.addresses.show', [$customer->id, $address->id]) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> {{ __('admin/common.action_show') }}</a>
                                            <a href="{{ route('admin.customers.addresses.edit', [$customer->id, $address->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a> -->
                                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> {{ __('admin/common.action_delete') }}</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="btn-group">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-default btn-sm">{{ __('admin/common.action_back') }}</a>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection
