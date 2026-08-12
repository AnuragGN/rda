<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail as SendGridMail;
use Exception;

class MailService
{
    /**
     * Send mail with SMTP first, fallback to SendGrid API
     */
    public static function send($to, $subject, $html)
    {
        try {
            // Try Laravel SMTP
            Mail::send([], [], function ($message) use ($to, $subject, $html) {
                $message->to($to)
                        ->subject($subject)
                        ->setBody($html, 'text/html');
            });

            Log::info("Mail sent via SMTP to {$to}");
            return true;

        } catch (Exception $e) {

            Log::warning("SMTP failed: " . $e->getMessage());

            // Fallback to API
            return self::sendViaApi($to, $subject, $html, $e);
        }
    }

    /**
     * Send using SendGrid API
     */
    protected static function sendViaApi($to, $subject, $html, $previousException)
    {
        try {
            $email = new SendGridMail();
            $email->setFrom(
                config('mail.from.address'),
                config('mail.from.name')
            );

            $email->setSubject($subject);
            $email->addTo($to);
            $email->addContent("text/html", $html);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $sendgrid->send($email);

            Log::info("Mail sent via SendGrid API to {$to}");

            return true;

        } catch (Exception $e) {

            Log::error("SendGrid API failed", [
                'smtp_error' => $previousException->getMessage(),
                'api_error'  => $e->getMessage(),
                'to'         => $to,
            ]);

            return false;
        }
    }
}
