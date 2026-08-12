<?php
/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 01-09-2023
 * Time: 21:55
 */

namespace App\Http\Controllers\Agency; 

use App\Forms\FormFundHistoryFilter;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\AddressType;
use App\Models\PhoneType;
use App\Models\FundRecommendation;
use App\Models\GiftHistory;
use App\Models\GrantHistory;
use App\Helpers\GnUtils;
use App\Helpers\GConst;
use App\Models\LogActivity;
use App\Models\ContactFund;
use Auth;
use App\Models\Api;
use App\Models\Fund;
use App\Models\Organization;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use League\Csv\Writer;
use PDF;
use Carbon\Carbon;

// Funds = 'JCFEX', 'Abra';
/**
 * Class FundController
 * @package App\Http\Controllers
 */
class AgencyAdvisorController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    
    public function index(Request $request) {

        $limit = 5;
        $date_range = 'Last 30 Days';
        $grant_type = 'Fund Wise';
        $date_arr = $this->getStartDateEndDate($date_range);
       
        $startDate = $date_arr['startDate'];
        $endDate = $date_arr['endDate'];

        $overAllGrant = $this->getGrantsForDashboard($limit,$startDate,$endDate,$grant_type,'','');
        $overAllGift  = $this->getGiftsForDashboard($limit,$startDate,$endDate,$grant_type,'');
        $funds = Fund::getAdvisorFunds($limit);
        $openTickets = Ticket::paginateMyTicket($limit,'','open','','');
        #echo '<pre>';print_r($openTickets);die;
        $recommendation  = $this->getRecommendationForDashboard($limit,'',$startDate,$endDate);

        foreach ($funds as $fundkey => $fundvalue) {
            $funds[$fundkey]['balance_format'] = '$'.number_format($fundvalue['balance'],2);
        }
        return view('agency.agency-advisor.dashboard', compact('funds','openTickets','overAllGrant','overAllGift','recommendation'));
    }

    public function fund(Request $request)
    {
        $activity = new LogActivity(LogActivity::NAME_FUND, LogActivity::ACTION_LIST);
        $activity->description(LogActivity::DESCRIPTION_FUND_LIST)->add();;

        $interestBasedArticles = true;
        
        $contact = Contact::sessionContact();
        $pendingGrants = [];

        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');

        return view('agency.agency-advisor.funds.index', compact('contact', 'interestBasedArticles', 'pendingGrants', 'categoryDropdown', 'priorityDropdown'));
    }

    public function ajaxMyFunds(Request $request)
    {
        $limit = 3;
        $funds = Fund::getAdvisorFunds($limit);
        $html = '';
        if (count($funds) || $request->page == 1) {
            $html = view('agency.agency-advisor.funds.list', compact('funds'))->render();
        }
        return [
            'more' => (count($funds) < $limit) ? 0 : 1,
            'html' => $html
        ];
    }

    public function ajaxMyFundsForGraph(Request $request) 
    {
        $funds = Fund::advisorChartFundBalance();
        return $funds;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myFunds(Request $request)
    {
        // log activity
        $activity = new LogActivity(LogActivity::NAME_FUND, LogActivity::ACTION_LIST);
        $activity->description(LogActivity::DESCRIPTION_FUND_LIST)->add();;

        $interestBasedArticles = true;
        $contact = Contact::sessionContact();
        $funds = $this->apiMyFunds($request);
        return view('agency.agency-advisor.funds.home', compact('funds', 'contact', 'interestBasedArticles'));
    }

    public function apiMyFunds(Request $request) {
        $contact = Contact::sessionContact();
        return Fund::getViewableByContactId($contact->contact_id);
    }

    public function grantDetail(Request $request) {

        return view('agency.agency-advisor.grants_detail');
    }

    public function getGrantsForDashboard($limit,$startDate = false,$endDate = false,$grantType = false,$fund_id= false,$org_id= false) {

        $funds = FundRecommendation::getOverAllGrantsFundList($limit,$startDate,$endDate,$fund_id);

        $newArray = [];
        $overallArray = [];

        foreach ($funds as $fundkey => $fundvalue) {

            $fund_arr = Fund::where('fund_id', $fundvalue['fund_id'])->first();

            $fund_name = 'NA';
            if ($fund_arr) {

                $fund_name = $fund_arr->name;
            }

            $newArray['fund_id'] = $fundvalue['fund_id'];
            $newArray['fund_name'] = $fund_name;
            $newArray['total_fund_grant'] = $fundvalue['total_fund_grant'];
            $newArray['total_fund_grant_format'] = '$'.number_format($fundvalue['total_fund_grant'],2);
            
            $orgfunds = FundRecommendation::getOverAllGrantsOrganizationListFundWise($fundvalue['fund_id'],$startDate,$endDate,$org_id);

            $orgData = [];

            foreach ($orgfunds as $orgkey => $orgval) {

                $org_arr = Organization::find($orgval['organization_id']);

                $org_name = 'NA';
                if($org_arr){
                    $org_name = $org_arr['name'];
                }
                $orgData[] = [
                    'organization_id' => $orgval['organization_id'],
                    'organization_name' => $org_name,
                    'total_org_grant' => $orgval['total_org_grant'],
                    'total_org_grant_format' => '$'.number_format($orgval['total_org_grant'],2)
                ];

                $donorfunds = FundRecommendation::getOverAllGrantsDonorListFundOrganizationWise($fundvalue['fund_id'], $orgval['organization_id'],$startDate,$endDate);

                $donorData = [];

                foreach ($donorfunds as $donorkey => $donorval) {

                    $contact_arr = Contact::find($donorval['contact_id']);
                    $donorData[] = [
                        'contact_id' => $donorval['contact_id'],
                        'contact_name' => $contact_arr['first_name'].' '.$contact_arr['last_name'],
                        'total_donor_grant' => $donorval['total_donor_grant'],
                        'total_donor_grant_format' => '$'.number_format($donorval['total_donor_grant'],2)
                    ];
                }
                $orgData[$orgkey]['donor_data'] = $donorData;
            }
            $newArray['organization_data'] = $orgData;
            $overallArray[] = $newArray;
        }
        return $overallArray;
    }

    public function grantDetailAjax(Request $request) {

        $limit = '';
        $request_data = $request->all();
        $grant_type = $request_data['grant_type'];
        $grant_date_range = $request_data['grant_date_range'];
        $fund_id = $request_data['fund_id'];
        $org_id  = $request_data['org_id'];

        $startDate = '';
        $endDate   = '';

        if($grant_date_range) {

            if($grant_date_range == 'Custom Date') {

                $startDate = $request_data['start_date'];
                $endDate = $request_data['end_date'];

            }else{

                $date_arr = $this->getStartDateEndDate($grant_date_range);
                $startDate = $date_arr['startDate'];
                $endDate = $date_arr['endDate'];
            }
        }

        if($grant_type == 'Fund Wise'){

            return $overallArray=$this->getGrantsForDashboard($limit,$startDate,$endDate,$grant_type,$fund_id,$org_id);
        }

        if($grant_type == 'Organization Wise'){
            
            $orgfunds = FundRecommendation::getOverAllGrantsOrganizationList($startDate,$endDate);
            $orgData = [];

            foreach ($orgfunds as $orgkey => $orgval) {

                $org_arr = Organization::find($orgval['organization_id']);

                $org_name = 'NA';
                if($org_arr){
                    $org_name = $org_arr['name'];
                }
                $orgData[] = [
                    'organization_id' => $orgval['organization_id'],
                    'organization_name' => $org_name,
                    'total_org_grant' => $orgval['total_org_grant'],
                    'total_org_grant_format' => '$'.number_format($orgval['total_org_grant'],2)
                ];
            }
            return $arr['organization_data'] = $orgData;
        }

        if($grant_type == 'Donor Wise'){
            
            $donorfunds = FundRecommendation::getOverAllGrantsDonorList($startDate,$endDate);
            $donorData = [];

            foreach ($donorfunds as $donorkey => $donorval) {

                $contact_arr = Contact::find($donorval['contact_id']);
                $donorData[] = [
                    'contact_id' => $donorval['contact_id'],
                    'contact_name' => $contact_arr['first_name'].' '.$contact_arr['last_name'],
                    'total_donor_grant' => $donorval['total_donor_grant'],
                    'total_donor_grant_format' => '$'.number_format($donorval['total_donor_grant'],2)
                ];
            }
            return $arr['donor_data'] = $donorData;  
        }
    }

    # Gift functions

    public function giftDetail(Request $request) {

        return view('agency.agency-advisor.gifts_detail');
    }

    public function getGiftsForDashboard($limit,$startDate = false,$endDate = false,$grantType = false,$fund_id = false) {

        $funds = GiftHistory::getOverAllGiftFundList($limit,$startDate,$endDate,$fund_id);

        $newArray = [];
        $overallArray = [];

        foreach ($funds as $fundkey => $fundvalue) {

            $fund_arr = Fund::where('fund_id', $fundvalue['fund_id'])->first();

            $newArray['fund_id'] = $fundvalue['fund_id'];
            $newArray['fund_name'] = $fund_arr->name;
            $newArray['total_fund_grant'] = $fundvalue['total_fund_grant'];
            $newArray['total_fund_grant_format'] = '$'.number_format($fundvalue['total_fund_grant'],2);
            
            $donorfunds = GiftHistory::getOverAllGiftDonorListFundWise($fundvalue['fund_id'],$startDate,$endDate);

            $donorData = [];  

            foreach ($donorfunds as $donorkey => $donorval) {

                $donorData[] = [
                    'contact_name' => $donorval['donor'],
                    'total_donor_grant' => $donorval['total_donor_grant'],
                    'total_donor_grant_format' => '$'.number_format($donorval['total_donor_grant'],2)
                ];
            }
            $newArray['donor_data'] = $donorData;
            $overallArray[] = $newArray;
        }
        return $overallArray;
    }

    public function giftDetailAjax(Request $request) {

        $limit ='';
        $request_data = $request->all();
        $grant_type = $request_data['grant_type'];
        $grant_date_range = $request_data['grant_date_range'];
        $fund_id = $request_data['fund_id'];
        $startDate = '';
        $endDate   = '';

        if($grant_date_range) {

            if($grant_date_range == 'Custom Date') {

                $startDate = $request_data['start_date'];
                $endDate = $request_data['end_date'];

            }else{

                $date_arr = $this->getStartDateEndDate($grant_date_range);
                $startDate = $date_arr['startDate'];
                $endDate = $date_arr['endDate'];
            }
        }

        if($grant_type == 'Fund Wise') {

            return $overallArray = $this->getGiftsForDashboard($limit,$startDate,$endDate,$grant_type,$fund_id);
        }

        if($grant_type == 'Donor Wise') {
            
            $donorfunds = GiftHistory::getOverAllGiftDonorList($startDate,$endDate);
            $donorData = [];

            foreach ($donorfunds as $donorkey => $donorval) {

                $donorData[] = [
                    'contact_name' => $donorval['donor'],
                    'total_donor_grant' => $donorval['total_donor_grant'],
                    'total_donor_grant_format' => '$'.number_format($donorval['total_donor_grant'],2)
                ];
            }
            return $arr['donor_data'] = $donorData;
        }
    }

    // Recommendation 
    public function getRecommendationForDashboard($limit,$fund_id = false,$startDate = false,$endDate = false) {

        $recommendation = FundRecommendation::getRecommendationLists($limit,$fund_id,$startDate,$endDate);
        
        $newArray = [];
        $overallArray = [];

        foreach ($recommendation as $key => $val) {

            $contact_arr = Contact::find($val['contact_id']);
            $fund_arr = Fund::where('fund_id', $val['fund_id'])->first();

            $fund_name = 'NA';
            if ($fund_arr) {

                $fund_name = $fund_arr->name;
            }

            $ticketDetail = Ticket::checkTicketRecommendationWise($val['fund_recommendation_id']);

            $newArray['fund_recommendation_id'] = $val['fund_recommendation_id'];
            $newArray['fund_id'] = $val['fund_id'];
            $newArray['fund_name'] = $fund_name;
            $newArray['contact_id'] = $val['contact_id'];
            $newArray['contact_name'] = $contact_arr['first_name'].' '.$contact_arr['last_name'];
            $newArray['amount'] = GnUtils::money($val['amount']);
            $newArray['org_name'] = $val['org_name'];
            $newArray['date_submitted'] = GnUtils::customDate($val['date_submitted']);
            $newArray['approved_date'] = GnUtils::customDate($val['approved_date']);
            #$newArray['status'] = $val['status'];
            $newArray['status'] = 'N';
            $newArray['ticket'] = @$ticketDetail['0']['id'];
            
            $overallArray[] = $newArray;
        }
        #echo '<pre>';print_r($overallArray);die;
        return $overallArray;
    }
    
    public function recommendation(Request $request)
    {
        GnUtils::addBreadcrumb('Recommendations');

        $limit = 10;

        /* ---------------------------------------------------------
        FILTER INPUTS
        ---------------------------------------------------------- */
        $fundId         = $request->input('fund_id');
        $grantDateRange = $request->input('grant_date_range');
        $startDate      = $request->input('start_date');
        $endDate        = $request->input('end_date');

        /* ---------------------------------------------------------
        USER SESSION
        ---------------------------------------------------------- */
        $contactId    = Contact::sessionContactId();
        $contactFunds = Fund::getSelectableForGrantRecommendation();

        /* ---------------------------------------------------------
        USER PREFERENCES 
        ---------------------------------------------------------- */
        $preferredCharityId = null;
        $preferredChartType = null;

        $userPreference = UserPreference::where('contact_id', $contactId)->first();

        if ($userPreference && !empty($userPreference->preferences)) {

            $preferences = $userPreference->preferences;

            if (is_array($preferences)) {
                $preferredCharityId = isset($preferences['top_charity_id'])
                    ? $preferences['top_charity_id']
                    : null;

                $preferredChartType = isset($preferences['chart_type'])
                    ? $preferences['chart_type']
                    : null;
            }
        }

        /* ---------------------------------------------------------
        CHART DROPDOWN ORDER
        ---------------------------------------------------------- */
        $charts = config('dropdown.chart_dropdown');

        if ($preferredChartType && isset($charts[$preferredChartType])) {
            $charts = array_merge(
                array($preferredChartType => $charts[$preferredChartType]),
                array_diff_key($charts, array($preferredChartType => ''))
            );
        }

        /* ---------------------------------------------------------
        DATE RANGE
        ---------------------------------------------------------- */
        if ($grantDateRange && $grantDateRange !== 'Custom Date') {
            $dateRange = $this->getStartDateEndDate($grantDateRange);

            $startDate = isset($dateRange['startDate']) ? $dateRange['startDate'] : null;
            $endDate   = isset($dateRange['endDate']) ? $dateRange['endDate'] : null;
        }

        /* ---------------------------------------------------------
        LIST DATA
        ---------------------------------------------------------- */
        $recommendation = $this->getRecommendationForDashboard(
            $limit,
            $fundId,
            $startDate,
            $endDate
        );

        /* ---------------------------------------------------------
        GRAPH DATA
        ---------------------------------------------------------- */
        $recommendationGraph = FundRecommendation::getRecommendationGraph(
            $fundId,
            $startDate,
            $endDate
        );

        // Avoid N+1 query
        $fundNames = Fund::pluck('name', 'fund_id');

        $recommendationGraphArr = array();

        foreach ($recommendationGraph as $item) {

            $name = ($fundId > 0)
                ? $item->org_name
                : (isset($fundNames[$item->fund_id]) ? $fundNames[$item->fund_id] : 'Unknown Fund');

            $recommendationGraphArr[] = array(
                'name'   => $name,
                'amount' => $item->total_amount
            );
        }

        /* ---------------------------------------------------------
        RETURN VIEW
        ---------------------------------------------------------- */
        return view(
            'agency.agency-advisor.recommendation.index',
            compact(
                'contactFunds',
                'charts',
                'recommendation',
                'recommendationGraphArr',
                'preferredChartType',
                'preferredCharityId'
            )
        );
    }


    public function ajaxRecommendation(Request $request)
    {
        $limit = 15;
        $request_data = $request->all();
        $grant_date_range = $request_data['grant_date_range'];
        $fund_id = $request_data['fund_id'];
        
        if($grant_date_range) {

            if($grant_date_range == 'Custom Date') {

                $startDate = $request_data['start_date'];
                $endDate = $request_data['end_date'];

            }else{

                $date_arr = $this->getStartDateEndDate($grant_date_range);
                $startDate = $date_arr['startDate'];
                $endDate = $date_arr['endDate'];
            }
        }
       
        $recommendation = $this->getRecommendationForDashboard($limit,$fund_id,$startDate,$endDate);
        $totalRecommendation = FundRecommendation::getRecommendationLists('count',$fund_id,$startDate,$endDate);
        #print_r(count($totalRecommendation));die;
        #$funds = Fund::getAdvisorFunds($limit);

        $html = '';
        if ($totalRecommendation || $request->page == 1) {
            $html = view('agency.agency-advisor.recommendation.list', compact('recommendation'))->render();
        }
        return [
            'more' => ($totalRecommendation) > $limit  *  $request->page ? 1 : 0,
            'html' => $html,
            'totalTicket' => $totalRecommendation,
            'totalLimit' => $limit  *  $request->page
        ];

        // $html = '';
        // if (count($recommendation) || $request->page == 1) {
        //     $html = view('agency.agency-advisor.recommendation.list', compact('recommendation'))->render();
        // }
        // return [
        //     'more' => (count($recommendation) < $limit) ? 0 : 1,
        //     'html' => $html
        // ];
    }

    public function ajaxRecommendationGraph(Request $request)
    {
        $request_data = $request->all();
        $grant_date_range = $request_data['grant_date_range'];
        $fund_id = $request_data['fund_id'];
        
        if($grant_date_range) {

            if($grant_date_range == 'Custom Date') {

                $startDate = $request_data['start_date'];
                $endDate = $request_data['end_date'];

            }else{

                $date_arr = $this->getStartDateEndDate($grant_date_range);
                $startDate = $date_arr['startDate'];
                $endDate = $date_arr['endDate'];
            }
        }
        
        $recommendation = FundRecommendation::getRecommendationGraph($fund_id,$startDate,$endDate);
        $newArray = [];
        $overallArray = [];

        foreach ($recommendation as $key => $val) 
        {
            $fund_arr = Fund::where('fund_id', $val['fund_id'])->first();
            $name = ($fund_id > 0) ? $val['org_name'] : $fund_arr->name;
            
            $newArray['name'] = $name;
            $newArray['amount'] = $val['total_amount'];
            
            $overallArray[] = $newArray;
        }
        #echo '<pre>';print_r($overallArray);die;
        return $overallArray;
    }


    public function getStartDateEndDate($date_range) {

        if($date_range == 'Last 30 Days') { 

            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(29);
        }

        if($date_range == 'Last Month') {

            #$endDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->subMonth()->endOfMonth();
            $startDate = Carbon::now()->subMonth()->startOfMonth();
        }

        if($date_range == 'Last 3 Months') {

            $endDate = Carbon::now()->startOfDay();
            $startDate = Carbon::now()->subMonths(3);
        } 
        if($date_range == 'Last 6 Months') {

            $endDate = Carbon::now()->startOfDay();
            $startDate = Carbon::now()->subMonths(6);
        }
        if($date_range == 'Last 1 Year') {

            $endDate = Carbon::now()->startOfDay();
            $startDate = Carbon::now()->subMonths(12);
        }
        return array('startDate'=>$startDate->format('Y-m-d'),'endDate'=>$endDate->format('Y-m-d'));
    }

    public function client11(Request $request) {

        GnUtils::addBreadcrumb('Client');
        $contactFunds = Fund::getSelectableByContactId();

        $activity = new LogActivity();
       
        $fund_id = $request->fund_id;
        $search_contact = $request->search_contact;
        $limit = 6;
        
        $models = ContactFund::getAdvisorContacts($limit,$fund_id,$search_contact,'data');
        $totalContact = ContactFund::getAdvisorContacts($limit,$fund_id,$search_contact,'count');

        $items = [];
        foreach($models as $model) {
            //$fundName = Fund::getNameById($model->fund_id);
            $contact = Contact::getByContactId($model->contact_id);
            
            $logActivity = $activity->getClientLastLogin($model->contact_id);
        
            $item = [
                'contact_id' => $contact->contact_id,
                'contact_name' => $contact->name,
                'contact_email' => $contact->email_address,
                'last_login' => $logActivity['created_on'] ?? 'NA',
            ];
            $items[] = $item;
        }

        return view('agency.agency-advisor.client.index', compact('contactFunds', 'items', 'totalContact'));
    }

    /**
     * client function with  filtering
     */

    public function client(Request $request)
    {
        GnUtils::addBreadcrumb('Clients');

        $contactFunds = Fund::getSelectableByContactId();

        $fundId = $request->input('fund_id');
        $search = $request->input('search_contact');
        $limit = 10;
        
        $items = ContactFund::getAdvisorContacts($limit, $fundId, $search, 'data');

        return view('agency.agency-advisor.client.index', [
            'items'        => $items,
            'contactFunds' => $contactFunds,
        ]);
    }

    /**
     * client detail function
     */

    public function clientDetail(Request $request, $id)
    {
        GnUtils::addBreadcrumb('Clients', route('agency-client'));
        GnUtils::addBreadcrumb('Client Profile');

        $view = 'view';
        $profile = Contact::getByContactId($id);

        $addresses = [];
        $AddressTypes = AddressType::getContactAddressTypes();

        foreach($AddressTypes as $type) {
            $address = [];
            $address['type'] = $type->address_type;
            $address['label'] = isset($type->label) ? $type->label : ucfirst($type->address_type);
            $address['is_primary'] = $type->is_primary;
            $address['address'] = $profile->getMultiLineAddress($type->address_type);
            $addresses[] = $address;
        }

        $primaryPhoneType = PhoneType::getContactPhoneTypePrimary();
        $phones = [];
        foreach($profile->phones() as $phone) {
            $phone->formatPhoneNumber();
            $phone['is_primary'] = ($primaryPhoneType == $phone->phone_type ? true : false);
            $phone['phone_type'] = $phone->phone_type;
            $phone['label'] = PhoneType::getContactPhoneTypeLabel($phone->phone_type);
            $phone['phone_number'] = $phone->phone_number;
            $phones[] = $phone;
        }

        # funds
        $contactFunds = ContactFund::getFundIdsForViewByContactId($id);
        $fundData = [];
        foreach ($contactFunds as $key => $fund) {

            $fund = Fund::getFundById($fund);

            $fundData[] = [
                'fund_id' => $fund['fund_id'],
                'fund_name' => $fund['name']
            ];
        }
        #End
        return view('agency.agency-advisor.client.view', compact('profile', 'addresses', 'phones', 'view','fundData'));
    }

    public function dashboard(Request $request) {

        $limit = 5;
        $date_range = 'Last 30 Days';
        $grant_type = 'Fund Wise';
        $date_arr = $this->getStartDateEndDate($date_range);

        $startDate = $date_arr['startDate'];
        $endDate = $date_arr['endDate'];

        $overAllGrant = $this->getGrantsForDashboard($limit,$startDate,$endDate,$grant_type,'','');
        $overAllGift  = $this->getGiftsForDashboard($limit,$startDate,$endDate,$grant_type,'');
        $funds = Fund::getAdvisorFunds($limit);
        $openTickets = Ticket::paginateMyTicket($limit,'','open','','');

        # Ticket Count Status wise
        $totalTicketStatus = Ticket::getTicketCountStatusWise();
        $ticketArr = [];
        foreach ($totalTicketStatus as $item) {
            $ticketArr[] = [
                'status' => $item->status,
                'status_name' => config('dropdown.status')[$item->status],
                'total' => $item->total,
            ];
        }

        $recommendation  = $this->getRecommendationForDashboard('','',$startDate,$endDate);

        foreach ($funds as $fundkey => $fundvalue) {
            $funds[$fundkey]['balance_format'] = '$'.number_format($fundvalue['balance'],2);
        }

        $charities = config('charities');
        $charts = config('dropdown.chart_dropdown');
        #echo '<pre>';print_r($charities[0]['charity_id']);die;
        return view('agency.agency-advisor.new_dashboard', compact('funds','openTickets','overAllGrant','overAllGift','recommendation','ticketArr','charities','charts'));
    }
    # Your Preferences
    public function yourPreferences(Request $request)
    {
        $charities = config('charities');
        $charts = config('dropdown.chart_dropdown');
        
        return view('agency.agency-advisor.preferences.view', compact('charities', 'charts'));
    }


    public function getCharityById($charities, $charity_id) {
        foreach ($charities as $charity) {
            if ($charity['charity_id'] == $charity_id) {
                return $charity;
            }
        }
        return null;
    } 
    
    public function charity($id, Request $request)
    {
        $charities = config('charities');
        $charity = $this->getCharityById($charities, $id);
        
        GnUtils::addBreadcrumb('Charity', route('agency-dashboard'));
        GnUtils::addBreadcrumb($charity['charity_name']);
        
        $charities = config('charities');
        return view('agency.agency-advisor.charity.view', compact('charity','charities'));
    }
    public function charityFundClients($id,$fund_id, Request $request)
    {
        $charities = config('charities');
        $charity = $this->getCharityById($charities, $id);
        $fund = $this->getFundById($charity['funds'], $fund_id);
       # echo '<pre>';print_r($fund['client']);die;
        $funds = $charity['funds'];

        GnUtils::addBreadcrumb('Charity', route('agency-dashboard'));
        GnUtils::addBreadcrumb($charity['charity_name'], route('agency-charity',[$id]));
        GnUtils::addBreadcrumb($fund['fund_name']);
        
        $charities = config('charities');
        return view('agency.agency-advisor.charity.clients', compact('charity','charities','fund','funds'));
    }

    public function getFundById($funds, $fund_id) {
        foreach ($funds as $fund) {
            if ($fund['fund_id'] == $fund_id) {
                return $fund;
            }
        }
        return null;
    }

    public function agencyCharityDaf($id, Request $request)
    {
        $charities = config('charities');
        $charity = null;
        if ($id) {
            foreach ($charities as $c) {
                if ($c['charity_id'] == $id) {
                    $charity = $c;
                    break;
                }
            }
        }
        if (!$charity) {
            // Optionally, redirect or show a not found page
            return redirect()->route('agency-dashboard')->with('error', 'Charity not found.');
        }
        GnUtils::addBreadcrumb($charity['charity_name']);
        return view('agency.agency-advisor.daf_charity', compact('charity', 'charities'));
    }

    public function agencyCharityNewdDaf(Request $request)
    {
        $charity_id = $request->input('charity_id');
        $charities = config('charities');
        $charity = null;
        if ($charity_id) {
            foreach ($charities as $c) {
                if ($c['charity_id'] == $charity_id) {
                    $charity = $c;
                    break;
                }
            }
        }
        if (!$charity) {
            // Optionally, redirect or show a not found page
            return redirect()->route('agency-dashboard')->with('error', 'Charity not found.');
        }
       

        GnUtils::addBreadcrumb($charity['charity_name'], route('agency-charity-daf',[$charity_id]));
        GnUtils::addBreadcrumb('New DAF Account');

        return view('agency.agency-advisor.new_daf_charity', compact('charity', 'charities'));
    }

}
