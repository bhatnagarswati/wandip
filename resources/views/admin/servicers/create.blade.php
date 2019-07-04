@extends('layouts.admin.app')

@section('content')
<section class="content admin-forms">

   <div class="box">
      <div class="box-body">
	 <h2>Service Providers</h2>
	 <div class="card col-md-8">
	    <div class="card-body">
	       @if ($errors->any())
	       <ul class="alert alert-danger">
		  @foreach ($errors->all() as $error)
		  <li>{{ $error }}</li>
		  @endforeach
	       </ul>
	       @endif
	       <form method="POST" action="{{ url('/admin/servicers') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
		  {{ csrf_field() }}
		  @include ('admin.servicers.form', ['formMode' => 'create'])

	       </form>

	    </div>
	 </div>
      </div>

   </div>

</section>
@endsection
