<?php

namespace App\Models;

use App\GNA\GNAFunds;
use App\JCF\JCFFunds;
use Illuminate\Database\Eloquent\Model;

//fund_id: "JCFEX",
//agree_uri: null,
//anonymous: "N",
//balance: "11424.75",
//begin_balance: null,
//closed: "N",
//date_established: "2005-10-17",
//description: null,
//end_balance: null,
//fund_type: "Donor Advised Fund",
//grant_rec_type: "individual",
//investment_month: null,
//investment_performance: null,
//is_locked: "N",
//last_updated: "2015-12-11 23:10:12.481957",
//min_donation: null,
//name: "JCF Chief Executive Officers",
//pool_id: null,
//pool_name: null,
//trustee: null,
//trustee_id: null,
//statement_style: "cust",
//account_id: null,
//statement_balance: "11424.75"

class Fund extends BaseModel
{
    /* @var string */
    protected $table = 'fund';

    /* @var string */
    // protected $primaryKey = 'fund_id'; DON'T UNCOMMENT IT - it will not return Fund Id.

    /* @var boolean */
    public $timestamps = false;

    public function getModelId()
    {
        return $this->fund_id;
    }

    public function isModelIdInteger()
    {
        return false;
    }

    public function getModelType()
    {
        return "fund";
    }

    static public function getFundById($id) {
        return Fund::where('fund_id', 'ilike', $id)->first();
    }

    /**
     * NOT-IN-USE get all associated funds
     * @param null $id
     * @return mixed
     */
    static public function getSelectableByContactId($id=null) {
        if (!$id) $id = Contact::sessionContactId();
        $fundIds = ContactFund::getFundIdsByContactId($id);
        return Fund::whereIn('fund_id', $fundIds)->orderBy('name')->pluck('name', 'fund_id');
    }

    /**
     * get associated funds for grant recommendation
     * @param null $id
     * @return mixed
     */
    static public function getSelectableForGrantRecommendation($id=null) {
        if (!$id) $id = Contact::sessionContactId();
        $fundIds = ContactFund::getFundIdsForGrantRecommendationByContactId($id);
        $items = Fund::whereIn('fund_id', $fundIds)->orderBy('name')->pluck('name', 'fund_id');
        return $items->toArray();
    }

    /**
     * get associated funds for view
     * @param null $id
     * @return mixed
     */
    static public function getSelectableViewable($id=null) {
        if (!$id) $id = Contact::sessionContactId();
        $fundIds = ContactFund::getFundIdsForViewByContactId($id);
        $items = Fund::whereIn('fund_id', $fundIds)->orderBy('name')->pluck('name', 'fund_id');
        return $items->toArray();
    }

    /**
     * in use for Agency User's dashboard funds
     * @param $id
     * @return mixed
     */
    static public function getViewableByContactId($id) {
        $fundIds = ContactFund::getFundIdsForViewByContactId($id);
        $limit = (env('APP_ENV') == 'dev') ? 2 : 100;
        return Fund::whereIn('fund_id', $fundIds)->limit($limit)->orderBy('name')->get();
    }

    /**
     * @param $limit
     * @return array
     */
    static public function paginateMyFunds($limit) {
        $contactId = Contact::sessionContactId();

        $fundIds = ContactFund::getFundIdsForViewByContactId($contactId);
        if (!count($fundIds)) return [];

        return Fund::whereIn('fund_id', $fundIds)->orderBy('name')->paginate($limit);
    }

    static public function getAdvisorFunds($limit) {
        $contactId = Contact::sessionContactId();

        $fundIds = ContactFund::getFundIdsForViewByContactId($contactId);
        if (!count($fundIds)) return [];

        return Fund::whereIn('fund_id', $fundIds)->orderBy('balance', 'desc')->paginate($limit);
    }
    
    static public function advisorChartFundBalance() {
        $contactId = Contact::sessionContactId();

        $fundIds = ContactFund::getFundIdsForViewByContactId($contactId);
        if (!count($fundIds)) return [];

        return Fund::whereIn('fund_id', $fundIds)->orderBy('balance', 'desc')->get();
    }
    /*
     * For dashboard page - accessed on 'view'
     */
    public function getStatementBalance() {
        if (ClientInfo::isJCF()) {
            // return $this->statement_balance;
            return JCFFunds::getStatementBalance($this);
        } else if (ClientInfo::isGNA()) {
            return GNAFunds::getStatementBalance($this);
            // return $this->balance;
        } else if (ClientInfo::isGMF()) {
            return $this->balance;
        } else if (ClientInfo::isHGA()) {
            return $this->balance;
        } else if (ClientInfo::isNIF()) {
            return $this->balance;
        } else if (ClientInfo::isJSV()) {
            return $this->balance;
        } else if (ClientInfo::isCCT()) {
            return $this->balance;
        } else if (ClientInfo::isNTC()) {
            return $this->balance;
        } else {
            return $this->balance;
        }
    }

    /**
     * @param $text
     * @return array
     */
    static public function searchTypeahead($text)
    {
        //
        $data = [];
        // $models = self::where(['status' => 'Active'])->where('name', 'ilike', '%' . $text . '%')->limit(50)->get();
        $models = self::where([])->where('name', 'ilike', '%' . $text . '%')->orderBy('name')->limit(50)->get();
        /** @var Organization $model */
        foreach ($models as $model) {
            $data[] = [
                'label' => $model->name,
                'value' => "" . $model->fund_id,
                'source' => "" . $model->fund_id,
                // 'address' => "" . $model->getPrimaryAddressInline(),
                'id' => $model->fund_id
            ];
        }
        return $data;
    }


    /**
     * @param $id
     * @return string
     */
    static public function getNameById($id)
    {
        $name = Fund::where('fund_id', 'ilike', $id)->pluck('name')->first();
        return $name ? $name : $id;
    }

}
