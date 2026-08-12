<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;

class AllocationUpdated extends MailableQueue
{
    public $name;
    public $allocations;

    /**
     * AllocationUpdated constructor.
     * @param $name
     */
    public function __construct($name, $allocations)
    {
        $this->allocations = $allocations;
        $this->name = $name;
        $this->title = "Fund Allocation Request";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_ALLOCATION_CHANGED;
    }

    /**
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('donor.emails.allocation-updated');
        return $this->subject($this->title)->view($view);
    }
}
