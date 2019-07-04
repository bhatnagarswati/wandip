@extends('layouts.admin.app')@section('content')
<section class="content admin-forms">
   <div class="box">
      <div class="box-body">
         <div class="card">
            <h2 class="card-header">{{ __('admin/routers.view_route') }} #{{ $route->id }}</h2>
            <div class="card-body">
               <a href="{{ url('/admin/routers') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button></a>		    <a href="{{ url('/admin/routers/' . $route->id . '/edit') }}" title="Edit route"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button></a>		    
               <form method="POST" action="{{ url('admin/routers' . '/' . $route->id) }}" accept-charset="UTF-8" style="display:inline">			{{ method_field('DELETE') }}			{{ csrf_field() }}			<button type="submit" class="btn btn-danger btn-sm" title="Delete route" onclick="return confirm('Confirm delete?');"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>		    </form>
               <br/>		    <br/>		    
               <div class="table-responsive">
                  <table class="table">
                     <tbody>
                        <tr>
                           <th>{{ __('admin/routers.routeId') }}</th>
                           <td>{{ $route->id }}</td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.driverName') }} </th>
                           <td> {{ $route->driver->firstName }} {{ $route->driver->lastName }}    </td>
                        </tr>
                         <tr>
                           <th> {{ __('admin/routers.servicer') }} </th>
                           <td> {{ $route->servicer->name }}    </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.routeLocations') }} </th>
                           <td>  @foreach($route->routeInfo as $infoitem)    
                                    {{ $infoitem->location }}
                                     @if( $loop->iteration <  count($route->routeInfo))
                                       <b> => </b>
                                     @endif
                                  @endforeach  
                           </td>
                        </tr>
                         <tr>
                           <th> {{ __('admin/routers.routeDepartureDate') }} </th>
                           <td> {{ $route->deliveryDate->format('Y-m-d') }} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.routeDeparture') }} </th>
                           <td> {{ $route->departureTime }} </td>
                        </tr>
                       
                        <tr>
                           <th> {{ __('admin/routers.volumeContained') }} </th>
                           <td> {{ $route->volumeContained   }} {{ $route->priceUnit}}</td>
                        </tr><tr>
                           <th> {{ __('admin/routers.price') }} </th>
                           <td> {{ $route->price }}  per {{ $route->priceUnit}} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/routers.notifyUser') }} </th>
                           <td> {{ $route->notifyUsers }} Miles </td>
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