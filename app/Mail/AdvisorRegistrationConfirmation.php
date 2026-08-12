<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\FaPartner;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdvisorRegistrationConfirmation extends MailableQueue
{
    public $name;
    public $email;
    public $logoPath;

    /**
     * Create a new message instance.
     * @param $name
     * @param $email
     * @param $url
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->title = "Advisor Registration Confirmation";
        $this->logoPath = FaPartner::getClientHeaderLogo();
    }


    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_EMAIL_CHANGE_CONFIRMATION;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.advisor-registration-confirmation');
        
        return $this->subject($this->title)->view($view)->with([
            'logoPath' => $this->logoPath
        ]);
    }
}
