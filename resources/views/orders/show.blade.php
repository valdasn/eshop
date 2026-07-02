@extends('layouts.app')

@section('content')
<div class="container order-details-page">
    <div class="order-details-header">
        <h2 class="order-details-heading">Order #{{ $order->id }}</h2>
        <a href="{{ route('orders.index') }}" class="btn-outline order-details-back-link">← Back to My Orders</a>
    </div>

    <div class="product-card order-details-card">
        <div class="order-details-meta">
            <div class="order-details-meta-item">
                <p class="order-details-meta-label">Date Placed</p>
                <strong>{{ $order->created_at->format('F j, Y') }}</strong>
            </div>
            <div class="order-details-meta-item order-details-meta-item--right">
                <p class="order-details-meta-label">Status</p>
                <span class="category-badge">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        <table class="order-details-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="qty-col">Qty</th>
                    <th class="price-col">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="order-details-product">
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                            <strong>{{ $item->product->name }}</strong>
                        </div>
                    </td>
                    <td class="qty-col">{{ $item->quantity }}</td>
                    <td class="price-col">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="order-details-total-row">
                    <td colspan="2" class="order-details-total-label">Order Total:</td>
                    <td class="order-details-total-value">${{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection