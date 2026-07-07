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
            $headerTitle = null;
        }

        $displayProducts = $featuredProducts ?? $products ?? collect();
    @endphp

@if(Request::is('/'))
    <div class="hero-slider">
        <a href="{{ route('products.index', ['promo' => 'sleep-sale']) }}" class="slide active">
            <img src="{{ asset('storage/images/banners/sleep_banner.webp') }}" alt="Sleep Supplements">
        </a>

        <a href="{{ route('products.index', ['promo' => 'supercharge']) }}" class="slide">
            <img src="{{ asset('storage/images/banners/activelifestyle_banner.webp') }}" alt="Active Lifestyle">
            <div class="hero-content"></div>
        </a>

        <a href="{{ route('products.index', ['promo' => 'fish-oil']) }}" class="slide">
            <img src="{{ asset('storage/images/banners/pills_banner.webp') }}" alt="Browse All Supplements">
            <div class="hero-content"></div>
        </a>

        <div class="slider-dots">
            <span class="dot active" onclick="currentSlideShow(0)"></span>
            <span class="dot" onclick="currentSlideShow(1)"></span>
            <span class="dot" onclick="currentSlideShow(2)"></span>
        </div>
    </div>
@endif

@if($headerTitle)
    <header class="category-header-section">
        <h1 class="page-title">{{ $headerTitle }}</h1>
    </header>
@endif

@if($currentLabel || isset($category))
    <div class="category-header">
        <div class="breadcrumb-wrapper">
            <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                <ol class="breadcrumb-list">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item">
                        <span class="separator">/</span>
                        <span class="current-page">{{ $currentLabel }}</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="sort-wrapper">
            <form id="sortForm" action="{{ url()->current() }}" method="GET" class="sort-form">
                @if(request('all'))
                    <input type="hidden" name="all" value="true">
                @endif
                
                <label for="sort" class="sort-label">Sort by:</label>
                <select id="sort" name="sort" onchange="this.form.submit()" class="sort-dropdown">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>
    </div>
@endif

    @if(Request::is('/') && isset($featuredProducts))
        <h2 class="homepage-section-title">Featured Products</h2>
    @endif

    <div class="product-grid">
        @foreach($displayProducts as $product)
            <div class="product-card">
                @if($product->original_price)
                    <span class="sale-badge">SALE</span>
                @endif
                <a href="{{ route('products.show', ['product' => $product->slug]) }}" class="product-card-link">
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

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
                    @csrf
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            </div>
        @endforeach
    </div>

    @if(Request::is('/') && isset($saleProducts) && $saleProducts->isNotEmpty())
        <h2 class="homepage-section-title--offers">Special Offers</h2>
        <div class="product-grid">
            @foreach($saleProducts as $product)
                <div class="product-card">
                    <span class="sale-badge">SALE</span>
                    <a href="{{ route('products.show', ['product' => $product->slug]) }}" class="product-card-link">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <div class="product-price-container">
                            <span class="old-price">${{ number_format($product->original_price, 2) }}</span>
                            <span class="product-price sale-price">${{ number_format($product->price, 2) }}</span>
                        </div>
                    </a>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
                        @csrf
                        <button type="submit" class="btn">Add to Cart</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    @if($displayProducts->isEmpty() && (!isset($saleProducts) || $saleProducts->isEmpty()))
        <div class="empty-state-container">
            <p>No products found matching these criteria.</p>
            <a href="{{ route('home') }}" class="btn btn-auto">Back Home</a>
        </div>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        let currentSlide = 0;
        let slideTimer;

        function showSlide(index) {
            if(!slides.length) return;
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            currentSlide = index;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
            resetTimer();
        }
        function nextSlide() {
            if(!slides.length) return;
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }
        function resetTimer() {
            clearInterval(slideTimer);
            if(slides.length) {
                slideTimer = setInterval(nextSlide, 4000);
            }
        }
        window.currentSlideShow = function(index) {
            showSlide(index);
        };
        resetTimer();
    });
    </script>
@endsection