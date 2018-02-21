<?php

namespace App\Packages\Store\Mail;

use App\Models\EmailMessage;
use App\Models\Store\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_message;
    public $order;

    /**
     * OrderPlacedUserMail constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->email_message = EmailMessage::where('ref', 'order.user')->first();

        $this->email_message->message = str_parser(
            [
                'name' => $this->order->user->billing_name
            ],
            $this->email_message->message
        );

        return $this->subject($this->email_message->subject)
            ->markdown('emails.user-order-notification');
    }
}