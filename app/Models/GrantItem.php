<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 25-10-2019
 * Time: 19:32
 */

namespace App\Models;

use App\Helpers\Data;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/*
 * GrantItem is MyCart Item
 */
class GrantItem extends BaseModel
{
    const STATUS_CREATED = 'C';
    const STATUS_FINAL = 'F';
    const STATUS_DELETED = 'D'; // deleted - new status (should be taken care of in old rahul's code)

    const GRANT_TYPE_GENERAL = 'general';
    const GRANT_TYPE_SPECIAL = 'special';

    // active, canceled, completed
    const RECUR_STATUS_ACTIVE = 'active';
    const RECUR_STATUS_CANCELED = 'canceled';
    const RECUR_STATUS_COMPLETED = 'completed';

    /* @var string */
    protected $table = 'recom_cart_details';

    /* @var string */
    protected $primaryKey = 'cart_id';

    /* @var boolean */
    public $timestamps = false;

    // Cache
    /** @var Organization */
    private $organization = null;

    public function getModelId()
    {
        return $this->cart_id;
    }

    public function getModelType()
    {
        return "grant-item";
    }

    public function isModelIdInteger()
    {
        return true;
    }

    public function fund()
    {
        return $this->belongsTo('App\Models\Fund', "fund_id", "fund_id");
    }

    // Ad-hoc orgs do not have an organization_id
    // public function organization()
    // {
    //    return $this->belongsTo('App\Models\Organization', "organization_id");
    // }

    public function getOrganization()
    {
        if (!$this->organization && $this->organization_id) {
            $this->organization = Organization::getById($this->organization_id);
        }
        return $this->organization;
    }

    /**
     * @return string
     */
    public function getOrgName()
    {
        /** @var Organization $org */
        $org = $this->getOrganization();
        return $org ? $org->name : $this->organization_name;
    }

    /**
     * @return OrganizationAddress
     */
    public function getOrgAddress()
    {
        /** @var Organization $org */
        $org = $this->getOrganization();
        if ($org) return $org->getAnyAddress();

        $address = new OrganizationAddress();
        $address->address_1 = $this->org_address1;
        $address->address_2 = $this->org_address2;
        $address->city = $this->org_city;
        $address->state = $this->org_state;
        $address->zip = $this->org_zip;
        $address->country = $this->org_country;
        return $address;
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function getById($id)
    {
        return self::where(['cart_id' => $id])->first();
    }

    /**
     * @param Contact $contact
     * @return array
     */
    static public function myCartItems($contact = null)
    {
        if (!$contact) $contact = Contact::sessionContact();
        if (!$contact) return [];

        $models = GrantItem::where(['contact_id' => $contact->contact_id, 'status' => GrantItem::STATUS_CREATED])
            ->orderBy('last_updated', 'DESC')
            ->get();
        return $models;
    }

    static public function countCartItems($contact = null)
    {
        if (!$contact) $contact = Contact::sessionContact();
        if (!$contact) return 0;

        $count = GrantItem::where(['contact_id' => $contact->contact_id, 'status' => 'C'])->count();
        return $count;
    }

    /**
     * @param $id
     * @param null $contact
     * @return true|string
     */
    static public function deleteById($id, $contact = null)
    {
        if (!$contact) $contact = Contact::sessionContact();
        if (!$contact) return "Please refresh page and retry!";
        $model = self::getById($id);
        if (!$model) return true; // doesn't exist
        if ($model->contact_id != $contact->contact_id) return "Permission denied!";
        $model->status = GrantItem::STATUS_DELETED;
        $model->save();
        return true;
    }

    /*
     * Obsolete: May be deleted
     * if there is a grant sitting in my cart for more than 2 weeks (MIN_WEEK) but less than 5 weeks (MAX_WEEK), then send a reminder mail
     */
    static public function sendPendingGrantReminder()
    {
        \Illuminate\Support\Facades\Log::info('Execute Grant Reminder process.');

        $minWeek = 2;
        $minWeekTs = date('Y-m-d H:i:s', strtotime("-" . $minWeek . " week"));
        $maxWeek = 500;
        $maxWeekTs = date('Y-m-d H:i:s', strtotime("-" . $maxWeek . " week"));

        $query = GrantItem::where(['status' => GrantItem::STATUS_CREATED]);
        $query->whereBetween('last_updated', [$maxWeekTs, $minWeekTs])->get();

        $grantItemsData = $query->groupBy('contact_id')
            ->selectRaw("COUNT(cart_id) as total_cart_items, contact_id")
            ->get();

        $informedToUserCount = 0;

        if (count($grantItemsData)) {
            foreach ($grantItemsData as $grantItem) {
                $pendingGrantCount = $grantItem['total_cart_items'];
                $contact_id = $grantItem['contact_id'];
                $contact = Contact::getByContactId($contact_id);
                // get user name , email
                // send email to user about $pendingGrantCount items in the user cart
                $informedToUserCount += 1;
            }
        }

        return $informedToUserCount;
    }

    public function getGrantingFrequencyAttribute()
    {
        return Data::displayableGrantingFrequency($this->frequency);
    }

    /**
     * added for JNY, Not for CCT
     * @return string
     */
    public function getGrantFromName()
    {
        if ($this->anonymous == 'Y' || !$this->from_contact_id) return '';
        $contact = Contact::getByContactId($this->from_contact_id);
        if (!$contact) return '';
        return $contact->name;
    }

    public function isRecurring()
    {
        return $this->frequency && $this->frequency !== Data::GRANTING_FREQUENCY_ONCE;
    }

    public function getRecurringStatus()
    {
        // active, canceled, completed
    }

    public function displayRecurringCount()
    {
        if (!$this->isRecurring()) return 1;
        if ($this->no_end == 'Y') return "Ongoing";
        return $this->occurrences;
    }

//    public function contact()
//    {
//        return $this->belongsTo('App\Models\Contact', "contact_id", "contact_id");
//    }

    /**
     * @return string
     */
    public function getTwoLineFromAddress()
    {
        $address = '';
        if ($this->from_address1) $address .= $this->from_address1 . ', ';
        if ($this->from_address2) $address .= $this->from_address2 . ', ';
        if ($this->from_address1 || $this->from_address2) $address .= '<br />';

        if ($this->from_city) $address .= $this->from_city . ', ';
        if ($this->from_state) $address .= $this->from_state . ' ';
        if ($this->from_zip) $address .= ' ' . $this->from_zip;

        return $address;
    }

    /**
     * set end date
     */
    public function setEndDate()
    {
        $startDate = Carbon::parse($this->start_date);
        $this->end_date = null;

        if (!$this->occurrences || $this->occurrences <= 0) return;

        switch ($this->frequency) {
            case 'weekly':
                $this->end_date = $startDate->addWeeks($this->occurrences);
                return;

            case 'monthly':
                $this->end_date = $startDate->addMonths($this->occurrences);
                return;

            case 'quarterly':
                $this->end_date = $startDate->addQuarters($this->occurrences);
                return;

            case 'semi-annual':
                $this->end_date = $startDate->addQuarters($this->occurrences*2);
                return;

            case 'annual':
                $this->end_date = $startDate->addYears($this->occurrences);
                return;
        }
        return;
    }
    static public function advisorCartItems($fund_ids,$limit)
    {
        $contact = Contact::sessionContact();
        if (!$contact) return [];

        $models = GrantItem::where('status', GrantItem::STATUS_CREATED)
            ->whereIn('fund_id',explode(',', $fund_ids))
            ->orderBy('last_updated', 'ASC')
            ->paginate($limit);
        return $models;
    }
    static public function advisorCartDetailItems($cart_id)
    {
        $contact = Contact::sessionContact();
        if (!$contact) return [];
        if (!$cart_id) return [];
        
        $models = GrantItem::where(['cart_id' => $cart_id,'status' => GrantItem::STATUS_CREATED])
            ->orderBy('last_updated', 'DESC')
            ->get();
        return $models;
    }
}
