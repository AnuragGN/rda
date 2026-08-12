<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investments extends Model
{
    use HasFactory;

    const STIFEL_POOL_ID = "SDPF";
    const STIFEL_DONOR_EMAIL = "dpellface@aol.com";
    const ACTION_REQUEST = 'requested';
    const ACTION_APPROVED = 'approved';
    const ACTION_REJECTED = 'rejected';

    const STATUS_ACTIVE = 'active';
    const STATUS_HISTORY = 'history';

    /* @var string */
    protected $table = 'fund_pool_allocation';


    /**
     * @return string
     */
    static public function poolTitle()
    {
        if (ClientInfo::isHGA()) {
            return "Fund Name";
        } else {
            return "Pool Name";
        }
    }

    /**
     * @param $fundId
     * @return array
     */
    static public function getCurrentAllocation($fundId)
    {
        // make sure that the fund belongs to user
        ContactFund::assertViewable($fundId);

        return Investments::where([
            'fund_id' => $fundId,
            'status' => 'active'
        ])->get();
    }

    /**
     * @param $fundId
     * @return array
     */
    static public function getCurrentAllocationData($fundId)
    {
        $pools = FundPool::getAll();

        // make sure that the fund belongs to user
        ContactFund::assertViewable($fundId);

        $allocations = Investments::where([
            'fund_id' => $fundId,
            'status' => 'active'
        ])->get();

        $records = [];
        foreach($pools as $pool) {

            // For NIF Only
            if ($pool->pool_id == self::STIFEL_POOL_ID) {
                /** @var User $user */
                $user = User::getSessionUser();
                // only Stifel can see this pool
                if ($user && $user->getAccountEmailAddress() != self::STIFEL_DONOR_EMAIL) {
                    continue;
                }
            }
            $record = new FundPool();
            $record->pool_id = $pool->pool_id;
            $record->pool_name = $pool->pool_name;
            $record->pool_link = $pool->pool_link;

            // initialize with default values
            $record->allocation = 0;
            $record->requested_allocation = 0;
            $record->action = 'approved';

            foreach($allocations as $allocation) {
                if ($allocation->pool_id === $record->pool_id) {
                    // set current values
                    $record->allocation = $allocation->allocation;
                    $record->requested_allocation = $allocation->requested_allocation;
                    $record->action = $allocation->action;
                }
            }
            $records[] = $record;;
        }
        return $records;
    }

}
