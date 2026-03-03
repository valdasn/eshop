@extends('layouts.app')

@section('content')
<div class="container" style="padding: 40px 0;">
    <h2 style="color: var(--dark-blue); margin-bottom: 30px;">Your Order History</h2>

    @if($orders->isEmpty())
        <div class="auth-container" style="text-align: center; padding: 50px;">
            <p style="color: #4a5568;">You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" class="btn">Start Shopping</a>
        </div>
    @else
        <div class="auth-container" style="max-width: 100%; margin: 0; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee;">
                        <th style="padding: 15px; color: #4a5568;">Order #</th>
                        <th style="padding: 15px; color: #4a5568;">Date</th>
                        <th style="padding: 15px; color: #4a5568;">City</th>
                        <th style="padding: 15px; color: #4a5568;">Total</th>
                        <th style="padding: 15px; color: #4a5568;">Status</th>
                        <th style="padding: 15px; color: #4a5568;">Action</th>
                    </tr>
                </thead>
                <tbody style="color: #2d3748;">
                    @foreach($orders as $order)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;"><strong>{{ $order->id }}</strong></td>
                            <td style="padding: 15px;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td style="padding: 15px;">{{ $order->city }}</td>
                            <td style="padding: 15px; color: var(--primary-green); font-weight: bold;">
                                ${{ number_format($order->total_price, 2) }}
                            </td>
                            <td style="padding: 15px;">
                                <span style="background: #eef2ff; color: #4f46e5; padding: 5px 10px; border-radius: 15px; font-size: 0.85rem; font-weight: 600;">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <button onclick="openOrderModal({{ $order->id }})" 
                                        style="background: var(--dark-blue); color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
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

{{-- MODAL STRUCTURE --}}
<div id="orderDetailsModal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75);">
    {{-- Forced Solid Background and Dark Text --}}
    <div style="background: #ffffff; margin: 5% auto; max-width: 600px; position: relative; padding: 30px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); animation: slideDown 0.3s ease-out; color: #1a202c;">
        
        <span onclick="closeOrderModal()" style="position: absolute; right: 20px; top: 15px; font-size: 28px; cursor: pointer; color: #4a5568; font-weight: bold; line-height: 1;">&times;</span>
        
        <h3 id="modalTitle" style="color: #1a202c; margin-top: 0; border-bottom: 2px solid #f4f7f6; padding-bottom: 15px; font-size: 1.5rem;">Order Details</h3>
        
        <div id="modalItems" style="margin-top: 20px; color: #1a202c;">
            {{-- Items will be injected here --}}
        </div>

        <div style="text-align: right; margin-top: 25px; border-top: 1px solid #f4f7f6; padding-top: 20px;">
            <button onclick="closeOrderModal()" style="background: #4a5568; color: white; border: none; padding: 10px 25px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.95rem;">
                Close
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes slideDown {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    /* Modal Table Styling */
    .modal-table { width: 100%; border-collapse: collapse; }
    .modal-table th { color: #718096; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 12px 10px; text-align: left; border-bottom: 2px solid #f4f7f6; }
    .modal-table td { padding: 12px 10px; color: #2d3748; border-bottom: 1px solid #f4f7f6; font-size: 0.95rem; }
</style>

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
            <table class="modal-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
        `;

        order.items.forEach(item => {
            const subtotal = (item.price * item.quantity).toFixed(2);
            tableHtml += `
                <tr>
                    <td style="font-weight: 500;">${item.product ? item.product.name : 'Unknown Product'}</td>
                    <td>$${parseFloat(item.price).toFixed(2)}</td>
                    <td>${item.quantity}</td>
                    <td style="text-align: right; font-weight: bold; color: #1a202c;">$${subtotal}</td>
                </tr>
            `;
        });

        tableHtml += `
                </tbody>
            </table>
            <div style="margin-top: 20px; text-align: right; font-size: 1.2rem; color: #1a202c;">
                <span style="color: #718096; font-size: 0.9rem; margin-right: 10px;">Order Total:</span>
                <strong style="color: #2f855a;">$${parseFloat(order.total_price).toFixed(2)}</strong>
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