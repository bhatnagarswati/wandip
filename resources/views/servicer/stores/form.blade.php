<div class="form-group {{ $errors->has('storeTitle') ? 'has-error' : ''}}">
    <label for="storeTitle" class="control-label">{{ __('admin/stores.storeTitle') }}</label>
    <input class="form-control" name="storeTitle" type="text" id="storeTitle" value="{{ isset($store->storeTitle) ? $store->storeTitle : ''}}" >
    {!! $errors->first('storeTitle', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('storeDescription') ? 'has-error' : ''}}">
    <label for="storeDescription" class="control-label">{{ __('admin/stores.storeDescription') }}</label>
    <textarea class="form-control" name="storeDescription" type="text" id="storeDescription" >{{ isset($store->storeDescription) ? $store->storeDescription : ''}}</textarea>
    {!! $errors->first('storeDescription', '<p class="help-block">:message</p>') !!} 
</div>
<div class="form-group {{ $errors->has('storeLocation') ? 'has-error' : ''}}">
    <label for="storeLocation" class="control-label">{{ __('admin/stores.storeLocation') }}</label>
    <input class="form-control autocomplete" name="storeLocation" type="text" id="storeLocation" value="{{ isset($store->storeLocation) ? $store->storeLocation : ''}}">
    {!! $errors->first('storeLocation', '<p class="help-block">:message</p>') !!}
    <input name="locationLat" type="hidden" id="locationLat" value="{{ isset($store->storeLat) ? $store->storeLat : ''}}" >
    <input name="locationLong" type="hidden" id="locationLong" value="{{ isset($store->storeLong) ? $store->storeLong : ''}}" >

</div>

 <div class="form-group {{ $errors->has('storePic') ? 'has-error' : ''}}">
   <label for="storePic" class="control-label">{{ __('admin/stores.storePic') }}</label>
   
   <label class="btn btn-default btn-file">
    Browse <input class="form-control showPic" name="storePic" id="storePic" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
   </label>
   
   <img class="imageShow" src="" alt="" />
   @if(isset($store->storePic))
   <img class="sprofilePic" src="{{ config('constants.store_pull_path').$store->storePic}}" alt="" />
   @endif
   
   {!! $errors->first('storePic', '<p class="help-block">:message</p>') !!}
</div> 

<div class="form-group {{ $errors->has('isActive') ? 'has-error' : ''}}">
   <label for="isActive" class="control-label">{{ __('admin/stores.store_status') }}</label>
   
   <select class="form-control" name="isActive" id="isActive">
      <option value="1" @if(!empty($store))  @if($store->isActive == 1) {{ "selected"}} @endif @endif >{{ __('admin/stores.store_active') }}</option>
      <option value="0" @if(!empty($store))  @if($store->isActive == 0) {{ "selected"}} @endif @endif >{{ __('admin/stores.store_inactive') }}</option>
      
   </select>
   
   {!! $errors->first('isActive', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('admin/common.action_update') :  __('admin/common.action_create') }}">
   <a href="{{ url('/servicer/stores') }}" class="btn btn-warning" title="Back"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back </a>
   
   
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