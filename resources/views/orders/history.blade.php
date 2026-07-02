@extends('layouts.app')

@section('content')
<div class="container orders-page">
    <h2 class="orders-title">Your Order History</h2>

    @if($orders->isEmpty())
        <div class="auth-container orders-empty-state">
            <p class="orders-empty-copy">You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-auto">Start Shopping</a>
        </div>
    @else
        <div class="auth-container orders-table-wrapper">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>City</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><strong>{{ $order->id }}</strong></td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ $order->city }}</td>
                            <td class="orders-total-cell">${{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="orders-status-badge">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <button onclick="openOrderModal({{ $order->id }})" class="orders-action-btn">
                                    View Items
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div id="orderDetailsModal" class="order-modal-backdrop">
    <div class="order-modal-dialog">
        <button type="button" onclick="closeOrderModal()" class="order-modal-close" aria-label="Close order details">&times;</button>
        <h3 id="modalTitle" class="order-modal-title">Order Details</h3>
        <div id="modalItems" class="order-modal-body"></div>
        <div class="order-modal-footer">
            <button type="button" onclick="closeOrderModal()" class="order-modal-close-btn">Close</button>
        </div>
    </div>
</div>

<script>
    const ordersData = @json($orders);

    function openOrderModal(orderId) {
        const order = ordersData.find(o => o.id === orderId);
        if(!order) return;

        const modal = document.getElementById('orderDetailsModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalItems = document.getElementById('modalItems');

        modalTitle.innerText = "Items in Order #" + order.id;

        let tableHtml = `
            <table class="order-modal-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th class="order-modal-total-row">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
        `;

        order.items.forEach(item => {
            const subtotal = (item.price * item.quantity).toFixed(2);
            tableHtml += `
                <tr>
                    <td>${item.product ? item.product.name : 'Unknown Product'}</td>
                    <td>$${parseFloat(item.price).toFixed(2)}</td>
                    <td>${item.quantity}</td>
                    <td class="order-modal-total-row">$${subtotal}</td>
                </tr>
            `;
        });

        tableHtml += `
                </tbody>
            </table>
            <div class="order-modal-summary">
                <span class="order-modal-summary-label">Order Total:</span>
                <strong class="order-modal-summary-value">$${parseFloat(order.total_price).toFixed(2)}</strong>
            </div>
        `;

        modalItems.innerHTML = tableHtml;
        modal.style.display = "block";
    }

    function closeOrderModal() {
        document.getElementById('orderDetailsModal').style.display = "none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById('orderDetailsModal');
        if (event.target == modal) {
            closeOrderModal();
        }
    }
</script>
@endsection