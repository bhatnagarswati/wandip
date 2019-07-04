@extends('layouts.admin.app')@section('content')
<section class="content admin-forms">
    <div class="box">
        <div class="box-body">
            <div class="card">
                <h2 class="card-header">{{ __('admin/stores.view_store') }} #{{ $store->id }}</h2>
                <div class="card-body">
                    <a href="{{ url('/admin/stores') }}" title="Back">
                        <button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button>
                    </a>
                    <a href="{{ url('/admin/stores/' . $store->id . '/edit') }}" title="Edit Store">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button>
                    </a>
                    <form method="POST" action="{{ url('admin/stores' . '/' . $store->id) }}" accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }} {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Store" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>
                    </form>
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $store->id }}</td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/stores.storeTitle') }} </th>
                                    <td> {{ $store->storeTitle }} </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/stores.storeDescription') }} </th>
                                    <td> {{ $store->storeDescription }} </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/stores.storeLocation') }} </th>
                                    <td> {{ $store->storeLocation }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> @endsection