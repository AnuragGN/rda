<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class DAFCompleted extends MailableQueue
{
    public $name;

    /**
     * AllocationUpdated constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->title = "Application Submitted";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_DAF_COMPLETED;
    }

    /**
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.daf-completed');
        return $this->subject($this->title)->view($view);
    }
}
