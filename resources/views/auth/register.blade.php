@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>Create Account</h2>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 20px;">Join the VitaShop community</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" style="display:block; margin-bottom:5px; font-weight:600;">Full Name</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe">
        </div>

        <div style="margin-top: 15px;">
            <label for="email" style="display:block; margin-bottom:5px; font-weight:600;">Email Address</label>
            <input id="email" type="email" name="email" :value="old('email')" required placeholder="email@example.com">
        </div>

        <div style="margin-top: 15px;">
            <label for="password" style="display:block; margin-bottom:5px; font-weight:600;">Password</label>
            <input id="password" type="password" name="password" required placeholder="Minimum 8 characters">
        </div>

        <div style="margin-top: 15px;">
            <label for="password_confirmation" style="display:block; margin-bottom:5px; font-weight:600;">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Repeat password">
        </div>

        <div style="margin-top: 25px;">
            <button type="submit" class="btn">Create Account</button>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="{{ route('login') }}" style="color: var(--primary-green); text-decoration: none; font-size: 0.9rem;">
                Already have an account? Log in
            </a>
        </div>
    </form>
</div>
@endsection