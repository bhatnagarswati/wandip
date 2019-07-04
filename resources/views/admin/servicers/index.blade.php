@extends('layouts.admin.app')@section('content')

<section class="content">
    <div class="box">
        <div class="box-body">
            <h2>Service Providers</h2>
            <div class="card-body ">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('admin/servicers.provider_name') }}</th>
                            <th>{{ __('admin/servicers.provider_email') }}</th>
                            <th>{{ __('admin/servicers.provider_phone') }}</th>
                            <th>{{ __('admin/servicers.provider_status') }}</th>
                            <th>{{ __('admin/common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody> @foreach($servicers as $item) <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone }}</td>
                            <td> @if( $item->status == 1) <i class="fa fa-check" aria-hidden="true"></i><b>
                                    {{ __('admin/common.status_approved') }}</b> @elseif ( $item->status == 0) <i
                                    class="fa fa-times" aria-hidden="true"></i><b>
                                    {{ __('admin/common.status_pending') }}</b> @endif </td>
                            <td> <a href="{{ url('/admin/servicers/' . $item->id) }}" title="View Servicer"><button
                                        class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>
                                        {{ __('admin/common.action_view') }}</button></a> <a
                                    href="{{ url('/admin/servicers/' . $item->id . '/edit') }}"
                                    title="Edit Servicer"><button class="btn btn-primary btn-sm"><i
                                            class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        {{ __('admin/common.action_edit') }}</button></a>
                                <form method="POST" action="{{ url('/admin/servicers' . '/' . $item->id) }}"
                                    accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }}
                                    {{ csrf_field() }} <button type="submit" class="btn btn-danger btn-sm"
                                        title="Delete Servicer" onclick="return confirm('Confirm delete?')"><i
                                            class="fa fa-trash-o" aria-hidden="true"></i>
                                        {{ __('admin/common.action_delete') }}</button> </form>
                            </td>
                        </tr> @endforeach </tbody>
                </table>
                <div class="pagination-wrapper"> {!! $servicers->appends(['search' => Request::get('search')])->render() !!} </div>
            </div>
        </div>
    </div>
</section>@endsection