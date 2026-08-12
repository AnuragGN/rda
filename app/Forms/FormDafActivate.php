<?php

namespace App\Forms;

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 24-02-2020
 * Time: 18:36
 */
class FormDafActivate
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

}