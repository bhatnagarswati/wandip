
<div class="card-content">
     <div class="form-group">
        <label for="fname">{{ __('admin/routers.routeLocations') }}</label>
        <div class="input_fields_wrap">
            
             @if(@$routes) 
             @foreach($routes->routeInfo as $key => $infoitem)    
              @if( $loop->iteration == 1)
                <div style="margin-bottom:10px;">
                    <input type="text" value="{{ $infoitem->location }}" required="requierd" class="form-control autocomplete required" id="locations" name="locations[{{$key}}][location]">
                 
                   <input type="hidden" value="{{ $infoitem->id }}" name="locations[{{$key}}][id]">
                   <input type="hidden" value="{{ $infoitem->city }}" name="locations[{{$key}}][city]">
                   <input type="hidden" value="{{ $infoitem->locationLat }}" name="locations[{{$key}}][locationLat]">
                   <input type="hidden" value="{{ $infoitem->locationLong }}" name="locations[{{$key}}][locationLong]">

                </div>
                @else
                <div>
                <input type="text" value="{{ $infoitem->location }}" required="requierd" class="form-control autocomplete required" id="locations" name="locations[{{$key}}][location]">
                 
                   <input type="hidden" value="{{ $infoitem->id }}" name="locations[{{$key}}][id]">
                   <input type="hidden" value="{{ $infoitem->city }}" name="locations[{{$key}}][city]">
                   <input type="hidden" value="{{ $infoitem->locationLat }}" name="locations[{{$key}}][locationLat]">
                   <input type="hidden"  value="{{ $infoitem->locationLong }}" name="locations[{{$key}}][locationLong]">
                   <a href="#" class="remove_field">Remove</a>
                </div>

                @endif
             @endforeach   
              <input type="hidden" id="keyIncrement" value="{{count($routes->routeInfo)}}">
             @else
             <div style="margin-bottom:10px;">
              <input type="text" value=""  required="required"  class="form-control autocomplete required" id="locations" name="locations[0][location]">
                <input type="hidden" value="" name="locations[0][city]">
                <input type="hidden" value="" name="locations[0][locationLat]">
                <input type="hidden" value="" name="locations[0][locationLong]">
              </div>
              <input type="hidden" id="keyIncrement" value="0">
             @endif
            </div>
            <button type="button" class="add_field_button">{{ __('admin/routers.routeMoreLocations') }}</button>
        <p></p>
    </div>
</div>


<div class="form-group {{ $errors->has('deliveryDate') ? 'has-error' : ''}}">
    <label for="driverEmail" class="control-label">{{ __('admin/routers.routeDate') }}</label>
    <input class="form-control datepicker" name="deliveryDate" type="text" autocomplete="off"  id="deliveryDate" value="{{ isset($routes->deliveryDate) ? $routes->deliveryDate->format('Y-m-d') : ''}}" >
    {!! $errors->first('driverEmail', '
    <p class="help-block">:message</p>
    ') !!}
</div>
  <div class="col-md-6 " style="padding-left:0px;"> 
<div class="form-group {{ $errors->has('departureTime') ? 'has-error' : ''}}">
    <label for="departureTime" class="control-label">{{ __('admin/routers.routeDeparture') }}</label>
    <input class="form-control timepicker" name="departureTime" type="text" autocomplete="off"  id="departureTime" value="{{ isset($routes->departureTime) ? $routes->departureTime : ''}}" >
    {!! $errors->first('departureTime', '
    <p class="help-block">:message</p>
    ') !!}
</div>
</div>
<!-- <div class="col-md-6"> 
    <div class="form-group {{ $errors->has('arrivalTime') ? 'has-error' : ''}}">
        <label for="arrivalTime" class="control-label">{{ __('admin/routers.routeArrival') }}</label>
        <input class="form-control timepicker" name="arrivalTime" autocomplete="off" type="text" id="arrivalTime" value="{{ isset($routes->arrivalTime) ? $routes->arrivalTime : ''}}" >
        {!! $errors->first('arrivalTime', '
        <p class="help-block">:message</p>
        ') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('timeNote') ? 'has-error' : ''}}">
    <label for="timeNote" class="control-label">{{ __('admin/routers.timeNote') }}</label>
    <input class="form-control" name="timeNote" type="text" autocomplete="off"  id="timeNote" value="{{ isset($routes->timeNote) ? $routes->timeNote : ''}}" >
    {!! $errors->first('timeNote', '
    <p class="help-block">:message</p>
    ') !!}
</div> -->
<div class="clearfix"></div>
<div class="form-group {{ $errors->has('volumeContained') ? 'has-error' : ''}}">
    <label for="volumeContained" class="control-label">{{ __('admin/routers.volumeContained') }}</label>
    <input class="form-control" name="volumeContained" type="text" autocomplete="off"  id="volumeContained" value="{{ isset($routes->volumeContained) ? $routes->volumeContained : ''}}" >
    {!! $errors->first('volumeContained', '
    <p class="help-block">:message</p>
    ') !!}
</div>
<div class="form-group {{ $errors->has('price') ? 'has-error' : ''}}">
     <label for="address" class="control-label">{{ __('admin/routers.price') }}</label>
    <div class="clearfix"></div>
    <div class="col-md-7" style="padding-left:0px;"> 
     <input class="form-control" name="price" type="text" id="price" autocomplete="off"  value="{{ isset($routes->price) ? $routes->price : ''}}">
    </div>  
    <div class="col-md-5">
       <select name="mass_unit" id="mass_unit" class="form-control col-md-4 select2">
            @foreach($weight_units as $key => $unit)
                <option @if(@$routes->priceUnit  == $unit) selected="selected" @endif value="{{ $unit }}">per ({{ $unit }}) </option>
            @endforeach
      </select>
    </div>  
    {!! $errors->first('price', '
    <p class="help-block">:message</p>
    ') !!} 
</div>

<div class="form-group {{ $errors->has('notifyUsers') ? 'has-error' : ''}}">
    <label for="notifyUsers" class="control-label">{{ __('admin/routers.notifyUser') }}</label>
    <input class="form-control" name="notifyUsers" type="text" autocomplete="off"  id="notifyUsers" value="{{ isset($routes->notifyUsers) ? $routes->notifyUsers : ''}}" >
    {!! $errors->first('notifyUsers', '
    <p class="help-block">:message</p>
    ') !!}
</div>

<div class="form-group {{ $errors->has('driverId') ? 'has-error' : ''}}">
    <label for="driverId" class="control-label">{{ __('admin/routers.routeAssignDriver') }}</label>
    <select name="driverId" id="driverId" class="form-control select2">
            @foreach($drivers as $key => $driver)
                <option @if(@$routes->driverId  == $driver->driverId) selected="selected" @endif value="{{ $driver->driverId }}">{{ $driver->firstName }} {{ $driver->lastName }} </option>
            @endforeach
    </select>  
    {!! $errors->first('driverId', '
    <p class="help-block">:message</p>
    ') !!}
</div>

<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ __('admin/routers.route_status') }}</label>
    <select class="form-control" name="status" id="status">
	<option value="1" @if(!empty($routes))  @if($routes->status == 1) {{ "selected"}} @endif @endif >{{ __('admin/routers.route_active') }}</option>
	<option value="0" @if(!empty($routes))  @if($routes->status == 0) {{ "selected"}} @endif @endif >{{ __('admin/routers.route_inactive') }}</option>
    </select>
    {!! $errors->first('status', '
    <p class="help-block">:message</p>
    ') !!}
</div>
<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('admin/common.action_update') :  __('admin/common.action_create') }}">
    <a href="{{ url('/servicer/routers') }}" class="btn btn-warning" title="Back"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back </a>
</div>
