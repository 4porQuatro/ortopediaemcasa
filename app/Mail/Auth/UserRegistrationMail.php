<?php

namespace App\Mail\Auth;

use App\Models\EmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $values;
    public $email_message;

    /**
     * UserRegistrationMail constructor.
     *
     * @param $name
     * @param $email
     */
    public function __construct($name, $email)
    {
        $this->values['name'] = $name;
        $this->values['email'] = $email;

        $this->email_message = EmailMessage::where('ref', 'user-signup.admin')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
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
