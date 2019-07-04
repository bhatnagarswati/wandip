@extends('layouts.admin.app')@section('content')
<section class="content">
    <div class=" box">
        <div class="box-body ">
            <h2>{{ __('admin/pumps.all_pumps') }}</h2>
		<!--   		      <form method="GET" action="{{ url('/admin/pumps') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">   			      <div class="input-group">   				 <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">   				 <span class="input-group-append">   				    <button class="btn btn-secondary" type="submit">   				       <i class="fa fa-search"></i>   				    </button>   				 </span>   			      </div>   			   </form>   	    -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin/pumps.pumpTitle') }}</th>
                        <th>{{ __('admin/pumps.pumpStoreTitle') }}</th>
                        <th>{{ __('admin/pumps.pumpAddress') }}</th>
                        <th>{{ __('admin/pumps.pumpPrice') }}</th>
                        <th>{{ __('admin/pumps.pump_status') }}</th>
                        <th>{{ __('admin/common.action') }}</th>
                    </tr>
                </thead>
                <tbody> @foreach($pumps as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->pumpTitle }}</td>
                        <td>{{ @$item->stores->storeTitle }}</td>
                        <td>{{ strlen($item->pumpAddress) > 15 ? substr($item->pumpAddress, 0, 15)."..." : $item->pumpAddress }}</td>
                        <td>{{ '$'.$item->pumpPrice }}</td>
                        <td> @if( $item->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $item->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                        <td>
                            <a href="{{ url('/admin/pumps/' . $item->pumpId) }}" title="View Pump">
                                <button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('admin/common.action_view') }}</button>
                            </a>
                            <a href="{{ url('/admin/pumps/' . $item->pumpId . '/edit') }}" title="Edit Pump">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button>
                            </a>
                            <form method="POST" action="{{ url('/admin/pumps' . '/' . $item->pumpId) }}" accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }} {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Pump" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>
                            </form>
                        </td>
                    </tr> @endforeach </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $pumps->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>
    </div>
</section>@endsection