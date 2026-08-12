<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class ResetPassword extends MailableQueue
{
    public $name;
    public $url;

    /**
     * Create a new message instance.
     * ResetPassword constructor.
     * @param $name
     * @param $url
     */
    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
        $this->title = "Reset Password";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_RESET_PASSWORD;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.reset-password');
        return $this->subject($this->title)->view($view);
    }
}
