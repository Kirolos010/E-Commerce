@extends('layouts.app')

@section('title', 'Price List')

@section('content')
<div class="container mt-4">
    <h1>Price List</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('prices.create') }}" class="btn btn-primary mb-3">Add New Price</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prices as $price)
                <tr>
                    <td>{{ $price->id }}</td>
                    <td>{{ $price->product->name ?? 'No product found' }}</td>
                    <td>${{ number_format($price->price, 2) }}</td>
                    <td>{{ $price->start_date }}</td>
                    <td>{{ $price->end_date }}</td>
                    <td>
                        <a href="{{ route('prices.edit', $price->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('prices.destroy', $price->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this price?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
