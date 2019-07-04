<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ __('admin/banners.title') }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ isset($banner->title) ? $banner->title : ''}}" >
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">{{ __('admin/banners.description') }}</label>
    <textarea class="form-control" name="description" type="text" id="description" >{{ isset($banner->description) ? $banner->description : ''}}</textarea>
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!} 
</div>
 

 <div class="form-group {{ $errors->has('bannerImage') ? 'has-error' : ''}}">
   <label for="bannerImage" class="control-label">{{ __('admin/banners.banner_image') }}</label>
   
   <label class="btn btn-default btn-file">
    Browse <input class="form-control showPic" name="bannerImage" id="bannerImage" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
   </label>
   
   <img class="imageShow" src="" alt="" />
   @if(isset($banner->bannerImage))
   <img class="sprofilePic" src="{{ config('constants.banner_pull_path').$banner->bannerImage}}" alt="" />
   @endif
   
   {!! $errors->first('bannerImage', '<p class="help-block">:message</p>') !!}
</div> 

<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
   <label for="status" class="control-label">{{ __('admin/banners.banner_status') }}</label>
   
   <select class="form-control" name="status" id="status">
      <option value="1" @if(!empty($banner))  @if($banner->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/banners.banner_active') }}</option>
      <option value="0" @if(!empty($banner))  @if($banner->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/banners.banner_inactive') }}</option>
      
   </select>
   
   {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="sortOrder" class="control-label">{{ __('admin/banners.banner_order') }}</label>
    <input class="form-control" name="sortOrder" type="text" id="sortOrder" value="{{ isset($banner->sortOrder) ? $banner->sortOrder : ''}}" >
    {!! $errors->first('sortOrder', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group  {{ $errors->has('bannerType') ? 'has-error' : ''}}"">
    <label for="bannerType">{{ __('admin/banners.banner_type') }} </label>
    <select name="bannerType" id="bannerType" class="form-control select2">
    <option value="">Select</option>
    <option @if(@$banner->bannerType  == 'web') selected="selected" @endif value="web">{{ __('admin/banners.banner_web') }} </option>
    <option @if(@$banner->bannerType  == 'app') selected="selected" @endif value="app">{{ __('admin/banners.banner_app') }} </option>
 
    </select>
    {!! $errors->first('bannerType', '<p class="help-block">:message</p>') !!}
</div>   


<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('admin/common.action_update') :  __('admin/common.action_create') }}">
   <a href="{{ url('/admin/banners') }}" class="btn btn-warning" title="Back"> <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }} </a>
   
   
</div>
