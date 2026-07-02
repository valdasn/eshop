@extends('layouts.app')

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Log in to your VitaShop account</p>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="your@email.com">
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="••••••••">
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-options">
                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-auth">Log In</button>

            <div class="auth-footer">
                <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
            </div>
        </form>
    </div>
</div>
@endsection