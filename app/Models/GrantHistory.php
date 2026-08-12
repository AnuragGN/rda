<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrantHistory extends Model
{
    /* @var string */
    protected $table = 'fund_grant_history';

    /* @var string */
    protected $primaryKey = 'fund_grant_history_id';

    /* @var boolean */
    public $timestamps = false;

    /**
     * @param $id
     * @return mixed
     */
    static public function getById($id) {
        return self::where(['fund_grant_history_id' => $id])->first();
    }

    static public function getRecentGrantRecommendations($count = 10)
    {
        $contactId = Contact::sessionContactId();
        $fundsIds = ContactFund::getViewableFundIdsByContactId($contactId);
        return self::whereIn('fund_id', $fundsIds)->orderBy('payment_date', 'desc')->take($count)->get();
    }

    public function orgExists()
    {
        return Organization::where('organization_id', $this->organization_id)->exists();
    }

    /**
     * @param $fundId
     * @param array $params
     * @return mixed
     */
    static public function filterQuery($fundId, $params=[])
    {
        $query = GrantHistory::query();

        // check if we got any values from the drop down
        if (isset($params['fund_id']) && !empty($params['fund_id'])) {
            if ($params['fund_id'] == 'all') {
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
            } else {
                $query = $query->where('fund_id', 'ilike', $params['fund_id']);
            }
        } else {
            $query = $query->where('fund_id', 'ilike', $fundId);
        }
        if (isset($params['interest_area']) && !empty(($params['interest_area'])) && $params['interest_area'] != 'all') {
            $query->join('org_interest_area', 'org_interest_area.organization_id', '=', 'fund_grant_history.organization_id')
                ->where(['interest_area_id' => $params['interest_area']]);
        }
        if (isset($params['status']) && !empty($params['status']) && $params['status'] != 'all') {
            $query = $query->where('status', $params['status']);
        }
        if (isset($params['amount_min']) && !empty($params['amount_min'])) {
            $query = $query->where('amount', '>=', $params['amount_min']);
        }
        if (isset($params['amount_max']) && !empty($params['amount_max'])) {
            $query = $query->where('amount', '<=', $params['amount_max']);
        }
        if (isset($params['startDate']) && isset($params['endDate'])) {
            $from = date($params['startDate']);
            $to = date($params['endDate']);
            $query->whereBetween('grant_date', [$from, $to])->get();
        }
        if (isset($params['organization_id']) && !empty($params['organization_id'])) {
            $query->where(['organization_id' => $params['organization_id']]);
        }
        return $query;
    }

    /**
     * @return mixed
     */
    static public function getInterestAreasSelectable()
    {
        $params = ['fund_id' => 'all'];
        $query = GrantHistory::filterQuery('all', $params);

        // org id should not be null
        $query->whereNotNull('fund_grant_history.organization_id');

        // get org interest areas
        $query->join('org_interest_area', 'org_interest_area.organization_id', '=', 'fund_grant_history.organization_id');

        // get interest areas
        $query->join('interest_area', 'interest_area.interest_area_id', '=', 'org_interest_area.interest_area_id')
            ->where('interest_area.parent_interest_area_id', 100);

        $query->select('interest_area.interest_area', 'interest_area.interest_area_id')
            ->groupBy('interest_area.interest_area_id');

        $items = $query->get();
        $selectable = ['all' => "All"];
        foreach($items as $item) {
            $selectable[$item['interest_area_id']] = $item['interest_area'];
        }
        return $selectable;
    }

}
