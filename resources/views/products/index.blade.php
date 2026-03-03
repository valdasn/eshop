@extends('layouts.app') 

@section('title', isset($categoryName) ? $categoryName : 'All Products')

@section('content')
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>{{ $categoryName ?? 'All Supplements' }}</h1>
            <p>Showing products for your health needs.</p>
        </div>

        {{-- SORTING DROPDOWN --}}
        <form id="sortForm" action="{{ url()->current() }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
            {{-- This keeps your search term active when you sort --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('all'))
                <input type="hidden" name="all" value="true">
            @endif

            <label for="sort" style="font-size: 0.9rem; color: #666;">Sort by:</label>
            <select name="sort" onchange="document.getElementById('sortForm').submit()" 
                    style="padding: 8px; border-radius: 5px; border: 1px solid #ddd; cursor: pointer; background: white;">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </form>
    </div>

    <div class="product-grid">
        @forelse($products as $product)
            <div class="product-card">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </a>
                <h3>{{ $product->name }}</h3>
                <p class="price">${{ number_format($product->price, 2) }}</p>
                
                <a href="{{ route('products.show', $product->id) }}" class="btn-outline">View Details</a>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
    @csrf
    <button type="submit" class="btn" style="max-width: 300px;">Add to Cart</button>
</form>
            </div>
        @empty
            {{-- This section handles the "No products" case --}}
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                @if(request('search'))
                    <h3>No vitamins found for "{{ request('search') }}"</h3>
                @else
                    <h3>No products found in this category yet!</h3>
                @endif
                <a href="{{ route('products.index', ['all' => 'true']) }}" style="color: var(--primary-green); text-decoration: underline;">View all products</a>
            </div>
        @endforelse
    </div>
@endsection