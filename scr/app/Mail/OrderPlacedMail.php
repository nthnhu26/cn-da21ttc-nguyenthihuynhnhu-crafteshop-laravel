<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;


    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $orderUrl = url('/orders/' . $this->order->order_id);
        

        return $this->view('emails.orders.placed')
            ->subject('Thông tin đơn hàng #' . $this->order->order_id)
            ->with([
                'order' => $this->order,
                'orderUrl' => $orderUrl
            ]);
    }
}