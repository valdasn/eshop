@extends('layouts.app')

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Join VitaShop</h2>
        <p class="auth-subtitle">Create an account to start your health journey</p>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="Min. 8 characters">
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-auth">Create Account</button>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Log In</a></p>
            </div>
        </form>
    </div>
</div>
@endsection