<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 12/27/2021
 * Time: 1:53 PM
 */

namespace App\Helpers;
use App\Models\LogActivity;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;


class SMSManager
{

    static $indianPhoneNumbers = [
        '9871745111', // alkesh
        '9540161591', // rajiv
		'9690017299', // ashish
        '8376811277'
    ];

    /**
     * @param $to
     * @param $code
     * @return bool
     */
    static public function sendVerificationCode($to, $code)
    {
        $phone = GnUtils::phoneNumbersOnly($to);
        if (strlen($phone) < 10) {
            self::addLog('Phone number length is less than 10!');
            return false;
        }

        if (in_array($phone, self::$indianPhoneNumbers)) {
            $phone = '+91' . $phone;
        } else {
            $phone = '+1' . $phone;
        }

        $host = request()->getHost();
        $message = 'Your temporary verification code on ' . $host . ' is: ' . $code;
        return self::sendSms($phone, $message);
    }

    /**
     * @param $to
     * @param $message
     * @return bool
     */
    static public function sendSms($to, $message)
    {
        $fromPhone = config('app.twilio.TWILIO_PHONE','');
        $authToken = config('app.twilio.TWILIO_AUTH_TOKEN', ''); // $config['TWILIO_AUTH_TOKEN'];
        $accountSid = config('app.twilio.TWILIO_ACCOUNT_SID', ''); // $config['TWILIO_ACCOUNT_SID'];

        try {
            $client = new Client($accountSid, $authToken);
            $client->messages->create(
                $to, // the number you'd like to send the message to
                [
                    'from' => $fromPhone,
                    'body' => $message
                ]
            );
            self::addLog('To: ' . $to . ', Message:' . $message);
            return true;
        }
        catch (\Exception $e) {
            self::addLog("Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param $message
     */
    static private function addLog($message)
    {
        $activity = new LogActivity(LogActivity::NAME_SMS, LogActivity::ACTION_SEND_SMS);
        $activity->description($message)->add();
    }

}
