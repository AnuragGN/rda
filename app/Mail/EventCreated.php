<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param array $emailData
     * @param string $subject
     */
    public function __construct($emailData, $subject)
    {
        $this->emailData = $emailData;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('agency.agency-advisor.email.event_created')
            ->with([
                'emailData' => $this->emailData,
            ]);
    }
}

