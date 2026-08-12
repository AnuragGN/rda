<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class ObjectUpdated extends MailableQueue
{
    public $contact;
    public $title;
    public $changes;

    /**
     * ObjectUpdated constructor.
     * @param $title
     * @param $changes
     */
    public function __construct($contact, $title, Array $changes)
    {
        $this->contact = $contact;
        $this->title = $title;
        $this->changes = $changes;
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_OBJECT_UPDATED;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.object-updated');
        return $this->subject($this->title)->view($view);
    }
}
