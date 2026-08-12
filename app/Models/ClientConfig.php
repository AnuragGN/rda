<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 16-06-2020
 * Time: 20:03
 */

namespace App\Models;

class ClientConfig
{
    static $config = null;

    const DEFAULT_DATE_FORMAT = "M d, Y";

    static public function clientConfig()
    {
        if (!self::$config) {
            $client = ClientInfo::client();
            self::$config = 'custom.' . $client . '_config';
        }
        return self::$config;
    }

    /**
     * @return object
     */
    static public function object()
    {
        $defaultConfig = config('custom.default_config');
        $clientConfig = config(self::clientConfig());

        $array = self::array_merge_recursive_distinct($defaultConfig, $clientConfig);
        return self::array_to_object($array);
    }

    static private function array_to_object(array $array)
    {
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::array_to_object($value);
            }
        }
        return (object)$array;
    }

    /**
     * standard array_merge_recursive() is different than this funtion
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    static public function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value)
        {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
            {
                $merged[$key] = self::array_merge_recursive_distinct($merged[$key], $value);
            }
            else
            {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }

    /**************************************************************************/

    /**
     * date formats: "M d, Y" (default), "m/d/Y"
     * @return \Illuminate\Config\Repository|mixed
     */
    static public function dateFormat()
    {
        $key = self::clientConfig() . '.date_format';
        return config($key, self::DEFAULT_DATE_FORMAT);
    }

    static function message($var, $default='')
    {
        $key = self::clientConfig() . '.message.' . $var;
        $message =  config($key, null);
        if ($message === null) {
            $key = 'custom.gna_config.message.' . $var;
            $message = config($key, $default);
        }
        return $message;
    }

    static function text($var, $default='')
    {
        $key = self::clientConfig() . '.text.' . $var;
        $value =  config($key, null);
        if ($value === null) {
            $key = 'custom.gna_config.text.' . $var;
            $value = config($key, $default);
        }
        return $value;
    }

    static function value($var, $default=null)
    {
        $key = self::clientConfig() . '.value.' . $var;
        $value =  config($key, null);
        if ($value === null) {
            $key = 'custom.gna_config.value.' . $var;
            $value = config($key, $default);
        }
        return $value;
    }

    static function feature($var, $default=false)
    {
        $key = self::clientConfig() . '.feature.' . $var;
        $value =  config($key, null);
        if ($value === null) {
            $key = 'custom.gna_config.feature.' . $var;
            $value = config($key, $default);
        }
        return $value;
    }

    static public function assetServer()
    {
        return self::getEnv('ASSET_SERVER');
    }

    static public function getEnv($param, $default=null)
    {
        return env($param, $default);
    }

    static public function getReadablePDFDocumentListForDonor()
    {
        $key = self::clientConfig() . '.donor_docs_readable';
        return config($key, null);
    }

    static public function getWritablePDFDocumentListForDonor()
    {
        $key = self::clientConfig() . '.donor_docs_writable';
        return config($key, null);
    }

}
