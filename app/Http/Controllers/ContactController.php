<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ContactFormNotification;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Send notification to all admins
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            $admin->notify(new ContactFormNotification($validated));
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Thank you for your message! We will respond within 2 business days.');
    }
}
