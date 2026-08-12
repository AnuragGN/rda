<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class ObjectDeleted extends MailableQueue
{
    public $contact;
    public $title;
    public $info;

    /**
     * ObjectDeleted constructor.
     * @param $title
     * @param $info
     */
    public function __construct($contact, $title, Array $info)
    {
        $this->contact = $contact;
        $this->title = $title;
        $this->info = $info;
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_OBJECT_DELETED;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.object-deleted');
        return $this->subject($this->title)->view($view);
    }
}
