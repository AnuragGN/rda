<?php
/**
 * Created by PhpStorm.
 * User: esquv
 * Date: 2/24/2023
 * Time: 1:11 PM
 */

namespace App\Helpers;
use Illuminate\Http\Request;

class ReportManager
{

    /**
     * @return array
     */
    static public function getReportTypeList()
    {
        return ReportData::getReportTypeList();
    }

    /**
     * @return array
     */
    static public function getReportDurationOptions()
    {
        return ReportData::getReportDurationOptions();
    }

    /**
     * @param $type
     * @return null
     */
    static public function getOutputColumnsByReportType($type)
    {
        $outputColumns = ReportData::getReportOutputColumns();
        return $outputColumns[$type] ?? null;
    }

    /**
     * @param $type
     * @return null
     */
    static public function getSearchCriteriaByReportType($type)
    {
        $searchCriteria = ReportData::getReportSearchCriteria();
        return $searchCriteria[$type] ?? null;
    }

    /**
     * @param $type
     * @return null
     */
    static public function getSortingOrdersByReportType($type)
    {
        $sortingOrders = ReportData::getReportSortingOrders();
        return $sortingOrders[$type] ?? null;
    }

    /**
     * @param $type
     * @return array|null
     */
    static public function getReportFilterCriteriaByType($type)
    {
        $filterCriteria = ReportData::getReportTypeList();
        if (array_key_exists($type, $filterCriteria)) {

            return [
                'search_criteria' => self::getSearchCriteriaByReportType($type),
                'sorting_order' => self::getSortingOrdersByReportType($type),
                'output_columns' => self::getOutputColumnsByReportType($type)
            ];
        }
        return null;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    static public function getReportConfigParams(Request $request)
    {

        $filterCriteria = self::getReportFilterCriteriaByType($request->report_type);

        if (!$filterCriteria) {
            return null;
        }
        $SearchCriteriaList = $filterCriteria['search_criteria'];
        $sortingOrderList = $filterCriteria['sorting_order'];
        $outputColumnsList = $filterCriteria['output_columns'];

        $searchCriteria = [];
        foreach($SearchCriteriaList as $key => $val)
        {
            $searchCriteria[$key] = $request->$key ? $request->$key : false;
        }

        if ($request->date_range == 'calendar') {
            $searchCriteria['start_date'] = $request->start_date;
            $searchCriteria['end_date'] = $request->end_date;
        } else {
            $searchCriteria['duration'] = $request->duration;
        }

        if ($request->updated_date_range == 'calendar') {
            $searchCriteria['updated_start_date'] = $request->updated_start_date;
            $searchCriteria['updated_end_date'] = $request->updated_end_date;
        } else {
            $searchCriteria['updated_duration'] = $request->updated_duration;
        }

        $sortingOrders = [];
        foreach ($sortingOrderList as $key => $val) {
            $sortingOrders[$key] = $request->$key ? $request->$key : false;
        }

        $outputColumns = [];
        foreach ($outputColumnsList as $key => $name) {
            $outputColumns[$key] = in_array($key, $request->output_columns);
        }

        $filterData = [];
        $filterData['report_type'] = $request->report_type;
        $filterData['search_criteria'] = $searchCriteria;
        $filterData['sorting_order'] = $sortingOrders;
        $filterData['output_columns'] = $outputColumns;

        return $filterData;
    }

}