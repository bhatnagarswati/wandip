@extends('layouts.admin.app')@section('content')
<section class="content admin-forms">
    <div class="box">
        <div class="box-body">
            <div class="card">
                <h2 class="card-header">{{ __('admin/blogs.view_blog') }} #{{ $blog->id }}</h2>
                <div class="card-body">
                    <a href="{{ url('/admin/blogs') }}" title="Back">
                        <button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button>
                    </a>
                    <a href="{{ url('/admin/blogs/' . $blog->id . '/edit') }}" title="Edit blog">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button>
                    </a>
                    <form method="POST" action="{{ url('admin/blogs' . '/' . $blog->id) }}" accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }} {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete blog" onclick="return confirm( 'Confirm delete?'); "><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>
                    </form>
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $blog->id }}</td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/blogs.title') }} </th>
                                    <td> {{ $blog->title }} </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/blogs.shortDescription') }} </th>
                                    <td> {{ $blog->shortDescription }} </td>
                                </tr>
                                
                                <tr>
                                    <th> {{ __('admin/blogs.fullDescription') }} </th>
                                    <td> {{ $blog->fullDescription }} </td>
                                </tr>

                                <tr>
                                <th> {{ __('admin/blogs.blog_status') }} </th>   
                                <td> @if( $blog->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $blog->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                                
                                </tr>
                                

                                 <tr>
                                  <th> {{ __('admin/blogs.blog_language') }} </th>   
                                  <td>{{ ucfirst($blog->languageType) }} </td>
                                 </tr>
                                 <tr>
                                    <th> {{ __('admin/blogs.blog_image') }} </th>
                                    <td> @if(isset($blog->blogImage))
                                        <img class="sprofilePic" src="{{ config('constants.blog_pull_path').$blog->blogImage}}" alt="" />
                                        @endif </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> @endsection