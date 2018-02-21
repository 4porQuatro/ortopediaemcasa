<?php

namespace App\Mail\Newsletter;

use App\Models\EmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Http\Requests\Newsletter\SubscriptionRequest;

class SubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $values;
    public $email_message;

    /**
     * SubscriptionMail constructor.
     *
     * @param SubscriptionRequest $request
     */
    public function __construct(SubscriptionRequest $request)
    {
        $this->values['email'] = $request->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->email_message = EmailMessage::where('ref', 'newsletter.admin')->first();

        $receivers = [];

        foreach($this->email_message->receivers as $receiver)
        {
            $this->to[] = [
                'address' => $receiver->email,
                'name' => $receiver->name
            ];
        }

        return $this->subject($this->email_message->subject)
                    ->markdown('emails.system-notification');
    }
}
