<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;

class ContactResponseMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        return $this->view('emails.contact-response')
            ->subject('Phản hồi từ Đồ Thủ Công Mỹ Nghệ')
            ->with([
                'responseMessage' => $this->contact->response,
                'customer_name' => $this->contact->name
            ]);
    }
}
