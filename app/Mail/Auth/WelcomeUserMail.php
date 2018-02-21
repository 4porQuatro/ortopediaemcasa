<?php

namespace App\Mail\Auth;

use App\Models\EmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email_message;

    /**
     * WelcomeUserMail constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $this->email_message = EmailMessage::where('ref', 'user-signup.user')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->email_message->message = str_parser(
            [
                'name' => $this->name
            ],
            $this->email_message->message
        );

        return $this->subject($this->email_message->subject)
            ->markdown('emails.system-notification');
    }
}
