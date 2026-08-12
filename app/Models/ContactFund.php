<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFund extends Model
{
    /* @var string */
    protected $table = 'contact_fund';

    /* @var string */
    // protected $primaryKey = 'fund_id'; DON'T UNCOMMENT IT - it will not return Fund Id.

    /* @var boolean */
    public $timestamps = false;

    // NOT-IN-USE : USE getViewableFundIdsByContactId()
    static public function getFundIdsByContactId($id) {
        $fundIds = ContactFund::where(['contact_id' => $id])
            ->pluck('fund_id');
        return $fundIds->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function getViewableFundIdsByContactId($id) {
        $fundIds = ContactFund::where(['contact_id' => $id, 'viewable' => 'Y'])
            ->orderBy('fund_id')
            ->pluck('fund_id');
        return $fundIds->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function getFundIdsForGrantRecommendationByContactId($id) {
        $fundIds = ContactFund::where(['contact_id' => $id, 'make_grant_recommendation' => 'Y'])
            ->pluck('fund_id');
        return $fundIds->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function getFundIdsForViewByContactId($id) {
        $fundIds = ContactFund::where(['contact_id' => $id, 'viewable' => 'Y'])
            ->pluck('fund_id');
        return $fundIds->toArray();
    }

    /**
     * @param $fundId
     * @param null $contactId
     * @return mixed
     */
    static public function isAssociated($fundId, $contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();

        return self::where(['contact_id' => $contactId])
            ->where('fund_id', 'ilike', $fundId)
            ->exists();
    }

    /**
     * @param $fundId
     * @param null $contactId
     */
    static public function assertAssociation($fundId, $contactId=null)
    {
        if (!ContactFund::isAssociated($fundId, $contactId)) {
            abort(403, "You do not have access to this fund");
        }
    }

    /**
     * @param $fundId
     * @param null $contactId
     * @return mixed
     */
    static public function isViewable($fundId, $contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();

        return self::where(['contact_id' => $contactId])
            ->where('fund_id', 'ilike', $fundId)
            ->where('viewable', 'Y')
            ->exists();
    }

    /**
     * @param $fundId
     * @param null $contactId
     */
    static public function assertViewable($fundId, $contactId=null)
    {
        if (!ContactFund::isViewable($fundId, $contactId)) {
            abort(403, "You do not have access to this fund");
        }
    }

    /**
     * @param $fundId
     * @param null $contactId
     * @return mixed
     */
    static public function canRecommendGrant($fundId, $contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();

        return self::where(['contact_id' => $contactId])
            ->where('fund_id', 'ilike', $fundId)
            ->where('make_grant_recommendation', 'Y')
            ->exists();
    }

    /**
     * @param $fundId
     * @param null $contactId
     */
    static public function assertRecommendGrant($fundId, $contactId=null)
    {
        if (!ContactFund::canRecommendGrant($fundId, $contactId)) {
            abort(403, "You do not have access to this fund");
        }
    }

    /**
     * @InUse for GNA
     * For JNY: Replaced by FundRepType::getPTAContactIdsByFundId($fundId)
     * get Contact-Ids associated with a Fund-Id
     * @param $id
     * @return array
     */
    static public function getContactIdsByFundId($id) {
        $contactFunds = ContactFund::where(['fund_id' => $id])->get();
        if (!count($contactFunds)) return [];
        $contactIds = [];
        foreach($contactFunds as $contactFund) {
            $contactIds[] = $contactFund->contact_id;
        }
        return $contactIds;
    }

    static public function getByFundId($id) {
        return ContactFund::where(['fund_id' => $id])->get();
    }

    static public function getAdvisorContacts($limit,$fund_id,$search_contact,$type) {

        $contactId = Contact::sessionContactId();
        $fundIds = ContactFund::getFundIdsForViewByContactId($contactId);
        if (!count($fundIds)) return [];

        $query = ContactFund::select('contact_fund.*')
                ->leftJoin('contact', 'contact_fund.contact_id', '=', 'contact.contact_id') 
                ->where('contact_fund.contact_type_id', 10)
                ->whereIn('contact_fund.fund_id', $fundIds);

        if ($fund_id) {
            $query->where('contact_fund.fund_id', $fund_id);
        }

        if ($search_contact) {
            $query->where(function ($q) use ($search_contact) {
                $q->whereRaw("CONCAT(contact.first_name, ' ', contact.last_name) LIKE ?", ['%' . $search_contact . '%']);
            });
        }
        $query->distinct('contact_fund.contact_id');
        
        if ($type == 'count') {
            return $query->count();

        } else {
            return $query->paginate($limit);
        }    
    }
}
