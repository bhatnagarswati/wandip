@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
   @include('layouts.errors-and-messages')
   <div class="box">
      <form action="{{ route('admin.brands.update', $brand->id) }}" method="post" class="form" enctype="multipart/form-data">
	 <div class="box-body col-md-7">
	    {{ csrf_field() }}
	    <input type="hidden" name="_method" value="put">
	    <div class="form-group">
	       <label for="name">{{ __('admin/brands.title') }} <span class="text-danger">*</span></label>
	       <input type="text" name="name" id="name" placeholder="{{ __('admin/brands.title') }}" class="form-control" value="{{ $brand->name }}">
	    </div>

	    
	    <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
           <label for="status" class="control-label">{{ __('admin/stores.store_status') }}</label>
           <select class="form-control" name="status" id="status">
            <option value="1" @if(!empty($brand))  @if($brand->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/stores.store_active') }}</option>
  			<option value="0" @if(!empty($brand))  @if($brand->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/stores.store_inactive') }}</option>
           </select>
           
           {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>

	 </div>
	 <div class="clearfix"></div>
	 <!-- /.box-body -->
	 <div class="box-footer">
	    <div class="btn-group">
	       <a href="{{ route('admin.brands.index') }}" class="btn btn-default">{{ __('admin/common.action_back') }}</a>
	       <button type="submit" class="btn btn-primary">{{ __('admin/brands.update_button') }}</button>
	    </div>
	 </div>
      </form>
   </div>
   <!-- /.box -->

</section>
<!-- /.content -->
@endsection
