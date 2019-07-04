@extends('layouts.servicer.app')@section('content')
<section class="content">
   <div class=" box">
      <div class="box-body ">
         <h2>{{ __('admin/drivers.all_drivers') }}</h2>
<!--            		      <form method="GET" action="{{ url('/servicer/drivers') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">   			      <div class="input-group">   				 <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">   				 <span class="input-group-append">   				    <button class="btn btn-secondary" type="submit">   				       <i class="fa fa-search"></i>   				    </button>   				 </span>   			      </div>   			   </form>   	    	    -->
         <table class="table table-bordered table-striped">
            <thead>
               <tr>
                  <th>#</th>
                  <th>{{ __('admin/drivers.driverTitle') }}</th>
                  <th>{{ __('admin/drivers.driverEmail') }}</th>
		   <th>{{ __('admin/drivers.driverAddress') }}</th>
		   <th>{{ __('admin/drivers.driverLicence') }}</th>
                  <th>{{ __('admin/drivers.driver_status') }}</th>
                  <th>{{ __('admin/common.action') }}</th>
               </tr>
            </thead>
            <tbody>
               @foreach($drivers as $item)		    
               <tr>
                  <td>{{ $loop->iteration  }}</td>
                  <td>{{ $item->firstName }} {{ $item->lastName }}</td>
                  <td>{{ $item->driverEmail }}</td>
                  <td>{{ strlen($item->address) > 15 ? substr($item->address, 0, 15)."..." : $item->address  }}</td>
                  <td>{{ $item->licenceNumber }}</td>
                  <td> @if( $item->status == 1)	 <i class="fa fa-check"></i>
		      <b> {{ __('admin/common.status_active') }}</b>    @elseif ( $item->status  == 0)	
		      <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b>  @endif	</td>
                  <td>
                     <a href="{{ url('/servicer/drivers/' . $item->driverId) }}" title="View Driver"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('admin/common.action_view') }}</button></a>			    <a href="{{ url('/servicer/drivers/' . $item->driverId . '/edit') }}" title="Edit Driver"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button></a>			    
                     <form method="POST" action="{{ url('/servicer/drivers' . '/' . $item->driverId) }}" accept-charset="UTF-8" style="display:inline">				{{ method_field('DELETE') }}				{{ csrf_field() }}				<button type="submit" class="btn btn-danger btn-sm" title="Delete Driver" onclick="return confirm( & quot; Confirm delete? & quot; )"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>			    </form>
                  </td>
               </tr>
               @endforeach		
            </tbody>
         </table>
         <div class="pagination-wrapper"> {!! $drivers->appends(['search' => Request::get('search')])->render() !!} </div>
      </div>
   </div>
</section>
@endsection