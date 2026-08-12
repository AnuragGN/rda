<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//org_need_app_id: 174,
//app_order: null,
//campaign_end: "2019-08-31",
//campaign_start: "2018-09-01",
//date_submitted: "2019-02-22 20:24:27.579656",
//funding_opp: null,
//grant_num: null,
//img_url: "/images/need_app/1741550885068.jpg",
//last_update: "2019-02-22 20:24:27.579656",
//min_donation: "0",
//organization_id: 8023,
//pending_fund: null,
//preferred_email: null,
//priority: null,
//proposal_id: null,
//rec_code: null,
//report_completed: "N",
//staff_title: null,
//summary: "<p> text </p>",
//survey_id: 1,
//title: "Brava - Service Dog for Anxiety Disorders",
//total_requested: "5344",
//is_amount_ongoing: "N ",
//is_date_ongoing: "N "


class Project extends Model
{
    /* @var string */
    protected $table = 'org_need_app';

    /* @var string */
    // protected $primaryKey = 'fund_id'; DON'T UNCOMMENT IT - it will not return Fund Id.

    /* @var boolean */
    public $timestamps = false;


    public function organization()
    {
        return Organization::getById($this->organization_id);
        // return $this->hasOne('App\Models\Organization', "organization_id");
    }

}
