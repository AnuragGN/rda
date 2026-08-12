<?php

namespace App\Mail;

use App\Helpers\GnUtils;
use App\Models\Email;
use App\Models\FailedJobs;

class SendTestMail extends MailableQueue
{
    public $name;
    public $email;

    /**
     * Create a new message instance.
     * @param $email
     * @param $name
     */
    public function __construct($email, $name)
    {
        $this->name = $name;
        $this->email = GnUtils::configEmailsToString($email);
        $this->title = "TEST MAIL";
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EMAIL_TEST;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)->view('emails.send-test-mail');
    }


    static public function payload()
    {
        $model = FailedJobs::first();
        // return $model;
        $payload = json_decode($model->payload);

        $command = unserialize($payload->data->command);

        return [ $command->mailable->name ];
    }
}
