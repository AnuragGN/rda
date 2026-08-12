<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 9/16/2021
 * Time: 4:55 PM
 */

namespace App\Models;

use App\Helpers\GnUtils;
use Illuminate\Database\Eloquent\Model;

class EmailArchive extends Model
{
    /* @var string */
    protected $table = 'email_archive';

    static public function saveEmail(Email $email)
    {
        $model = new EmailArchive();
        $model->contact_id = $email->contactId;
        $model->organization_id = $email->orgId;

        $model->mail_type_id = $email->typeId;
        $model->mail_subject = $email->subject;
        $model->mail_to = GnUtils::configEmailsToString($email->to);
        $model->mail_cc = GnUtils::configEmailsToString($email->cc);
        // mail_bcc is not available in DB table
        // $model->mail_bcc = GnUtils::configEmailsToString($email->bcc);
        $model->mail_html = $email->html;
        $model->status = $email->status;
        $model->save();
        return $model;
    }

    static public function getPaginated()
    {
        return self::paginate();
    }

}