<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GiftHistory extends Model
{
    /* @var string */
    protected $table = 'fund_gift_history';

    /* @var string */
    protected $primaryKey = 'fund_history_id';

    /* @var boolean */
    public $timestamps = false;

    static public function filterQuery($fundId, $params = [])
    {
        $query = GiftHistory::query();

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

        if (isset($params['startDate']) && isset($params['endDate'])) {
            $from = date($params['startDate']);
            $to = date($params['endDate']);
            $query->whereBetween('gift_date', [$from, $to]);
        }

        return $query;
    }

    static public function getOverAllGiftFundList($limit,$startDate,$endDate,$fund_id)
    {
        $contactId = Contact::sessionContactId();
        $fundArr = ContactFund::getFundIdsByContactId($contactId);

        $results = GiftHistory::whereBetween('gift_date', [$startDate, $endDate])
            ->groupBy('fund_id')
            ->select('fund_id', DB::raw('SUM(amount) as total_fund_grant'))
            ->orderByDesc('total_fund_grant');

        if ($fund_id != '') {
            $results->where('fund_id', $fund_id);
        } else {
            $results->whereIn('fund_id', $fundArr);
        }
        if($limit > 0) {

            return $results->paginate($limit);
        } else {

            return $results->get();
        }
    }

    static public function getOverAllGiftDonorListFundWise($fund_id,$startDate,$endDate)
    {
        $contactId = Contact::sessionContactId();

        $condition = [
            'fund_id' => $fund_id
        ];

        $results = GiftHistory::where($condition)
            ->whereBetween('gift_date', [$startDate, $endDate])
            ->groupBy('donor')
            ->select('donor', DB::raw('SUM(amount) as total_donor_grant'))
            ->orderByDesc('total_donor_grant')
            ->get();

        return $results;
    }

    static public function getOverAllGiftDonorList($startDate,$endDate)
    {
        $contactId = Contact::sessionContactId();
        $fundArr = ContactFund::getFundIdsByContactId($contactId);
        
        $results = GiftHistory::whereIn('fund_id', $fundArr)
            ->whereBetween('gift_date', [$startDate, $endDate])
            ->groupBy('donor')
            ->select('donor', DB::raw('SUM(amount) as total_donor_grant'))
            ->orderByDesc('total_donor_grant')
            ->get();

        return $results;
    }
}