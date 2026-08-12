<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundPool extends Model
{
    /* @var string */
    protected $table = 'fund_pools';

    // For NIF Only
    static $nifLinks = [
        'VFIAX' => 'https://investor.vanguard.com/mutual-funds/profile/performance/vfiax',
        'VBTLX' => 'https://investor.vanguard.com/mutual-funds/profile/VBTLX',
        'VTIAX' => 'https://investor.vanguard.com/mutual-funds/profile/vtiax',
        'ESGV' => 'https://investor.vanguard.com/etf/profile/ESGV',
        'VSGX' => 'https://investor.vanguard.com/etf/profile/VSGX',
    ];

    static public function getAll()
    {
        $pools = [];
        $models = FundPool::where([])->orderBy('priority')->get();

        foreach($models as $model) {

            // for NIF Only
            if ($model->pool_id == Investments::STIFEL_POOL_ID) {
                /** @var User $user */
                $user = User::getSessionUser();
                // only Stifel can see this pool
                if ($user && $user->getAccountEmailAddress() != Investments::STIFEL_DONOR_EMAIL) {
                    continue;
                }
            }

            // For NIF Only
            $poolId = $model->pool_id;
            if (isset(self::$nifLinks[$poolId])) {
                $model->pool_link = self::$nifLinks[$poolId];
            } else {
                $model->pool_link = '';
            }
            $pools[] = $model;
        }
        return $pools;
        // return $models;
        // return FundPool::where([])->orderBy('priority')->get();
    }

//    /* @var boolean */
//    public $timestamps = false;

}