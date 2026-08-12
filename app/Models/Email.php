<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 13-03-2020
 * Time: 19:46
 */

namespace App\Models;

use App\Helpers\GnUtils;
use App\Mail\Auth2FACode;
use App\Mail\DAFCompleted;
use App\Mail\Donation;
use App\Mail\EmailChangeConfirmation;
use App\Mail\EmailChangeRequest;
use App\Mail\GrantRecommendation;
use App\Mail\GrantReminder;
use App\Mail\MailableQueue;
use App\Mail\ObjectDeleted;
use App\Mail\ObjectUpdated;
use App\Mail\PasswordUpdated;
use App\Mail\RequestInfo;
use App\Mail\ResetPassword;
use App\Mail\SendTestMail;
use App\Mail\Transaction;
use App\Mail\AllocationUpdated;
use App\Mail\DAFRegistrationLink;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DAFTransaction;
use App\Mail\AdvisorRegistrationConfirmation;
use App\Mail\AdvisorsDafCompleted;
use App\Models\DAFAccount;

class Email
{
    const STATUS_PENDING = "pending";
    const STATUS_SENDING = "sending";
    const STATUS_SENT = "sent";
    const STATUS_FAILED = "failed";

    const EMAIL_TEST = 1000;
    const EID_PASSWORD_UPDATED = 1010;
    const EID_RESET_PASSWORD = 1011;
    const EID_EMAIL_CHANGE_REQUEST = 1012;
    const EID_EMAIL_CHANGE_CONFIRMATION = 1013;
    const EID_AUTH_2FA_CODE = 1014;

    const EID_DONATION = 1020;
    const EID_TRANSACTION = 1021;

    const EID_GRANT_RECOMMENDATION = 1030;
    const EID_GRANT_REMINDER = 1031;

    const EID_REQUEST_INFO = 1040;

    const EID_OBJECT_UPDATED = 1050;
    const EID_OBJECT_DELETED = 1051;

    const EID_ALLOCATION_CHANGED = 1060;

    const EID_DAF_REGISTER_LINK = 1070;
    const EID_DAF_TRANSACTION = 1071;
    const EID_DAF_COMPLETED = 1072;

    // Mail::to($request->user())->cc($moreUsers)->bcc($evenMoreUsers)->send(new OrderShipped($order));
    // Mail::to($request->user())->cc($moreUsers)->bcc($evenMoreUsers)->queue(new OrderShipped($order));

    // DB fields - public
    public $contactId;
    public $orgId;
    public $typeId;

    public $subject;
    public $to;
    public $cc;
    public $bcc;
    public $html;
    public $status;

    // Non-DB field - private
    private $mailable;
    // private $from;
    // private $bcc;

    /**
     * Email constructor.
     * @param $to
     * @param MailableQueue $mailable
     */
    private function __construct($to, MailableQueue $mailable)
    {
        $this->typeId = $mailable->getEmailTypeId();
        $this->to = $to;
        $this->bcc = 'alkeshksingh@giftingnetwork.com';
        $this->mailable = $mailable;
        $this->status = Email::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    private function SendMail()
    {
        // update
        $this->subject = $this->mailable->getSubject();
        $this->html = $this->mailable->render();

        // save in DB
        $model = EmailArchive::saveEmail($this);

        // save id for Job
        $this->mailable->setEmailArchiveId($model->id);
        // Log::channel('jobs')->info(json_encode((array) $this));

        // send mail
        try {
            $message = $this->mailable->onQueue('emails');
            $mail = Mail::to($this->to);
            if ($this->cc) $mail = $mail->cc($this->cc);
            if ($this->bcc) $mail = $mail->bcc($this->bcc);
            $mail->send($message);
            return true;
        } catch(\Exception $e) {
            $this->onException($e);
            return false;
        }
    }

    /**
     * @param $to
     * @param $name
     * @return bool
     */
    static public function sendTestEmail($to, $name)
    {
        $mailable = new SendTestMail($to, $name);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        return $email->SendMail();
    }

    /**
     * @param $to
     * @param $user
     * @param $url
     */
    static public function resetPassword($to, User $user, $url)
    {
        $name = $user->getContactName();
        $mailable = new ResetPassword($name, $url);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->contactId = $user->getContactId();
        $email->SendMail();
    }

    /**
     * DAF registration and activation mail
     */
    static public function dafRegistration($link, User $user)
    {
        /** @var User $user */
        $to = $user->username;
        $name = $user->getDAFUserName();
        $mailable = new DAFRegistrationLink($name, $link);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        //$email->cc = ClientEnv::value('EMAIL_CC');
        // $email->contactId = $contact->contact_id;
        $email->SendMail();
    }

    /**
     * @param User $user
     */
    static public function passwordUpdated(User $user)
    {
        $to = $user->getAccountEmailAddress();
        $name = $user->getContactName();
        $mailable = new PasswordUpdated($name);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->contactId = $user->getContactId();
        $email->SendMail();
    }

    /**
     * @param User $user
     * @param $code
     */
    static public function auth2FACode(User $user, $code)
    {
        $to = $user->getAccountEmailAddress();
        $name = $user->getContactName();
        $mailable = new Auth2FACode($name, $code);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->contactId = $user->getContactId();
        $email->SendMail();
    }

    /**
     * update allocation mail
     */
    static public function allocationUpdated($allocations)
    {
        /** @var User $user */
        $user = auth()->user();
        $to = $user->getAccountEmailAddress();
        $contact = Contact::sessionContact();

        $mailable = new AllocationUpdated($contact->name, $allocations);
        /** @var Email $email */
        $email = new Email($to, $mailable);

        if (env('APP_ENV') == 'qa') {
            $email->cc = ["pushyamipullabhotla@giftingnetwork.com", "ashishkumar@giftingnetwork.com", "alkeshksingh@giftingnetwork.com"];
        } else if (env('APP_ENV') == 'uat') {
            $email->cc = ["pushyamipullabhotla@giftingnetwork.com", "ashishkumar@giftingnetwork.com", "alkeshksingh@giftingnetwork.com"];
        } else if (env('APP_ENV') == 'prod') {
            if (ClientInfo::isNIF()) {
                $email->cc = ["jennifer@nif.org", "thomas@nif.org", "andrewg@nif.org"];
            } else {
                $email->cc = ClientEnv::value('EMAIL_CC');
            }
        } else {
            $email->cc = ["alkeshksingh@giftingnetwork.com"];
        }

        $email->contactId = $contact->contact_id;
        $email->SendMail();
    }

    /**
     * @param $grants
     */
    static public function grantRecommendation($grants)
    {
        /** @var User $user */
        $user = auth()->user();
        $to = $user->getAccountEmailAddress();
        $contact = Contact::sessionContact();
        $mailable = new GrantRecommendation($contact->name, $grants);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        if (ClientInfo::isJCF() && env('APP_ENV') == 'prod') {
            $email->cc = ["ollie@jcfsandiego.org", "grants@jcfsandiego.org"];
        } else {
            $email->cc = ClientEnv::value('EMAIL_CC');
        }
        $email->contactId = $contact->contact_id;
        $email->SendMail();

        // Mail::to($to)->send(new GrantRecommendation($name, $grants));
    }

    /**
     * Reminder if grants has been sitting in cart for long time
     * @param $records
     */
    static public function cronGrantReminder($records)
    {
        foreach($records as $contactId => $grants){
            $contact = Contact::getByContactId($contactId);
            if (!$contact) continue;
            $to = $contact->email_address;
            if (!$to) continue;

            if (env('APP_ENV') != 'prod') {
                $to = ClientEnv::value('EMAIL_GRANT_REMINDER_TO');
                if (!$to) $to = 'alkeshksingh@giftingnetwork.com';
            }

            $mailable = new GrantReminder($contact->name, $grants);

            /** @var Email $email */
            $email = new Email($to, $mailable);
            if (ClientInfo::isJCF() && env('APP_ENV') == 'prod') {
                $email->cc = ["email" => "grants@jcfsandiego.org"];
            } else {
                $email->cc = ClientEnv::value('EMAIL_CC');
            }
            Log::channel('cron')->info('Grant Reminder mail queued for ' . GnUtils::configEmailsToString($to));
            $email->SendMail();
        }
    }

    /**
     * @param ContactUs $contactUs
     */
    static public function requestInfo(ContactUs $contactUs)
    {
        $to = ClientEnv::value('EMAIL_REQ_INFO_TO');
        $mailable = new RequestInfo($contactUs);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->cc = ClientEnv::value('EMAIL_CC');
        $email->contactId = Contact::sessionContactId();
        $email->SendMail();
    }

    /**
     * Gift/Contribution: Debit/Create Card or Bank Transaction
     * @param \App\Models\Transaction $transaction
     */
    static public function transaction(\App\Models\Transaction $transaction)
    {
        /** @var User $user */
        $user = auth()->user();
        $to = $user->getAccountEmailAddress();
        $name = $user->getContactName();
        $mailable = new Transaction($name, $transaction);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        if (ClientInfo::isJCF() && env('APP_ENV') == 'prod') {
            $email->cc = [ "mandy@jcfsandiego.org", "josie@jcfsandiego.org", "alexg@jcfsandiego.org" ];
        } else {
            $email->cc = ClientEnv::value('EMAIL_CC');
        }
        $email->contactId = $user->getContactId();
        $email->SendMail();
    }

    /**
     * Donation: Debit/Create Card or Bank Transaction
     * @param \App\Models\Donation $donation
     */
    static public function donation(\App\Models\Donation $donation)
    {
        $to = $donation->guest_email;
        $name = $donation->guest_fname . ' ' . $donation->guest_lname;

        $mailable = new Donation($name, $donation);
        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->cc = ClientEnv::value('EMAIL_CC');
        $email->SendMail();
    }

    /**
     * daf/Contribution: Debit/Create Card or Bank Transaction
     * @param \App\Models\Transaction $transaction
     */
    static public function dafTransaction(\App\Models\Transaction $transaction)
    {
        /** @var User $user */
        $user = auth()->user();
        $to = $user->getAccountEmailAddress();
        $name = $user->getContactName();
        $mailable = new DAFTransaction($name, $transaction);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->cc = ClientEnv::value('EMAIL_CC');
        $email->contactId = $user->getContactId();
        $email->SendMail();
    }

    /**
     * DAF registration completed mail

     */
    static public function dafRegistrationCompleted()
    {

        /** @var User $user */
        $user = auth()->user();
        $to = $user->getAccountEmailAddress();
        $name = $user->getContactName();

        $mailable = new DAFCompleted($name);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->cc = ClientEnv::value('EMAIL_CC');
        $email->contactId = $user->getContactId();
        $email->SendMail();
    }

    /**
     * @param $to
     * @param $url
     */
    static public function emailChangeRequest($to, $url)
    {
        /** @var User $user */
        $user = User::getSessionUser();
        $name = $user->getContactName();
        $mailable = new EmailChangeRequest($name, $url);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        // $email->cc = ClientEnv::value('EMAIL_CC');
        $email->contactId = Contact::sessionContactId();
        $email->SendMail();
    }


    /**
     * @param $to
     * @param $name
     * @param $url
     */
    static public function emailChangeConfirmation($to, $name, $email)
    {
        /** @var User $user */
        $mailable = new EmailChangeConfirmation($name, $email);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->cc = ClientEnv::value('EMAIL_CC');
        $email->contactId = Contact::sessionContactId();
        $email->SendMail();
    }

    /**
     * @param $title
     * @param array $changes
     */
    static public function objectUpdatedNotification($title, Array $changes)
    {
        /** @var Contact $contact */
        $contact = Contact::sessionContact();
        $to = GnUtils::configEmailsToArray(ClientEnv::value('EMAIL_CLIENT_ADMIN'));
        $mailable = new ObjectUpdated($contact, $title, $changes);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->contactId = $contact->getModelId();
        $email->SendMail();
    }

    /**
     * @param $title
     * @param array $info
     */
    static public function objectDeletedNotification($title, Array $info)
    {
        /** @var Contact $contact */
        $contact = Contact::sessionContact();
        $to = ClientEnv::value('EMAIL_CLIENT_ADMIN');
        $mailable = new ObjectDeleted($contact, $title, $info);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->contactId = $contact->getModelId();
        $email->SendMail();
    }

    /**
     * @param $e
     */
    private function onException($e)
    {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage()];
        $activity = new LogActivity(LogActivity::NAME_EMAIL, LogActivity::ACTION_SEND_EMAIL);
        $activity->data($error)->description(GnUtils::configEmailsToString($this->to))->add();
    }


    /**
     * Advisor registration mail
     */
    static public function advisorRegistration(UserRegistration $advisor)
    {
        /** @var UserRegistration $advisor */

        $to = $advisor->email;
        $name = $advisor->first_name . ' ' . $advisor->last_name;
        $mailable = new AdvisorRegistrationConfirmation($name, $to);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->SendMail();
    }

    /**
     * DAF registration completed mail
     */
    static public function dafRegistrationCompletedByAdvisor($id)
    {
         /** @var User $user */
        $user = auth()->user();
        # $to = $user->getAccountEmailAddress();
        $to = 'rajanktiwari@giftingnetwork.com';

        $advisorName = $user->getContactName();

        $daf = DAFAccount::getDAFAccount($id);
        $donorInfo  = json_decode($daf->donor ?? '{}', true);
        $donorName = $donorInfo['first_name'] . ' ' . $donorInfo['last_name'] ?? 'Donor';
        
        $mailable = new AdvisorsDafCompleted($advisorName, $donorName);

        /** @var Email $email */
        $email = new Email($to, $mailable);
        $email->cc = ClientEnv::value('EMAIL_CC');
        $email->contactId = $user->getContactId();
        $email->SendMail();
        
    }
}
