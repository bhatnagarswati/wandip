@extends('layouts.admin.app')@section('content')
<section class="content admin-forms">
    <div class="box">
        <div class="box-body">
            <div class="card">
                <h2 class="card-header">{{ __('admin/pages.view_page') }} #{{ $page->id }}</h2>
                <div class="card-body">
                    <a href="{{ url('/admin/pages') }}" title="Back">
                        <button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button>
                    </a>
                    <a href="{{ url('/admin/pages/' . $page->id . '/edit') }}" title="Edit page">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button>
                    </a>
                    <form method="POST" action="{{ url('admin/pages' . '/' . $page->id) }}" accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }} {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete page" onclick="return confirm( 'Confirm delete?'); "><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>
                    </form>
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $page->id }}</td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/pages.title') }} </th>
                                    <td> {{ $page->title }} </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/pages.shortDescription') }} </th>
                                    <td> {{ $page->shortDescription }} </td>
                                </tr>
                                
                                <tr>
                                    <th> {{ __('admin/pages.fullDescription') }} </th>
                                    <td> {{ $page->fullDescription }} </td>
                                </tr>

                                <tr>
                                  <th> {{ __('admin/pages.page_status') }} </th>   
                                <td> @if( $page->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $page->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                                
                                 </tr>


                                 <tr>
                                  <th> {{ __('admin/pages.page_language') }} </th>   
                                  <td>{{ ucfirst($page->languageType) }} </td>
                                 </tr>

                                  <tr>
                                      <th> {{ __('admin/pages.pageType') }} </th>   
                                      <td>{{ ucfirst($page->pageType) }} </td>
                                 </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> @endsection