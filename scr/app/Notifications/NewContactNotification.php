<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Contact;

class NewContactNotification extends Notification
{
    use Queueable;

    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'contact_id' => $this->contact->contact_id,
            'name' => $this->contact->name,
            'message' => $this->contact->message,
            'email' => $this->contact->email,
        ];
    }
}
