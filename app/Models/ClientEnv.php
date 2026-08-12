<?php
/**
 * Author: alkesh
 * Date: 08-02-2021
 * Time: 20:03
 */

namespace App\Models;

use Illuminate\Support\Facades\App;

class ClientEnv
{
    static $config = null;

    static public function clientEnv()
    {
        if (!self::$config) {
            $env = App::environment();
            $client = ClientInfo::client();
            self::$config = $env . '.' . $client . '_env';
        }
        return self::$config;
    }

    /**
     * @return object
     */
    static public function object()
    {
        $array = config(self::clientEnv());
        return [self::array_to_object($array)];
    }

    static private function array_to_object(array $array)
    {
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::array_to_object($value);
            }
        }
        // return $array;
        return (object)$array;
    }

    /**************************************************************************/

    static function message($var, $default='')
    {
        $key = self::clientEnv() . '.message.' . $var;
        $message =  config($key, null);
        if ($message === null) {
            $key = App::environment() . '.gna_env.message.' . $var;
            $message = config($key, $default);
        }
        return $message;
    }

    static function text($var, $default='')
    {
        $key = self::clientEnv() . '.text.' . $var;
        $value =  config($key, null);
        if ($value === null) {
            $key = App::environment() . '.gna_env.text.' . $var;
            $value = config($key, $default);
        }
        return $value;
    }

    static function value($var, $default=null)
    {
        $key = self::clientEnv() . '.value.' . $var;
        $value =  config($key, null);
        if ($value === null) {
            $key = App::environment() . '.gna_env.value.' . $var;
            $value = config($key, $default);
        }
        return $value;
    }

    static function feature($var, $default=false)
    {
        $key = self::clientEnv() . '.feature.' . $var;
        $value =  config($key, null);
        if ($value === null) {
            $key = App::environment() . '.gna_env.feature.' . $var;
            $value = config($key, $default);
        }
        return $value;
    }

}
