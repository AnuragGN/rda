<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partners extends BaseModel
{
    /* @var string */
    protected $table = 'partners';

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
        'partner_id',
        'status',
        'branding',
        'created_at',
        'updated_by',
        'updated_at'
    ];

     /**
     * @return string, fund|transaction|etc.
     */
    public function getModelType()
    {
        return 'partners';
    }

    /**
     * @return mixed model-id
     */
    public function getModelId()
    {
        return $this->id;
    }

    /**
     * Get partner by partner_id
     * @param string $partnerId
     * @return \App\Models\Partners|null
     */
    static public function getPartnerByPartnerId($partnerId)
    {
        return self::where('partner_id', $partnerId)
                ->where('status', 'Active')
                ->first();
    }
}
