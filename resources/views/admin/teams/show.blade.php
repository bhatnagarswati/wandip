@extends('layouts.admin.app')@section('content')
<section class="content admin-forms">
    <div class="box">
        <div class="box-body">
            <div class="card">
                <h2 class="card-header">{{ __('admin/teams.view_team') }} #{{ $team->id }}</h2>
                <div class="card-body">
                    <a href="{{ url('/admin/teams') }}" title="Back">
                        <button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button>
                    </a>
                    <a href="{{ url('/admin/teams/' . $team->id . '/edit') }}" title="Edit blog">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button>
                    </a>
                    <form method="POST" action="{{ url('admin/teams' . '/' . $team->id) }}" accept-charset="UTF-8" style="display:inline"> {{ method_field('DELETE') }} {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete blog" onclick="return confirm( 'Confirm delete?'); "><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>
                    </form>
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $team->id }}</td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/teams.name') }} </th>
                                    <td> {{ $team->name }} </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/teams.description') }} </th>
                                    <td> {{ $team->description }} </td>
                                </tr>


                                <tr>
                                <th> {{ __('admin/teams.team_status') }} </th>
                                <td> @if( $team->status == 1) <i class="fa fa-check"></i><b> {{ __('admin/common.status_active') }}</b> @elseif ( $team->status == 0) <i class="fa fa-times"></i><b> {{ __('admin/common.status_inactive') }}</b> @endif </td>
                                
                                </tr>
                                

                                 <tr>
                                  <th> {{ __('admin/teams.team_language') }} </th>
                                  <td>{{ ucfirst($team->languageType) }} </td>
                                 </tr>
                                 <tr>
                                    <th> {{ __('admin/teams.image') }} </th>
                                    <td> @if(isset($team->teamImage))
                                        <img class="sprofilePic" src="{{ config('constants.team_pull_path').$team->teamImage}}" alt="" />
                                        @endif </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/teams.facebook_link') }} </th>
                                    <td>{{ ucfirst($team->facebook_link) }} </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/teams.twitter_link') }} </th>
                                    <td>{{ ucfirst($team->twitter_link) }} </td>
                                </tr>
                                <tr>
                                    <th> {{ __('admin/teams.linkedin_link') }} </th>
                                    <td>{{ ucfirst($team->linkedin_link) }} </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> @endsection