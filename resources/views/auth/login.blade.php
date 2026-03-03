@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>Welcome Back</h2>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 20px;">Log in to your VitaShop account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" style="display:block; margin-bottom:5px; font-weight:600;">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="your@email.com">
            @error('email') <span style="color: #e53e3e; font-size: 0.8rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-top: 15px;">
            <label for="password" style="display:block; margin-bottom:5px; font-weight:600;">Password</label>
            <input id="password" type="password" name="password" required placeholder="••••••••">
            @error('password') <span style="color: #e53e3e; font-size: 0.8rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-top: 15px; display: flex; align-items: center; gap: 8px;">
            <input id="remember_me" type="checkbox" name="remember" style="width: auto; margin-bottom: 0;">
            <label for="remember_me" style="font-size: 0.9rem; color: var(--text-muted);">Remember me</label>
        </div>

        <div style="margin-top: 25px;">
            <button type="submit" class="btn">Log In</button>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="{{ route('register') }}" style="color: var(--primary-green); text-decoration: none; font-size: 0.9rem;">
                Don't have an account? Register
            </a>
        </div>
    </form>
</div>
@endsection