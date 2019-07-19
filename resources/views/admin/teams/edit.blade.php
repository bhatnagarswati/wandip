@extends('layouts.admin.app')
@section('content')
<section class="content admin-forms">
        
            <div class="box">
                <div class="card box-body">
                    <h2 class="card-header">{{ __('admin/teams.edit_team') }} #{{ $team->id }}</h2>
                    <div class="card-body col-md-7">
                       
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <form method="POST" action="{{ url('/admin/teams/' . $team->id) }}" accept-charset="UTF-8" class="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            @include ('admin.teams.form', ['formMode' => 'edit'])
                        </form>

                    </div>
                </div>
            </div>
         
    
   </section>
@endsection
