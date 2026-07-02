@extends('layouts.app')

@section('content')
<div class="container checkout-success-page">
    <h1 class="checkout-success-icon">✓</h1>
    <h2 class="checkout-success-title">Order Confirmed!</h2>
    <p class="checkout-success-copy">Thank you for your purchase. Your vitamins are on the way!</p>
    <a href="{{ route('home') }}" class="btn checkout-success-button">Back to Home</a>
</div>
@endsection