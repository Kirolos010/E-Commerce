@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<div class="container mt-4">
    <h1>Your Cart</h1>

    @if(session('cart') && count(session('cart')) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('cart') as $id => $details)
                    <tr>
                        <td>{{ $details['name'] }}</td>
                        <td>{{ $details['quantity'] }}</td>
                        <td>${{ number_format($details['price'], 2) }}</td>
                        <td>${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                    <td>
                        ${{ number_format(array_sum(array_column(session('cart'), 'price')) * array_sum(array_column(session('cart'), 'quantity')), 2) }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Checkout Button -->
        {{-- <a href="{{ route('checkout') }}" class="btn btn-success mt-3">Proceed to Checkout</a> --}}
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
@endsection
