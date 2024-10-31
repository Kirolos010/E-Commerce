@extends('layouts.app')

@section('title', 'Edit Price')

@section('content')
<div class="container mt-4">
    <h1>Edit Price</h1>

    <form action="{{ route('prices.update', $price->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select class="form-select" id="product_id" name="product_id" required>
                <option value="" disabled>Select a product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $product->id == $price->product_id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $price->price }}" required>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $price->start_date }}" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $price->end_date }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Price</button>
        <a href="{{ route('prices.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
