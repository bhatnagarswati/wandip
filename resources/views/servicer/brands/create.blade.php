@extends('layouts.servicer.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('servicer.brands.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body col-md-7">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Name" class="form-control" value="{{ old('name') }}">
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="clearfix"></div>
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('servicer.brands.index') }}" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
