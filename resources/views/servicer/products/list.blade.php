@extends('layouts.servicer.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Products</h2>
                    {{-- @include('layouts.search', ['route' => route('servicer.products.index')]) --}}
                    @include('servicer.shared.products')
                    {{ $products->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
       

    </section>
    <!-- /.content -->
@endsection
