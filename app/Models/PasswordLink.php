<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * password_link
 * valid - date
 * created_on - date
 * auth_user_id
 * pwd_count
 */

/**
 * Class Contact
 * @package App
 */

class PasswordLink extends Model
{
    /* @var string */
    protected $table = 'password_link';

    /* @var boolean */
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    /**
     * TODO: pwd_count is not being managed properly!
     * @param $user
     * @return string
     */
    public function generatePasswordLink($user, $dafActivateLink = false)
    {
        // check if exists
        $new = false;
        $model = self::where(['auth_user_id' => $user->auth_user_id])->first();
        if (!$model) {
            $new = true;
            $model = new PasswordLink();
        }

        $token = Str::random(40);
        $model->password_link = $token;

        $hours = Config::getPasswordResetTime();
        $model->valid = date("Y-m-d H:i:s", strtotime(sprintf("+%d hours", $hours)));
        $model->created_on = date('Y-m-d H:i:s');
        $model->auth_user_id = $user->auth_user_id;
        $model->pwd_count = 0;
        if ($new) {
            $model->save();
        } else {
            $model->pwd_count = $model->pwd_count + 1;
            self::where(['auth_user_id' => $user->auth_user_id])->update($model->toArray());
        }

        $routeName = $dafActivateLink ? 'activate-daf-form' : 'reset-password-form';
        $link = route($routeName, ['token' => $token]);

        return $link;
    }

    /**
     * Just set the valid time to current time, so that no new reset password is possible
     *
     * @param $user
     */
    static public function resetPasswordLink($user)
    {
        // check if exists
        $model = self::where(['auth_user_id' => $user->auth_user_id])->first();
        if ($model) {
            $valid = date('Y-m-d H:i:s');
            self::where(['auth_user_id' => $user->auth_user_id])->update(['valid' => $valid]);
        }
    }

    static public function getLinkUser($token)
    {
        // check if exists
        $model = self::where(['password_link' => $token])->first();
        if ($model) {
            // check if link has expired
            $now = date('Y-m-d H:i:s');
            if ($now > $model->valid) {
                return null;
            }
            // check if max attempts already reached
            $maxAttempts = Config::getPasswordMaxAttempt();
            $attempts = $model->pwd_count ? $model->pwd_count : 0;
            if ($maxAttempts <= $attempts) {
                return null;
            }

            $user = User::getById($model->auth_user_id);
            return $user;
        }
        return null;
    }

    static public function getAccountActivationLinkAttempts ($auth_user_id) {
        $model = self::where(['auth_user_id' => $auth_user_id])->first();
        return $model;
    }

    static public function resendAccountActivationLink ($auth_user_id)
    {
        $model = self::where(['auth_user_id' => $auth_user_id])->first();

        if (!$model) {
            $model = new PasswordLink();
            $model->auth_user_id = $auth_user_id;
            $model->pwd_count = 0;
        }

        $token = Str::random(40);

        self::where(['auth_user_id' => $auth_user_id])->update([
            'password_link' => $token,
            'pwd_count' => $model->pwd_count + 1,
        ]);

        $link = route('activate-daf-form', ['token' => $token]);
        return $link;
    }
}
