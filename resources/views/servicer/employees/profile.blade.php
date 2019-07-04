@extends('layouts.servicer.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <form action="{{ url('/servicer/profile/' . $employee->id) }}" enctype="multipart/form-data" method="post"
        class="form">
        <input type="hidden" name="_method" value="post">
        {{ csrf_field() }}
        <!-- Default box -->
        <div class="box">
            <div class="box-body col-md-7">
                <table class="table table-bordered table-striped">


                    <tbody>


                        <tr>

                            <th> {{ __('admin/servicers.name') }} </th>

                            <td>
                                <div class="form-group"><input name="name" type="text" class="form-control"
                                        value="{{ $employee->name }}"> </div>
                            </td>

                        </tr>

                        <tr>

                            <th> {{ __('admin/servicers.email') }} </th>

                            <td>
                                <div class="form-group"> <input name="email" type="email" class="form-control"
                                        value="{{ $employee->email }}"></div>
                            </td>

                        </tr>

                        <tr>

                            <th> {{ __('admin/servicers.phone') }} </th>

                            <td>
                                <div class="form-group"> <input name="phone" type="text" class="form-control"
                                        value="{{ $employee->phone }}" placeholder="">
                            </td>
            </div>

            </tr>

            <tr>

                <th> {{ __('admin/servicers.profile_pic') }} </th>

                <td>
                    <div class="form-group">
                        <label class="btn btn-default btn-file">
                            Browse <input class="form-control showPic" name="profilePic" id="profilePic" type="file"
                                style="display: none;" accept="image/jpg, image/jpeg,image/png">
                        </label>

                        <img class="imageShow" src="" alt="" />
                        @if(isset($employee->profilePic))
                        <img class="sprofilePic"
                            src="{{ config('constants.service_provider_pull_path').$employee->profilePic}}" alt="" />
                        @endif
                    </div>

                </td>

            </tr>
            <tr>
                <th>{{ __('admin/servicers.password') }}</th>
                <td>
                    <div class="form-group">
                        <input name="password" type="password" class="form-control" value="" placeholder="New Password">
                    </div>
                </td>

            </tr>

            </tbody>










            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="btn-group">
                <a href="{{ route('servicer.dashboard') }}" class="btn btn-default btn-sm">Back</a>
                <button class="btn btn-success btn-sm" type="submit"> <i class="fa fa-save"></i> Save</button>
            </div>
        </div>
        </div>
        <!-- /.box -->
    </form>

</section>
<!-- /.content -->
@endsection