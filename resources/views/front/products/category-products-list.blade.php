@if(!empty($products) && !collect($products)->isEmpty())

@foreach($products as $product)


<div class="col-md-3">
    <div class="our_project_bx">
        <figure class="product_figure"><a href="{{ route('front.get.product', str_slug($product->slug)) }}">

                @if(isset($product->cover))
                <img src="{{ asset("storage/app/public/$product->cover") }}" alt="{{ $product->name }}"
                    class="img-bordered img-responsive">
                @else
                <img src="https://placehold.it/263x330" alt="{{ $product->name }}"
                    class="img-bordered img-responsive" />
                @endif


            </a>
        </figure>
        <div class="item_content">
            <h3><a href="{{ route('front.get.product', str_slug($product->slug)) }}">{{ $product->name }} </a></h3>
            <h4>{{ config('cart.currency') }}
                @if(!is_null($product->attributes->where('default', 1)->first()))
                @if(!is_null($product->attributes->where('default', 1)->first()->sale_price))
                {{ number_format($product->attributes->where('default', 1)->first()->sale_price, 2) }}
                <p class="text text-danger">{{ __('common.listproduct_sale') }}!</p>
                @else
                {{ number_format($product->attributes->where('default', 1)->first()->price, 2) }}
                @endif
                @else
                {{ number_format($product->price, 2) }}
                @endif</h4>
            <a class="btn blue-btn creative_btn" href="{{ route('front.get.product', str_slug($product->slug)) }}"> <i
                    class="fa fa-cart-plus"></i> {{ __('common.listproduct_addtocart') }} </a>
            <!--  <form action="{{ route('cart.store') }}" class="form-inline" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="quantity" value="1" />
                        <input type="hidden" name="product" value="{{ $product->id }}">
                        <button id="add-to-cart-btn" type="submit" class="btn blue-btn creative_btn" data-toggle="modal" data-target="#cart-modal"> <i class="fa fa-cart-plus"></i> Add to cart</button>
                    </form> -->
        </div>
    </div>
</div>




@endforeach
@if($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
<div class="row">
    <div class="col-md-12">
        <div class="pull-left">{{ $products->links() }}</div>
    </div>
</div>
@endif

@else
<p class="alert alert-warning">{{ __('common.listproduct_noproduct') }}</p>
@endif