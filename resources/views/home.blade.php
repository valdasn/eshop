@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - VitaShop' : ($displayName ?? 'Welcome') . ' - VitaShop')

@section('content')
    @php
        $singularMap = ['Eyes' => 'Eye', 'Joints' => 'Joint', 'Bones' => 'Bone'];
        $customH1Map = ['Discounts' => 'Current Discounts', 'Shop All' => 'Our Entire Collection'];
        $currentLabel = $displayName ?? (isset($category) ? ($singularMap[$category->name] ?? $category->name) : null);
        
        if ($currentLabel) {
            $headerTitle = $customH1Map[$currentLabel] ?? $currentLabel . ' Supplements';
        } else {
            $headerTitle = 'Your Health, Simplified.';
        }
    @endphp

    <div class="hero-banner">
        <h1>{{ $headerTitle }}</h1>
        <p>
            @if($currentLabel == 'Shop All')
                Browse our full range of premium health supplements.
            @elseif($currentLabel)
                Exploring our premium range of {{ strtolower($currentLabel) }} products.
            @else
                Premium quality supplements delivered to your door.
            @endif
        </p>
    </div>

    <div class="category-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <h2 style="margin: 0;">{{ $headerTitle ?? 'Featured Products' }}</h2>
            @if($currentLabel)
                <a href="{{ route('home') }}" class="back-link" style="text-decoration: none; color: #666; font-size: 0.9rem;">← Back to Home</a>
            @endif
        </div>

        {{-- Only show sorting if we are in "Shop All" or a specific Category --}}
        @if($currentLabel || isset($category))
            <form id="sortForm" action="{{ url()->current() }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
                {{-- Keep 'all' parameter if we're in Shop All mode --}}
                @if(request('all'))
                    <input type="hidden" name="all" value="true">
                @endif
                
                <label for="sort" style="font-size: 0.9rem; color: #666; white-space: nowrap;">Sort by:</label>
                <select name="sort" onchange="document.getElementById('sortForm').submit()" 
                        style="padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd; cursor: pointer; background-color: white; font-size: 0.9rem; outline: none; border-color: var(--primary-green);">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        @endif
    </div>

    <div class="product-grid"> 
        @foreach($products as $product)
            <div class="product-card">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </a>
                
                <h3>{{ $product->name }}</h3>
                <p class="price">${{ number_format($product->price, 2) }}</p>
                
                <div class="card-actions">
                    <a href="{{ route('products.show', $product->id) }}" class="btn-outline">View Details</a>

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
                     @csrf
                    <button type="submit" class="btn" style="max-width: 300px;">Add to Cart</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    @if($products->isEmpty())
        <div class="empty-state" style="text-align: center; padding: 60px 20px; background: #f9f9f9; border-radius: 12px; margin-top: 20px;">
            <p style="color: #666; margin-bottom: 20px;">No products found matching these criteria.</p>
            <a href="{{ route('home') }}" class="btn" style="padding: 10px 30px;">Back to Home</a>
        </div>
    @endif
@endsection