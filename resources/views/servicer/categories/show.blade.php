@extends('layouts.servicer.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($category)
            <div class="box">
                <div class="box-body">
                    <h2>Category</h2>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td class="col-md-4">Name</td>
                            <td class="col-md-4">Description</td>
                            <td class="col-md-4">Cover</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description }}</td>
                                <td>
                                    @if(isset($category->cover))
                                        <img src="{{asset("storage/$category->cover")}}" alt="category image" class="img-thumbnail">
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if(!$categories->isEmpty())
                <hr>
                    <div class="box-body">
                        <h2>Sub Categories</h2>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <td class="col-md-3">Name</td>
                                <td class="col-md-3">Description</td>
                                <td class="col-md-3">Cover</td>
                                <td class="col-md-3">Actions</td>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $cat)
                                    <tr>
                                        <td><a href="{{route('servicer.categories.show', $cat->id)}}">{{ $cat->name }}</a></td>
                                        <td>{{ $cat->description }}</td>
                                        <td>@if(isset($cat->cover))<img src="{{asset("storage/$cat->cover")}}" alt="category image" class="img-thumbnail">@endif</td>
                                        <td><a class="btn btn-primary" href="{{route('servicer.categories.edit', $cat->id)}}"><i class="fa fa-edit"></i> Edit</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(!$products->isEmpty())
                    <div class="box-body">
                        <h2>Products</h2>
                        @include('servicer.shared.products', ['products' => $products])
                    </div>
                @endif
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('servicer.categories.index') }}" class="btn btn-default btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
