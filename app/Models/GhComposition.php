<?php

namespace App\Models;

use App\Helpers\GnUtils;
use Illuminate\Database\Eloquent\Model;

class GhComposition extends Model
{
    const SEG_LEVEL_ONE = 1;
    const SEG_LEVEL_TWO = 2;
    const SEG_LEVEL_THREE = 3;
    const ROR_BASIS_GROSS = 'Gross';

    /* @var string */
    protected $table = 'gh_composition';

    /* @var string */
    protected $primaryKey = null;

    /* @var boolean */
    public $timestamps = false;

    static public function compositionExists($accountId)
    {
        if (ClientInfo::isGNA()) return true;

        // 1. Total fund
        $segmentId = 1;
        return self::where([
            'segment_id' => $segmentId,
            'segment_level' => self::SEG_LEVEL_ONE,
            'ror_basis' => self::ROR_BASIS_GROSS
        ])->where('account_id', 'ilike', $accountId)->exists();
    }

    /**
     * @param $accountId
     * @return array
     */
    static public function getFundComposition($accountId)
    {
        $segment2 = self::getFundCompositionData($accountId, false);
        if (!$segment2) return [];
        $segment3 = self::getFundCompositionData($accountId, true);
        if (!$segment3) return [$segment2];
        return [$segment2, $segment3];
    }

    /**
     * @param $model
     * @return array
     */
    static public function getFundCompositionChildrenData($model)
    {
        $segmentIds = GhSegment::where([
            'segment_level' => $model->segment_level + 1,
            'parent_segment_id' => $model->segment_id
        ])->pluck('segment_id');

        if (!count($segmentIds)) return [];

        $models = self::where([
            // 'account_id' => $model->account_id,
            'segment_level' => $model->segment_level + 1,
            'ror_basis' => GhComposition::ROR_BASIS_GROSS
        ])->where('account_id', 'ilike', $model->account_id)
        ->whereIn('segment_id', $segmentIds)->get();

        return $models;
    }

    /**
     * @param $accountId
     * @param bool|false $children
     * @return array|null
     */
    static public function getFundCompositionData($accountId, $children=false)
    {
        if (ClientInfo::isGNA()) {
            $accountId = 'Beth';
        }

        $tableData = [];
        $data = [];

        // 1. Total fund
        $segmentId = 1;
        $model = self::where([
            'segment_id' => $segmentId,
            'segment_level' => self::SEG_LEVEL_ONE,
            'ror_basis' => self::ROR_BASIS_GROSS
        ])->where('account_id', 'ilike', $accountId)->first();

        if (!$model) return null;
        $data['total'] = $model;

        $tableData[] = ['', 'Market Value', '% of total'];
        $tableData[] = [
            $model->segment_name,
            GnUtils::StrToMoney($model->market_value),
            $model->percentage_of_total
        ];

        $endDate = GnUtils::customDate($model->end_date);

        // 2. Fund Composition
        $segments = GhSegment::where(['segment_level' => self::SEG_LEVEL_TWO])->get();

        $chartLabels = [];
        $chartValues = [];
        foreach($segments as $segment) {
            $model = self::where([
                'segment_id' => $segment->segment_id,
                'segment_level' => self::SEG_LEVEL_TWO,
                'ror_basis' => self::ROR_BASIS_GROSS
            ])->where('account_id', 'ilike', $accountId)->first();

            if ($model) {
                $childrenExist = false;

                if ($children) {
                    $records = self::getFundCompositionChildrenData($model);
                    if (count($records)) {
                        $childrenExist = true;
                        foreach ($records as $record) {
                            $data['composition'][] = $record;
                            $chartLabels[] = $record->segment_name;
                            $chartValues[] = PerformanceData::strToFloat($record->market_value);
                            $tableData[] = [
                                $record->segment_name,
                                GnUtils::StrToMoney($record->market_value),
                                $record->percentage_of_total
                            ];
                        }
                    }
                }

                if (!$childrenExist) {
                    $data['composition'][] = $model;
                    $chartLabels[] = $model->segment_name;
                    $chartValues[] = PerformanceData::strToFloat($model->market_value);
                    $tableData[] = [
                        $model->segment_name,
                        GnUtils::StrToMoney($model->market_value),
                        $model->percentage_of_total
                    ];
                }
            }
        }
        $pieChartData = [
            'title' => "Total Pool Composition",
            'labels' => $chartLabels,
            'datasets' => [[
                'backgroundColor' => PerformanceData::getPieChartColors(),
                'data' => $chartValues
            ]]
        ];

        $result = ['pieChartData' => $pieChartData, 'pieChartTable' => $tableData];
        $result['title'] =  $children ? 'Details' : 'Summary';
        $result['end_date'] = $endDate;
        return $result;
        // return ['data' => $data, 'pieChartData' => $pieChartData, 'pieChartTable' => $tableData];
    }
}

?>