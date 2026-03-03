@extends('layouts.app')

@section('content')
<div class="container" style="padding: 40px 15px;">
    <h1 style="color: var(--dark-blue); margin-bottom: 30px;">Your Shopping Cart</h1>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="cart-wrapper">
            {{-- Left Side: Items Table --}}
            <div>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th style="text-align: center;">Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity']; @endphp
                            <tr data-id="{{ $id }}">
                                {{-- Product Name & Image --}}
                                <td>
                                    <div class="cart-item-info">
                                        <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}">
                                        <span style="font-weight: 600;">{{ $details['name'] }}</span>
                                    </div>
                                </td>

                                {{-- Price --}}
                                <td>${{ number_format($details['price'], 2) }}</td>
                                
                                {{-- Quantity Controls --}}
                                <td>
                                    <div class="quantity-controls" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                        <button class="qty-btn minus" style="width: 28px; height: 28px; cursor: pointer; border: 1px solid #ddd; background: #f8fafc; border-radius: 4px;">-</button>
                                        <span class="qty-display" style="font-weight: bold; min-width: 20px; text-align: center;">{{ $details['quantity'] }}</span>
                                        <button class="qty-btn plus" style="width: 28px; height: 28px; cursor: pointer; border: 1px solid #ddd; background: #f8fafc; border-radius: 4px;">+</button>
                                    </div>
                                </td>

                                {{-- Subtotal --}}
                                <td style="color: var(--text-main); font-weight: bold;">
    <span class="item-subtotal-container">
        $<span class="item-subtotal">{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
    </span>
</td>
                                
                                {{-- Action --}}
                                <td>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="remove-btn">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Right Side: Order Summary Card --}}
            <div class="cart-summary">
                <h3 style="margin-top: 0; margin-bottom: 20px;">Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span style="color: var(--primary-green); font-weight: 600;">FREE</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total</span>
                    <span id="cart-total-price">${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn" style="margin-top: 20px; text-decoration: none;">
                    Proceed to Checkout
                </a>
                
                <a href="{{ route('home') }}" class="btn-outline" style="margin-top: 10px; text-decoration: none;">
                    Continue Shopping
                </a>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
            <div style="font-size: 3rem; margin-bottom: 20px;">🛒</div>
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 30px;">Your cart is empty!</p>
            <a href="{{ route('home') }}" class="btn" style="display: inline-block; width: auto; padding: 12px 40px;">Go Shopping</a>
        </div>
    @endif
</div>

<script>
// Your AJAX script stays exactly the same - it will still work perfectly!
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
                const navCartCount = document.getElementById('cart-count');
                if(navCartCount) navCartCount.innerText = data.cartCount;
            }
        })
        .catch(err => console.error('Error updating cart:', err));
    });
});
</script>
@endsection