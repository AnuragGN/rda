<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\FaPartner;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdvisorsDafCompleted extends MailableQueue
{
    public $advisorName;
    public $donorName;
    public $email;
    public $logoPath;

    /**
     * Create a new message instance.
     * @param $name
     * @param $email
     * @param $url
     */
    public function __construct($advisorName, $donorName)
    {
        $this->advisorName = $advisorName;
        $this->donorName = $donorName;
       // $this->email = $email;
        $this->title = "DAF Application Submitted by Advisor -" . $advisorName;
        $this->logoPath = FaPartner::getClientHeaderLogo();
    }


    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_DAF_COMPLETED;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.advisor-daf-completed');
      
        return $this->subject($this->title)->view($view)->with([
            'advisorName' => $this->advisorName,
            'donorName' => $this->donorName,
            'logoPath' => $this->logoPath
        ]);
    }
}
