<div class="form-group {{ $errors->has('driverEmail') ? 'has-error' : ''}}">
    <label for="driverEmail" class="control-label">{{ __('admin/drivers.driverEmailId') }}</label>
    <input class="form-control" disabled="disabled" name="driverEmail" type="email" id="driverEmail" value="{{ isset($driver->driverEmail) ? $driver->driverEmail : ''}}" >
    {!! $errors->first('driverEmail', '
    <p class="help-block">:message</p>
    ') !!}
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
    <label for="password" class="control-label">{{ __('admin/drivers.driverPassword') }}</label>
    <input class="form-control" name="password" type="password" id="password" value="{{ isset($driver->password) ? $driver->driverPassword : ''}}" >
    {!! $errors->first('password', '
    <p class="help-block">:message</p>
    ') !!}
</div>
<div class="form-group {{ $errors->has('confirmPassword') ? 'has-error' : ''}}">
    <label for="confirmPassword" class="control-label">{{ __('admin/drivers.driverConfirmPassword') }}</label>
    <input class="form-control" name="confirmPassword" type="password" id="confirmPassword">
    {!! $errors->first('confirmPassword', '
    <p class="help-block">:message</p>
    ') !!}
</div>
<div class="form-group {{ $errors->has('firstName') ? 'has-error' : ''}}">
    <label for="firstName" class="control-label">{{ __('admin/drivers.driverFirstName') }}</label>
    <input class="form-control" name="firstName" type="text" id="firstName" value="{{ isset($driver->firstName) ? $driver->firstName : ''}}" >
    {!! $errors->first('firstName', '
    <p class="help-block">:message</p>
    ') !!}
</div>
<div class="form-group {{ $errors->has('lastName') ? 'has-error' : ''}}">
    <label for="lastName" class="control-label">{{ __('admin/drivers.driverLastName') }}</label>
    <input class="form-control" name="lastName" type="text" id="lastName" value="{{ isset($driver->lastName) ? $driver->lastName : ''}}" >
    {!! $errors->first('lastName', '
    <p class="help-block">:message</p>
    ') !!}
</div>

<div class="form-group {{ $errors->has('contactNumber') ? 'has-error' : ''}}">
    <label for="driverContactNo" class="control-label">{{ __('admin/drivers.driverContactNo') }}</label>
    <input class="form-control" name="contactNumber" type="text" id="contactNumber" value="{{ isset($driver->contactNumber) ? $driver->contactNumber : ''}}" >
    {!! $errors->first('contactNumber', '
    <p class="help-block">:message</p>
    ') !!}
</div>


<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    <label for="address" class="control-label">{{ __('admin/drivers.driverAddress') }}</label>
    <input class="form-control autocomplete" name="address" id="address" value="{{ isset($driver->address) ? $driver->address : ''}}" > 
    {!! $errors->first('driverAddress', '
    <p class="help-block">:message</p>
    ') !!} 
    <input name="driverLat" type="hidden" id="locationLat" value="{{ isset($driver->driverLat) ? $driver->driverLat : ''}}" >
    <input name="driverLong" type="hidden" id="locationLong" value="{{ isset($driver->driverLong) ? $driver->driverLong : ''}}" >
</div>

<div class="form-group {{ $errors->has('licenceNumber') ? 'has-error' : ''}}">
    <label for="licenceNumber" class="control-label">{{ __('admin/drivers.driverLicence') }}</label>
    <input class="form-control" name="licenceNumber" type="text" id="licenceNumber" value="{{ isset($driver->licenceNumber) ? $driver->licenceNumber : ''}}" >
    {!! $errors->first('licenceNumber', '
    <p class="help-block">:message</p>
    ') !!}
</div>


<div class="form-group {{ $errors->has('driverPic') ? 'has-error' : ''}}">
    <label for="driverPic" class="control-label">{{ __('admin/drivers.driverAddPhoto') }}</label>
    <label class="btn btn-default btn-file">
	Browse <input class="form-control showPic " name="driverPic" id="driverPic" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
    </label>
    @php $driverpic = "" @endphp
    @if(isset($driver->driverPic))
    @if($driver->driverPic != "")
    @php $driverpic = config('constants.driver_pull_path').$driver->driverPic; @endphp
    @endif
    @endif
    <img class="imageShow" src="{{ $driverpic }}" alt="" />

    {!! $errors->first('driverPic', '
    <p class="help-block">:message</p>
    ') !!}
</div>

<div class="form-group {{ $errors->has('idProof') ? 'has-error' : ''}}">
    <label for="idProof" class="control-label">{{ __('admin/drivers.driverIdProof') }}</label>
    <label class="btn btn-default btn-file">
	Browse <input class="form-control showPic showPic_proof" name="idProof" id="idProof" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
    </label>
    @php $idproof = ""; @endphp
    @if(isset($driver->idProof))
    @if($driver->idProof != "")
    @php  $idproof = config('constants.driver_id_proof_pull_path').$driver->idProof; @endphp
    @endif
    @endif

    <img class="imageShow" src="{{ $idproof }}" alt="" />

      {!! $errors->first('driverPic', '
   <p class="help-block">:message</p>
    ') !!}
</div>



<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ __('admin/drivers.driver_status') }}</label>
    <select class="form-control" name="status" id="status">
	<option value="1" @if(!empty($driver))  @if($driver->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/drivers.driver_active') }}</option>
	<option value="0" @if(!empty($driver))  @if($driver->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/drivers.driver_inactive') }}</option>
    </select>
    {!! $errors->first('status', '
    <p class="help-block">:message</p>
    ') !!}
</div>
<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('admin/common.action_update') :  __('admin/common.action_create') }}">
    <a href="{{ url('/admin/drivers') }}" class="btn btn-warning" title="{{  __('admin/common.action_back') }}"> <i class="fa fa-arrow-left" aria-hidden="true"></i> {{  __('admin/common.action_back') }} </a>
</div>
@section('js')
<script>
function initialize() {
    var elements = document.body.querySelectorAll(".autocomplete")
    for (var i = 0, element; element = elements[i++];) {
        var autocomplete = new google.maps.places.Autocomplete(element);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            let cityLat = place.geometry.location.lat();
            let cityLong = place.geometry.location.lng();
            $('#locationLat').val(cityLat);
            $('#locationLong').val(cityLong);
        });
    }
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection