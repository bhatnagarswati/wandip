@extends('layouts.admin.app')
@section('content')
<section class="content admin-forms">
        
            <div class="box">
                <div class="card box-body">
                    <h2 class="card-header">{{ __('admin/banners.edit_banner') }} #{{ $banner->id }}</h2>
                    <div class="card-body">
                       
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <form method="POST" action="{{ url('/admin/banners/' . $banner->id) }}" accept-charset="UTF-8" class="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            @include ('admin.banners.form', ['formMode' => 'edit'])
                        </form>

                    </div>
                </div>
            </div>
         
    
   </section>
@endsection
