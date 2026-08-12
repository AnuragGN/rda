<?php

namespace App\Models;

use App\Helpers\Data;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//fund_recommendation_id: 8,
//amount: "100",
//anonymous: "N",
//contact_id: 15188,
//contact_name: null,
//contact_phone: null,
//contact_title: null,
//date_submitted: "2016-05-05 18:50:19.724432",
//fund_id: "Landau",
//grant_num: null,
//grant_purpose: "In honor of Dotche",
//notes: "",
//organization_id: 6159,
//org_address1: "4950 Murphy Canyon Road",
//org_address2: null,
//org_city: "San Diego",
//org_name: "Kelly and Jeremy Pearl Fund of the JCF",
//org_need_app_id: null,
//org_state: "California",
//org_zip: "92123",
//payment_schedule: "asap",
//is_approved: "Y",
//status: "approved",
//last_updated: "2016-05-05 18:50:19.724432",
//_remote_id: null,
//primary_contact_id: null,
//approved_date: "2016-05-05 23:16:11",
//batch_no: null,
//default_grant_date: null,
//default_board_date: null,
//default_payment_date: null,
//default_action: null,
//default_payment_type: null,
//default_grant_status: null

class FundRecommendation extends Model
{
    // Also pay attention to the following statuses
    // $status
    // $is_approved
    // $grant_paid_status
    // recurring_status
    const GRANT_STATUS_PENDING = "pending";
    const GRANT_STATUS_SUBMITTED = "submitted"; // by donor
    const GRANT_STATUS_APPROVED = "approved"; // by admin
    // granted and paid might be same
    const GRANT_STATUS_PAID = "paid"; // might be paid from donor account
    const GRANT_STATUS_GRANTED = "granted"; // i.e. granted to grantee
    const GRANT_STATUS_CANCELLED = "cancelled"; // i.e. granted to grantee


    /* @var string */
    protected $table = 'fund_recommendation';

    /* @var string */
     protected $primaryKey = 'fund_recommendation_id';

    /* @var boolean */
    public $timestamps = false;

    /**
     * @param $grants
     * @return array|bool
     */

    static public function getRecommendationById($id) {
        return FundRecommendation::where('fund_recommendation_id', 'ilike', $id)->first();
    }

    static public function saveFromCartCCT($grants)
    {
        $data = [];
        if (!count($grants)) return false;

        $isApproved = "Y";

        /** @var GrantItem $grant */
        foreach($grants as $grant) {
            $model = new FundRecommendation();
            $model->origin = 'gn';

            $model->fund_id = $grant->fund_id;
            $model->amount = $grant->amount;

            // last_grant_date = last_grant_date;
            // org_nickname = org_nickname;
            // org_website = org_website;
            // grant_approval_date = grant_approval_date;

            // added for CCT
            $model->is_closing_grant = $grant->is_closing_grant;
            $model->requested_disbursement_date = $grant->requested_disbursement_date;
            $model->show_fund_name = $grant->show_fund_name;
            $model->show_advisor_name = $grant->show_advisor_name;
            $model->show_advisor_address = $grant->show_advisor_address;
            $model->from_name = $grant->from_name;
            $model->from_address1 = $grant->from_address1;
            $model->from_address2 = $grant->from_address2;
            $model->from_city = $grant->from_city;
            $model->from_state = $grant->from_state;
            $model->from_zip = $grant->from_zip;
            $model->recurring_status = $grant->recurring_status;

            $model->frequency = $grant->frequency;
            $model->no_end = $grant->no_end;
            $model->anonymous = $grant->anonymous;
            $model->occurrences = $grant->occurrences;
            $model->contact_id = $grant->contact_id;
            $model->from_contact_id = $grant->from_contact_id;

            $model->purpose_type = $grant->purpose_type;
            $model->grant_purpose = $grant->grant_purpose;
            $model->notes = $grant->notes;
            $model->dedication_type = $grant->dedication_type;
            $model->grant_dedication = $grant->grant_dedication;
            $model->notification_info = $grant->notification_info;

            if ($grant->organization_id) {
                $model->organization_id = $grant->organization_id;
            }

            /** @var Organization $organization */
            $organization = $grant->getOrganization();
            $model->primary_contact_id = null;

            if ($organization) {
                $primaryContact = $organization->getPrimaryContactInfoForRecommendation();
                // $model->contact_name = $primaryContact['name'];
                // $model->contact_title = $primaryContact['title'];
                // $model->contact_phone = str_replace("-", "", $primaryContact['phone']); ;
                // $model->primary_contact_id = $primaryContact['contact_id'];
                // $model->org_email = '';

                $model->org_name = $organization->name;
                $model->org_ein = str_replace("-", "", $organization->ein);
                $model->org_website = $organization->web_site;

                $address = $organization->getAnyAddress();
                if ($address) {
                    $model->org_address1 = $address->address_1 ? $address->address_1 : '';
                    $model->org_address2 = $address->address_2 ? $address->address_2 : '';
                    $model->org_city = $address->city ? $address->city : '';
                    $model->org_state = $address->state ? $address->state : '';
                    $model->org_country = $address->country ? $address->country : '';
                    $model->org_zip = $address->zip ? $address->zip : '';
                }
            } else {
                $model->org_name = $grant->organization_name;
                $model->org_ein = str_replace("-", "", $grant->org_ein);

                $model->org_address1 = $grant->org_address1;
                $model->org_address2 = $grant->org_address2;
                $model->org_city = $grant->org_city;
                $model->org_state = $grant->org_state;
                $model->org_country = $grant->org_country;
                $model->org_zip = $grant->org_zip;
            }

            $model->contact_name = $grant->org_contact;
            $model->contact_title = $grant->org_contact_title;
            $model->contact_phone = str_replace("-", "", $grant->org_phone);
            $model->contact_email = $grant->org_email;

            /*
            $model->org_name = $grant->getOrgName();
            $model->org_ein = $grant->org_ein ? $grant->org_ein : '';
            $model->org_email = $grant->org_email ? $grant->org_email : '';

            // organization contact
            $primaryContact = null;
            if ($organization) {
                $primaryContact = $organization->getPrimaryContactInfoForRecommendation();
            } else {
                $primaryContact = ['title' => '', 'name' => $grant->org_contact, 'phone' => $grant->org_phone, 'contact_id' => null];
            }
            $model->contact_title = $primaryContact['title'];
            $model->contact_name = $primaryContact['name'];
            $model->contact_phone = str_replace("-","",$primaryContact['phone']); ;
            $model->primary_contact_id = $primaryContact['contact_id'];

            // set address
            $address = $grant->getOrgAddress();
            if ($address) {
                $model->org_address1 = $address->address_1 ? $address->address_1 : '';
                $model->org_address2 = $address->address_2 ? $address->address_2 : '';
                $model->org_city = $address->city ? $address->city : '' ;
                $model->org_state = $address->state ? $address->state : '';
                $model->org_country = $address->country ? $address->country : '';
                $model->org_zip = $address->zip ? $address->zip : '';
            }
            */

            /********************/

            $model->is_approved = $isApproved;
            $model->status = 'approved';
            $model->grant_status = FundRecommendation::GRANT_STATUS_PENDING;

            $model->next_run_date = null;
            $model->last_grant_date = null;
            $model->remaining_grants = $grant->occurrences;

            // save the fund recommendation
            $model->save();

            if ($grant->cart_id) {
                // update grant-item status
                $grant->status = GrantItem::STATUS_FINAL;
                $grant->save();
            }

            $data['grants'][] = $grant;
            $data['recommendations'][] = $model;
        }

        return $data;
    }

    /**
     * @param $grants
     * @return array|bool
     */
    static public function saveFromCart($grants)
    {
        if (ClientInfo::isCCT()) {
            return self::saveFromCartCCT($grants);
        }

        $data = [];
        if (!count($grants)) {
            return false;
        }

        $approvalRequired = Config::getRecommendationRequireApprovalAll();
        $isApproved = ($approvalRequired == "Y") ? "N" : "Y";

        /** @var GrantItem $grant */
        foreach($grants as $grant) {
            $model = new FundRecommendation();
            $model->origin = 'gn';

            /** @var Organization $organization */
            $organization = $grant->getOrganization();

            //$data[] = ['organization' => $organization, 'address' => $address, 'primaryContact' =>$primaryContact];
            //continue;

            $model->fund_id = $grant->fund_id;
            $model->amount = $grant->amount;
            $model->frequency = $grant->frequency;
            $model->anonymous = $grant->anonymous;
            $model->contact_id = $grant->contact_id;
            $model->from_contact_id = $grant->from_contact_id;
            if ($grant->organization_id) {
                $model->organization_id = $grant->organization_id;
            }
            $model->org_name = $grant->getOrgName();
            $model->purpose_type = $grant->purpose_type;
            $model->grant_purpose = $grant->grant_purpose;
            $model->dedication_type = $grant->dedication_type;
            $model->grant_dedication = $grant->grant_dedication;


            $model->notes = $grant->notes;

            $model->org_ein = $grant->org_ein ? str_replace("-", "", $grant->org_ein) : '';
            $model->contact_email = $grant->org_email ? $grant->org_email : '';

            // organization contact
            $primaryContact = null;
            if ($organization) {
                $primaryContact = $organization->getPrimaryContactInfoForRecommendation();
            } else {
                $primaryContact = ['title' => '', 'name' => $grant->org_contact, 'phone' => $grant->org_phone, 'contact_id' => null];
            }
            $model->contact_title = $primaryContact['title'];
            $model->contact_name= $primaryContact['name'];
            $model->contact_phone = str_replace("-","",$primaryContact['phone']); ;
            $model->primary_contact_id = $primaryContact['contact_id'];

            // set address
            $address = $grant->getOrgAddress();
            if ($address) {
                $model->org_address1 = $address->address_1 ? $address->address_1 : '';
                $model->org_address2 = $address->address_2 ? $address->address_2 : '';
                $model->org_city = $address->city ? $address->city : '' ;
                $model->org_state = $address->state ? $address->state : '';
                $model->org_country = $address->country ? $address->country : '';
                $model->org_zip = $address->zip ? $address->zip : '';
            }
            $model->is_approved = $isApproved;
            $model->status = 'approved';

            // save the fund recommendation
            $model->save();

            if ($grant->cart_id) {
                // update grant-item status
                $grant->status = GrantItem::STATUS_FINAL;
                $grant->save();
            }

            $data['grants'][] = $grant;
            $data['recommendations'][] = $model;
        }

        return $data;
    }

    /**
     * @returns null, if there are no more funds associated with the contact
     * @returns array, if there are funds associated with the contact
     * @param Request $request
     * @return null
     */
    static public function paginatePendingRecommendations(Request $request)
    {
        $contactId = Contact::sessionContactId();

        $limit = 3;
        $page = $request->page ? $request->page : 1;
        $offset = ($page - 1) * $limit;
        $fundIds = ContactFund::where([
            'contact_id' => $contactId,
            'viewable' => 'Y'
        ])
            ->orderBy('fund_id')
            ->offset($offset)
            ->limit($limit)
            ->pluck('fund_id');
        if (count($fundIds) < 1) return null;

        $date = date('Y-m-d');
        if (ClientInfo::isCCT()) {
            // Note: 'is_approved' is always 'Y' as admin-approval is not required
            // keep it for later
            // for CCT, this query could be based on the new field - "grant_status"
            $models = FundRecommendation::where('is_approved', 'N')->whereIn('fund_id', $fundIds)
                ->orWhere(function ($query) use ($fundIds, $date) {
                    $query->where('is_approved', 'Y')->where('grant_paid_status', null)
                        ->whereIn('fund_id', $fundIds);
                })->orderBy('org_name')->get();
        } else if (ClientInfo::isJCF()) {
            $models = FundRecommendation::where('is_approved', 'N')->whereIn('fund_id', $fundIds)
                ->orWhere(function ($query) use ($fundIds, $date) {
                    $query->where('is_approved', 'Y')->where('default_payment_date', '>=', $date)
                        ->whereIn('fund_id', $fundIds);
                })->orderBy('org_name')->get();
        } else {
            $models = FundRecommendation::where('is_approved', 'N')->whereIn('fund_id', $fundIds)
                ->orWhere(function ($query) use ($fundIds, $date) {
                    $query->where('is_approved', 'Y')->where('grant_paid_status', null)
                        ->whereIn('fund_id', $fundIds);
                })->orderBy('org_name')->get();	
        }

        return $models;
    }

    static public function getRecentGrantRecommendations()
    {
        $contactId = Contact::sessionContactId();

		// TODO: Lawakush - fund_ids?
		// whereIn('fund_id', contactFundIds);
        $condition = [
            'contact_id' => $contactId,
            'status' => 'approved',
            'is_approved' => 'Y'
        ];
        return self::where($condition)
            ->orderBy('date_submitted', 'desc')->take(5)->get();

    }

    /**
     * @param null $fundId
     * @return array
     */
    static public function getRecurringGrants($fundId=null)
    {
        $contact = Contact::sessionContact();
        if (!$contact) return [];

        $query  = FundRecommendation::where(['contact_id' => $contact->contact_id,])->whereNotIn('frequency', ['once']);
        if ($fundId && $fundId != 'all') {
            $query->where('fund_id', $fundId);
        }
        $models = $query->orderBy('last_updated', 'DESC')->get();

        /** @var FundRecommendation $model */
        foreach ($models as $model) {
            // not required
            // $data = $model->getRecurringGrantInfo();
            // $model->next_run_date = $data['next_run_date'];
            // $model->remaining_grants = $data['remaining_grants'];
            // $model->last_grant_date = $data['last_grant_date'];
        }
        return $models;
    }


    public function getNextPaymentDate($date)
    {
        switch ($this->frequency) {
            case 'weekly':
                return $date->addWeek();

            case 'monthly':
                return $date->addMonth();

            case 'quarterly':
                return $date->addQuarter();

            case 'semi-annual':
                return $date->addQuarters(2);

            case 'annual':
                return $date->addYear();
        }
        return $date;
    }

    /**
     * @return array
     */
    public function getRecurringGrantInfo()
    {
        $today = Carbon::now();

        // if (!$this->start_date) {
        //     $this->start_date = $this->requested_disbursement_date;
        // }
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        $occurrences = $this->occurrences;

        $grantDetails = [];

        if ($today > $endDate) {
            $grantDetails['last_grant_date'] = $endDate;
            $grantDetails['next_run_date'] = null;
            $grantDetails['remaining_grants'] = 0;
            $this->recurring_status = GrantItem::RECUR_STATUS_COMPLETED;
            return $grantDetails;
        }

        if ($today == $endDate) {
            $grantDetails['last_grant_date'] = $endDate;
            $grantDetails['next_run_date'] = $endDate;
            $grantDetails['remaining_grants'] = 1;
            return $grantDetails;
        }

        if ($today <= $startDate) {
            $grantDetails['last_grant_date'] = null;
            $grantDetails['next_run_date'] = $startDate;
            $grantDetails['remaining_grants'] = $occurrences;
            return $grantDetails;
        }

        $prev = $startDate->copy();
        $next = $this->getNextPaymentDate($prev->copy());
        $n = 1;
        while ($today > $next) {    // may be > or >=
            $n += 1;
            $prev = $next->copy();
            $next = $this->getNextPaymentDate($prev->copy()); // $next + F;
        }

        $grantDetails['last_grant_date'] = $prev;
        $grantDetails['next_run_date'] = $next;
        $grantDetails['remaining_grants'] = $occurrences - $n;
        return $grantDetails;
    }

    public function getOrgAddress()
    {
        return new OrganizationAddress();
    }

    public function isRecurring()
    {
        return $this->frequency && $this->frequency !== Data::GRANTING_FREQUENCY_ONCE;
    }

    public function isOngoing()
    {
        return $this->no_end == 'Y';
    }

    public function displayRecurringCount()
    {
        if (!$this->isRecurring()) return 1;
        if ($this->no_end == 'Y') return "Ongoing";
        return $this->occurrences;
    }

    public function getGrantingFrequencyAttribute()
    {
        return Data::displayableGrantingFrequency($this->frequency);
    }

    public function isCancelable()
    {
        if ($this->grant_status != FundRecommendation::GRANT_STATUS_APPROVED) {
            return false;
        }
        return $this->recurring_status == GrantItem::RECUR_STATUS_ACTIVE;
    }

    /**
     * @return string
     */
    public function getTwoLineFromAddress()
    {
        $address = '';
        if ($this->org_address1) $address .= $this->org_address1 . ', ';
        if ($this->org_address2) $address .= $this->org_address2 . ', ';
        if ($this->org_address1 || $this->org_address2) $address .= '<br />';

        if ($this->org_city) $address .= $this->org_city . ', ';
        if ($this->org_state) $address .= $this->org_state . ' ';
        if ($this->org_zip) $address .= ' ' . $this->org_zip;

        return $address;
    }

    static public function getOverAllGrantsFundList($limit,$startDate,$endDate,$fund_id)
    {
        $contactId = Contact::sessionContactId();
        $fundArr = ContactFund::getFundIdsByContactId($contactId);

        $condition = [
            'is_approved' => 'Y',
        ];

        $results = FundRecommendation::where($condition)
            ->whereBetween('date_submitted', [$startDate, $endDate])
            ->groupBy('fund_id')
            ->select('fund_id', DB::raw('SUM(amount) as total_fund_grant'))
            ->orderByDesc('total_fund_grant');

        if ($fund_id != '') {
            $results->where('fund_id', $fund_id);
        } else {
            $results->whereIn('fund_id', $fundArr);
        }
        #$results->whereIn('fund_id', $fundArr);
        if($limit > 0) {

            return $results->paginate($limit);
        } else {

            return $results->get();
        }
    }

    static public function getOverAllGrantsOrganizationListFundWise($fund_id,$startDate,$endDate,$org_id)
    {
        $contactId = Contact::sessionContactId();

        $condition = [
            'fund_id' => $fund_id,
            'is_approved' => 'Y',
        ];

        $results = FundRecommendation::where($condition)
            ->when($org_id, function ($query) use ($org_id) {
                $query->where('organization_id', $org_id);
            })
            ->whereBetween('date_submitted', [$startDate, $endDate])
            ->groupBy('organization_id')
            ->select('organization_id', DB::raw('SUM(amount) as total_org_grant'))
            ->orderByDesc('total_org_grant')
            ->get();

        return $results;
    }

    static public function getOverAllGrantsOrganizationList($startDate,$endDate)
    {
        $contactId = Contact::sessionContactId();
        $fundArr = ContactFund::getFundIdsByContactId($contactId);

        $condition = [
            'is_approved' => 'Y'
        ];

        $results = FundRecommendation::whereIn('fund_id', $fundArr)
            ->where($condition)
            ->whereBetween('date_submitted', [$startDate, $endDate])
            ->groupBy('organization_id')
            ->select('organization_id', DB::raw('SUM(amount) as total_org_grant'))
            ->orderByDesc('total_org_grant')
            ->get();

        return $results;
    }

    static public function getOverAllGrantsDonorListFundOrganizationWise($fund_id,$organization_id,$startDate,$endDate)
    {
        $contactId = Contact::sessionContactId();

        $condition = [
            'fund_id' => $fund_id,
            'organization_id' => $organization_id,
            'is_approved' => 'Y'
        ];

        $results = FundRecommendation::where($condition)
            ->whereBetween('date_submitted', [$startDate, $endDate])
            ->groupBy('contact_id')
            ->select('contact_id', DB::raw('SUM(amount) as total_donor_grant'))
            ->orderByDesc('total_donor_grant')
            ->get();

        return $results;
    }

    static public function getOverAllGrantsDonorList($startDate,$endDate)
    {
        $contactId = Contact::sessionContactId();
        $fundArr = ContactFund::getFundIdsByContactId($contactId);

        $condition = [
            'is_approved' => 'Y'
        ];

        $results = FundRecommendation::whereIn('fund_id', $fundArr)
            ->where($condition)
            ->whereBetween('date_submitted', [$startDate, $endDate])
            ->groupBy('contact_id')
            ->select('contact_id', DB::raw('SUM(amount) as total_donor_grant'))
            ->orderByDesc('total_donor_grant')
            ->get();

        return $results;
    }

    static public function getRecommendationList($fundArr)
    {
        // $contactId = Contact::sessionContactId();
	    // $fundArr = ContactFund::getFundIdsByContactId($contactId);
	 if (!is_array($fundArr)) {
        return collect();
		}
        $results = FundRecommendation::whereIn('fund_id', $fundArr)
        ->select('*')
        ->orderByDesc('amount')
        ->where('is_approved', 'N');
       
        return $results->get();
        #return $results;
    }

    static public function getRecommendationLists($limit,$fund_id,$startDate,$endDate)
    {
        $contactId = Contact::sessionContactId();
        $fundArr = ContactFund::getFundIdsByContactId($contactId);

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $results = FundRecommendation::whereIn('fund_id', $fundArr)
        //->where(DB::raw('DATE(date_submitted)'), '>=', $startDate)
        //->where(DB::raw('DATE(date_submitted)'), '<=', $endDate)
        ->select('*')
        ->orderByDesc('amount')
        ->where('is_approved', 'N');
        if($fund_id > 0) {
            $results->where('fund_id', $fund_id);
        }
        if($limit == 'count') 
        {
            return $results->count();
        }
        else
        {
            if($limit > 0) {
                return $results->paginate($limit);
            } else {
                return $results->get();
            }
        }
    }

    /* static public function getRecommendationList($limit,$fund_id,$startDate,$endDate)
    // {
    //     $contactId = Contact::sessionContactId();
    //     $fundArr = ContactFund::getFundIdsByContactId($contactId);

    //     $startDate = Carbon::parse($startDate)->startOfDay();
    //     $endDate = Carbon::parse($endDate)->endOfDay();

    //     $results = FundRecommendation::whereIn('fund_id', $fundArr)
    //     //->where(DB::raw('DATE(date_submitted)'), '>=', $startDate)
    //     //->where(DB::raw('DATE(date_submitted)'), '<=', $endDate)
    //     ->select('*')
    //     ->orderByDesc('amount')
    //     ->where('is_approved', 'N');
    //     if($fund_id > 0) {
    //         $results->where('fund_id', $fund_id);
    //     }
    //     if($limit == 'count') 
    //     {
    //         return $results->count();
    //     }
    //     else
    //     {
    //         if($limit > 0) {
    //             return $results->paginate($limit);
    //         } else {
    //             return $results->get();
    //         }
    //     }
    //     #return $results;
	    // }*/
    
    static public function getRecommendationGraph($fund_id, $startDate, $endDate)
    {
        $contactId = Contact::sessionContactId();
        $fundArr = ContactFund::getFundIdsByContactId($contactId);

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $results = FundRecommendation::whereIn('fund_id', $fundArr)
            //->where(DB::raw('DATE(date_submitted)'), '>=', $startDate)
            //->where(DB::raw('DATE(date_submitted)'), '<=', $endDate)
            ->orderByDesc('total_amount')
            ->where('is_approved', 'N');

        if ($fund_id > 0) {
            $results->select('org_name', DB::raw('SUM(amount) as total_amount'))
                ->where('fund_id', $fund_id)
                ->groupBy('org_name');
        } else {
            $results->select('fund_id', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('fund_id');
        }

        return $results->get();
    }
}
