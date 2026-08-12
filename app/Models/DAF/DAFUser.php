<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/14/2022
 * Time: 4:57 PM
 */

namespace App\Models\DAF;


class DAFUser
{
    public $first_name;
    public $last_name;
    public $email;

    /**
     * @param $contact
     * @return string
     */
    static public function createDAFUserJsonFromContact($contact)
    {
        $user = new DAFUser();
        $user->first_name = $contact->first_name;
        $user->last_name = $contact->last_name;
        $user->email = $contact->email_address;

        return json_encode($user);
    }

    public function name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}