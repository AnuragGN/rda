<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GhPerformance extends Model
{
    /* @var string */
    protected $table = 'gh_performance';

    /* @var string */
    protected $primaryKey = null;

    /* @var boolean */
    public $timestamps = false;


    static public function getFundPerformanceBarChartData($accountId)
    {
        // process fund / total
        $index = 0;
        $barChartsData = [];
        $barChartsData[] = self::getFundPerformanceBarChartDataForSegment($index++, $accountId, 1, GhComposition::SEG_LEVEL_ONE);

        // process segments / pools
        $segments = GhSegment::where(['segment_level' => GhComposition::SEG_LEVEL_TWO])->get();
        foreach ($segments as $segment) {
            $data = self::getFundPerformanceBarChartDataForSegment($index, $accountId, $segment->segment_id, GhComposition::SEG_LEVEL_TWO);
            if ($data) {
                $index++;
                $barChartsData[] = $data;
            }
            // else $barChartsData[] = $segment->segment_id;
        }

        return $barChartsData;
    }

    static public function getFundPerformanceTableData($accountId)
    {
        // process fund / total
        $tableData = self::getFundPerformanceTableDataForSegment($accountId, 1, GhComposition::SEG_LEVEL_ONE);

        // process segments / pools
        $segments = GhSegment::where(['segment_level' => GhComposition::SEG_LEVEL_TWO])->get();
        foreach ($segments as $segment) {
            $data = self::getFundPerformanceTableDataForSegment($accountId, $segment->segment_id, GhComposition::SEG_LEVEL_TWO);
            if ($data) $tableData = array_merge($tableData, $data);
            // else $barChartsData[] = $segment->segment_id;
        }

        return $tableData;
    }

    static private function getFundPerformanceTableDataForSegment($accountId, $segmentId, $segmentLevel)
    {
        $data = [];

        /** @var GhPerformance $model */
        $model = self::where([
            'segment_id' => $segmentId,
            'segment_level' => $segmentLevel,
            'ror_basis' => GhComposition::ROR_BASIS_GROSS
        ])->where('account_id', 'ilike', $accountId)->first();

        if (!$model) return null;

        if ($segmentLevel == GhComposition::SEG_LEVEL_ONE) {
            // $labels = ['', '', 'Current Month', 'Current Quarter', 'Fiscal Yr to Date', 'Latest 1 Yr', 'From ' . $model->incept_date];
            $labels = array_merge(['', ''], self::getLabels(6));
            $data[] = $labels;
        }

        $data[] = array_merge(['gross', $model->segment_name], $model->getFundPerformanceData());

        $model = self::where([
            'segment_id' => $segmentId,
            'segment_level' => $segmentLevel,
            'ror_basis' => null
        ])->where('account_id', 'ilike', $accountId)->first();

        if (!$model) return $data;
        $data[] = array_merge(['index', $model->return_type], $model->getFundPerformanceData());

        return $data;
    }

    static private function getLabels($count)
    {
        $model = self::where(['agi' => 'AGI'])->first();

        return [
            $model->tp_1,
            $model->tp_2,
            $model->tp_3,
            $model->tp_4,
            $model->tp_5,
            $model->tp_6,
        ];
    }

    static private function getFundPerformanceBarChartDataForSegment($index, $accountId, $segmentId, $segmentLevel)
    {
        $baseColor = PerformanceData::getBaseColor();
        $colors = PerformanceData::getColors();

        $dataSets = [];
        $bcd = []; // barChartsData
        // $bcd['labels'] = ['Current Month', 'Current Quarter', 'Fiscal Yr to Date', 'Latest 1 Yr', 'From 12/31/19'];
        $labels = self::getLabels(6); //  ['Current Month', 'Current Quarter', 'Fiscal Yr to Date', 'Latest 1 Yr'];

        /** @var GhPerformance $model */
        $model = self::where([
            'segment_id' => $segmentId,
            'segment_level' => $segmentLevel,
            'ror_basis' => GhComposition::ROR_BASIS_GROSS
        ])->where('account_id', 'ilike', $accountId)->first();

        if (!$model) return null;

        $bcd['title'] = $model->segment_name;
        // $labels[] = 'From ' . $model->incept_date;

        $bcd['labels'] = $labels;

        $dataSets[] = [
            'backgroundColor' => $colors[$index%6],
            // 'label' => $model->return_type,
            'label' => $model->segment_name,
            'data' => $model->getFundPerformanceData()
        ];


        $model = self::where([
            'segment_id' => $segmentId,
            'segment_level' => $segmentLevel,
            'ror_basis' => null
        ])->where('account_id', 'ilike', $accountId)->first();

        if (!$model) {
            $bcd['datasets'] = $dataSets;
            return $bcd;
        }

        $dataSets[] = [
            'backgroundColor' => $baseColor,
            'label' => $model->return_type,
            'data' => $model->getFundPerformanceData()
        ];

        $bcd['datasets'] = $dataSets;
        return $bcd;
    }

    private function getFundPerformanceData()
    {
        return [
            PerformanceData::strToFloat($this->tp_1),
            PerformanceData::strToFloat($this->tp_2),
            PerformanceData::strToFloat($this->tp_3),
            PerformanceData::strToFloat($this->tp_4),
            PerformanceData::strToFloat($this->tp_5),
            PerformanceData::strToFloat($this->tp_6)
        ];
    }

    static public function getPoolComposition($accountId)
    {
        $segment2 = self::getPoolCompositionData($accountId, false);
        if (!$segment2) return [];
        $segment3 = self::getPoolCompositionData($accountId, true);
        if (!$segment3) return [$segment2];
        return [$segment2, $segment3];
    }

    /**
     * @param $model
     * @return array
     */
    static public function getPoolCompositionChildrenData($model)
    {
        $segmentIds = GhSegment::where([
            'segment_level' => $model->segment_level + 1,
            'parent_segment_id' => $model->segment_id
        ])->pluck('segment_id');

        if (!count($segmentIds)) return [];

        $models = self::where([
            'account_id' => $model->account_id,
            'segment_level' => $model->segment_level + 1,
            'ror_basis' => GhComposition::ROR_BASIS_GROSS
        ])->whereIn('segment_id', $segmentIds)->get();

        return $models;
    }

    static public function getPoolCompositionData($accountId, $children=false)
    {
        $tableData = [];
        $data = [];

        // 1. Total fund
        $segmentId = 1;
        $model = self::where([
            'segment_id' => $segmentId,
            'segment_level' => GhComposition::SEG_LEVEL_ONE,
            'ror_basis' => GhComposition::ROR_BASIS_GROSS
        ])->where(['account_id' => $accountId])->first();

        if (!$model) return null;
        $data['total'] = $model;

        $tableData[] = ['', 'Market Value', '% of total'];
        $tableData[] = [$model->segment_name, $model->market_value, $model->percentage_of_total];

        // 2. Fund Composition
        $segments = GhSegment::where(['segment_level' => GhComposition::SEG_LEVEL_TWO])->get();

        $chartLabels = [];
        $chartValues = [];
        foreach ($segments as $segment) {
            $model = self::where([
                'segment_id' => $segment->segment_id,
                'segment_level' => GhComposition::SEG_LEVEL_TWO,
                'ror_basis' => GhComposition::ROR_BASIS_GROSS
            ])->where(['account_id' => $accountId])->first();

            if ($model) {

                $childrenExist = false;

                if ($children) {
                    $records = self::getPoolCompositionChildrenData($model);
                    if (count($records)) {
                        $childrenExist = true;
                        foreach ($records as $record) {
                            $data['composition'][] = $record;
                            $chartLabels[] = $record->segment_name;
                            $chartValues[] = PerformanceData::strToFloat($record->market_value);
                            $tableData[] = [$record->segment_name, $record->market_value, $record->percentage_of_total];
                        }
                    }
                }

                if (!$childrenExist) {
                    $data['composition'][] = $model;
                    $chartLabels[] = $model->segment_name;
                    $chartValues[] = PerformanceData::strToFloat($model->market_value);
                    $tableData[] = [$model->segment_name, $model->market_value, $model->percentage_of_total];
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
        return $result;
        // return ['data' => $data, 'pieChartData' => $pieChartData, 'pieChartTable' => $tableData];
    }

    static public function getPoolCompositionLevelOneOnlyOBS($accountId)
    {
        $tableData = [];
        $data = [];

        // 1. Total fund
        $segmentId = 1;
        $model = self::where([
            'segment_id' => $segmentId,
            'segment_level' => GhComposition::SEG_LEVEL_ONE,
            'ror_basis' => GhComposition::ROR_BASIS_GROSS
        ])->where(['account_id' => $accountId])->first();

        if (!$model) return null;
        $data['total'] = $model;

        $tableData[] = ['', 'Market Value', '% of total'];
        $tableData[] = [$model->segment_name, $model->market_value, $model->percentage_of_total];

        // 2. Fund Composition
        $segments = GhSegment::where(['segment_level' => GhComposition::SEG_LEVEL_TWO])->get();

        $chartLabels = [];
        $chartValues = [];
        foreach ($segments as $segment) {
            $model = self::where([
                'segment_id' => $segment->segment_id,
                'segment_level' => GhComposition::SEG_LEVEL_TWO,
                'ror_basis' => GhComposition::ROR_BASIS_GROSS
            ])->where(['account_id' => $accountId])->first();

            if ($model) {
                $data['composition'][] = $model;
                $chartLabels[] = $model->segment_name;
                $chartValues[] = PerformanceData::strToFloat($model->market_value);
                $tableData[] = [$model->segment_name, $model->market_value, $model->percentage_of_total];
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

        return ['data' => $data, 'pieChartData' => $pieChartData, 'pieChartTable' => $tableData];
    }


    static public function isFundCompositionPerformanceAvailable($fundId)
    {
        $accountId = $fundId . "_comp";
        return GhPerformance::where('account_id', "ilike", $accountId)->exists();
    }

}
