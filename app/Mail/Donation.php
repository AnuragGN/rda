<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class Donation extends MailableQueue
{
    public $name;
    public $donation;

    /**
     * Donation constructor.
     * @param $name
     * @param \App\Models\Donation $donation
     */
    public function __construct($name, \App\Models\Donation $donation)
    {
        $this->name = $name;
        $this->donation = $donation;
        $this->title = "Donation";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_DONATION;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.donation');
        return $this->subject($this->title)->view($view);
    }
}
