<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docs extends Model
{
    const STATUS_UNPUBLISHED = 'unpublished';
    const STATUS_PUBLISHED = 'published';
    const STATUS_DELETED = 'deleted';

    const PRIVACY_PRIVATE = 'private';
    const PRIVACY_PROTECTED = 'protected';
    const PRIVACY_PUBLIC = 'public';

    /* @var string */
    protected $table = 'docs';

    // protected $primaryKey = null;
    // public $incrementing = false;

    /* @var boolean */
    // public $timestamps = false;

    static public function getInstance()
    {
        $model = new Docs();
        $model->owner_contact_id = Contact::sessionContactId();
        $model->approved = true;
        $model->status = self::STATUS_PUBLISHED;
        return $model;
    }

    static public function getDonorDocumentListByType($type)
    {
        $contactId = Contact::sessionContactId();
        $conditions = [
            'assoc_contact_id' => $contactId,
            'type' => $type,
            'approved' => true,
            'status' => Docs::STATUS_PUBLISHED
        ];

        return Docs::where($conditions)
            ->whereIn('privacy', [Docs::PRIVACY_PROTECTED, Docs::PRIVACY_PUBLIC])
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function savedFilename()
    {
        return pathinfo($this->file_path, PATHINFO_BASENAME);
    }

    public function userFilenameOnly()
    {
        return pathinfo($this->file_name, PATHINFO_FILENAME);
    }
}