@extends('layouts.app')

@section('content')
<style>
    /* Ensure the checkout grid stacks on mobile devices like Galaxy S20 */
    @media (max-width: 850px) {
        .checkout-grid {
            grid-template-columns: 1fr !important;
            gap: 20px !important;
        }
        .checkout-container {
            padding: 20px 10px !important;
        }
    }
</style>

<div class="container checkout-container" style="padding: 40px 0;">
    <h1 style="color: var(--dark-blue); margin-bottom: 30px; text-align: center;">Complete Your Order</h1>

    @if(session('error'))
        <div style="background: #fff5f5; color: #c53030; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #feb2b2;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.stripe') }}" method="POST" id="checkout-form">
        @csrf
        
        <div class="checkout-grid" style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; align-items: start;">
            
            {{-- Left Side: Shipping Details --}}
            <div class="auth-container" style="margin: 0; max-width: 100%;">
                <h3 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">Shipping Details</h3>
                
                <div style="margin-top: 20px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" readonly style="width: 100%; padding: 10px; background: #f4f7f6; color: #718096; border: 1px solid #e2e8f0; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="Enter phone number" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Shipping Address</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}" required placeholder="Street address, apartment, suite" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" required placeholder="City" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" required placeholder="Zip code" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>
            </div>

            {{-- Right Side: Order Summary --}}
            <div style="position: sticky; top: 20px;">
                <div class="auth-container" style="margin: 0; max-width: 100%; background: #fafafa; border: 1px solid #e2e8f0;">
                    <h3 style="margin-top: 0; margin-bottom: 20px;">Order Summary</h3>
                    
                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px; padding-right: 10px;">
                        @foreach($cart as $id => $details)
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem;">
                                <span>
                                    <strong style="color: var(--primary-green);">{{ $details['quantity'] }}x</strong> {{ $details['name'] }}
                                </span>
                                <span style="color: var(--dark-blue); font-weight: 600;">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div style="border-top: 2px solid #edf2f7; padding-top: 15px;">
                        <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold; color: var(--dark-blue);">
                            <span>Total</span>
                            <span style="color: var(--primary-green);">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div style="background: #ebf8ff; border-left: 4px solid #4299e1; padding: 15px; margin-top: 20px; border-radius: 4px; text-align: left;">
                        <p style="margin: 0; color: #2b6cb0; font-size: 0.85rem; line-height: 1.4;">
                            <strong>🚀 Portfolio Demo</strong><br>
                            Use this Stripe test card for checkout:<br>
                            <code style="display: inline-block; background: #fff; padding: 2px 8px; border-radius: 4px; border: 1px solid #bee3f8; font-weight: bold; color: #2d3748; margin-top: 5px; font-size: 1rem;">
                                4242 4242 4242 4242
                            </code>
                        </p>
                    </div>

                    <button type="submit" class="btn" style="margin-top: 20px; width: 100%; padding: 15px; font-size: 1.1rem; cursor: pointer; border: none; background-color: var(--primary-green); color: white; border-radius: 8px;">
                        Pay with Stripe
                    </button>
                    
                    <p style="text-align: center; font-size: 0.8rem; color: #a0aec0; margin-top: 15px;">
                        🔒 Secure SSL Encrypted Checkout
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection