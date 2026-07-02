@extends('layouts.app')

@section('content')
<div class="container checkout-page">
    <h1 class="checkout-title">Complete Your Order</h1>

    @if(session('error'))
        <div class="checkout-alert">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.stripe') }}" method="POST" id="checkout-form">
        @csrf

        <div class="checkout-grid">
            <div class="checkout-form-panel">
                <h3 class="checkout-section-title">Shipping Details</h3>

                <div class="checkout-form-group">
                    <label class="checkout-field-label">Full Name</label>
                    <input class="checkout-input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="checkout-inline-grid">
                    <div>
                        <label class="checkout-field-label">Email</label>
                        <input class="checkout-input" type="email" name="email" value="{{ $user->email }}" readonly>
                    </div>
                    <div>
                        <label class="checkout-field-label">Phone</label>
                        <input class="checkout-input" type="text" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="Enter phone number">
                    </div>
                </div>

                <div class="checkout-form-group">
                    <label class="checkout-field-label">Shipping Address</label>
                    <input class="checkout-input" type="text" name="address" value="{{ old('address', $user->address) }}" required placeholder="Street address, apartment, suite">
                </div>

                <div class="checkout-inline-grid">
                    <div>
                        <label class="checkout-field-label">City</label>
                        <input class="checkout-input" type="text" name="city" value="{{ old('city', $user->city) }}" required placeholder="City">
                    </div>
                    <div>
                        <label class="checkout-field-label">Postal Code</label>
                        <input class="checkout-input" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" required placeholder="Zip code">
                    </div>
                </div>
            </div>

            <div class="checkout-sidebar">
                <div class="checkout-summary-panel">
                    <h3 class="checkout-section-title">Order Summary</h3>

                    <div class="checkout-summary-list">
                        @foreach($cart as $id => $details)
                            <div class="checkout-summary-item">
                                <span class="checkout-summary-item-name">
                                    <strong class="checkout-summary-item-qty">{{ $details['quantity'] }}x</strong> {{ $details['name'] }}
                                </span>
                                <span class="checkout-summary-item-price">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="checkout-summary-total">
                        <span>Total</span>
                        <span class="checkout-total-value">${{ number_format($total, 2) }}</span>
                    </div>

                    <div class="checkout-demo-note">
                        <strong>Demo</strong><br>
                        Use this Stripe test card for checkout:<br>
                        <code class="checkout-demo-code">4242 4242 4242 4242</code>
                    </div>

                    <button type="submit" class="btn checkout-submit-btn">
                        Pay with Stripe
                    </button>

                    <p class="checkout-security-note">
                        🔒 Secure SSL Encrypted Checkout
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection