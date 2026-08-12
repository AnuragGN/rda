<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaSponser extends Model
{
    protected $table = 'fa_sponsor';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $keyType = 'int';
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        "id",
        "sponsor_id",
        "name",
        "status",
        "updated_by",
    ];

    public $sortable = ['id', 'sponsor_id', 'name', 'status', 'created_at', 'updated_at'];

    /**
     * @return \App\Models\FaSponser|null
     */
    static public function getDafSponsors()
    {
        return self::where('status', 'active')
                ->get();
    }

    public static function getDafSponsorById($sponsorId)
    {
        return self::where('id', $sponsorId)
            ->where('status', 'active')
            ->first();
    }
}
