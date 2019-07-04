@extends('layouts.admin.app')



@section('content')

<section class="content admin-forms">





    <div class="box">



        <div class="box-body">

            <h2 class="card-header">{{ __('admin/stores.create_store') }}</h2>

            <div class="card-body col-md-7">
 


                @if ($errors->any())



                <ul class="alert alert-danger">

                    @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                    @endforeach

                </ul>

                @endif



                <form method="POST" action="{{ url('/admin/stores') }}" accept-charset="UTF-8" class="form"
                    enctype="multipart/form-data">

                    {{ csrf_field() }}



                    @include ('admin.stores.form', ['formMode' => 'create'])



                </form>



            </div>

        </div>

    </div>





</section>

@endsection