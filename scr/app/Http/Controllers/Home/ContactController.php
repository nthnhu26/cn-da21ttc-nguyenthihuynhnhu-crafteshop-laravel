<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Notifications\NewContactNotification;
use App\Models\User;

class ContactController extends Controller
{
    public function index()
    {
        return view('home.contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:15',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        // Notify admin(s)
        $admins = User::where('role_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewContactNotification($contact));
        }

        return redirect()->back()->with('success', 'Tin nhắn của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ lại sớm nhất có thể.');
    }
}