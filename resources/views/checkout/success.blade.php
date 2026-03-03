@extends('layouts.app')

@section('content')
<div class="container" style="text-align: center; padding: 100px 20px;">
    <h1 style="color: var(--primary-green); font-size: 3rem;">✓</h1>
    <h2 style="color: var(--dark-blue);">Order Confirmed!</h2>
    <p style="color: var(--text-muted); margin-bottom: 30px;">Thank you for your purchase. Your vitamins are on the way!</p>
    <a href="{{ route('home') }}" class="btn" style="display: inline-block; width: auto; padding: 12px 40px;">Back to Home</a>
</div>
@endsection