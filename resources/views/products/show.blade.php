@extends('layouts.app')

@section('title', $product->name . ' | Vitamin Shop')

@section('content')
<div class="container">
    <div class="product-details-wrapper">
        
        <div class="product-image-section">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
        </div>

        <div class="product-info-section">
            <a href="{{ url('/') }}" class="back-link" style="display: inline-block; margin-bottom: 15px;">&larr; Back to Shop</a>
            
            <h1>{{ $product->name }}</h1>
            
            <div class="price-large">${{ number_format($product->price, 2) }}</div>

           <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
    @csrf
    <button type="submit" class="btn" style="max-width: 300px;">Add to Cart</button>
</form>

            <div class="description-box">
                <h3>About this supplement</h3>
                <p>{!! nl2br(e($product->description)) !!}</p>
            </div>
            
            <div class="meta-info" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <strong>Categories:</strong> 
                @foreach($product->categories as $category)
                    <span class="category-badge">{{ $category->name }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection