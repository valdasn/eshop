@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 40px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="margin: 0;">Order #{{ $order->id }}</h2>
        <a href="{{ route('orders.index') }}" class="btn-outline" style="width: auto; padding: 8px 20px;">← Back to My Orders</a>
    </div>

    <div class="product-card" style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
            <div>
                <p style="color: var(--text-muted); margin: 0;">Date Placed</p>
                <strong>{{ $order->created_at->format('F j, Y') }}</strong>
            </div>
            <div style="text-align: right;">
                <p style="color: var(--text-muted); margin: 0;">Status</p>
                <span class="category-badge">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; color: var(--text-muted); font-size: 0.9rem;">
                    <th style="padding: 10px 0;">Product</th>
                    <th style="padding: 10px 0; text-align: center;">Qty</th>
                    <th style="padding: 10px 0; text-align: right;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <td style="padding: 15px 0;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="{{ asset('storage/' . $item->product->image) }}" style="width: 50px; height: 50px; object-fit: contain;">
                            <strong>{{ $item->product->name }}</strong>
                        </div>
                    </td>
                    <td style="padding: 15px 0; text-align: center;">{{ $item->quantity }}</td>
                    <td style="padding: 15px 0; text-align: right;">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="padding: 20px 0; text-align: right; font-weight: bold;">Order Total:</td>
                    <td style="padding: 20px 0; text-align: right; font-size: 1.4rem; font-weight: 800; color: var(--primary-price);">
                        ${{ number_format($order->total_price, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection