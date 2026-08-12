<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\ContactUs;
use App\Models\Email;

class RequestInfo extends MailableQueue
{
    public $contact;
    public $contactUs;
    public $targetInfo;

    /**
     * Donation constructor.
     * @param ContactUs $contactUs
     */
    public function __construct(ContactUs $contactUs)
    {
        $this->contact = Contact::sessionContact();
        $this->targetInfo = $contactUs->getTargetInfo();
        $this->contactUs = $contactUs->toArray();
        $this->title = "Request for Info - " . $this->targetInfo['name'];
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_REQUEST_INFO;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.request_info', 'donor.');
        return $this->subject($this->title)->view($view);
    }

}
