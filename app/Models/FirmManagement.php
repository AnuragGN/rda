<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirmManagement extends BaseModel
{
    /* @var string */
    protected $table = 'firm_management';

    /* @var string */
    protected $primaryKey = 'id';

    /* @var boolean */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        '_remote_id',
        'type',
        'website',
        'email_address',
        'phone_number',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'country',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * @return mixed model-id
     */
    public function getModelId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getModelType()
    {
        return 'firm_management';
    }

    /**
     * Check if a firm exists by _remote_id
     * @param string $remoteId
     * @return bool
     */
    public static function isFirmExists($firmId)
    {
        return self::where('_remote_id', $firmId)
            ->where('status', 'Active')
            ->exists();
    }

    /**
     * Relationship with contact who created the firm management record
     */
    public function createdBy()
    {
        return $this->belongsTo(Contact::class, 'created_by', 'contact_id');
    }

    /**
     * Relationship with contact who updated the firm management record
     */
    public function updatedBy()
    {
        return $this->belongsTo(Contact::class, 'updated_by', 'contact_id');
    }

    /**
     * Relationship with contact who deleted the firm management record
     */
    public function deletedBy()
    {
        return $this->belongsTo(Contact::class, 'deleted_by', 'contact_id');
    }
}
