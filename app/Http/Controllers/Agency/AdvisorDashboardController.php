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
use App\Models\ContactFund;
use App\Models\Contact;
use App\Models\FundRecommendation;
use App\Models\Organization;
use App\Models\GiftHistory;
use App\Models\GrantHistory;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use Auth;
use App\Models\Api;
use App\Models\Fund;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Http\Request;
use League\Csv\Writer;
use PDF;
use Carbon\Carbon;

// Funds = 'JCFEX', 'Abra';
/**
 * Class FundController
 * @package App\Http\Controllers
 */
class AdvisorDashboardController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index(Request $request) {

        # Fund Balance
        $fundBalanceData = [];
        $funds = Fund::advisorChartFundBalance();
        foreach ($funds as $item) {

            $fundBalanceData[$item['name']] = $item['balance'];
        }

        $fundsBalanceChartData = [];
        if (count($fundBalanceData)) {

            $fundsBalanceChartData['label'] = array_keys($fundBalanceData);
            $fundsBalanceChartData['data'] = array_values($fundBalanceData);
        }
        # End

        # Pending Action
        $pendingActionData = [];
        $funds = Fund::advisorChartFundBalance();

        foreach ($funds as $item) {

            $pendingActionData[$item['name']] = $item['balance'];
        }

        $pendingActionChartData = [];  

        if (count($pendingActionData)) {

            $pendingActionChartData['label'] = array_keys($pendingActionData);
            $pendingActionChartData['data'] = array_values($pendingActionData);
        }
        # End

        # Contribution
        $query = GiftHistory::query();
        $contactId = Contact::sessionContactId();
        $funds = ContactFund::getFundIdsForViewByContactId($contactId);

        $query->where(function ($query) use ($funds) {
            foreach ($funds as $i => $fund) {
                if ($i == 0) {
                    $query->where('fund_id', 'ilike', $fund);
                } else {
                    $query->orWhere('fund_id', 'ilike', $fund);
                }
            }
        });

        $query->where('gift_date', '>=', now()->subDays(300));
        $giftHistoryData = $query->groupBy('fund_id')
            ->selectRaw("sum(amount) as total_amount,fund_id")
            ->orderByDesc('total_amount')
            ->get();

        $contributionData = [];
        foreach ($giftHistoryData as $item) {

            $res = Fund::getFundById($item['fund_id']);
            $contributionData[$res['name']] = $item['total_amount'];
        }

        $contributionChartData = [];
        if (count($contributionData)) {
            $contributionChartData['label'] = array_keys($contributionData);
            $contributionChartData['data'] = array_values($contributionData);
        }
        # End

        # Grants
        $query = GrantHistory::query();
        $query->where(function ($query) use ($funds) {
            foreach ($funds as $i => $fund) {
                if ($i == 0) {
                    $query->where('fund_id', 'ilike', $fund);
                } else {
                    $query->orWhere('fund_id', 'ilike', $fund);
                }
            }
        });

        $query->where('grant_date', '>=', now()->subDays(300));
        $grantHistoryData = $query->groupBy('fund_id')
            ->selectRaw("sum(amount) as total_amount,fund_id")
            ->orderByDesc('total_amount')
            ->get();

        $grantsData = [];
        foreach ($grantHistoryData as $item) {
            $res = Fund::getFundById($item['fund_id']);
            $grantsData[$res['name']] = $item['total_amount'];
        }
        $grantsChartData = [];  
        if (count($grantsData)) {
            $grantsChartData['label'] = array_keys($grantsData);
            $grantsChartData['data'] = array_values($grantsData);
        }
        # End

        return view('agency.agency-advisor.dashboard_chart', compact('fundsBalanceChartData','pendingActionChartData','contributionChartData','grantsChartData'));
    }

    public function dashboard(Request $request) {

        $limit = 2;
        $date_range = 'Last 30 Days';
        $grant_type = 'Fund Wise';
        $date_arr = $this->getStartDateEndDate($date_range);
       
        $startDate = $date_arr['startDate'];
        $endDate = $date_arr['endDate'];

        $overallGrantArray = $this->getGrantsForDashboard($limit,$startDate,$endDate,$grant_type,'','');
        $overallGiftArray  = $this->getGiftsForDashboard($limit,$startDate,$endDate,$grant_type,'');
        $funds = Fund::getAdvisorFunds($limit);
        $openTickets = Ticket::paginateMyTicket($limit,'open','','');
        #echo '<pre>';print_r($openTickets);die;

        foreach ($funds as $fundkey => $fundvalue) {
            $funds[$fundkey]['balance_format'] = '$'.number_format($fundvalue['balance'],2);
        }
        return view('agency.agency-advisor.dashboard', compact('funds','openTickets','overallGrantArray','overallGiftArray'));
    }


    // Grants Functions

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

    public function getStartDateEndDate($date_range) {

        if($date_range == 'Last 30 Days') { 

            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(29);
        }

        if($date_range == 'Last Month') {

            $endDate = Carbon::now()->startOfMonth();
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
}
