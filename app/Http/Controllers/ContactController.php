<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        // Here you can:
        // 1. Send email to admin
        // 2. Store in database
        // 3. Or both

        // For now, we'll just redirect with success message
        // You can later add email functionality with Mail::send()

        return redirect()->route('contact.show')
            ->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
