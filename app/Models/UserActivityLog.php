<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $table = 'user_activity_log';

    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';

    const ST_LOGIN_DIRECT = 'direct';
    const ST_LOGIN_SSO = 'sso';


    /**
     * @param $type
     * @param $subtype
     * @param null $description
     */
    public static function add($type, $subtype, $description = null)
    {
        $userAction = new UserActivityLog();
        $userAction->auth_user_id = User::getSessionUserId();;
        $userAction->client_id = ClientInfo::client();
        $userAction->type = $type;
        $userAction->subtype = $subtype;
        $userAction->description = $description;
        $userAction->ip = request()->ip();
        $userAction->browser = substr(request()->header('User-Agent'), 0 , 255);
        $userAction->url = substr(url()->full(), 0, 128);
        # $userAction->save();
    }

}
