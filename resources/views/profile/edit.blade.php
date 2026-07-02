@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="container main-content profile-edit-view">
    
    <div class="profile-header">
        <h1>Account Settings</h1>
        <p>Manage your personal information and security preferences.</p>
    </div>

    <div class="settings-grid">
        
        <div class="settings-card">
            <div class="card-intro">
                <h3>Profile Information</h3>
                <p>Update your name, email, and shipping details.</p>
            </div>
            <div class="form-wrapper">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="settings-card">
            <div class="card-intro">
                <h3>Security</h3>
                <p>Ensure your account is protected with a strong password.</p>
            </div>
            <div class="form-wrapper">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="settings-card danger-card full-width">
            <div class="card-intro">
                <h3 class="text-red">Delete Account</h3>
                <p>Permanently remove your account and all associated data.</p>
            </div>
            <div class="form-wrapper">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection