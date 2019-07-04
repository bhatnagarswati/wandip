@if(!$stores->isEmpty())
<div class="form-group">
   <label for="store_id">Stores </label>
   <select name="storeId" id="storeId" class="form-control select2">
      <option value=""></option>
      @foreach($stores as $store)
      <option @if(@$pump->storeId  == $store->id) selected="selected" @endif value="{{ $store->id }}">{{ $store->storeTitle }}</option>
      @endforeach
   </select>
</div>

<div class="form-group {{ $errors->has('pumpTitle') ? 'has-error' : ''}}">
   <label for="pumpTitle" class="control-label">{{ __('admin/pumps.pumpTitle') }}</label>
   <input class="form-control" name="pumpTitle" type="text" id="pumpTitle" value="{{ isset($pump->pumpTitle) ? $pump->pumpTitle : ''}}" >
   {!! $errors->first('pumpTitle', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group {{ $errors->has('pumpDescription') ? 'has-error' : ''}}">
   <label for="pumpDescription" class="control-label">{{ __('admin/pumps.pumpDescription') }}</label>
   <textarea class="form-control" name="pumpDescription" type="text" id="pumpDescription" >{{ isset($pump->pumpDescription) ? $pump->pumpDescription : ''}}</textarea>
   {!! $errors->first('pumpDescription', '
   <p class="help-block">:message</p>
   ') !!} 
</div>
<div class="form-group {{ $errors->has('pumpAddress') ? 'has-error' : ''}}">
   <label for="pumpAddress" class="control-label">{{ __('admin/pumps.pumpAddress') }}</label>
   <input class="form-control autocomplete" name="pumpAddress" type="text" id="pumpAddress"
        value="{{ isset($pump->pumpAddress) ? $pump->pumpAddress : ''}}">
   <input name="locationLat" type="hidden" id="locationLat" value="{{ isset($pump->pumpLat) ? $pump->pumpLat : ''}}" >
    <input name="locationLong" type="hidden" id="locationLong" value="{{ isset($pump->pumpLong) ? $pump->pumpLong : ''}}" >
    
   {!! $errors->first('pumpLocation', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group {{ $errors->has('pumpPrice') ? 'has-error' : ''}}">
   <label for="pumpPrice" class="control-label">{{ __('admin/pumps.pumpPrice') }}</label>
   <input class="form-control" name="pumpPrice" type="text" value="{{ isset($pump->pumpPrice) ? $pump->pumpPrice : ''}}"  id="pumpPrice"></textarea>
   {!! $errors->first('pumpPrice', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group {{ $errors->has('pumpPic') ? 'has-error' : ''}}">
   <label for="pumpPic" class="control-label">{{ __('admin/pumps.pumpPic') }}</label>
   <label class="btn btn-default btn-file">
   Browse <input class="form-control showPic" name="pumpPic" id="pumpPic" type="file" style="display: none;"  accept="image/jpg, image/jpeg,image/png">
   </label>
   <img class="imageShow" src="" alt="" />
   @if(isset($pump->pumpPic))
   @if($pump->pumpPic != "")
   <img class="sprofilePic" src="{{ config('constants.pump_pull_path').$pump->pumpPic}}" alt="" />
   @endif
   @endif
   {!! $errors->first('pumpPic', '
   <p class="help-block">:message</p>
   ') !!}
</div>

<div class="form-group {{ $errors->has('pumpMassUnit') ? 'has-error' : ''}}">
   <label for="pumpMassUnit" class="control-label">{{ __('admin/pumps.pump_unit') }}</label>
   <select class="form-control" name="pumpMassUnit" id="pumpMassUnit">
   @foreach($mass_units as $unit => $value)
   <option value="{{$value}} " @if(!empty($pump))  @if($pump->pumpMassUnit == $value) {{ "selected"}} @endif @endif > {{$unit}} {{ "(". $value .")" }}</option>
   @endforeach
  
   </select>
   {!! $errors->first('pumpMassUnit', '
   <p class="help-block">:message</p>
   ') !!}
</div>


<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
   <label for="isActive" class="control-label">{{ __('admin/pumps.pump_status') }}</label>
   <select class="form-control" name="status" id="status">
   <option value="1" @if(!empty($pump))  @if($pump->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/pumps.pump_active') }}</option>
   <option value="0" @if(!empty($pump))  @if($pump->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/pumps.pump_inactive') }}</option>
   </select>
   {!! $errors->first('status', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('admin/common.action_update') :  __('admin/common.action_create') }}">
   <a href="{{ url('/servicer/pumps') }}" class="btn btn-warning" title="Back"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back </a>
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

@else

<p>Please add store first.</p>

@endif

