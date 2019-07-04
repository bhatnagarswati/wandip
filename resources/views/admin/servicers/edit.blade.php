@extends('layouts.admin.app')

@section('content')

<section class="content admin-forms">

   <div class="box">

      <div class="box-body">

	 <div class="card-body col-md-8">

	    @if ($errors->any())

	    <ul class="alert alert-danger">

	       @foreach ($errors->all() as $error)

	       <li>{{ $error }}</li>

	       @endforeach

	    </ul>

	    @endif



	    <form  method="POST" action="{{ url('/admin/servicers/' . $servicer->id) }}" accept-charset="UTF-8" class="form" enctype="multipart/form-data">

	       {{ method_field('PATCH') }}

	       {{ csrf_field() }}

	       @include ('admin.servicers.form', ['formMode' => 'edit'])

	    </form>



	 </div>



      </div>

   </div>





</section>

@endsection

