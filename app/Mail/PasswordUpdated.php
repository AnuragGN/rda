<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class PasswordUpdated extends MailableQueue
{
    public $name;
    public $url;

    /**
     * PasswordUpdated constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->url = url('/');
        $this->title = "Password Updated";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_PASSWORD_UPDATED;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.password-updated');
        return $this->subject($this->title)->view($view);
    }
}
