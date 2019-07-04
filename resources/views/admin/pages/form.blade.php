<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ __('admin/pages.title') }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ isset($page->title) ? $page->title : ''}}" >
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('shortDescription') ? 'has-error' : ''}}">
    <label for="shortDescription" class="control-label">{{ __('admin/pages.shortDescription') }}</label>
    <textarea class="form-control" name="shortDescription" type="text" id="shortDescription" >{{ isset($page->shortDescription) ? $page->shortDescription : ''}}</textarea>
    {!! $errors->first('shortDescription', '<p class="help-block">:message</p>') !!} 
</div>

 <div class="form-group {{ $errors->has('fullDescription') ? 'has-error' : ''}}">
    <label for="fullDescription" class="control-label">{{ __('admin/pages.fullDescription') }}</label>
    <textarea class="form-control ckeditor" name="fullDescription" type="text" id="fullDescription" >{{ isset($page->fullDescription) ? $page->fullDescription : ''}}</textarea>
    {!! $errors->first('fullDescription', '<p class="help-block">:message</p>') !!} 
</div>

<div class="form-group {{ $errors->has('pagePic') ? 'has-error' : ''}}">
   <label for="pagePic" class="control-label">{{ __('admin/pages.pagePic') }}</label>
   
   <label class="btn btn-default btn-file">
    Browse <input class="form-control showPic" name="pagePic" id="pagePic" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
   </label>
   
   <img class="imageShow" src="" alt="" />
      @if(isset($page->pagePic))
      <img class="sprofilePic" src="{{ config('constants.page_pull_path').$page->pagePic}}" alt="" />
      @endif
   
   {!! $errors->first('pagePic', '<p class="help-block">:message</p>') !!}
</div> 

<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
   <label for="status" class="control-label">{{ __('admin/pages.page_status') }}</label>
   
   <select class="form-control" name="status" id="status">
      <option value="1" @if(!empty($page))  @if($page->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/pages.page_active') }}</option>
      <option value="0" @if(!empty($page))  @if($page->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/pages.page_inactive') }}</option>
      
   </select>
   
   {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
 
<div class="form-group  {{ $errors->has('pageType') ? 'has-error' : ''}}"">
    <label for="pageType">{{ __('admin/pages.pageType') }} </label>
    <select name="pageType" id="pageType" class="form-control select2">
    <option value="">Select</option>
    <option @if(@$page->pageType  == 'website') selected="selected" @endif value="website">{{ __('admin/pages.pageForWeb') }} </option>
    <option @if(@$page->pageType  == 'apps') selected="selected" @endif value="apps">{{ __('admin/pages.pageForApp') }} </option>
 
    </select>
    {!! $errors->first('pageType', '<p class="help-block">:message</p>') !!}
</div>




<div class="form-group {{ $errors->has('metaTitle') ? 'has-error' : ''}}">
    <label for="metaTitle" class="control-label">{{ __('admin/pages.meta_title') }}</label>
    <input class="form-control" name="metaTitle" type="text" id="metaTitle" value="{{ isset($page->metaTitle) ? $page->metaTitle : ''}}" >
    {!! $errors->first('metaTitle', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('metaDescription') ? 'has-error' : ''}}">
    <label for="metaDescription" class="control-label">{{ __('admin/pages.meta_description') }}</label>
    <textarea class="form-control" name="metaDescription" type="text" id="metaDescription" >{{ isset($page->metaDescription) ? $page->metaDescription : ''}}</textarea>
    {!! $errors->first('metaDescription', '<p class="help-block">:message</p>') !!} 
</div>

 <div class="form-group {{ $errors->has('metaKeywords') ? 'has-error' : ''}}">
    <label for="metaKeywords" class="control-label">{{ __('admin/pages.meta_keywords') }}</label>
    <textarea class="form-control" name="metaKeywords" type="text" id="metaKeywords" >{{ isset($page->metaKeywords) ? $page->metaKeywords : ''}}</textarea>
    {!! $errors->first('metaKeywords', '<p class="help-block">:message</p>') !!} 
</div>

<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('admin/common.action_update') :  __('admin/common.action_create') }}">
   <a href="{{ url('/admin/pages') }}" class="btn btn-warning" title="Back"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back </a>
   
   
</div>
