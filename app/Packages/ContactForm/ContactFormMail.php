<?php

namespace App\Packages\ContactForm;

use App\Models\EmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactFormMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $values;
    public $email_message;

    /**
     * ContactMail constructor.
     * @param ContactFormRequest $request
     */
    public function __construct(ContactFormRequest $request)
    {
        $this->values['name'] = $request->name;
        $this->values['phone'] = $request->phone;
        $this->values['email'] = $request->email;
        $this->values['subject'] = $request->subject;
        $this->values['message'] = $request->message;
    }

    /**
     * Build the message
     * @return $this
     */
    public function build()
    {
        $this->email_message = EmailMessage::where('ref', 'contact.admin')->first();

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
