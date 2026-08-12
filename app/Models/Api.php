<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 20-10-2019
 * Time: 18:13
 */

namespace App\Models;


use App\Helpers\GnUtils;
use Symfony\Component\HttpFoundation\Request;

class Api
{
    const PAGE_SIZE = 10;

    /**
     * Gift history
     *
     * @param $fundId
     * @param array $params
     * @return mixed
     */
    public function apiPaginatedGiftHistory($fundId, $params=[], $allRecords = false)
    {
        $limit = self::PAGE_SIZE;

        $query = GiftHistory::filterQuery($fundId, $params);

        // $query = GiftHistory::where(['fund_id' => $fundId]);
        // $query = GiftHistory::where('fund_id', 'ilike', $fundId);
        // if (isset($params['startDate']) && isset($params['endDate'])) {
        //    $from = date($params['startDate']);
        //    $to = date($params['endDate']);
        //    $query->whereBetween('gift_date', [$from, $to]);
        // }

        if ($allRecords) {
            $giftHistory = $query->orderBy('gift_date', 'DESC')->orderBy('fund_history_id', 'ASC')->get();
        } else {
            $giftHistory = $query->orderBy('gift_date', 'DESC')->orderBy('fund_history_id', 'ASC')->paginate($limit);
        }
        return $giftHistory;
    }

    /**
     * @param $fundId
     */
    public function apiGiftHistorySelectableOrganizations($fundId)
    {
        $records = GiftHistory::where('fund_id', 'ilike', $fundId)->orderBy('grantee')->pluck('grantee', 'organization_id')->toArray();
        foreach($records as $key => $value) {
            if(is_null($key) || $key == '' || is_null($value) || $value == '')
                unset($records[$key]);
        }
        return $records;
    }

    /**
     * Grant History
     *
     * @param $fundId
     * @param array $params
     * @return mixed
     */
    public function apiPaginatedGrantHistory($fundId, $params=[], $allRecords = false)
    {
        $limit = self::PAGE_SIZE;
        $query = GrantHistory::filterQuery($fundId, $params);

        // $query = GrantHistory::where(['fund_id' => $fundId]);
        // $query = GrantHistory::where('fund_id', 'ilike', $fundId);
        // if (isset($params['startDate']) && isset($params['endDate'])) {
        //    $from = date($params['startDate']);
        //    $to = date($params['endDate']);
        //    $query->whereBetween('grant_date', [$from, $to]);
        // }
        // if (isset($params['organization_id'])) {
        //     if ($organizationId = $params['organization_id']) {
        //        $query->where(['organization_id' => $organizationId]);
        //    }
        // }

        if ($allRecords) {
            $grantHistory = $query->orderBy('grant_date', 'DESC')->orderBy('fund_grant_history_id', 'ASC')->get();
        } else {
            $grantHistory = $query->orderBy('grant_date', 'DESC')->orderBy('fund_grant_history_id', 'ASC')->paginate($limit);
        }
        return $grantHistory;
    }

    /**
     * @param $fundId
     * @param array $params
     * @return array
     */
    public function apiPendingDisbursements($fundId, $params=[]){
        // TODO: Add a back button to statement
        $contact = Contact::sessionContact();
        $models = FundRecommendation::where('fund_id', 'ilike', $fundId)
            ->where(['contact_id' => $contact->contact_id, 'is_approved' => 'N'])
            ->orderBy('date_submitted', 'DESC')->get();
        return $models;
    }

    public function apiPendingDisbursementsTotal($fundId, $contact=null){
        $contact = Contact::sessionContact();
        $models = FundRecommendation::where('fund_id', 'ilike', $fundId)
            ->where(['contact_id' => $contact->contact_id, 'is_approved' => 'N'])->sum('amount');
        return $models;
    }

    /**
     * @param $fundId
     */
    public function apiGrantHistorySelectableOrganizations($fundId)
    {
        $records = GrantHistory::where('fund_id', 'ilike', $fundId)->orderBy('grantee')->pluck('grantee', 'organization_id')->toArray();
        foreach($records as $key => $value) {
            if(is_null($key) || $key == '' || is_null($value) || $value == '')
                unset($records[$key]);
        }
        return $records;
    }

    /**
     * Grant History grouped data by Grantee : used to display in chart
     *
     * @param $fundId
     * @param array $params
     * @return mixed
     */
    public function apiGrantHistoryGroupedByGranteeData($fundId, $params=[])
    {
        $limit = self::PAGE_SIZE;

        $query = GrantHistory::filterQuery($fundId, $params);

        // $query = GrantHistory::where(['fund_id' => $fundId]);
        // $query = GrantHistory::where('fund_id', 'ilike', $fundId);
        // if (isset($params['startDate']) && isset($params['endDate'])) {
        //    $from = date($params['startDate']);
        //    $to = date($params['endDate']);
        //    $query->whereBetween('grant_date', [$from, $to]);
        // }
        // if (isset($params['organization_id'])) {
        //     if ($organizationId = $params['organization_id']) {
        //        $query->where(['organization_id' => $organizationId]);
        //    }
        // }

        if(ClientInfo::isJCF()) {
            $grantHistoryData = $query->groupBy('grantee')->orderBy('total_amount', 'desc')->selectRaw('sum(amount) as total_amount, grantee')->pluck('total_amount','grantee');
        } else {
            $grantHistoryData = $query->groupBy('grantee')->orderBy('total_amount', 'desc')->selectRaw('sum(amount) as total_amount, grantee')->pluck('total_amount','grantee');
        }
        return $grantHistoryData;
    }


    /**
     * CCT Grant History grouped data by Interest Area : used to display in chart
     *
     * @param $fundId
     * @param array $params
     * @return mixed
     */
    public function apiGrantHistoryGroupedByInterestsDataCCT($fundId, $params=[])
    {
        $query = GrantHistory::filterQuery($fundId, $params);

        // $query = GrantHistory::where(['fund_id' => $fundId]);
        // $query = GrantHistory::where('fund_id', 'ilike', $fundId);
        // if (isset($params['startDate']) && isset($params['endDate'])) {
        //     $from = date($params['startDate']);
        //     $to = date($params['endDate']);
        //     $query->whereBetween('grant_date', [$from, $to])->get();
        // }
        // if (isset($params['organization_id'])) {
        //     if ($organizationId = $params['organization_id']) {
        //         $query->where(['organization_id' => $organizationId]);
        //     }
        // }

        $grantHistoryData = $query->groupBy('fund_grant_history.organization_id')
            ->orderBy('total_amount', 'desc')
            ->selectRaw('sum(amount) as total_amount, fund_grant_history.organization_id')
            ->pluck('total_amount', 'fund_grant_history.organization_id');
        $items = [];
        foreach ($grantHistoryData as $orgId => $amount) {
            if (!$orgId) continue;
            $name = OrgInterestArea::getPrimaryInterestName($orgId);
            if (empty($name)) $name = 'Other';
            $items[$name] = isset($items[$name]) ? $items[$name] + $amount : $amount;
        }

        return $items;
    }

    /**
     * Grant History grouped data by Monthly grant_date : used to display in bar chart
     *
     * @param $fundId
     * @param array $params
     * @return mixed
     */
    public function apiGrantHistoryGroupedByMonthlyGrantDate($fundId, $params=[])
    {
        $limit = self::PAGE_SIZE;

        $query = GrantHistory::filterQuery($fundId, $params);

        // $query = GrantHistory::where(['fund_id' => $fundId]);
        // $query = GrantHistory::where('fund_id', 'ilike', $fundId);
        // if (isset($params['startDate']) && isset($params['endDate'])) {
        //    $from = date($params['startDate']);
        //    $to = date($params['endDate']);
        //    $query->whereBetween('grant_date', [$from, $to])->get();
        //}
        //if (isset($params['organization_id'])) {
        //     if ($organizationId = $params['organization_id']) {
        //          $query->where(['organization_id' => $organizationId]);
        //     }
        //}

        if (ClientInfo::isJCF()) {
            $models = $query->groupBy('year', 'quarter')->orderBy('year')->orderBy('quarter')
                ->selectRaw("sum(amount) as total_amount, extract(YEAR from grant_date) as year, extract(QUARTER from grant_date) as quarter")
                ->get();

            $data = [];
            foreach ($models as $model) {
                $a = [];
                $a['total_amount'] = $model['total_amount'];
                $a['year_month'] = 'Q' . $model['quarter'] . '-' . $model['year'];
                $data[] = $a;
            }
            return $data;
        } else {
            $grantHistoryData = $query->groupBy('grant_date')
                ->selectRaw("sum(amount) as total_amount, to_char(grant_date, 'MM-YYYY') as year_month")
                ->get();
            return $grantHistoryData;
        }
    }

    /**
     * Gift History grouped data by Monthly grant_date : used to display in bar chart
     *
     * @param $fundId
     * @param array $params
     * @return mixed
     */
    public function apiGiftHistoryGroupedByMonthlyGrantDate($fundId, $params=[])
    {
        $query = GiftHistory::filterQuery($fundId, $params);

        // $query = GrantHistory::where(['fund_id' => $fundId]);
        // $query = GiftHistory::where('fund_id', 'ilike', $fundId);
        // if (isset($params['startDate']) && isset($params['endDate'])) {
        //     $from = date($params['startDate']);
        //     $to = date($params['endDate']);
        //     $query->whereBetween('gift_date', [$from, $to])->get();
        // }

        $giftHistoryData = $query->groupBy('gift_date')
            ->selectRaw("sum(amount) as total_amount, to_char(gift_date, 'MM-YYYY') as year_month")
            ->get();

        $monthWiseData = [];
        foreach ($giftHistoryData as $item) {
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
        return $barChartData;
    }

    public function apiMyCart($fundId, $params=[])
    {
        $grantHistory = GrantItem::where('fund_id', 'ilike', $fundId)->orderBy('grant_date', 'DESC')->get();
        return $grantHistory;
    }

    public function MyCart() {

    }

    public function apiCharitableCatalog(Request $request)
    {
        $conditions['status'] = "Active";
        $organizations = Organization::where($conditions)->limit(20)->orderBy('name', 'ASC')->get();

        $data = [];
        /** @var Organization $organization */
        foreach($organizations as $i => $organization) {

            $item = [];
            $item['name'] = $organization->name;
            $item['organization_id'] = $organization->organization_id;
            $item['address'] = $organization->getAddressFromCity();
            $summary = GnUtils::textTruncate(strip_tags($organization->mission), 200);

            $route = route('organization', ['id' => $organization->organization_id]);
            $item['mission'] = $summary . " <a href='" . $route . "'>Read more</a>";
            // $item['image'] = $organization->img_url;
            $data[] = $item;
        }
        return $data;
    }

    public function apiProjectMatches(Request $request)
    {
        $projects = Project::where([])->limit(20)->get();

        $data = [];
        /** @var Project $project */
        foreach($projects as $project) {
            /** @var Organization $organization */
            $organization = $project->organization();
            $item = [];
            $item['organization'] = $organization->name;
            $item['organization_id'] = $organization->organization_id;
            $item['project_id'] = $project->org_need_app_id;
            $item['title'] = $project->title;
            $summary = GnUtils::textTruncate(strip_tags($project->summary), 200);

            $route = route('project', ['id' => $project->org_need_app_id]);
            $item['summary'] = $summary . " <a href='" . $route . "'>Read more</a>";
            $item['image'] = $project->img_url;
            $data[] = $item;
        }

        return $data;

        /*
        $project = Project::where(['org_need_app_id' => 159])->first();
        $organization = $project->organization();
        return ['data' => $project, 'org' => $organization];

        $orgId = $params['org_id'];
        $appId = $params['org_need_app_id'];

        $organization = Organization::find($orgId);
        $project = Project::find($appId);

        $models = [];
        return ['projects' => [
            'organization' => $organization,
            'project' => $project
        ]];
        */
    }

    public function apiOrganizationMatches(Request $request)
    {
        $conditions['status'] = "Active";
        $organizations = Organization::where($conditions)->limit(20)->get();

        $data = [];
        /** @var Organization $organization */
        foreach($organizations as $i => $organization) {

            $item = [];
            $item['name'] = $organization->name;
            $item['organization_id'] = $organization->organization_id;
            $item['address'] = $organization->getAddressFromCity();
            $summary = GnUtils::textTruncate(strip_tags($organization->mission), 200);

            $route = route('organization', ['id' => $organization->organization_id]);
            $item['mission'] = $summary . " <a href='" . $route . "'>Read more</a>";
            // $item['image'] = $organization->img_url;
            $data[] = $item;
        }
        return $data;
    }

}