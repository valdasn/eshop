@extends('layouts.app')

@section('title', $product->name . ' | Vitamin Shop')

@section('content')
<div class="container main-content">
    
    <div class="category-header">
        <div class="breadcrumb-wrapper">
            <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                <ol class="breadcrumb-list">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>

                    @if($product->categories->count() > 0)
                        <li class="breadcrumb-item">
                            <span class="separator">/</span>
                            <a href="{{ route('categories.show', $product->categories->first()->slug) }}">
                                {{ $product->categories->first()->name }}
                            </a>
                        </li>
                    @endif

                    <li class="breadcrumb-item">
                        <span class="separator">/</span>
                        <span class="current-page">{{ $product->name }}</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="product-details-wrapper">
        
        <div class="product-image-section">
            <div class="image-box">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            </div>
        </div>

        <div class="product-info-section">
            
            <h1 class="details-title">{{ $product->name }}</h1>
            
            <div class="price-large">
                @if($product->original_price)
                    <span class="sale-price">${{ number_format($product->price, 2) }}</span>
                    <span class="old-price product-old-price">${{ number_format($product->original_price, 2) }}</span>
                @else
                    ${{ number_format($product->price, 2) }}
                @endif
            </div>

            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
                @csrf
                <button type="submit" class="btn btn-large">Add to Cart</button>
            </form>

            <div class="description-box">
                <h3>About this supplement</h3>
                <p>{!! nl2br(e($product->description)) !!}</p>
            </div>
            
            <div class="meta-info">
                <strong class="meta-label">Categories:</strong> 
                <div class="badge-container">
                    @foreach($product->categories as $category)
                        <span class="category-badge">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection