<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
   <label for="name" class="control-label">{{ __('admin/servicers.name') }}</label>
   <input class="form-control" name="name" type="text" id="name" value="{{ @$servicer->name }}" >
   {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
   <label for="email" class="control-label">{{ __('admin/servicers.email') }}</label>
   <input class="form-control" name="email" type="text" id="email" value="{{ @$servicer->email}}" >
   {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
   <label for="phone" class="control-label">{{ __('admin/servicers.phone') }}</label>
   <input class="form-control" name="phone" type="text" id="phone" value="{{ @$servicer->phone }}" >
   {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
</div> 
<div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
   <label for="password" class="control-label">{{ __('admin/servicers.password') }}</label>
   <input class="form-control" name="password" type="password" id="password" >
   {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
   <label for="password_confirmation" class="control-label">{{ __('admin/servicers.password_confirmation') }}
   </label>
   <input class="form-control" name="password_confirmation" type="password" id="password_confirmation">
   {!! $errors->first('password_confirmation ', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('profilePic') ? 'has-error' : ''}}">
   <label for="profilePic" class="control-label">{{ __('admin/servicers.profile_pic') }}</label>

   <label class="btn btn-default btn-file">
      Browse <input class="form-control showPic" name="profilePic" id="profilePic" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
   </label>

   <img class="imageShow" src="" alt="" />
   @if(isset($servicer->profilePic))
   <img class="sprofilePic" src="{{ config('constants.service_provider_pull_path').$servicer->profilePic}}" alt="" />
   @endif

   {!! $errors->first('profilePic', '<p class="help-block">:message</p>') !!}
</div>

@if($formMode === 'edit')

<div class="form-group {{ $errors->has('deviceType') ? 'has-error' : ''}}">
   <label for="deviceType" class="control-label">{{ __('admin/servicers.device_type') }}</label>
   <input class="form-control" name="deviceType" type="text" id="deviceType" value="{{ @$servicer->deviceType }}" >
   {!! $errors->first('deviceType', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('deviceToken') ? 'has-error' : ''}}">
   <label for="deviceToken" class="control-label">{{ __('admin/servicers.device_token') }}</label>
   <input class="form-control" name="deviceToken" type="text" id="deviceToken" value="{{ @$servicer->deviceToken }}" >
   {!! $errors->first('deviceToken', '<p class="help-block">:message</p>') !!}
</div>

@endif

<div class="form-group {{ $errors->has('isActive') ? 'has-error' : ''}}">
   <label for="isActive" class="control-label">{{ __('admin/servicers.status') }}</label>

   <select class="form-control" name="isActive" id="isActive">
      <option value="0" @if(!empty($servicer))  @if($servicer->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/servicers.status_pending') }}</option>
      <option value="1" @if(!empty($servicer))  @if($servicer->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/servicers.status_approved') }}</option>
   </select>

   {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
   <input type="hidden" name="role_id" value="2">
   <a href="{{ route('admin.servicers.index') }}" class="btn btn-default">{{ __('admin/common.action_back') }}</a>
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ?   __('admin/common.action_update')  :  __('admin/common.action_create') }}">
</div>

