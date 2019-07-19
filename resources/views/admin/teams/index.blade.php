@extends('layouts.admin.app')@section('content')
<section class="content">
    <div class=" box">
        <div class="box-body ">
             @include('layouts.errors-and-messages')
            <h2>{{ __('admin/teams.all_team') }}</h2>
            <!--		   <form method="GET" action="{{ url('/admin/team') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">			   <div class="input-group">			      <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">			      <span class="input-group-append">				 <button class="btn btn-secondary" type="submit">				    <i class="fa fa-search"></i>				 </button>			      </span>			   </div>			</form>	 -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin/teams.name') }}</th>
                        <th>{{ __('admin/teams.description') }}</th>
                        <th>{{ __('admin/teams.image') }}</th>
                        <th>{{ __('admin/teams.team_status') }}</th>
                        <th>{{ __('admin/common.action') }}</th>
                    </tr>
                </thead>
                <tbody> @foreach($teams as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ strip_tags(stripslashes($item->description)) }}</td>
                        <td></td>
                        <td> @if( $item->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $item->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                        <td>
                            <a href="{{ url('/admin/teams/' . $item->id) }}" title="View blog">
                                <button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('admin/common.action_view') }}</button>
                            </a>
                            <a href="{{ url('/admin/teams/' . $item->id . '/edit') }}" title="Edit blog">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button>
                            </a>
                            <form method="POST" action="{{ url('/admin/teams' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }} {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete blog" onclick="return confirm('Confirm delete?');"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>
                            </form>
                        </td>
                    </tr> @endforeach </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $teams->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>
    </div>
</section>@endsection