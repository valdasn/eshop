@extends('layouts.app')

@section('content')
<div class="container" style="padding: 40px 0;">
    <h1 style="color: var(--dark-blue); margin-bottom: 30px;">Account Settings</h1>

    <div style="display: grid; grid-template-columns: 1fr; gap: 30px; max-width: 600px;">
        
        <div class="auth-container" style="margin: 0; max-width: 100%;">
            <h3 style="margin-top: 0;">Profile Information</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">Update your account's profile information and email address.</p>
            
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="auth-container" style="margin: 0; max-width: 100%;">
            <h3 style="margin-top: 0;">Update Password</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">Ensure your account is using a long, random password to stay secure.</p>
            
            @include('profile.partials.update-password-form')
        </div>

        <div class="auth-container" style="margin: 0; max-width: 100%; border-color: #feb2b2;">
            <h3 style="margin-top: 0; color: #c53030;">Delete Account</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</div>
@endsection