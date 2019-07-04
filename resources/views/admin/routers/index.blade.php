@extends('layouts.admin.app')@section('content')
<section class="content">
    <div class=" box">
        <div class="box-body ">
            @include('layouts.errors-and-messages')
            <h2>{{ __('admin/routers.all_routes') }}</h2>
            <!--            		      <form method="GET" action="{{ url('/admin/routers') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">   			      <div class="input-group">   				 <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">   				 <span class="input-group-append">   				    <button class="btn btn-secondary" type="submit">   				       <i class="fa fa-search"></i>   				    </button>   				 </span>   			      </div>   			   </form>   	    	    -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin/routers.routeId') }}</th>
                        <th>{{ __('admin/routers.routeDate') }}</th>
                        <th style="width: 40%;">{{ __('admin/routers.routeinfo') }}</th>
                        <th>{{ __('admin/routers.Servicer') }}</th>
                        <th>{{ __('admin/routers.route_status') }}</th>
                        <th>{{ __('admin/common.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routes as $item)
                    <tr>
                        <td>{{ $loop->iteration  }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ @$item->deliveryDate->format('Y-m-d') }}</td>
                        <td>
                            @foreach($item->routeInfo as $infoitem)
                            {{ $infoitem->location }}
                            @if( $loop->iteration < count($item->routeInfo))
                                <b> => </b>
                                @endif
                                @endforeach
                        </td>
                        <td>{{ @$item->servicer->name }}</td>
                        <td> @if( $item->status == 1) <i class="fa fa-check"></i>
                            <b> {{ __('admin/common.status_active') }}</b> @elseif ( $item->status == 0)
                            <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                        <td>
                            <a href="{{ url('/admin/routers/' . $item->id) }}" title="View Route"><button
                                    class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>
                                    {{ __('admin/common.action_view') }}</button></a> <a
                                href="{{ url('/admin/routers/' . $item->id . '/edit') }}" title="Edit Route"><button
                                    class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o"
                                        aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button></a>
                            <form method="POST" action="{{ url('/admin/routers' . '/' . $item->id) }}"
                                accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }}
                                {{ csrf_field() }} <button type="submit" class="btn btn-danger btn-sm"
                                    title="Delete Route" onclick="return confirm('Confirm delete? ');"><i
                                        class="fa fa-trash-o" aria-hidden="true"></i>
                                    {{ __('admin/common.action_delete') }}</button> </form>

                                    <a href="{{ url('/admin/route/' . $item->id.'/requests') }}" title="View Route Requests"><button
                                    class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>
                                    {{ __('admin/routers.requests_route_show') }}</button></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $routes->appends(['search' => Request::get('search')])->render() !!}
            </div>
        </div>
    </div>
</section>
@endsection