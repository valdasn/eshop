@extends('layouts.app') 

@section('title', $displayName ?? 'All Products')

@section('content')
    <div class="category-header">
        <div class="category-title-section">
            <h1>{{ $displayName ?? 'All Supplements' }}</h1>
            <p>Showing products for your health needs.</p>
        </div>

        <form id="sortForm" action="{{ url()->current() }}" method="GET" class="sort-form">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('all'))
                <input type="hidden" name="all" value="true">
            @endif

            <label for="sort" class="sort-label">Sort by:</label>
            <select name="sort" onchange="document.getElementById('sortForm').submit()" class="sort-dropdown">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </form>
    </div>

    <div class="product-grid">
        @forelse($products as $product)
            <div class="product-card">
                <a href="{{ route('products.show', $product->slug) }}" class="product-card-link">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    <h3 class="product-title">{{ $product->name }}</h3>
                    <div class="product-price-container">
                         @if($product->original_price)
                            <span class="old-price">${{ number_format($product->original_price, 2) }}</span>
                            <span class="product-price sale-price">${{ number_format($product->price, 2) }}</span>
                        @else
                            <span class="product-price">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </a>
                <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="ajax-cart-form">
                    @csrf
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            </div>
        @empty
            <div class="empty-state-container">
                @if(request('search'))
                    <h3>No vitamins found for "{{ request('search') }}"</h3>
                @else
                    <h3>No products found in this category yet!</h3>
                @endif
                <a href="{{ route('products.index', ['all' => 'true']) }}" class="back-link">View all products</a>
            </div>
        @endforelse
    </div>
@endsection