<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Notifications\ContactResponseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactResponseMail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->get();
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact, $notification = null)
    {
        if ($notification) {
            Auth::user()->notifications()->findOrFail($notification)->markAsRead();
        }
        $contacts = Contact::latest()->paginate(10);
        // Phần còn lại của phương thức show
        return view('admin.contacts.show', compact('contact'));
    }

    public function respond(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'response' => 'required|string',
        ]);

        $contact->update([
            'response' => $validated['response'],
            'status' => 'resolved',
        ]);

        // Gửi mail (sẽ tự động queue)
        Mail::to($contact->email)->queue(new ContactResponseMail($contact));

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Phản hồi đã được gửi thành công.');
    }
}
