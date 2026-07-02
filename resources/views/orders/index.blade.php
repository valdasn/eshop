@extends('layouts.app')

@section('content')
<div class="container orders-page">
    <h2 class="orders-title">My Purchase History</h2>

    @if($orders->isEmpty())
        <div class="product-card orders-empty-state">
            <p class="orders-empty-copy">You haven't placed any orders yet.</p>
            <a href="{{ route('home') }}" class="btn btn-auto">Start Shopping</a>
        </div>
    @else
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="orders-total-cell">${{ number_format($order->total_price, 2) }}</td>
                    <td><span class="category-badge">{{ ucfirst($order->status) }}</span></td>
                    <td><a href="{{ route('orders.show', $order->id) }}" class="orders-link">View Details</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection