<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class Auth2FACode extends MailableQueue
{
    public $name;
    public $code;

    /**
     * Auth2FACode constructor.
     * @param $name
     * @param $code
     */
    public function __construct($name, $code)
    {
        $this->name = $name;
        $this->code = $code;
        $this->title = "The temporary code is " . $code;
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_AUTH_2FA_CODE;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.auth-2fa-code');
        return $this->subject($this->title)->view($view);
    }
}
