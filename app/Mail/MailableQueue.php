<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 9/16/2021
 * Time: 1:21 PM
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class MailableQueue extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title = '';
    public $emailArchiveId = null;

    abstract public function getEmailTypeId();

    public function getSubject()
    {
        return $this->title;
    }

    public function setEmailArchiveId($id)
    {
        return $this->emailArchiveId = $id;
    }
}