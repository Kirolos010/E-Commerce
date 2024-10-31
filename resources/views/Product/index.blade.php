@extends('layouts.app')

@section('title', 'Product List')

@section('content')
<div class="container mt-4">
    <h1>Product List</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add New Product</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
                <th>Categories</th>
                <th>Current Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->name }}" class="img-fluid" style="width: 100px; height: auto;">
                </td>
                <td>{{ $product->description }}</td>
                <td>
                    @foreach ($product->categories as $category)
                        {{ $category->name }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>
                    @php
                        // Find the price for the current product
                        $currentPrice = $current_price->firstWhere('id', $product->id);
                    @endphp
                    ${{ number_format($currentPrice->price ?? 0, 2) }} 
                </td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                    </form>

                    {{-- //Add to Cart Form --}}
                    <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="quantity" value="1" min="1" style="width: 60px;" class="form-control d-inline" />
                        <button type="submit" class="btn btn-success btn-sm">Add to Cart</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
