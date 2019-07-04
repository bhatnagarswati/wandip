@extends('layouts.servicer.app')@section('content')
<section class="content admin-forms">
   <div class="box">
      <div class="box-body">
         <h2 class="card-header">{{ __('admin/pumps.create_pump') }}</h2>
         <div class="card-body col-md-7">
            <br />		<br />		@if ($errors->any())		
            <ul class="alert alert-danger">
               @foreach ($errors->all() as $error)		    
               <li>{{ $error }}</li>
               @endforeach		
            </ul>
            @endif		
            <form method="POST" action="{{ url('/servicer/pumps') }}" accept-charset="UTF-8" class="form" enctype="multipart/form-data">		    {{ csrf_field() }}		    @include ('servicer.pumps.form', ['formMode' => 'create'])		</form>
         </div>
      </div>
   </div>
</section>
@endsection