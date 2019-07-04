@extends('layouts.servicer.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('servicer.products.store') }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                {{ csrf_field() }}
                <div class="col-md-8">
                    <h2>Product</h2>
                    <!--  <div class="form-group">
                        <label for="sku">SKU <span class="text-danger">*</span></label>
                        <input type="text" name="sku" id="sku" placeholder="xxxxx" class="form-control"
                            value="{{ old('sku') }}">
                    </div> -->
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Name" class="form-control"
                            value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description </label>
                        <textarea class="form-control ckeditor" name="description" id="description" rows="5"
                            placeholder="Description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="cover">Cover <span class="text-danger">*</span></label>
                        <input type="file" name="cover" id="cover" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="image">Images</label>
                        <input type="file" name="image[]" id="image" class="form-control" multiple>
                        <small class="text-warning">You can use ctr (cmd) to select multiple images</small>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                        <input type="text" name="quantity" id="quantity" placeholder="Quantity" class="form-control"
                            value="{{ old('quantity') }}">
                    </div>

                    <div class="form-group">
                        <label for="weight">Price <span class="text-danger">*</span></label>
                        <div class="form-inline">
                            <input type="text" name="price" id="price" placeholder="Price" class="form-control"
                                value="{{ old('price') }}">
                            <!-- <input type="text" class="form-control col-md-8" id="weight" name="weight" placeholder="0" value="{{ number_format($product->weight, 2) }}"> -->
                            <label for="mass_unit" class="sr-only">Mass unit</label>
                            <select name="mass_unit" id="mass_unit" class="form-control col-md-4 select2">
                                @foreach($weight_units as $key => $unit)
                                <option @if($default_weight==$unit) selected="selected" @endif value="{{ $unit }}">
                                    {{ $key }} - ({{ $unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>




                    <div class="form-group">
                        <ul class="list-unstyled attribute-lists">
                            @foreach($attributes as $attribute)
                            <!-- Attribute id 1 is for Sizes/ We are rendering sizes only  -->
                            @if($attribute->id == 1)
                            <li>
                                <label style="pointer-events:none;" for="attribute{{ $attribute->id }}"
                                    class="checkbox-inline">
                                    {{ trim($attribute->name) }} <span class="text-danger">*</span>
                                    <input name="attribute[]" class="attribute" type="checkbox"
                                        id="attribute{{ $attribute->id }}" checked value="{{ $attribute->id }}">
                                </label>

                                <label for="productSizes{{ $attribute->id }}"
                                    style="display: none; visibility: hidden"></label>
                                @if(!$attribute->values->isEmpty())
                                <select name="productSizes[]" id="productSizes{{ $attribute->id }}"
                                    class="form-control select2" multiple style="width: 100%">
                                    @foreach($attribute->values as $attr)
                                    <option value="{{ $attr->id }}">{{ $attr->value }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                    <!-- <div class="form-group">
                        <label for="price">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" name="price" id="price" placeholder="Price" class="form-control"
                                value="{{ old('price') }}">
                        </div>
                    </div> -->

                    @if(!$stores->isEmpty())
                    <div class="form-group">
                        <label for="store_id">Stores </label>
                        <select name="store_id" id="store_id" class="form-control select2">
                            <option value=""></option>
                            @foreach($stores as $store)
                            <option @if(old('store_id')==$store->id) selected="selected" @endif
                                value="{{ $store->id }}">{{ $store->storeTitle }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if(!$brands->isEmpty())
                    <div class="form-group">
                        <label for="brand_id">Brand </label>
                        <select name="brand_id" id="brand_id" class="form-control select2">
                            <option value=""></option>
                            @foreach($brands as $brand)
                            <option @if(old('brand_id')==$brand->id) selected="selected" @endif
                                value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif


                    @include('servicer.shared.status-select', ['status' => 1])

                    <div class="form-group">
                        <br />
                        <label for="delivery_charges">Delivery Charges <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" name="delivery_charges" id="price" placeholder="Delivery Charges"
                                class="form-control" value="{{ old('delivery_charges') }}">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="serviceOfferedType">Product Services Offered <span
                                class="text-danger">*</span></label>
                        <select name="serviceOfferedType" id="serviceOfferedType" class="form-control select2">
                            <option value=""></option>

                            <option @if(old('productType')=='home_delivery' ) selected="selected" @endif
                                value="home_delivery"> Home Delivery </option>

                            <option @if(old('productType')=='pick_up' ) selected="selected" @endif value="pick_up"> Pick
                                up</option>

                        </select>
                    </div>

                    <!-- <div class="form-group">
                            <label for="delivery_charges">Services Offered <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" name="services_offered" id="price" placeholder="Services Offered" class="form-control" value="{{ old('services_offered') }}">
                            </div>
                        </div>-->
                </div>
                <div class="col-md-4 product_categories_r">
                    <h2>Categories</h2>

                    @include('servicer.shared.categories', ['categories' => $categories, 'selectedIds' => []])
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="btn-group">
                    <a href="{{ route('servicer.products.index') }}" class="btn btn-default">Back</a>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection