@extends('layouts.app')

@section('content')
<div class="container main-content">
    <h1 class="page-title">Your Shopping Cart</h1>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="cart-wrapper">
            <div class="cart-items-container">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th class="text-center">Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity']; @endphp
                            <tr data-id="{{ $id }}">
                                <td>
                                    <div class="cart-item-info">
                                        <img src="{{ asset('storage/' . $details['image']) }}" 
                                             class="cart-product-img" 
                                             alt="{{ $details['name'] }}">
                                        <div class="cart-item-meta">
                                            <span class="cart-item-name">{{ $details['name'] }}</span>
                                            <span class="cart-item-price-mobile">${{ number_format($details['price'], 2) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="cart-price-cell">${{ number_format($details['price'], 2) }}</td>
                                <td>
                                    <div class="quantity-controls">
                                        <button class="qty-btn minus">-</button>
                                        <span class="qty-display">{{ $details['quantity'] }}</span>
                                        <button class="qty-btn plus">+</button>
                                    </div>
                                </td>
                                <td class="item-subtotal-cell">
                                    $<span class="item-subtotal">{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                </td>
                                <td>
                                    <button type="button" class="remove-btn">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="summary-subtotal">${{ number_format($total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span class="free-shipping">FREE</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total</span>
                    <span id="cart-total-price">${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn">Proceed to Checkout</a>
                <a href="{{ route('home') }}" class="btn-continue-shopping">← Continue Shopping</a>
            </div>
        </div>
    @else
        <div class="empty-cart-message">
            <p>Your cart is empty!</p>
            <a href="{{ route('home') }}" class="btn btn-empty-cart-action">Go Shopping</a>
        </div>
    @endif
</div>

<script>
document.querySelectorAll('.qty-btn').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('tr');
        const id = row.getAttribute('data-id');
        const display = row.querySelector('.qty-display');
        let qty = parseInt(display.innerText);

        if (this.classList.contains('plus')) {
            qty++;
        } else if (this.classList.contains('minus') && qty > 1) {
            qty--;
        }

        display.innerText = qty;

        fetch("{{ route('cart.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id, quantity: qty })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                row.querySelector('.item-subtotal').innerText = data.itemSubtotal;
                document.getElementById('cart-total-price').innerText = '$' + data.total;
                document.getElementById('summary-subtotal').innerText = '$' + data.total;
                const navCartCount = document.getElementById('cart-count');
                if(navCartCount) navCartCount.innerText = data.cartCount;
            }
        })
        .catch(err => console.error(err));
    });
});

document.querySelectorAll('.remove-btn').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('tr');
        const id = row.getAttribute('data-id');

        fetch(`/cart/remove/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0';
                
                setTimeout(() => {
                    row.remove();
                    document.getElementById('cart-total-price').innerText = '$' + data.total;
                    document.getElementById('summary-subtotal').innerText = '$' + data.total;
                    const navCartCount = document.getElementById('cart-count');
                    if(navCartCount) navCartCount.innerText = data.cartCount;

                    if (data.cartCount == 0) {
                        location.reload();
                    }
                }, 300);
            }
        })
        .catch(err => console.error(err));
    });
});
</script>
@endsection