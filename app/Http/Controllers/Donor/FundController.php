<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\Http\Controllers\Donor;

use App\Forms\FormFundHistoryFilter;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\FundRecommendation;
use App\Models\GiftHistory;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use Auth;
use App\Models\Api;
use App\Models\Fund;
use Illuminate\Http\Request;
use League\Csv\Writer;
use PDF;

// Funds = 'JCFEX', 'Abra';
/**
 * Class FundController
 * @package App\Http\Controllers
 */
class FundController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // log activity
        $activity = new LogActivity(LogActivity::NAME_FUND, LogActivity::ACTION_LIST);
        $activity->description(LogActivity::DESCRIPTION_FUND_LIST)->add();;

        $interestBasedArticles = true;
        /** @var Contact $contact */
        $contact = Contact::sessionContact();
        // $funds = $this->apiMyFunds($request);

        // SELECT fr.*, to_char(date_submitted, 'mm-dd-yyyy') as SUBMITT_DATE from fund_recommendation fr where fund_id = ? and contact_id=? and is_approved='N'
        // $date = date('Y-m-d', strtotime("-500 days"));
        // $pendingGrants =  FundRecommendation::where(['contact_id' => $contact->contact_id])
        //     ->where('date_submitted', '>', $date)
        //     ->orderBy('date_submitted', 'desc')->get(); // 'is_approved' => 'N',
        $pendingGrants = [];

        return view('donor.funds.index', compact('contact', 'interestBasedArticles', 'pendingGrants'));
    }

    public function ajaxMyFunds(Request $request)
    {
        $limit = 3;
        $funds = Fund::paginateMyFunds($limit);
        $html = '';
        if (count($funds) || $request->page == 1) {
            $html = view('donor.funds.list', compact('funds'))->render();
        }
        return [
            'more' => (count($funds) < $limit) ? 0 : 1,
            'html' => $html
        ];
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
        return view('donor.funds.home', compact('funds', 'contact', 'interestBasedArticles'));
    }

    public function apiMyFunds(Request $request) {
        $contact = Contact::sessionContact();
        return Fund::getViewableByContactId($contact->contact_id);
    }

    /**
     * @param Request $request
     * @param null $id, fund id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function giftHistory(Request $request, $id=null)
    {
        $api = new Api();
        $params = $request->all();
        $params['id'] = $id;

        if ($request->ajax()) {
            $models = $api->apiPaginatedGiftHistory($id, $params);
            return [
                'more' => $models->nextPageUrl() ? 1 : 0,
                'html' => view('donor.gifts.list', ['models' => $models])->render()
            ];
        }
        if(GnUtils::isAgencySession()){
            GnUtils::addBreadcrumb('Fund Statement', route('agency-fund', $id));
            GnUtils::addBreadcrumb('Contribution History');
        }else{
            GnUtils::addBreadcrumb('Fund Statement', route('fund', $id));
            GnUtils::addBreadcrumb(ClientConfig::text('GIFT_HISTORY'));
        }

        $fund = Fund::getFundById($id);
        $selectedId = isset($params['organization_id'])? $params['organization_id'] : 0;

        // log activity
        $activity = new LogActivity(LogActivity::NAME_FUND, LogActivity::ACTION_LIST);
        $activity->onModel($fund)->description(LogActivity::DESCRIPTION_GIFT_HISTORY)->add();

        // get sum
        $query = GiftHistory::filterQuery($id, $params);
//        $query = GiftHistory::where('fund_id', 'ilike', $id);
//        if (isset($params['startDate']) && isset($params['endDate'])) {
//            $from = date($params['startDate']);
//            $to = date($params['endDate']);
//            $query->whereBetween('gift_date', [$from, $to]);
//        }
        $giftTotal = $query->sum('amount');

        $barChartData = $api->apiGiftHistoryGroupedByMonthlyGrantDate($id, $params);

        // for UI
        if (isset($params['fund_id']) && !empty($params['fund_id'])) {
            $title = $params['fund_id'] == 'all' ? 'All' : Fund::getNameById($params['fund_id']);
        } else {
            $title = Fund::getNameById($id);
        }

        // for UI
        $filter = new FormFundHistoryFilter();
        $filter->set($id, $request->all());

        return view('donor.gifts.history', compact('filter', 'title', 'params', 'selectedId', 'giftTotal', 'barChartData'));
    }

    public function apiGiftHistory(Request $request, $id=null)
    {
        $params = $request->all();

        $api = new Api();
        return $api->apiPaginatedGiftHistory($id, $params);
    }

    /**
     * @param Request $request
     * @param null $id, fund id
     * @return PDF View
     */
    public function printGiftHistory(Request $request, $id=null)
    {
        $api = new Api();
        $params = $request->all();
        $allRecords = true;
        $gifts = $api->apiPaginatedGiftHistory($id, $params, $allRecords);
        $total = 0;
        foreach($gifts as $gift) {
            $total += $gift->amount;
        };
        // Set extra option
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // pass view file
        $pdf = PDF::loadView('donor.gifts.print-history', compact('gifts', 'params', 'total'));
        return $pdf->stream();
        // to download pdf
        // return $pdf->download('pdfview.pdf');
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function csvGiftHistory(Request $request, $id=null)
    {
        $api = new Api();
        $params = $request->all();
        $allRecords = true;
        $gifts = $api->apiPaginatedGiftHistory($id, $params, $allRecords);

        $header = ['Date', 'Donor', 'Amount'];
        $records = [];
        foreach ($gifts as $key => $gift) {
            $records[] = [
                GnUtils::customDate($gift->gift_date),
                $gift->donor,
                GnUtils::money($gift->amount)
            ];
        }

        // load the CSV document from a string
        /** @var Writer $csv */
        $csv = Writer::createFromString();
        $csv->insertOne($header);
        $csv->insertAll($records);

        $filename = trim($id) . '_' . 'Contributions.csv';

        // echo $csv->toString(); //returns the CSV document as a string
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename);
    }

    /**
     * @param Request $request
     * @param null $id, fund id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function grantHistory(Request $request, $id=null)
    {
        if (ClientInfo::isCCT()) {
            return $this->grantHistoryCCT($request, $id);
        }

        $grantTotal = 0;
        $api = new Api();
        $params = $request->all();
        $params['id'] = $id;

        if ($request->ajax()) {
            $models = $api->apiPaginatedGrantHistory($id, $params);
            $showRepeat = GnUtils::isDonorSession();
            return [
                'more' => $models->nextPageUrl() ? 1 : 0,
                'html' => view('donor.grants.list', ['showRepeat' => $showRepeat, 'models' => $models])->render()
            ];
        }

        // breadcrumb
        if(GnUtils::isAgencySession()){
            GnUtils::addBreadcrumb('Fund Statement', route('agency-fund', $id));
        }else{
            GnUtils::addBreadcrumb('Fund Statement', route('fund', $id));
        }
        GnUtils::addBreadcrumb((GnUtils::isDonorSession() ? 'Grant' : 'Disbursement') . ' History');

        $fund = Fund::getFundById($id);
        $organizations = $api->apiGrantHistorySelectableOrganizations($id);
        $selectedId = isset($params['organization_id'])? $params['organization_id'] : 0;

        $granteeGroupedData = $api->apiGrantHistoryGroupedByGranteeData($id, $params);

        $pieChartData = [];

        if (ClientInfo::isJCF()) {
            $whitespaces = '                                                                                          ';
        } else {
            $whitespaces = '';
        }
        foreach ($granteeGroupedData as $groupedDataLabel => $groupedDataAmount) {
            $granteeData = [];
            $granteeData['label'] = mb_strimwidth($groupedDataLabel, 0, 40, '...') .  $whitespaces;
            $granteeData['value'] = $groupedDataAmount;
            $pieChartData[] = $granteeData;
            $grantTotal += $groupedDataAmount;
        }

        $granteeMonthlyGroupedData = $api->apiGrantHistoryGroupedByMonthlyGrantDate($id, $params);

        $monthWiseData = [];
        foreach ($granteeMonthlyGroupedData as $item) {
            if(isset($monthWiseData[$item['year_month']]))
                $monthWiseData[$item['year_month']] +=  $item['total_amount'];
            else
                $monthWiseData[$item['year_month']] = $item['total_amount'];
        }

        $barChartData = [];
        if (count($monthWiseData)) {
            $barChartData['label'] = array_keys($monthWiseData);
            $barChartData['data'] = array_values($monthWiseData);
        }

        // log activity
        $activity = new LogActivity(LogActivity::NAME_FUND, LogActivity::ACTION_LIST);
        $activity->onModel($fund)->description(LogActivity::DESCRIPTION_GRANT_HISTORY)->add();

        // return compact('fund', 'organizations', 'selectedId', 'pieChartData', 'barChartData');
        
        return view('donor.grants.history', compact('fund', 'params', 'organizations', 'selectedId', 'pieChartData', 'barChartData', 'grantTotal'));
    }

    /**
     * CCT Specific
     * @param Request $request
     * @param null $id, fund id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function grantHistoryCCT(Request $request, $id=null)
    {
        $grantTotal = 0;
        $api = new Api();
        $params = $request->all();
        $params['id'] = $id;

        if ($request->ajax()) {
            $models = $api->apiPaginatedGrantHistory($id, $params);
            $showRepeat = GnUtils::isDonorSession();
            return [
                'more' => $models->nextPageUrl() ? 1 : 0,
                'html' => view('donor.grants.list', ['showRepeat' => $showRepeat, 'models' => $models])->render()
            ];
        }

        // breadcrumb  //agency-fund
        GnUtils::addBreadcrumb('Fund Statement', route('fund', $id));
        GnUtils::addBreadcrumb((GnUtils::isDonorSession() ? 'Grant' : 'Disbursement') . ' History');

        $pieChartData = [];
        $whitespaces = '                                                                                          ';
        // $whitespaces = '';

        $granteeGroupedData = $api->apiGrantHistoryGroupedByInterestsDataCCT($id, $params);
        foreach ($granteeGroupedData as $groupedDataLabel => $groupedDataAmount) {
            $granteeData = [];
            $granteeData['label'] = mb_strimwidth($groupedDataLabel, 0, 40, '...') .  $whitespaces;
            $granteeData['value'] = $groupedDataAmount;
            $pieChartData[] = $granteeData;
            $grantTotal += $groupedDataAmount;
        }

        // log activity
        // $activity = new LogActivity(LogActivity::NAME_FUND, LogActivity::ACTION_LIST);
        // $activity->onModel($fund)->description(LogActivity::DESCRIPTION_GRANT_HISTORY)->add();

        // for UI
        $filter = new FormFundHistoryFilter();
        $filter->set($id, $request->all());

        // for UI
        if (isset($params['fund_id']) && !empty($params['fund_id'])) {
            $title = $params['fund_id'] == 'all' ? 'All' : Fund::getNameById($params['fund_id']);
        } else {
            $title = Fund::getNameById($id);
        }
        return view('cct.grants.history', compact('filter', 'title', 'params', 'pieChartData', 'grantTotal'));
    }

    /**
     * @param Request $request
     * @param null $id, fund id
     * @return PDF View
     */
    public function printGrantHistory(Request $request, $id=null)
    {
        $api = new Api();
        $params = $request->all();
        $allRecords = true;
        $grants = $api->apiPaginatedGrantHistory($id, $params, $allRecords);
        $total = 0;
        foreach($grants as $grant) {
            $total += $grant->amount;
        };
        // Set extra option
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // pass view file
        // return view('donor.grants.print-history', compact('grants'));
        $pdf = PDF::loadView('donor.grants.print-history', compact('grants', 'params', 'total'));
        return $pdf->stream();
        // to download pdf
        // return $pdf->download('pdfview.pdf');
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function csvGrantHistory(Request $request, $id=null)
    {
        $api = new Api();
        $params = $request->all();
        $allRecords = true;
        $grants = $api->apiPaginatedGrantHistory($id, $params, $allRecords);

        $header = ['Date', 'Grantee', 'Description', 'Amount'];
        $records = [];
        foreach ($grants as $key => $grant) {
            $records[] = [
                GnUtils::customDate($grant->grant_date),
                $grant->grantee,
                $grant->grant_description,
                GnUtils::money($grant->amount)
            ];
        }

        // load the CSV document from a string
        /** @var Writer $csv */
        $csv = Writer::createFromString();
        $csv->insertOne($header);
        $csv->insertAll($records);

        $filename = trim($id) . '_' . 'Grants.csv';

        // echo $csv->toString(); //returns the CSV document as a string
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename);
    }

    public function apiGrantHistory(Request $request, $id=null)
    {
        $params = $request->all();

        $api = new Api();
        $organizations = $api->apiGrantHistorySelectableOrganizations($id);

        $models = $api->apiPaginatedGrantHistory($id, $params);
        return ['organizations' => $organizations, 'models' => $models];
    }

    /**
     * show any pending grants on dashboard
     * @param Request $request
     * @return array
     */
    public function ajaxPendingGrants(Request $request)
    {
        if (ClientInfo::isGNA()) return ['html' => ''];

        /* Recommendations made by the contact
        $date = date('Y-m-d');
        $contactId = Contact::sessionContactId();
        $models = FundRecommendation::where(['contact_id' => $contactId, 'is_approved' => 'N'])
            ->orWhere(function($query) use ($contactId, $date) {
                $query->where(['contact_id' => $contactId, 'is_approved' => 'Y'])
                    ->where('default_payment_date', '>=', $date);
            })
            ->orderBy('fund_id', 'DESC')->get();  
        */

        // get paginated recommendations
        $models = FundRecommendation::paginatePendingRecommendations($request);

        // if there are no FundRecommendation
        if ($models == null) {
            return ['more' => 0, 'html' => ''];
        }

        // arrange recommendations by fund-ids
        $grants = [];
        foreach($models as $model) {
            $grant = [];
            $grant['org'] = $model->org_name;
            $grant['amount'] = GnUtils::moneyJCFS($model->amount);
            $grant['status'] = $model->is_approved;
            $grant['grant_status'] = "";
            if (ClientInfo::isCCT()) {
                $grant['status'] = $model->is_approved;
                if ($model->grant_status == FundRecommendation::GRANT_STATUS_SUBMITTED){
                    $grant['grant_status'] = "Submitted";
                } else if ($model->grant_status == FundRecommendation::GRANT_STATUS_PENDING) {
                    $grant['grant_status'] = "Approval Pending";
                } else if ($model->grant_status == FundRecommendation::GRANT_STATUS_APPROVED){
                    $grant['grant_status'] = "Approved";
                } else if ($model->grant_status == FundRecommendation::GRANT_STATUS_PAID){
                    $grant['grant_status'] = "Paid";
                } else if ($model->grant_status == FundRecommendation::GRANT_STATUS_GRANTED){
                    $grant['grant_status'] = "Granted";
                } else if ($model->grant_status == FundRecommendation::GRANT_STATUS_CANCELLED){
                    $grant['grant_status'] = "Canceled";
                } else {
                    $grant['grant_status'] = "Approval Pending ";
                }
            }
            $grant['date_submitted'] = GnUtils::customDate($model->date_submitted);
            $grant['default_payment_date'] = GnUtils::customDate($model->default_payment_date);
            $grant['approved_date'] = GnUtils::customDate($model->approved_date);

            $name = Fund::getNameById($model->fund_id);
            $grants[$name][] = $grant;
        }

        $page = $request->page;
        $html = view('donor.grants.pending-all', compact('grants', 'page'))->render();
        return ['more' => 1, 'html' => $html];
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pendingDisbursements(Request $request, $id=null)
    {
        // log activity
        $activity = new LogActivity(LogActivity::NAME_GRANT, LogActivity::ACTION_LIST);
        $activity->data($request->all())->description(LogActivity::DESCRIPTION_DISBURSEMENTS_PENDING)->add();;

        GnUtils::addBreadcrumb('Fund Statement', route('fund', $id));
        GnUtils::addBreadcrumb('Pending Grants');

        $fund = Fund::getFundById($id);
        $data = $this->apiPendingDisbursements($request, $id);
        $models = $data['models'];
        $total = $data['total'];
        return view('donor.grants.pending', compact('fund', 'models', 'total'));
    }

    public function apiPendingDisbursements(Request $request, $id=null) {
        $params = $request->all();

        $api = new Api();
        $models = $api->apiPendingDisbursements($id, $params);
        $total = 0;
        foreach($models as $model) {
            $total += $model->amount;
        }
        return ['models' => $models, 'total' => $total];
    }

    // https://stackoverflow.com/questions/52034865/php-blowfish-cbc-vs-pearl-crypt
    public function password() {
        $value = 'chestercap';
        $cipher = 'bf-cbc';
        $key = "+9))u*,--ak<K;wpS/I{c`R`aKy+jaaY>J3=2-G.b1q SG?uio[cl6JT";
        $option = OPENSSL_RAW_DATA;
        $iv = '39480126';
        $crypted = openssl_encrypt($value, $cipher, $key, $option, $iv);
        // return $crypted;
        return unpack("H*", $crypted);
    }

    public function testJs()
    {
        return view('test');
    }
}
