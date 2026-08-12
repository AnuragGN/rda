<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 05-08-2020
 * Time: 19:21
 */

namespace App\Models;

/*
 * contact_type_id
 * contact_type
 */
use Kyslik\ColumnSortable\Sortable;
use App\Helpers\Data;
use App\Models\DAF\DAFAdditionalDonor;
use App\Models\DAF\DAFContributions;
use App\Models\DAF\DAFDonor;
use App\Models\DAF\DAFSecurity;
use App\Models\DAF\DAFStocks;
use App\Models\DAF\DAFSuccessorOrganizations;
use App\Models\DAF\DAFSuccessorIndividuals;
use App\Models\DAF\DAFUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Services\DafFlowService;


/**
 * Class ContactType - Contact Types
 * @package App
 */
class DAFAccount extends Model
{
    /* @var string */
    protected $table = 'daf_account';

//    protected $primaryKey = 'id';

    const LINK_DISABLED = 0;
    const LINK_ENABLED = 1;
    const LINK_SAVED = 2;

    const DAF_DONOR = 'donor';
    const DAF_ADDITIONAL_DONOR = 'additional_donor';
    const DAF_SUCCESSORS = 'successors';
    const DAF_SUCCESSORS_INDIVIDUALS = 'successors_individuals';
    const DAF_SUCCESSORS_ORGANIZATIONS = 'successors_organizations';
    const DAF_SUCCESSORS_ENDOWMENT = 'successors_endowment';
    const DAF_CONTRIBUTIONS = 'contributions';
    const DAF_CONTRIBUTIONS_CASH = 'contributions_cash';
    const DAF_CONTRIBUTIONS_CC = 'contributions_cc';
    const DAF_CONTRIBUTIONS_SECURITIES = 'contributions_securities';
    const DAF_CONTRIBUTIONS_STOCKS = 'stocks';
    const DAF_CONTRIBUTIONS_OTHERS = 'others';
    const DAF_INVESTMENTS = 'investments';
    const DAF_AUTHORIZATION = 'authorization';
    const DAF_TYPE = 'daf_type';
    const DAF_CONTRIBUTIONS_CASH_EQUIVALENTS = 'cash_equivalents';
    const DAF_CONTRIBUTIONS_SECURITIES_OR_MUTUAL_FUNDS = 'securities_or_mutual_funds';  
    
    const DAF_TYPE_LABEL = 'Donor-Advised Fund Type';

    public $sortable = [
        'id',
        'created_at',
        'status',
        'sponser_sync',
        // add any other sortable columns
    ];

    public function getDonorFullNameAttribute()
    {
        $donor = json_decode($this->donor ?? '', true) ?: [];

        return trim(
            ($donor['first_name'] ?? '') . ' ' .
            ($donor['last_name'] ?? '')
        ) ?: 'N/A';
    }

    public function sponsor()
    {
        return $this->belongsTo(FaSponser::class, 'sponsor_id');
    }

    static public function getAdvisorDafAccounts()
    {
        return self::with('sponsor')->get();
    }
    
    static public function isOwner($id)
    {
        if (!$id) return false;
        $model = DAFAccount::find($id);
        $sessionUserId = User::getSessionUserId();
        return $model && ($model->auth_user_id === $sessionUserId);
    }

    static public function isAdvisorAuthorizedForDaf($id)
    {
        if (!$id) return false;
        $model = DAFAccount::find($id);
        $sessionUserId = User::getSessionUserId();
        return $model && ($model->auth_user_id === $sessionUserId);
    }

    static public function reviewTitle()
    {
        if (ClientInfo::isHGA()) {
            return "Review, Acknowledge & Submit";
        } else {
            return "Review & Submit";
        }
    }

    static public function getDonorFundPrivilegesList ()
    {
        return [
            null => '',
            Data::DAFR_DONOR_INFO_FUND_PRIVILEGE_FULL => "Donor Advisor Full",
            Data::DAFR_DONOR_INFO_FUND_PRIVILEGE_VIEW => "Interested Party View",
            Data::DAFR_DONOR_INFO_FUND_PRIVILEGE_GRANT => "Interested Party Grant",
        ];
    }

    static public function getDonorCitizenshipList ()
    {
        return [
            'us_citizen' => "U.S. Citizen",
            'us_alien' => "U.S. Resident Alien",
        ];
    }

    static public function getDAFFundNameById($id)
    {
        $model = self::where(['id' => $id])->first();
        return isset($model) ? $model->fund_name : null;
    }

    static public function getDAFAccount($id=null)
    {
        // based on id
        if ($id) {
            $model = DAFAccount::find($id);
            return $model ? $model : abort(403);
        }

        // default i.e. potential donor's application
        $userId = User::getSessionUserId();
        if (!$userId) return null;

        $model = DAFAccount::where(['auth_user_id' => $userId, 'app_id' => 0])->first();
        return $model ? $model : abort(403);
    }

    static public function createDAFContact(Request $request, $registrationType, $authUserId = false)
    {
        $isPrimary = $registrationType == "successor" ?  "N" : "Y";

        $contact = new Contact();
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        if ($authUserId) $contact->auth_user_id = $authUserId;
        //$contact->created_on = date('Y-m-d H:i:s');
        $contact->registration_type = $registrationType;

//        if(ClientInfo::isHGA())
//        {
//            $contact->prefix = $request->prefix;
//            $contact->suffix1 = $request->suffix;
//            $contact->informal = $request->preferred_name;
//            $contact->dob = $request->dob;
//            $contact->ssn = $request->ssn;
//        }
        $contact->save();

        $dafUserEmailAddress = new EmailAddress();
        $dafUserEmailAddress->contact_id = $contact->contact_id;
        $dafUserEmailAddress->email_address = $request->email;
        $dafUserEmailAddress->email_address_name = "Primary";
        $dafUserEmailAddress->is_primary = $isPrimary;
        $dafUserEmailAddress->save();

        return $contact;
    }

    static public function getTotalIndividualOrgPercent($id)
    {
        $daf = DAFAccount::getDAFAccount($id);
        if ($daf == null) abort(403);

        $data = json_decode($daf->successors, true);

        if ( isset($data['endowment']['isSelected']) == true ) {
            // to avoid UI error message
            return 100;
        }

        $orgs = isset( $data['organizations'] ) ? $data['organizations'] : null;
        $individuals = isset( $data['individuals'] ) ? $data['individuals'] : null;

        $total = 0;
        if ($individuals) {
            foreach ($individuals as $individual) {
                $total += isset($individual['share_value']) ? $individual['share_value'] : null;
            }
        }
        if ($orgs) {
            foreach ($orgs as $org) {
                $total += isset($org['giving']) ? $org['giving'] : null;
            }
        }
        return $total;
    }

    static public function getAdditionalDonorsList($id)
    {
        $daf = DAFAccount::getDAFAccount($id);
        if ($daf == null) abort(403);

        $donors = json_decode($daf->donors, true);
        $additionalDonors = isset( $donors['donors'] ) ? $donors['donors'] : [];

        return $additionalDonors;
    }

    static public function  getDAFInfo($page, $id=null)
    {
        $dafInfo = [];

        /** @var DAFAccount $daf */
        $daf = DAFAccount::getDAFAccount($id);
        if ($daf == null) abort(403);

        $dafInfo['daf_account_id'] = $daf->id;

        $user = new DAFUser();
        if ($daf->user) {
            $data = json_decode($daf->user, true);
            foreach ($data as $key => $value) $user->{$key} = $value;
        }
        $dafInfo['user'] = $user;

        switch($page) {
            case self::DAF_DONOR:

                $donor = new DAFDonor();
                if ($daf->donor) {
                    $data = json_decode($daf->donor, true);
                    if (isset($data['fund_name'])) {
                        foreach ($data as $key => $value) $donor->{$key} = $value;

                        $personFields = Data::getDonorInfoCustomFields();
                        $mailingAddress = in_array(Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields);
                        $donor = Data::setAddressInObject($donor, $data, $mailingAddress);
                    }
                }

                if (empty($donor->first_name) || empty($donor->last_name)) {
                    $donor->first_name = $user->first_name;
                    $donor->last_name = $user->last_name;
                }

                $dafInfo['donor'] = $donor;
                $dafInfo['status'] = $daf->getLinkStatus();
                break;

            case self::DAF_ADDITIONAL_DONOR;
                $additionalDonors = [];

                if ($daf->donors) {
                    $data = json_decode($daf->donors, true);
                    $donorsList = isset($data['donors']) ? $data['donors'] : [];

                    foreach ($donorsList as $key => $model) {
                        $personFields = Data::getAdditionalDonorInfoCustomFields();
                        $mailingAddress = in_array(Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields);
                        $additionalDonors[] = Data::setAddressWithModel($model, $mailingAddress);
                    }
                }

                $dafInfo['donors'] = $additionalDonors;
                $dafInfo['status'] = $daf->getLinkStatus();
                break;

            case self::DAF_SUCCESSORS:
                $endowment = [];

                if ($daf->successors) {
                    $data = json_decode($daf->successors, true);

                    if ( isset($data['endowment']) ) {
                        // $cash = json_decode($data['cash'], true);
                        $endowment = $data['endowment'];
                    }
                }

                $dafInfo['endowment'] = $endowment;
                $dafInfo['status'] = $daf->getLinkStatus();
                break;

            case self::DAF_SUCCESSORS_INDIVIDUALS:
                $individuals = [];

                //$successorsIndividuals = new DAFSuccessorIndividuals();

                if ($daf->successors) {
                    $data = json_decode($daf->successors, true);

                    if ( isset($data['individuals']) ) {

                        $individualData = $data['individuals'];

                        foreach ($individualData as $individual) {
                            $personFields = Data::getSuccessorsIndividualCustomFields();
                            $mailingAddress = in_array(Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields);
                            $individuals[] = Data::setAddressWithModel($individual, $mailingAddress);
                        }
                    }

                    $individuals[] = new DAFSuccessorIndividuals();
                }

                $dafInfo['individuals'] = $individuals;
                $dafInfo['status'] = $daf->getLinkStatus();
                break;

            case self::DAF_SUCCESSORS_ORGANIZATIONS:
                $organizations = [];
                //$successorsOrganizations = new DAFSuccessorOrganizations();

                if ($daf->successors) {
                    $data = json_decode($daf->successors, true);

                    if ( isset($data['organizations']) ) {

                        $orgsData = $data['organizations'];
                        foreach ($orgsData as $org) {
                            $organizations[] = Data::setAddressWithModel($org);
                        }
                    }
                    $organizations[] = new DAFSuccessorOrganizations();
                }

                //$successorsOrganizations->organizations = $organizations;
                $dafInfo['organizations'] = $organizations;
                $dafInfo['status'] = $daf->getLinkStatus();
                break;

            case self::DAF_CONTRIBUTIONS:

                $securities = [];
                $creditCard = [];
                $stocks = [];
                $others = [];

                $contributions = new DAFContributions();
                if ($daf->contributions) {
                    $data = json_decode($daf->contributions, true);
                    if (isset($data['cash'])) {
                        $cash = $data['cash'];
                        foreach ($cash as $key => $value) $contributions->{$key} = $value;
                    }
                    if (isset($data['securities'])) {
                        foreach($data['securities'] as $security) {
                            $securityObj = new DAFSecurity();
                            foreach ($security as $key => $value) $securityObj->{$key} = $value;
                            $securities[] = $securityObj;
                        }
                    }
                    if (isset($data['credit_card'])) {
                        $cc = $data['credit_card'];
                        foreach ($cc as $key => $value) $creditCard[$key] = $value;
                    }

                    if (isset($data['stocks'])) {
                        $cStocks = $data['stocks'];

                        foreach ($cStocks as $key => $value) $stocks[] = $value;
                    }

                    if (isset($data['others'])) {
                        $others = $data['others'];
                        //$others = $cOthers;
//                        foreach ($cOthers as  $value) $others[] = $value;
                    }
                }

                $contributions->securities = $securities;
                $contributions->credit_card = $creditCard;
                $contributions->stocks = $stocks;
                $contributions->others = $others;

                $dafInfo['contributions'] = $contributions;
                $dafInfo['status'] = $daf->getLinkStatus();
                break;

            case self::DAF_INVESTMENTS:
                $dafInfo['allocations'] = DAFAccount::getCurrentAllocationData($daf);
                $dafInfo['status'] = $daf->getLinkStatus();
                break;
            
            case self::DAF_TYPE:
                $dafInfo['status'] = $daf->getLinkStatus();
                $dafInfo['daf_type'] = $daf->daf_type;
                break;

            case self::DAF_AUTHORIZATION:
                $dafInfo['status'] = $daf->getLinkStatus();
                $dafInfo['authorized'] = $daf->authorized;
                break;
        }

        return $dafInfo;
    }

    /**
     * I think this is not being used?
     * @param $key
     * @return array|mixed
     */
    static public function getAdditionalDonor($key, $id)
    {
        $daf = DAFAccount::getDAFAccount($id);
        if ($daf == null) abort(403);
        $dafDonors = json_decode($daf->donors, true);
        $additionalDonor = isset($dafDonors['donors']) ? $dafDonors['donors'] : null;

        if (!$additionalDonor) {
            $donor = [];
        } else {
            $donor = $dafDonors['donors'];
            foreach ($donor as $k => $val) {
                if ($val['key'] === $key)
                    return $val;
            }
        }
        return $donor;
    }

    /**
     * @param DAFAccount $daf
     * @return array
     */
    static private function getCurrentAllocationData(DAFAccount $daf)
    {
        $allocations = [];
        $pools = FundPool::getAll();

        if ($daf->investments) {
            $allocations = json_decode($daf->investments, true);
        }

        $records = [];
        foreach($pools as $pool) {
            $record = new FundPool();
            $record->pool_id = $pool->pool_id;
            $record->pool_name = $pool->pool_name;

            if (isset($allocations[$record->pool_id])) {
                $record->allocation = $allocations[$record->pool_id];
            } else {
                $record->allocation = 0;
            }
            $records[] = $record;;
        }
        return $records;
    }

    /**
     * @return int|null
     */
    static public function getAdditionalDonorCount($id) {
        $daf = DAFAccount::getDAFAccount($id);
        if ($daf == null) abort(403);
        $dafDonors = json_decode($daf->donors, true);
        $additionalDonor = isset($dafDonors['donors']) ? $dafDonors['donors'] : null;
        return $additionalDonor ? count($additionalDonor) : null;
    }

    /**
     * @param Request $request
     * @param $page
     */
    static public function deleteDAFInfo(Request $request, $page, $id)
    {
        $daf = DAFAccount::getDAFAccount($id);
        if ($daf == null) abort(403);
        $uniqueKey = $request->key;

        switch($page) {
            case self::DAF_ADDITIONAL_DONOR:
                $dafDonors = json_decode($daf->donors, true);
                $additionalDonor = isset($dafDonors['donors']) ? $dafDonors['donors'] : [];

                foreach ( $additionalDonor as $key => $val) {

                    if ($val['key'] == $uniqueKey) unset( $additionalDonor[$key]);
                }

                $additionalDonor = array_values($additionalDonor);

                $donorInfo['donors'] = $additionalDonor;
                $daf->donors = json_encode($donorInfo);
                $daf->save();
                return;

            case self::DAF_SUCCESSORS_INDIVIDUALS:
                $dafSuccessor = json_decode($daf->successors, true);

                $individuals = isset($dafSuccessor['individuals']) ? $dafSuccessor['individuals'] : [];

                foreach ($individuals as $key => $val) {
                    if ($val['contact_id'] == $request->contact_id) unset($individuals[$key]);
                }

                $individuals = array_values($individuals);

                $dafSuccessor['individuals'] = $individuals;
                $daf->successors = json_encode($dafSuccessor);
                $daf->save();
                return;

            case self::DAF_SUCCESSORS_ORGANIZATIONS:
                $dafSuccessor = json_decode($daf->successors, true);
                $orgs = isset($dafSuccessor['organizations']) ? $dafSuccessor['organizations'] : [];

                foreach ($orgs as $key => $val) {
                    if ($val['key'] == $uniqueKey) unset($orgs[$key]);
                }

                $orgs = array_values($orgs);

                $dafSuccessor['organizations'] = $orgs;
                $daf->successors = json_encode($dafSuccessor);
                $daf->save();
                return;

            case self::DAF_CONTRIBUTIONS_SECURITIES:
                $dafSecurities = json_decode($daf->contributions, true);
                $securities = isset($dafSecurities['securities']) ? $dafSecurities['securities'] : [];

                foreach ($securities as $key => $val) {
                    if ($val['key'] == $uniqueKey) unset($securities[$key]);
                }

                $securities = array_values($securities);

                $dafSecurities['securities'] = $securities;
                $daf->contributions = json_encode($dafSecurities);
                $daf->save();
                return;

            case self::DAF_CONTRIBUTIONS_STOCKS:
                $dafStocks = json_decode($daf->contributions, true);
                $stocks = isset($dafStocks['stocks']) ? $dafStocks['stocks'] : [];

                foreach ($stocks as $key => $val) {
                    if ($val['key'] == $uniqueKey) unset($stocks[$key]);
                }

                $stocks = array_values($stocks);

                $dafStocks['stocks'] = $stocks;
                $daf->contributions = json_encode($dafStocks);

                $daf->save();
                return;
        }

        return;
    }

    /**
     * @return array
     */
    static public function getCurrentAllocation($id) {
        $daf = DAFAccount::getDAFAccount($id);
        return self::getCurrentAllocationData($daf);
    }

    /**
     * @param $model
     */
    static public function updateContributionCCPaymentInfo($model, $id)
    {
        $daf = DAFAccount::getDAFAccount($id);
        if (!$daf) abort(404);

        $contributions = json_decode($daf->contributions, true);
        $contributions['credit_card'] = $model;
        $daf->contributions = json_encode($contributions);
        $daf->save();
        return;
    }

    /**
     * @param $page
     * @param Request $request
     */
    static public function updateDAFInfo(Request $request, $page, $id)
    {
        $daf = DAFAccount::getDAFAccount($id);
        if (!$daf) abort(404);

        $params = $request->all();
        unset($params['_token']);
        unset($params['save']);
        unset($params['save_next']);

        if (isset($params['ssn'])) {
            $params['ssn_star'] = Data::setSSNStar($params['ssn']);
            $params['ssn'] = Data::formatSSN($params['ssn']);
        }
        if (isset($params['phone_number'])) $params['phone_number'] = Data::formatDAFPhone($params['phone_number']);
        if (isset($params['ein'])) $params['ein'] = Data::formatOrgEin($params['ein']);
        if (isset($params['fund_privileges_key'])) $params['fund_privileges'] = Data::getFundPrivilegeName($params['fund_privileges_key']);
        if (isset($params['citizenship_key'])) $params['citizenship'] = Data::getCitizenshipName($params['citizenship_key']);
        if (isset($params['relationship_key'])) $params['relationship'] = Data::getRelationshipName($params['relationship_key']);

        switch($page) {
            case self::DAF_DONOR:
                $daf->fund_name = $params['fund_name'];
                $donorInfo = DAFDonor::getDonorFromParams($params);
                $donorInfo['contact_id'] = $daf->contact_id;
                $daf->donor = json_encode($donorInfo);
                $daf->status = "incomplete";
                $daf->save();
                return;

            case self::DAF_ADDITIONAL_DONOR:
                $additionalDonors = json_decode($daf->donors, true);
                $donors = isset($additionalDonors['donors']) ? $additionalDonors['donors'] : null;

                if ($params['isNew']) {

                    $params['isNew'] = false;
                    $donors[] = DAFAdditionalDonor::getAdditionalDonorFromParams($params);
                } else {

                    $data = [];
                    $params['isNew'] = false;
                    foreach ($donors as $donor) {
                        $data[] = $donor['key'] === $params['key'] ? DAFAdditionalDonor::getAdditionalDonorFromParams($params) : $donor;
                    }
                    $donors = $data;
                }

                $donorInfo['donors'] = $donors;
                $daf->donors = json_encode($donorInfo);
                $daf->save();

                return;

            case self::DAF_SUCCESSORS:

                $successors = json_decode($daf->successors, true);

                $endowment = isset($successors['endowment']) ? $successors['endowment'] : null;
                if (!$endowment || !count($endowment)) {
                    $endowment = [];
                }

                if ($params['isSelected'] == null) {
                    $endowment['isSelected'] = null;
                    $endowment['endowment_name'] = isset($endowment['endowment_name']) ?  $endowment['endowment_name'] : null;
                } else {
                    $endowment = $params;
                }

                $successors['endowment'] = $endowment;
                $daf->successors = json_encode($successors);

                $daf->save();

                return;

            case self::DAF_SUCCESSORS_INDIVIDUALS:
                $successors = json_decode($daf->successors, true);

                $individuals = isset($successors['individuals']) ? $successors['individuals'] : null;
                if (!$individuals || !count($individuals)) {
                    $individuals = [];
                }

                if ($params['isNew']) {
                    $params['isNew'] = false;
                    $individuals[] = DAFSuccessorIndividuals::getIndividualFromParams($params);
                } else {
                    $data = [];
                    $params['isNew'] = false;
                    foreach ($individuals as $individual) {
                        if (!isset($individual['key'])) continue;
                        $data[] = $individual['key'] === $params['key'] ? DAFSuccessorIndividuals::getIndividualFromParams($params) : $individual;
                    }
                    $individuals = $data;
                }

                $successors['individuals'] = $individuals;
                $daf->successors = json_encode($successors);

                $daf->save();
                break;

            case self::DAF_SUCCESSORS_ORGANIZATIONS:
                $successors = json_decode($daf->successors, true);

                $organizations = isset($successors['organizations']) ? $successors['organizations'] : null;
                if (!$organizations || !count($organizations)) {
                    $organizations = [];
                }

                if ($params['isNew']) {
                    $params['isNew'] = false;
                    $organizations[] = DAFSuccessorOrganizations::getOrganizationFromParams($params);

//                    DAFSuccessorOrganizations::getOrganizationFromParams($params);
                } else {
                    $data = [];
                    $params['isNew'] = false;
                    foreach ($organizations as $orgs) {
                        if (!isset($orgs['key'])) continue;
                        $data[] = $orgs['key'] === $params['key'] ? DAFSuccessorOrganizations::getOrganizationFromParams($params) : $orgs;
                    }
                    $organizations = $data;
                }

                $successors['organizations'] = $organizations;
                $daf->successors = json_encode($successors);
                $daf->save();
                break;

            case self::DAF_CONTRIBUTIONS_CASH:
                $contributions = json_decode($daf->contributions, true);
                $contributions['cash'] = $params;
                $daf->contributions = json_encode($contributions);
                $daf->save();
                break;

            case self::DAF_CONTRIBUTIONS_SECURITIES:
                $contributions = json_decode($daf->contributions, true);
                $securities = isset($contributions['securities']) ? $contributions['securities'] : null;
                if (!$securities || !count($securities)) {
                    $securities = [];
                }

                if ($params['isNew']) {
                    $params['isNew'] = false;
                    $securities[] = $params;
                } else {
                    $data = [];
                    $params['isNew'] = false;
                    foreach($securities as $security) {
                        if (!isset($security['key'])) continue;
                        $data[] = $security['key'] === $params['key'] ? $params : $security;
                    }
                    $securities = $data;
                }

                $contributions['securities'] = $securities;
                $daf->contributions = json_encode($contributions);
                $daf->save();
                break;

            case self::DAF_CONTRIBUTIONS_STOCKS:
                $contributions = json_decode($daf->contributions, true);

                $stocks = isset($contributions['stocks']) ? $contributions['stocks'] : null;
                if (!$stocks || !count($stocks)) {
                    $stocks = [];
                }

                if ($params['isNew']) {
                    $params['isNew'] = false;
                    $stocks[] = $params;
                } else {
                    $data = [];
                    $params['isNew'] = false;

                    foreach($stocks as $stock) {
                        if (!isset($stock['key'])) continue;
                        $data[] = $stock['key'] === $params['key'] ? $params : $stock;
                    }
                    $stocks = $data;
                }

                $contributions['stocks'] = $stocks;
                $daf->contributions = json_encode($contributions);

                $daf->save();
                break;

            case self::DAF_CONTRIBUTIONS_OTHERS:
                $dafContributions = json_decode($daf->contributions, true);

                if (! isset($params['is_active'])) $params['is_active'] = null;

                $dafContributions['others'] = $params;

                $daf->contributions = json_encode($dafContributions);
                $daf->save();
                break;

            case self::DAF_INVESTMENTS:
                $allocations = $params['allocations'];
                $daf->investments = json_encode($allocations);
                $daf->save();
                return;

            case self::DAF_AUTHORIZATION:

                // this is not required as 'status' field has been added in DAFAccount - March 24, 2023
                Contact::where('contact_id', $daf->contact_id)->update(["donor_approval_status" => "Submitted"]);

                $daf->authorized = true;
                $daf->status = "submitted";
                $daf->save();
                return;
        }

        return;
    }


    /**
     * @param $page
     * @param Request $request
     */
    static public function updateDAFInfoByAdvisor(Request $request, $page, $id)
    {
        $daf = DAFAccount::getDAFAccount($id);
        if (!$daf) abort(404);

        $params = $request->all();
        unset($params['_token']);
        unset($params['save']);
        unset($params['save_next']);

        if (isset($params['ssn'])) {
            $params['ssn_star'] = Data::setSSNStar($params['ssn']);
            $params['ssn'] = Data::formatSSN($params['ssn']);
        }
        if (isset($params['phone_number'])) $params['phone_number'] = Data::formatDAFPhone($params['phone_number']);
        if (isset($params['ein'])) $params['ein'] = Data::formatOrgEin($params['ein']);
        if (isset($params['fund_privileges_key'])) $params['fund_privileges'] = Data::getFundPrivilegeName($params['fund_privileges_key']);
        if (isset($params['citizenship_key'])) $params['citizenship'] = Data::getCitizenshipName($params['citizenship_key']);
        if (isset($params['relationship_key'])) $params['relationship'] = Data::getRelationshipName($params['relationship_key']);

        switch($page) {
            case self::DAF_DONOR:

                $userName = $request->only(['first_name', 'last_name']);
       
                $daf->fund_name = $params['fund_name'];
                $daf->user = json_encode($userName);
                $donorInfo = DAFDonor::getDonorFromParams($params);
                $donorInfo['contact_id'] = $daf->contact_id;
                $daf->donor = json_encode($donorInfo);
                $daf->status = "incomplete";
                $daf->save();
                return;

            case self::DAF_ADDITIONAL_DONOR:
                $additionalDonors = json_decode($daf->donors, true);
                $donors = isset($additionalDonors['donors']) ? $additionalDonors['donors'] : null;

                if ($params['isNew']) {

                    $params['isNew'] = false;
                    $donors[] = DAFAdditionalDonor::getAdditionalDonorFromParams($params);
                } else {

                    $data = [];
                    $params['isNew'] = false;
                    foreach ($donors as $donor) {
                        $data[] = $donor['key'] === $params['key'] ? DAFAdditionalDonor::getAdditionalDonorFromParams($params) : $donor;
                    }
                    $donors = $data;
                }

                $donorInfo['donors'] = $donors;
                $daf->donors = json_encode($donorInfo);
                $daf->save();

                return;

            case self::DAF_SUCCESSORS:

                $successors = json_decode($daf->successors, true);

                $endowment = isset($successors['endowment']) ? $successors['endowment'] : null;
                if (!$endowment || !count($endowment)) {
                    $endowment = [];
                }

                if ($params['isSelected'] == null) {
                    $endowment['isSelected'] = null;
                    $endowment['endowment_name'] = isset($endowment['endowment_name']) ?  $endowment['endowment_name'] : null;
                } else {
                    $endowment = $params;
                }

                $successors['endowment'] = $endowment;
                $daf->successors = json_encode($successors);

                $daf->save();

                return;

            case self::DAF_SUCCESSORS_INDIVIDUALS:
                $successors = json_decode($daf->successors, true);

                $individuals = isset($successors['individuals']) ? $successors['individuals'] : null;
                if (!$individuals || !count($individuals)) {
                    $individuals = [];
                }

                if ($params['isNew']) {
                    $params['isNew'] = false;
                    $individuals[] = DAFSuccessorIndividuals::getIndividualFromParams($params);
                } else {
                    $data = [];
                    $params['isNew'] = false;
                    foreach ($individuals as $individual) {
                        if (!isset($individual['key'])) continue;
                        $data[] = $individual['key'] === $params['key'] ? DAFSuccessorIndividuals::getIndividualFromParams($params) : $individual;
                    }
                    $individuals = $data;
                }

                $successors['individuals'] = $individuals;
                $daf->successors = json_encode($successors);

                $daf->save();
                break;

            case self::DAF_SUCCESSORS_ORGANIZATIONS:
                $successors = json_decode($daf->successors, true);

                $organizations = isset($successors['organizations']) ? $successors['organizations'] : null;
                if (!$organizations || !count($organizations)) {
                    $organizations = [];
                }

                if ($params['isNew']) {
                    $params['isNew'] = false;
                    $organizations[] = DAFSuccessorOrganizations::getOrganizationFromParams($params);

//                    DAFSuccessorOrganizations::getOrganizationFromParams($params);
                } else {
                    $data = [];
                    $params['isNew'] = false;
                    foreach ($organizations as $orgs) {
                        if (!isset($orgs['key'])) continue;
                        $data[] = $orgs['key'] === $params['key'] ? DAFSuccessorOrganizations::getOrganizationFromParams($params) : $orgs;
                    }
                    $organizations = $data;
                }

                $successors['organizations'] = $organizations;
                $daf->successors = json_encode($successors);
                $daf->save();
                break;

            case self::DAF_CONTRIBUTIONS_CASH:
                $contributions = json_decode($daf->contributions, true);
                $contributions['cash'] = $params;
                $daf->contributions = json_encode($contributions);
                $daf->save();
                break;

            case self::DAF_CONTRIBUTIONS_SECURITIES:
                $contributions = json_decode($daf->contributions, true);
                $securities = isset($contributions['securities']) ? $contributions['securities'] : null;
                if (!$securities || !count($securities)) {
                    $securities = [];
                }

                if ($params['isNew']) {
                    $params['isNew'] = false;
                    $securities[] = $params;
                } else {
                    $data = [];
                    $params['isNew'] = false;
                    foreach($securities as $security) {
                        if (!isset($security['key'])) continue;
                        $data[] = $security['key'] === $params['key'] ? $params : $security;
                    }
                    $securities = $data;
                }

                $contributions['securities'] = $securities;
                $daf->contributions = json_encode($contributions);
                $daf->save();
                break;

            case self::DAF_CONTRIBUTIONS_STOCKS:
                $contributions = json_decode($daf->contributions, true);

                $stocks = isset($contributions['stocks']) ? $contributions['stocks'] : null;
                if (!$stocks || !count($stocks)) {
                    $stocks = [];
                }

                if ($params['isNew']) {
                    $params['isNew'] = false;
                    $stocks[] = $params;
                } else {
                    $data = [];
                    $params['isNew'] = false;

                    foreach($stocks as $stock) {
                        if (!isset($stock['key'])) continue;
                        $data[] = $stock['key'] === $params['key'] ? $params : $stock;
                    }
                    $stocks = $data;
                }

                $contributions['stocks'] = $stocks;
                $daf->contributions = json_encode($contributions);

                $daf->save();
                break;

            case self::DAF_CONTRIBUTIONS_OTHERS:
                $dafContributions = json_decode($daf->contributions, true);

                if (! isset($params['is_active'])) $params['is_active'] = null;

                $dafContributions['others'] = $params;

                $daf->contributions = json_encode($dafContributions);
                $daf->save();
                break;

            case self::DAF_INVESTMENTS:
                $allocations = $params['allocations'];
                $daf->investments = json_encode($allocations);
                $daf->save();
                return;

            case self::DAF_AUTHORIZATION:

                // this is not required as 'status' field has been added in DAFAccount - March 24, 2023
               // Contact::where('contact_id', $daf->contact_id)->update(["donor_approval_status" => "Submitted"]);

                $daf->authorized = true;
                $daf->status = "submitted";
                $daf->save();
                return;
        }

        return;
    }

    /**
     * @return mixed
     */
    static public function getAll()
    {
        return DAFAccount::all();
    }

    /**
     * @param Request $request
     * @param User $user
     * @return DAFAccount
     */
    static public function createDAFAccountForUser(Request $request, User $user)
    {
        $userName = $request->only(['first_name', 'last_name']);
        $model = new DAFAccount();
        $model->auth_user_id = $user->auth_user_id;
        $model->contact_id = $user->contact_id;
        $model->user = json_encode($userName);
        $model->approved = false;
        $model->authorized = false;
        $model->save();
        return $model;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return DAFAccount
     */
    static public function createDAFAccountByAdvisor(Request $request)
    {
        $contactId = Contact::sessionContactId();

        $model = new DAFAccount();
        $model->auth_user_id = auth()->id();  
        $model->contact_id = $contactId;
        $model->sponsor_id = $request->sponsor_id;
        $model->sponsor_sync = 'pending';
        $model->approved = false;
        $model->authorized = false;
       # $model->created_by = auth()->id();  // Advisor user id
        $model->save();
        return $model;
    }

    /**
     * @param User $user
     * @return mixed
     */
    static public function hasUnapprovedDAFProfile(User $user)
    {
        return false; # to disable DAF profile creation for now
        if (!Config::enableDafAppNewDonor()) {
            return false;
        }
        return DAFAccount::where([
            'auth_user_id' => $user->auth_user_id,
            'approved' => false,
            'app_id' => 0
        ])->exists();
    }
                
    private function isContributionExist($contributions)
    {
        if (!$contributions) return false;
        if ( isset($contributions['securities']) && count($contributions['securities']) ) {
            return true;
        } else if (isset($contributions['cash']) && ($contributions['cash']['wire_amount'] != null || $contributions['cash']['check_amount'] != null)) {
            return true;
        } else if (isset($contributions['credit_card'])) {
            return true;
        } else if (isset($contributions['stocks']) && count($contributions['stocks'])) {
            return true;
        } else if (isset($contributions['others']) && $contributions['others']['is_active'] == true) {
            return true;
        } else {
            return false;
        }
    }
           
    /**
     * @return array
     */
    private function getLinkStatus()
    {
        $status = [
            'donor' => self::LINK_ENABLED,
            'additional_donor' => self::LINK_DISABLED,
            'successors' => self::LINK_DISABLED,
            'contributions' => self::LINK_DISABLED,
            'investments' => self::LINK_DISABLED,
            'authorization' => self::LINK_DISABLED,
        ];

        // donor - check fund name
        if ($this->donor && $this->fund_name) {
            $status['donor'] = self::LINK_SAVED;
            $status['additional_donor'] = self::LINK_ENABLED;
            $status['successors'] = self::LINK_ENABLED;
        } else {
            return $status;
        }

        $donors = json_decode($this->donors, true);

        if ( isset($donors['donors']) && count($donors['donors'])) {
            $status['additional_donor'] = self::LINK_SAVED;
        } else {
            $status['successors'] = self::LINK_ENABLED;
        }

        // successor designation
        $successors = json_decode($this->successors, true);
        if ( isset($successors['individuals']) && count($successors['individuals']) ) {

            $status['successors'] = self::LINK_SAVED;
            $status['contributions'] = self::LINK_ENABLED;

        } else if ( isset($successors['organizations']) && count($successors['organizations']) ) {
            $status['successors'] = self::LINK_SAVED;
            $status['contributions'] = self::LINK_ENABLED;
        } else if ( isset($successors['endowment']['isSelected']) == true ) {
            $status['successors'] = self::LINK_SAVED;
            $status['contributions'] = self::LINK_ENABLED;

        } else {
            return $status;
        }

        $contributions = json_decode($this->contributions, true);

        // contributions - check existing cash/securities/credit card/stocks/others
        $isContributionExist = self::isContributionExist($contributions);

        if ($isContributionExist) {
            $status['contributions'] = self::LINK_SAVED;
            $status['investments'] = self::LINK_ENABLED;
        } else {
            return $status;
        }

        if ($this->investments) {
            $status['investments'] = self::LINK_SAVED;
            $status['authorization'] = self::LINK_ENABLED;
        } else {
            return $status;
        }

        if ($this->authorized) {
            $status['authorization'] = self::LINK_SAVED;
        } else {
            return $status;
        }

        return $status;
    }

    /**
     * @return DAFAccount
     */
    static public function getNewDAFAccount()
    {
        /** @var Contact $contact */
        $contact = Contact::sessionContact();

        // check if a new DAF application exists
        $model = DAFAccount::where([
            'contact_id' => $contact->contact_id,
            'status' => "new"
        ])->first();
        if ($model) $model->delete();

        // get existing & set new app-id
        $appId = DAFAccount::where([
            'contact_id' => $contact->contact_id,
        ])->max('app_id');

        // set app id
        $appId = ($appId ? $appId : 0) + 1;

        // create a new DAF Application
        $model = new DAFAccount();
        $model->status = "new";
        $model->app_id = $appId;

        $model->contact_id = $contact->contact_id;
        $model->user = DAFUser::createDAFUserJsonFromContact($contact);

        $model->approved = false;
        $model->authorized = false;
        $model->auth_user_id = User::getSessionUserId();

        $model->donor = DAFDonor::createDAFDonorJsonFromContact($contact);

        $model->save();
        return $model;
    }

    public function isIncomplete()
    {
        // later on, this may be changed to 'status' === 'incomplete'
        return $this->authorized === false;
    }

    public function isSubmitted()
    {
        // later on, this may be changed to 'status' === 'submitted'
        return $this->authorized === true && $this->approved === false;
    }

    static public function getApplications()
    {
        $userId = User::getSessionUserId();
        $models = DAFAccount::where([
            'auth_user_id' => $userId,
            'approved' => false
        ])->where('app_id', '>', 0)->whereIn('status', ['incomplete', 'submitted'])->orderBy('app_id')->get();

        return $models;
    }

    public static function getDAFApplicationsforFADashboard($sponsorId)
    {
        $userId = User::getSessionUserId();

        $query = self::where('auth_user_id', $userId);
        // Apply sponsor filter ONLY when sponsorId > 0
        if ($sponsorId != 0) {
            $query->where('sponsor_id', $sponsorId);
        }
        return $query
            ->orderBy('id', 'desc')
            ->limit(5) // Top 5 latest records
            ->get();
    }
}
