@extends('layouts.app')

@section('content')
<div class="container main-content">
    <div class="contact-wrapper">
        <div class="contact-header">
            <h1>Get in Touch</h1>
            <p>Have a question or feedback? We'd love to hear from you.</p>
        </div>

        @if(session('success'))
            <div class="alert-success contact-alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="contact-content">
            <div class="contact-form-container">
                <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="John Doe"
                            required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            class="form-control @error('subject') is-invalid @enderror"
                            value="{{ old('subject') }}"
                            placeholder="How can we help?"
                            required>
                        @error('subject')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Message</label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6"
                            class="form-control form-textarea @error('message') is-invalid @enderror"
                            placeholder="Tell us more about your inquiry..."
                            required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-submit">Send Message</button>
                </form>
            </div>

            <div class="contact-info">
                <div class="info-card">
                    <h3>Email</h3>
                    <p><a href="mailto:support@vitashop.com">support@vitashop.com</a></p>
                </div>

                <div class="info-card">
                    <h3>Response Time</h3>
                    <p>We typically respond within 24-48 hours</p>
                </div>

                <div class="info-card">
                    <h3>Business Hours</h3>
                    <p>Monday - Friday: 9 AM - 6 PM EST<br>Saturday: 10 AM - 4 PM EST</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
