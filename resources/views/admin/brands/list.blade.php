@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(!$brands->isEmpty())
            <div class="box">
                <div class="box-body">
                    <h2>{{ __('admin/brands.top_heading') }}</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>{{ __('admin/brands.title') }}</td>
                                <td>{{ __('admin/brands.brand_status') }}</td>
                                <td>{{ __('admin/common.action') }}</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>
                                    {{ $brand->name }}
                                </td>
                                <td> @if( $brand->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $brand->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                                <td>
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> {{ __('admin/common.action_edit') }}</a>
                                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i>  {{ __('admin/common.action_delete') }}</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $brands->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @else
            <p class="alert alert-warning">{{ __('admin/brands.brand_notification') }} <a href="{{ route('admin.brands.create') }}">{{ __('admin/brands.create_notification') }} </a></p>
        @endif
    </section>
    <!-- /.content -->
@endsection
