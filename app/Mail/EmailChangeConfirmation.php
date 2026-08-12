<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangeConfirmation extends MailableQueue
{
    public $name;
    public $email;

    /**
     * Create a new message instance.
     * @param $name
     * @param $email
     * @param $url
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->title = "Email Change Confirmation";
    }


    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_EMAIL_CHANGE_CONFIRMATION;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.email-change-confirmation');
        return $this->subject($this->title)->view($view);
    }
}
