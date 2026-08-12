<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    /* @var string */
    protected $table = 'config';

    /* @var string */
    // protected $primaryKey = 'fund_id'; DON'T UNCOMMENT IT - it will not return Fund Id.

    /* @var boolean */
    public $timestamps = false;

    static public function getRecommendationRequireApprovalAll()
    {
        $model = self::where(['config_param' => 'recommendation_require_approval_all'])->first();
        if (!$model) return '0';
        return $model->param_value;
    }

    /**
     * default 24 hrs, max 30 days
     *
     * @return int
     */
    static public function getPasswordResetTime()
    {
        $model = self::where(['config_param' => 'password_reset_time'])->first();
        if ($model) {
            $hours = intval($model->param_value);
            if ($hours > 0 && $hours < 720) {
                return $hours;
            }
        }
        return 24; // 1 days
    }

    /**
     * default 10, max 100
     *
     * @return int
     */
    static public function getPasswordMaxAttempt()
    {
        $model = self::where(['config_param' => 'password_max_attempt'])->first();
        if ($model) {
            $attempts = intval($model->param_value);
            if ($attempts > 0 && $attempts < 100) {
                return $attempts;
            }
        }
        return 10;
    }

    static public function getInterestAreaWeight()
    {
        $weight = 1;
        $model = self::where(['config_param' => 'interest_weight'])->first();
        if ($model) $weight = intval($model->param_value);
        return $weight > 0 ? $weight : 1;
    }

    static public function getPopulationServedWeight()
    {
        $weight = 1;
        $model = self::where(['config_param' => 'pop_weight'])->first();
        if ($model) $weight = intval($model->param_value);
        return $weight > 0 ? $weight : 1;
    }

    static public function getGeographicAreaWeight()
    {
        $weight = 1;
        $model = self::where(['config_param' => 'geo_weight'])->first();
        if ($model) $weight = intval($model->param_value);
        return $weight > 0 ? $weight : 1;
    }

    /**
     * check if Open DAF application link should be visible to 'donors'
     * @return bool
     */
    static public function enableDafAppDonor()
    {
        if (ClientInfo::isCCT()) {
            $model = self::where(['config_param' => 'enable_daf_app_donor'])->first();
            return $model ? $model->param_value == 'Y' : false;
        } else {
            return false;
        }
    }

    /**
     * check if Open DAF application link should be visible to 'public' (i.e. potential donors)
     * @return bool
     */
    static public function enableDafAppNewDonor()
    {
        if (ClientInfo::isCCT()) {
            $model = self::where(['config_param' => 'enable_daf_app_new_donor'])->first();
            return $model ? $model->param_value == 'Y' : false;
        } else {
            return true;
        }
    }

    static public function getAll()
    {
        return self::where([])->first();
    }

}