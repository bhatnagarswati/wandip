@extends('layouts.servicer.app')@section('content')
<section class="content">
   <div class=" box">
      <div class="box-body ">
         <h2>{{ __('admin/routers.requests_all_routes') }}</h2>

         <a href="{{ url('/servicer/routers') }}" title="{{ __('admin/common.action_back') }}"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button></a>		    
               <br/>	

<!--            		      <form method="GET" action="{{ url('/servicer/requests') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">   			      <div class="input-group">   				 <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">   				 <span class="input-group-append">   				    <button class="btn btn-secondary" type="submit">   				       <i class="fa fa-search"></i>   				    </button>   				 </span>   			      </div>   			   </form>   	    	    -->
         <table class="table table-bordered table-striped">
            <thead>
               <tr>
                  <th>#</th>
                  <th>{{ __('admin/routers.requests_customer') }}</th>
                  <th>{{ __('admin/routers.requests_customerAddress') }}</th>
                  <th >{{ __('admin/routers.requests_driver') }}</th>
                  <th>{{ __('admin/routers.requests_servicer') }}</th>
                  <th>{{ __('admin/routers.requests_routeId') }}</th>
                  <th>{{ __('admin/routers.requests_date') }}</th>
                  <th>{{ __('admin/routers.route_status') }}</th>
                  <th>{{ __('admin/common.action') }}</th>
               </tr>
            </thead>
            <tbody>
               @foreach($route_requests as $item)
               <tr>
                  <td>{{ $loop->iteration  }}</td>
                  <td>{{ $item->customerinfo->name }}</td>
                  <td>{{ $item->requestedAddress }}</td>
                  <td>{{ $item->driverinfo->firstName }} {{ $item->driverinfo->lastName }}</td>
                  <td>{{ $item->servicerinfo->name }}</td>
                  <td>{{ $item->routeId }}</td>
                  
                  <td>{{ $item->created_at->format('Y-m-d') }}</td>
                  <td> @if( $item->status == 1 && $item->markedStatus == 0)	<span class="label label-info">  <i class="fa fa-check"></i> </span> 
		             <b> &nbsp; {{ __('admin/routers.requests_pending') }}</b>    @elseif ( $item->status  == 1 && $item->markedStatus == 1)	
                   <span class="label label-success">  <i class="fa fa-check"></i>  </span> &nbsp;  <b> {{ __('admin/routers.requests_delivered') }}</b>  @elseif ( $item->status  == 0)	
                   <span class="label label-warning"> <i class="fa fa-times"></i> </span> &nbsp; <b> {{ __('admin/routers.requests_cancelled') }} </b>  @endif   	</td>
                  <td>
                     <a href="{{ url('/servicer/requests/' . $item->id) }}" title="View Route Request"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('admin/common.action_view') }}</button></a>			
                     

                     @if($item->status == 1 && $item->markedStatus == 0)	

                     <a href="{{ url('/servicer/requests/' . $item->id . '/cancel-request') }}"  onclick="return confirm('Confirm Cancellation? ');" title="Cancel Route Request"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/routers.requests_cancel_btn') }}</button></a>
                     @elseif ($item->status  == 0  && $item->markedStatus == 0)	
                     
                     <a href="{{ url('/servicer/requests/' . $item->id . '/activate-request') }}"  title="Activate Route Request"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/routers.requests_active_btn') }}</button></a>

                     @elseif ($item->status  ==1  && $item->markedStatus ==1)	
                    <button class="btn btn-primary btn-sm label-success">  {{ __('admin/routers.requests_delivered_btn') }}</button>
                     @endif
                     
                     <!-- <a href="{{ url('/servicer/requests/' . $item->id . '/edit') }}" title="Edit Route Request"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button></a>
                     
                     
                     <form method="POST" action="{{ url('/servicer/requests' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">				{{ method_field('DELETE') }}				{{ csrf_field() }}				<button type="submit" class="btn btn-danger btn-sm" title="Delete Route Request" onclick="return confirm('Confirm delete? ');"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>			    </form> -->
                  </td>
               </tr>
               @endforeach		
            </tbody>
         </table>
         <div class="pagination-wrapper"> {!! $route_requests->appends(['search' => Request::get('search')])->render() !!} </div>
      </div>
   </div>
</section>
@endsection