<?php

namespace App\Packages\Store\Mail;

use App\Models\EmailMessage;
use App\Models\Store\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_message;
    public $order;

    /**
     * OrderPlacedAdminMail constructor.
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
        $this->email_message = EmailMessage::where('ref', 'order.admin')->first();

        foreach($this->email_message->receivers as $receiver)
        {
            $this->to[] = [
                'address' => $receiver->email,
                'name' => $receiver->name
            ];
        }

        return $this->subject($this->email_message->subject)
            ->markdown('emails.admin-order-notification');
    }
}