<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ __('admin/blogs.title') }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ isset($blog->title) ? $blog->title : ''}}" >
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('shortDescription') ? 'has-error' : ''}}">
    <label for="shortDescription" class="control-label">{{ __('admin/blogs.shortDescription') }}</label>
    <textarea class="form-control" name="shortDescription" type="text" id="shortDescription" >{{ isset($blog->shortDescription) ? $blog->shortDescription : ''}}</textarea>
    {!! $errors->first('shortDescription', '<p class="help-block">:message</p>') !!} 
</div>

 <div class="form-group {{ $errors->has('fullDescription') ? 'has-error' : ''}}">
    <label for="fullDescription" class="control-label">{{ __('admin/blogs.fullDescription') }}</label>
    <textarea class="form-control ckeditor" name="fullDescription" type="text" id="fullDescription" >{{ isset($blog->fullDescription) ? $blog->fullDescription : ''}}</textarea>
    {!! $errors->first('fullDescription', '<p class="help-block">:message</p>') !!} 
</div>
   

<div class="form-group {{ $errors->has('blogImage') ? 'has-error' : ''}}">
   <label for="blogImage" class="control-label">{{ __('admin/blogs.blog_image') }}</label>
   <label class="btn btn-default btn-file">
    Browse <input class="form-control showPic" name="blogImage" id="blogImage" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
   </label>
   <img class="imageShow" src="" alt="" />
   @if(isset($blog->blogImage))
   <img class="sprofilePic" src="{{ config('constants.blog_pull_path').$blog->blogImage}}" alt="" />
   @endif
   {!! $errors->first('blogImage', '<p class="help-block">:message</p>') !!}
</div> 

<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
   <label for="status" class="control-label">{{ __('admin/blogs.blog_status') }}</label>
   <select class="form-control" name="status" id="status">
      <option value="1" @if(!empty($blog))  @if($blog->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/blogs.blog_active') }}</option>
      <option value="0" @if(!empty($blog))  @if($blog->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/blogs.blog_inactive') }}</option>
   </select>   
   {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group {{ $errors->has('author') ? 'has-error' : ''}}">
    <label for="author" class="control-label">{{ __('admin/blogs.blog_author') }}</label>
    <input class="form-control" name="author" type="text" id="author" value="{{ isset($blog->author) ? $blog->author : ''}}" >
    {!! $errors->first('author', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group {{ $errors->has('metaTitle') ? 'has-error' : ''}}">
    <label for="metaTitle" class="control-label">{{ __('admin/blogs.meta_title') }}</label>
    <input class="form-control" name="metaTitle" type="text" id="metaTitle" value="{{ isset($blog->metaTitle) ? $blog->metaTitle : ''}}" >
    {!! $errors->first('metaTitle', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('metaDescription') ? 'has-error' : ''}}">
    <label for="metaDescription" class="control-label">{{ __('admin/blogs.meta_description') }}</label>
    <textarea class="form-control" name="metaDescription" type="text" id="metaDescription" >{{ isset($blog->metaDescription) ? $blog->metaDescription : ''}}</textarea>
    {!! $errors->first('metaDescription', '<p class="help-block">:message</p>') !!} 
</div>

 <div class="form-group {{ $errors->has('metaKeywords') ? 'has-error' : ''}}">
    <label for="metaKeywords" class="control-label">{{ __('admin/blogs.meta_keywords') }}</label>
    <textarea class="form-control" name="metaKeywords" type="text" id="metaKeywords" >{{ isset($blog->metaKeywords) ? $blog->metaKeywords : ''}}</textarea>
    {!! $errors->first('metaKeywords', '<p class="help-block">:message</p>') !!} 
</div>

<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('admin/common.action_update') :  __('admin/common.action_create') }}">
   <a href="{{ url('/admin/blogs') }}" class="btn btn-warning" title="Back"> <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }} </a>
   
   
</div>
