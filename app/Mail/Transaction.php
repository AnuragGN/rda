<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class Transaction extends MailableQueue
{
    public $name;
    public $transaction;

    /**
     * Transaction constructor.
     * @param $name
     * @param \App\Models\Transaction $transaction
     */
    public function __construct($name, \App\Models\Transaction $transaction)
    {
        $this->name = $name;
        $this->transaction = $transaction;
        $this->title = "Contribution Received";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_TRANSACTION;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.transaction', 'donor.');
        return $this->subject($this->title)->view($view);
    }
}
