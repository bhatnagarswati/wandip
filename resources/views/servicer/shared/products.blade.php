
<table class="table table-bordered table-striped">
   <thead>
      <tr>
	 <td>#</td>
	 <td>Name</td>
	 <td>Quantity</td>
	 <td>Price</td>
	 <td>Status</td>
	 <td>Actions</td>
      </tr>
   </thead>
   <tbody>
      @foreach ($products as $product)
      <tr>
	 <td>{{ $loop->iteration  }}</td>
	 <td>

	    <a href="{{ route('servicer.products.show', $product->id) }}">{{ $product->name }}</a>


	 </td>
	 <td>{{ $product->quantity }}</td>
	 <td>{{ config('cart.currency') }} {{ $product->price }}</td>
	 <td>@include('layouts.status', ['status' => $product->status])</td>
	 <td>
	    <form action="{{ route('servicer.products.destroy', $product->id) }}" method="post" class="form-horizontal">
	       {{ csrf_field() }}
	       <input type="hidden" name="_method" value="delete">
	       <div class="btn-group">
		  <a href="{{ route('servicer.products.edit', $product->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a> 
		  <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button> 
	       </div>
	    </form>
	 </td>
      </tr>
      @endforeach
   </tbody>
</table>
