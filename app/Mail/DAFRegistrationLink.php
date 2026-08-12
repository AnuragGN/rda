<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class DAFRegistrationLink extends MailableQueue
{
    public $link;
    public $name;

    /**
     * AllocationUpdated constructor.
     * @param $name
     */
    public function __construct($name, $link)
    {
        $this->link = $link;
        $this->name = $name;
        $this->title = "Account Activation Link";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_DAF_REGISTER_LINK;
    }

    /**
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.daf-registration-link');
        return $this->subject($this->title)->view($view);
    }
}
