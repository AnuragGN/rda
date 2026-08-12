<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class DAFTransaction extends MailableQueue
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
        $this->title = "Payment Received";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_DAF_TRANSACTION;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.daf-transaction', 'donor.');
        return $this->subject($this->title)->view($view);
    }
}
