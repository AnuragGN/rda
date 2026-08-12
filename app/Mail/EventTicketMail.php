<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $ticketNo;
    public $subject;
    public $msg;

    /**
     * Create a new message instance. 
     *
     * @return void
     */
    public function __construct($name, $ticketNo, $subject, $msg)
    {
        $this->name = $name;
        $this->ticketNo = $ticketNo;
        $this->subject = $subject;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('agency.agency-advisor.email.service_ticket_assign', [
                'name' => $this->name,
                'ticketNo' => $this->ticketNo,
                'msg' => $this->msg,
            ]);
    }
}
