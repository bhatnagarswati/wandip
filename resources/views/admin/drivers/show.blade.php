@extends('layouts.admin.app')@section('content')
<section class="content admin-forms">
   <div class="box">
      <div class="box-body">
         <div class="card">
            <h2 class="card-header">{{ __('admin/drivers.view_driver') }} #{{ $driver->driverId }}</h2>
            <div class="card-body">
               <a href="{{ url('/admin/drivers') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button></a>		    <a href="{{ url('/admin/drivers/' . $driver->driverId . '/edit') }}" title="Edit Driver"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button></a>		    
               <form method="POST" action="{{ url('admin/drivers' . '/' . $driver->driverId) }}" accept-charset="UTF-8" style="display:inline">			{{ method_field('DELETE') }}			{{ csrf_field() }}			<button type="submit" class="btn btn-danger btn-sm" title="Delete Driver" onclick="return confirm('Are you sure?')"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>		    </form>
               <br/>		    <br/>		    
               <div class="table-responsive">
                  <table class="table">
                     <tbody>
                        <tr>
                           <th>ID</th>
                           <td>{{ $driver->driverId }}</td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/drivers.driverTitle') }} </th>
                           <td> {{ $driver->firstName }} {{ $driver->lastName }}  </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/drivers.driverAddress') }} </th>
                           <td> {{ $driver->address }} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/drivers.driverLicence') }} </th>
                           <td> {{  $driver->licenceNumber }} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/drivers.driverEmail') }} </th>
                           <td> {{ $driver->driverEmail }} </td>
                        </tr>
			 <tr>
                           <th> {{ __('admin/drivers.driverPic') }} </th>
                           <td>  <img class="sprofilePic" src="{{ config('constants.driver_pull_path').$driver->driverPic}}" alt="" /></td>
                        </tr>
			<tr>
                           <th> {{ __('admin/drivers.driverIdPic') }} </th>
                           <td>  <img class="sprofilePic" src="{{ config('constants.driver_id_proof_pull_path').$driver->idProof}}" alt="" /></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection