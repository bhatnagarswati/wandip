@extends('layouts.admin.app')@section('content')
<section class="content admin-forms">
    <div class="box">
        <div class="box-body">
            <div class="card">
                <h2 class="card-header">{{ __('admin/banners.view_banner') }} #{{ $banner->id }}</h2>
                <div class="card-body">
                    <a href="{{ url('/admin/banners') }}" title="Back">
                        <button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button>
                    </a>
                    <a href="{{ url('/admin/banners/' . $banner->id . '/edit') }}" title="Edit banner">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button>
                    </a>
                    <form method="POST" action="{{ url('admin/banners' . '/' . $banner->id) }}" accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }} {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete banner" onclick="return confirm( 'Confirm delete?'); "><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>
                    </form>
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $banner->id }}</td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/banners.title') }} </th>
                                    <td> {{ $banner->title }} </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/banners.description') }} </th>
                                    <td> {{ $banner->description }} </td>
                                </tr>
                                
                                <tr>
                                    <th> {{ __('admin/banners.banner_image') }} </th>
                                    <td> <img class="sprofilePic" src="{{ config('constants.banner_pull_path').$banner->bannerImage}}" alt="" /> </td>
                                </tr>

                                <tr>
                                  <th> {{ __('admin/banners.banner_status') }} </th>   
                                <td> @if( $banner->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $banner->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                                
                                 </tr>


                                 <tr>
                                  <th> {{ __('admin/banners.banner_type') }} </th>   
                                  <td>{{ ucfirst($banner->bannerType) }} </td>
                                 </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> @endsection