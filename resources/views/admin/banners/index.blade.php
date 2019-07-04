@extends('layouts.admin.app')@section('content')
<section class="content">
    <div class=" box">
        <div class="box-body ">
             @include('layouts.errors-and-messages')
            <h2>{{ __('admin/banners.all_banners') }}</h2>
            <!--		   <form method="GET" action="{{ url('/admin/banners') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">			   <div class="input-group">			      <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">			      <span class="input-group-append">				 <button class="btn btn-secondary" type="submit">				    <i class="fa fa-search"></i>				 </button>			      </span>			   </div>			</form>	 -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin/banners.title') }}</th>
                        <th>{{ __('admin/banners.banner_image') }}</th>
                        <th>{{ __('admin/banners.banner_type') }}</th>
                        <th>{{ __('admin/banners.banner_status') }}</th>
                        <th>{{ __('admin/common.action') }}</th>
                    </tr>
                </thead>
                <tbody> @foreach($banners as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td><img class="sprofilePic" src="{{ config('constants.banner_pull_path').$item->bannerImage}}" alt="" /></td>
                         <td>{{ ucfirst($item->bannerType) }}</td>   
                        <td> @if( $item->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $item->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                        <td>
                            <a href="{{ url('/admin/banners/' . $item->id) }}" title="View banner">
                                <button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('admin/common.action_view') }}</button>
                            </a>
                            <a href="{{ url('/admin/banners/' . $item->id . '/edit') }}" title="Edit banner">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button>
                            </a>
                            <form method="POST" action="{{ url('/admin/banners' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }} {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete banner" onclick="return confirm( 'Confirm delete?' );"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>
                            </form>
                        </td>
                    </tr> @endforeach </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $banners->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>
    </div>
</section>@endsection