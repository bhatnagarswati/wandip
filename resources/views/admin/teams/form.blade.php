<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ __('admin/teams.name') }}</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ isset($team->name) ? $team->name : ''}}" >
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="shortDescription" class="control-label">{{ __('admin/teams.description') }}</label>
    <textarea class="form-control" name="description" type="text" id="shortDescription" >{{ isset($team->description) ? $team->description : ''}}</textarea>
    {!! $errors->first('shortDescription', '<p class="help-block">:message</p>') !!} 
</div>


<div class="form-group {{ $errors->has('teamImage') ? 'has-error' : ''}}">
   <label for="blogImage" class="control-label">{{ __('admin/teams.image') }}</label>
   <label class="btn btn-default btn-file">
    Browse <input class="form-control showPic" name="teamImage" id="image" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
   </label>
   <img class="imageShow" src="" alt="" />
   @if(isset($team->image))
   <img class="sprofilePic" src="{{ config('constants.team_pull_path').$team->image}}" alt="" />
   @endif
   {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div> 

<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
   <label for="status" class="control-label">{{ __('admin/teams.team_status') }}</label>
   <select class="form-control" name="status" id="status">
      <option value="1" @if(!empty($team))  @if($team->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/teams.team_active') }}</option>
      <option value="0" @if(!empty($team))  @if($team->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/teams.team_inactive') }}</option>
   </select>   
   {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>



<div class="form-group {{ $errors->has('facebook_link') ? 'has-error' : ''}}">
    <label for="metaTitle" class="control-label">{{ __('admin/teams.facebook_link') }}</label>
    <input class="form-control" name="facebook_link" type="text" id="facebook_link" value="{{ isset($team->facebook_link) ? $team->facebook_link : ''}}" >
    {!! $errors->first('facebook_link', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('twitter_link') ? 'has-error' : ''}}">
    <label for="twitter_link" class="control-label">{{ __('admin/teams.twitter_link') }}</label>
    <input class="form-control" name="twitter_link" type="text" id="twitter_link" value="{{ isset($team->twitter_link) ? $team->twitter_link : ''}}">
    {!! $errors->first('twitter_link', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('linkedin_link') ? 'has-error' : ''}}">
    <label for="linkedin_link" class="control-label">{{ __('admin/teams.linkedin_link') }}</label>
    <input class="form-control" name="linkedin_link" type="text" id="linkedin_link" value="{{ isset($team->linkedin_link) ? $team->linkedin_link : ''}}">
    {!! $errors->first('linkedin_link', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group {{ $errors->has('metaTitle') ? 'has-error' : ''}}">
    <label for="metaTitle" class="control-label">{{ __('admin/teams.meta_title') }}</label>
    <input class="form-control" name="metaTitle" type="text" id="metaTitle" value="{{ isset($team->metaTitle) ? $team->metaTitle : ''}}" >
    {!! $errors->first('metaTitle', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('metaDescription') ? 'has-error' : ''}}">
    <label for="metaDescription" class="control-label">{{ __('admin/teams.meta_description') }}</label>
    <textarea class="form-control" name="metaDescription" type="text" id="metaDescription" >{{ isset($team->metaDescription) ? $team->metaDescription : ''}}</textarea>
    {!! $errors->first('metaDescription', '<p class="help-block">:message</p>') !!} 
</div>

 <div class="form-group {{ $errors->has('metaKeywords') ? 'has-error' : ''}}">
    <label for="metaKeywords" class="control-label">{{ __('admin/teams.meta_keywords') }}</label>
    <textarea class="form-control" name="metaKeywords" type="text" id="metaKeywords" >{{ isset($team->metaKeywords) ? $team->metaKeywords : ''}}</textarea>
    {!! $errors->first('metaKeywords', '<p class="help-block">:message</p>') !!} 
</div>

<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('admin/common.action_update') :  __('admin/common.action_create') }}">
   <a href="{{ url('/admin/teams') }}" class="btn btn-warning" title="Back"> <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }} </a>
   
   
</div>
