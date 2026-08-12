<?php

namespace App\Http\Traits;
use App\Models\User;
use Illuminate\Validation\ValidationException;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 31-10-2019
 * Time: 12:57
 */
trait AuthTrait
{

    //use AuthenticatesUsers;

    public function encrypt($text)
    {
        // $text = 'chestercap';
        $cipher = 'bf-cbc';
        $key = "+9))u*,--ak<K;wpS/I{c`R`aKy+jaaY>J3=2-G.b1q SG?uio[cl6JT";
        $option = OPENSSL_RAW_DATA;
        $iv = '39480126';
        $crypted = openssl_encrypt($text, $cipher, $key, $option, $iv);
        $pass = unpack("H*", $crypted);
        return isset($pass[1]) ? $pass[1] : $pass;
        // return pack("H*", $crypted);
    }

    public function authenticateUser(Request $request)
    {

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('email');
        $password = $this->encrypt($request->input('password'));

        $user = User::where(['username' => $username, 'password' => $password])->first();
        if ($user && $user->active == "Y") {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/home');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], 
        ]);
        // return $this->sendFailedLoginResponse($request);
    }

    /**
     * Reset password - Save new password
     *
     * @param $user
     * @param $password
     */
    public function savePassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->password_algo = User::PASSWORD_ALGO_BCRYPT;
        $user->algo_modified_at = now();
        // $user->password = $this->encrypt($password);
        $user->modified_on = date('Y-m-d H:i:s');
        $user->has_changed_password = "Y";
        $user->save();
    }


}
