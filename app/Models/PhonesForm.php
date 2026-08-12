<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 30-01-2020
 * Time: 16:18
 */

namespace App\Models;

class PhonesForm
{
    public $home_area;
    public $home_prefix;
    public $home_number;
    public $home_extension;

    public $business_area;
    public $business_prefix;
    public $business_number;
    public $business_extension;

    public $cell_area;
    public $cell_prefix;
    public $cell_number;

    public $fax_area;
    public $fax_prefix;
    public $fax_number;

    function __construct($phones) {

        foreach($phones as $phone) {
            if (isset($phone['phone_type']) && $phone['phone_type'] == 'HOME') {
                $this->home_area = '121';
                $this->home_prefix = '121';
                $this->home_number = '1211';
                $this->home_extension = '1211';
            }
            if (isset($phone['phone_type']) && $phone['phone_type'] == 'BUS1') {
                $this->business_area = '121';
                $this->business_prefix = '121';
                $this->business_number = '1211';
                $this->business_extension = '1211';
            }
            if (isset($phone['phone_type']) && $phone['phone_type'] == 'Cell') {
                $this->cell_area = '121';
                $this->cell_prefix = '121';
                $this->cell_number = '1211';
            }
            if (isset($phone['phone_type']) && $phone['phone_type'] == 'Cell') {
                $this->cell_area = '121';
                $this->cell_prefix = '121';
                $this->cell_number = '1211';
            }
        }

    }

}