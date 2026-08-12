<?php

namespace App\Mail;

use App\Models\ClientInfo;
use App\Models\Email;
use App\Models\GrantItem;
use App\Models\OrganizationAddress;

class GrantReminder extends MailableQueue
{
    public $name;
    public $grants;

    /**
     * GrantReminder constructor.
     * @param $name
     * @param $grants
     */
    public function __construct($name, $grants)
    {
        $this->name = $name;
        $this->grants = [];
        if (ClientInfo::isJCF()) {
            $this->title = "JCF of San Diego Grant Reminder";
        } else {
            $this->title = "Grant Reminder";
        }

        /** @var GrantItem $grant */
        foreach($grants as $grant) {
            /** @var OrganizationAddress $address */
            $address = $grant->getOrgAddress();

            $data['amount'] = \App\Helpers\GnUtils::money($grant->amount);
            $data['fund_id'] = $grant->fund->fund_id;
            $data['fund'] = $grant->fund->name;
            $data['organization'] = $grant->getOrgName();
            $data['address'] = $address->getTwoLineAddress();
            $data['org_ein'] = $grant->org_ein;
            $data['org_contact'] = $grant->org_contact;
            $data['org_phone'] = $grant->org_phone;
            $data['org_email'] = $grant->org_email;
            $data['grant_purpose'] = $grant->grant_purpose;
            $data['note'] = $grant->notes;

            if ($grant->anonymous == "N") {
                $data['anonymous'] = "Non-anonymous";
            } else {
                $data['anonymous'] = "Yes, donor wishes to be anonymous";
            }

            $this->grants[] = $data;
        }
    }

    /**
     * @return int
     */
    public function getEmailTypeId()
    {
        return Email::EID_GRANT_REMINDER;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = ClientInfo::clientViewFor('emails.grant-reminder', 'donor.');
        return $this->subject($this->title)->view($view);
    }

}
