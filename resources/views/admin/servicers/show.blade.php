@extends('layouts.admin.app')

@section('content')

<section class="content admin-forms">

   <div class="row">

      <div class="col-md-12 box">

	 <div class="card-body">

	    <div class="table-responsive">

	       <table class="table">

		  <tbody>

		     <tr>

			<th>ID</th>

			<td>{{ $servicer->id }}</td> 

		     </tr>

		     <tr>

			<th> {{ __('admin/servicers.name') }} </th>

			<td> {{ $servicer->name }} </td>

		     </tr>

		     <tr>

			<th> {{ __('admin/servicers.email') }} </th>

			<td> {{ $servicer->email }} </td>

		     </tr>

		     <tr>

			<th> {{ __('admin/servicers.phone') }} </th>

			<td> {{ $servicer->phone }} </td>

		     </tr>

		     <tr>

			<th> {{ __('admin/servicers.profile_pic') }} </th>

			<td>@if(isset($servicer->profilePic))

			   <img class="sprofilePic" src="{{ config('constants.service_provider_pull_path').$servicer->profilePic}}" alt="" />

			   @endif 

			</td>

		     </tr>

		  </tbody>

	       </table>

	    </div>



	 </div>

	 <div class="box-footer">

	    <div class="btn-group">

	       <a href="{{ route('admin.servicers.index') }}" class="btn btn-default">Back</a>

	    </div>

	 </div>

      </div>

   </div>



</section>     

@endsection

