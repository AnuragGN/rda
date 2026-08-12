<?php
/**
 * Date: 9/18/2022
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthUserGroupMap extends Model
{
    /* @var string */
    protected $table = 'auth_user_group_map';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    public static function addAuthUserGroupMap ($authGroupId, $authUserId)
    {
        $groupMap = new AuthUserGroupMap();
        $groupMap->auth_group_id = $authGroupId;
        $groupMap->auth_user_id = $authUserId;
        return $groupMap->save();
    }

    /**
     * check if user belongs to a auth-group
     * @param $authUserId
     * @param $authGroupId
     * @return mixed
     */
    static public function userBelongsToGroup($authUserId, $authGroupId)
    {
        return AuthUserGroupMap::where([
            'auth_user_id' => $authUserId,
            'auth_group_id' => $authGroupId
        ])->exists();
    }
}
