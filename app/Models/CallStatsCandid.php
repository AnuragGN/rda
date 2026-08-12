<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use App\Helpers\GConst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class CallStatsCandid extends Model
{
    const STATUS_PENDING = "pending";
    const STATUS_SUCCESS = "success";
    const STATUS_FAILED = "failed";

    const APP_NAME = "responsive";

    const API_ESSENTIALS = "essentials";

    /* @var string */
    protected $table = 'call_stats_candid';

    // public $incrementing = false;

    static public function getEssentialInstance()
    {
        $model = new CallStatsCandid();
        $model->client_id = ClientInfo::client();
        $model->environment = env('APP_ENV'); // "dev", "qa", "uat", "prod"
        $model->contact_id = Contact::sessionContactId();
        $model->response = null; // jsonb;
    		// ["error" => ""]
            // ["response" => "2u2u elk2lk"]
        $model->status = self::STATUS_PENDING; // "pending", "success", "failed"
        $model->app_name = self::APP_NAME; // "responsive", "classic"
        $model->user_type = request()->session()->get(GConst::SESSION_ROLE); // "donor", "admin", "grantee", "agency"
        $model->page_url = url()->current(); // varchar(256),
        $model->api_type  = "essentials";
        $model->key = $model->client_id . '_' . $model->environment . '_' . intval(microtime(true) * 1000);
        return $model;
    }

}
