<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;
use App\Models\User;

class EmailChangeRequest extends MailableQueue
{
    public $name;
    public $url;

    /**
     * Create a new message instance.
     * @param $name
     * @param $url
     */
    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
        $this->title = "Change Email Request";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_EMAIL_CHANGE_REQUEST;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.email-change-request');
        return $this->subject($this->title)->view($view);
    }

}
