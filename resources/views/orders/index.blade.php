@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 40px;">
    <h2 style="color: var(--dark-blue); margin-bottom: 25px;">My Purchase History</h2>

    @if($orders->isEmpty())
        <div class="product-card" style="text-align: center; padding: 50px;">
            <p style="color: var(--text-muted);">You haven't placed any orders yet.</p>
            <a href="{{ route('home') }}" class="btn" style="width: auto; display: inline-block; margin-top: 20px;">Start Shopping</a>
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
                    <td style="font-weight: bold; color: var(--primary-price);">${{ number_format($order->total_price, 2) }}</td>
                    <td><span class="category-badge">{{ ucfirst($order->status) }}</span></td>
                    <td><a href="{{ route('orders.show', $order->id) }}" style="color: var(--primary-green); text-decoration: none; font-weight: 600;">View Details</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection