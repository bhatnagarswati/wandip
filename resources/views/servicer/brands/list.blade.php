@extends('layouts.servicer.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(!$brands->isEmpty())
            <div class="box">
                <div class="box-body">
                    <h2>Brands</h2>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Status</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>
                                    {{ $brand->name }}
                                </td>
                                 <td> @if( $brand->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $brand->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_brand_approval') }}</b> @endif </td>
                                <td>

                                    @if($brand->status == 0  &&  $brand->servicerId = $servicerId)
                                    <form action="{{ route('servicer.brands.destroy', $brand->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('servicer.brands.edit', $brand->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i>  {{ __('admin/common.action_delete') }}</button>
                                        </div>
                                    </form>
                                    @else
                                    <i class="fa fa-close"> <b>Can't edit</b></i> 
                                    @endif


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
            <p class="alert alert-warning">No brand created yet. <a href="{{ route('servicer.brands.create') }}">Create one!</a></p>
        @endif
    </section>
    <!-- /.content -->
@endsection
