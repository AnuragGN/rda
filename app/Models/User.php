<?php

namespace App\Models;

use App\Helpers\GConst;
use App\Http\Traits\AuthTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/*
 * auth_user_id
 * active
 * being_reviewed
 * created_on
 * modified_on
 * transaction_password
 * username
 * has_changed_password
 */

class User extends Authenticatable
{
    use Notifiable;
    use AuthTrait;

    /* @var string */
    protected $table = 'auth_user';

    // DON'T UNCOMMENT IT - it will not return Fund Id.
    protected $primaryKey = 'auth_user_id';

    /* @var boolean */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'transaction_password' // , 'remember_token',
    ];

    public const PASSWORD_ALGO_BCRYPT = 'bcrypt';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public function getAuthIdentifierName() {
//        return 'auth_user_id'; // 'username'; // $this->username;
//    }

//    public function getAuthIdentifier() {
//        return $this->auth_user_id;
//    }

    public function getModelId() {
        return $this->auth_user_id;
    }

    public function isModelIdInteger() {
        return true;
    }

    public function getModelType() {
        return "user";
    }

    // DO NOT USE IT! Use new getFirstByUsername()
    static public function getByUsername($email) {
        return User::where(['username' => $email])->first();
    }

    static public function getFirstByUsername($email) {
        return User::where('username', 'ilike', $email)->first();
    }

    static public function existsUsername($email) {
        return User::where('username', 'ilike', $email)->exists();
    }

    /**
     * @param $user
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password)
    {
        $rehash = false; // rehash is disabled to avoid any conflicts with classic app
        if ($this->password_algo === User::PASSWORD_ALGO_BCRYPT) {
            $valid = Hash::check($password, (string) $this->password);
            
            if ($rehash && $valid && Hash::needsRehash((string)$this->password)) {
                $this->password = Hash::make($password);
                $this->save();

                LogActivity::safe(LogActivity::NAME_AUTH, LogActivity::ACTION_PASSWORD_REHASHED)
                    ->onModel($this)
                    ->description(LogActivity::DESCRIPTION_SUCCESS)
                    ->data(['reason' => 'needs rehash'])
                    ->add();
            }
            return $valid;
        }
        $encryptedHex = $this->encrypt($password);
        $result = hash_equals((string) $this->password, (string) $encryptedHex);
        if ($result) {
            $this->password = Hash::make($password);
            $this->password_algo = User::PASSWORD_ALGO_BCRYPT;
            $this->algo_modified_at = now();
            $this->save();
            LogActivity::safe(LogActivity::NAME_AUTH, LogActivity::ACTION_PASSWORD_MIGRATED)
                ->onModel($this)
                ->description(LogActivity::DESCRIPTION_SUCCESS)
                ->data(['reason' => 'from legacy to bcrypt'])
                ->add();
        }
        return $result;
    }

    static public function getFirstByCredentials($email, $password) {
        /** @var User $user */
        $user = static::getFirstByUsername($email);
        if (!$user || !$user->verifyPassword($password)) {
            return null;
        }
        return $user;
    }

    static public function getById($id) {
        return self::where(['auth_user_id' => $id])->first();
    }

    /**
     * get contact name
     * @return string
     */
    public function getContactName() {
        $contact = Contact::where(['auth_user_id' => $this->auth_user_id])->first();
        return $contact ? $contact->name : '';
    }

    /**
     * get contact first name
     * @return string
     */
    public function getContactFirstName() {
        $contact = Contact::where(['auth_user_id' => $this->auth_user_id])->first();
        return $contact ? $contact->first_name : '';
    }

    /**
     * get DAF username
     * @return string
     */
    public function getDAFUserName() {
        $dafUser = DAFAccount::where(['auth_user_id' => $this->auth_user_id])->first();
        // return $dafUser ? json_decode($dafUser->user, true) : '';
        if ($dafUser) {
            $user = json_decode($dafUser->user, true);
            $name = $user['first_name'] . ' ' . $user['last_name'];
        }

        return $dafUser ? $name : '';
    }

    /**
     * get contact id
     * @return string
     */
    public function getContactId() {
        $contact = Contact::where(['auth_user_id' => $this->auth_user_id])->first();
        return $contact ? $contact->contact_id : null;
    }

    /**
     * @return mixed
     */
    public function getAccountEmailAddress() {
        // check if username is an email
        if (strpos($this->username, '@') !== false) {
            return $this->username;
        }

        // get email from contact
        $contact = Contact::where(['auth_user_id' => $this->auth_user_id])->first();
        return $contact->email_address;
    }

    static public function getSessionUserEmail() {
        /* @var User $user */
        $user = auth()->user();
        return $user ? $user->getAccountEmailAddress() : "";
    }

    static public function getSessionUserId() {
        /* @var User $user */
        $user = auth()->user();
        return $user ? $user->auth_user_id : null;
    }

    static public function getSessionUser() {
        return auth()->user();
    }

    /**
     * @return bool|\Carbon\Traits\bool
     */
    public function isLocked()
    {
        // check if attempts are less than the maximum allowed
        $maxAttempts = Config::getMaxLoginAttempts();
        if ($this->attempts < $maxAttempts) {
            return false;
        }

        // check if the locked_till time is past the current time
        return !empty($this->locked_till) && now()->lessThan($this->locked_till);
    }

    /**
     * Auto unlock if the time has passed
     */
    public function autoUnlock()
    {
        // check if the locked_till time is past the current time
        if (empty($this->locked_till) || now()->lessThan($this->locked_till)) {
            return;
        }

        // reset lock
        $this->attempts = 0;
        $this->locked_till = null;
        $this->last_updated = Carbon::now();
        $this->save();
    }

    /**
     * Auto unlock if the time has passed
     */
    public function resetLock()
    {
        // reset lock
        $this->attempts = 0;
        $this->locked_till = null;
        $this->last_updated = Carbon::now();
        $this->save();
    }

    /**
     * it gets and resets the session value in SESSION_USERNAME_ONCE
     * @return int|mixed
     */
    static public function onceRemainingAttempts() {
        $username = session(GConst::SESSION_USERNAME_ONCE);
        // forget session username
        session()->forget(GConst::SESSION_USERNAME_ONCE);
        if (empty($username)) return 100;
        $user = User::getFirstByUsername($username);
        if (!$user) return 100;
        // Check if attempts are less than the maximum allowed
        $maxAttempts = Config::getMaxLoginAttempts();
        return max(0, $maxAttempts - $user->attempts);
    }

    /**
     * @return $this
     */
    public function onLoginFailed()
    {
        $this->attempts = $this->attempts <= 0 ? 1 : $this->attempts + 1;

        $maxAttempts = Config::getMaxLoginAttempts();
        if ($this->attempts == $maxAttempts) {
            $accountLockTimeInMinutes = Config::getLoginBlockedDuration();
            if ($accountLockTimeInMinutes > 0) {
                $this->locked_till = date('Y-m-d H:i:s', strtotime('+' . $accountLockTimeInMinutes . 'minutes'));
            }
        }

        $this->last_updated = Carbon::now();
        $this->save();
    }

    /**
     * reset the attempts
     */
    public function onLoginSuccess()
    {
        // reset attempts, etc.
        $this->attempts = 0;
        $this->locked_till = null;
        $this->last_login = date('Y-m-d H:i:s');
        $this->last_updated = date('Y-m-d H:i:s');
        $this->save();
    }

}
