@extends('layouts.admin.app')@section('content')
<section class="content admin-forms">
   <div class="box">
      <div class="box-body">
         <div class="card">
            <h2 class="card-header">{{ __('admin/routers.requests_info') }} #{{ $route_request->id }}</h2>
            <div class="card-body">
               <a href="{{ url('/admin/route/'.$route_request->routeId.'/requests') }}" title="{{ __('admin/common.action_back') }}"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button></a>		    
               <br/>		    
  
               <div class="table-responsive">
                  <table class="table">
                     <tbody>
                        <tr>
                           <th>{{ __('admin/routers.requests_info_id') }}</th>
                           <td>{{ $route_request->id }}</td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.requests_customername') }} </th>
                           <td> {{ $route_request->customerinfo->name }}    </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.requests_customerAddress') }} </th>
                           <td> {{ $route_request->requestedAddress }} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.driverName') }} </th>
                           <td> {{ $route_request->driverinfo->firstName }} {{ $route_request->driverinfo->lastName }}    </td>
                        </tr>
                         <tr>
                           <th> {{ __('admin/routers.servicer') }} </th>
                           <td> {{ $route_request->servicerinfo->name }}    </td>
                        </tr>
                         <tr>
                           <th> {{ __('admin/routers.requests_requesteddate') }} </th>
                           <td> {{ $route_request->created_at->format('Y-m-d') }} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.requests_requestedqty') }} </th>
                           <td> {{ $route_request->requestedQty   }} {{ $route_request->requestedMassUnit}}</td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.requests_route_price') }} </th>
                           <td> {{ $route_request->requestedUnitPrice }}  per {{ $route_request->requestedMassUnit}} </td>
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