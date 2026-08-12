<?php

namespace App\Http\Controllers\Agency;

use App\Helpers\ReportData;
use App\Helpers\ReportManager;
use App\Models\ConfigReport;
use App\Models\ContactType;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Helpers\GnUtils;
use App\Models\Fund;
use App\Models\GiftHistory;
use App\Models\GrantHistory;
use App\Models\Ticket;
//use App\Forms\FormReportFilter;
//use PDF;

class ReportsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request) {
        GnUtils::addBreadcrumb('Reports');

        $reportTypeList = ReportManager::getReportTypeList();
        $configReports = ConfigReport::all();

        return view('agency.agency-advisor.reports.index', compact('reportTypeList', 'configReports'));
    }

    /**
     * @param Request $request
     * @param $type
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getReportFilterForm(Request $request, $type)
    {
        $reports = ReportManager::getReportTypeList();
        if (!array_key_exists($type, $reports)) {

            return redirect()->back()->with('error', 'Invalid report');
        }
        GnUtils::addBreadcrumb('Reports', route('report-home'));
        GnUtils::addBreadcrumb('Filter');

        return view('agency.agency-advisor.reports.filter-form', compact('type'));
    }

    /**
     * @param Request $request
     * @param $type
     * @param $id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getReportConfig(Request $request, $type, $id)
    {
        GnUtils::addBreadcrumb('Reports', route('report-home'));
        GnUtils::addBreadcrumb($type, route('report-filter', ['type' => $type]));

        $configReport = ConfigReport::getConfigReportById($type, $id);
        // dd($configReport);

        if(!$configReport) return redirect()->back()->with('error', 'Invalid report');
        GnUtils::addBreadcrumb($configReport->filter_name);

        // $fundNames = Fund::pluck('name', 'id');
        // dd($fundNames);

        return view('agency.agency-advisor.reports.filter-form', compact('type', 'configReport'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewFilteredReports(Request $request) {
        $typeList = \App\Helpers\ReportData::getReportTypeList();

        GnUtils::addBreadcrumb('Reports', route('report-home'));
        GnUtils::addBreadcrumb($typeList[$request->report_type], route('report-filter', ['type' => $request->report_type]));
        GnUtils::addBreadcrumb('View');

        $reportConfigData = ReportManager::getReportConfigParams($request);
        $models = self::generateReportData($reportConfigData);

        if ($request->save_view_report) {
            ConfigReport::saveFilter($request);
        }

        return view('agency.agency-advisor.reports.view-report', compact('reportConfigData', 'models'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsv(Request $request)
    {
        $reportConfigData = json_decode($request->reportConfigData, true);
        $models = self::generateReportData($reportConfigData);
        //$models = $models->toArray();

        $reportColumns = ReportManager::getOutputColumnsByReportType($reportConfigData['report_type']);
        $selectedOutputColumns = $reportConfigData['output_columns'];

        $filename = $reportConfigData['report_type'].'-report.csv';
        //$filename = $reportConfigData['report_type'] . '-' . date('Y-m-d') . '-report.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        $output = fopen('php://temp', 'r+');

        // Write header row with selected columns
        $headerRow = [];
        foreach ($selectedOutputColumns as $columnKey => $columnValue) {
            if ($columnValue && array_key_exists($columnKey, $reportColumns)) {
                $headerRow[] = $reportColumns[$columnKey];
            }
        }
        fputcsv($output, $headerRow);

        // Write data rows with selected column values
        foreach ($models as $model) {
            $rowData = [];

            foreach ($selectedOutputColumns as $columnKey => $columnValue) {
                if ($columnValue && array_key_exists($columnKey, $reportColumns)) {
                    switch ($columnKey) {
                        case 'type':
                            $types = '';
                            foreach ($model->types as $t) {
                                $types .= ContactType::getTypeName($t['contact_type_id']) . ', ';
                            }
                            $rowData[] = rtrim($types, ', ');
                            break;
                        case 'email':
                            $rowData[] = '';
                            if ($model->email && !empty($model->email['email_address'])) {
                                $rowData[] = $model->email['email_address'];
                            }
                            break;
                        case 'phone':
                            $phone_numbers = '';

                            $items = $model->phones();
                            if (!empty($items)) {
                                foreach ($items as $p) {
                                    $phone_numbers .= $p['phone_number'] . ', ';
                                }
                                $rowData[] = rtrim($phone_numbers, ', ');
                            } else {
                                $rowData[] = '';
                            }

                            break;
                        default:
                            $value = $model[$columnKey] ?? '';

                            if (in_array($columnKey, ['last_updated', 'created_on'])) {
                                $value = GnUtils::customDate($value);
                            }
                            $rowData[] = $value;
                            break;
                    }
                }
            }
            fputcsv($output, $rowData);
        }

        rewind($output);
        $csvData = stream_get_contents($output);
        fclose($output);

        return response()->streamDownload(function () use ($csvData) {
            echo $csvData;
        }, $filename, $headers);
    }


    /**
     * @param $reportConfigData
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function generateReportData($reportConfigData)
    {
        if ($reportConfigData['report_type'] == ReportData::REPORT_TYPE_FUND_GIFT_HISTORY) {

            $reportData = self::generateFundGiftHistoryReportData($reportConfigData);

        } elseif ($reportConfigData['report_type'] == ReportData::REPORT_TYPE_FUND_GRANT_HISTORY) {

            $reportData = self::generateFundGrantHistoryReportData($reportConfigData);

        } elseif ($reportConfigData['report_type'] == ReportData::REPORT_TYPE_SERVICE_TICKET) {

            $reportData = self::generateServiceTicketReportData($reportConfigData);

        } elseif ($reportConfigData['report_type'] == ReportData::REPORT_TYPE_CLIENT) {

            $reportData = self::generateClientReportData($reportConfigData);

        }

        return $reportData;
    }


    /**
     * @param $configDetails
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function generateFundGiftHistoryReportData($configDetails)  //cretae function based on that..
    {
        $searchCriteria = $configDetails['search_criteria'];
        $sortingOrder = $configDetails['sorting_order'];
        $outputColumns = $configDetails['output_columns'];

        // dd($configDetails, $searchCriteria, $sortingOrder, $outputColumns);

        $query = GiftHistory::query();

        // Add name search filter
        // $name = $searchCriteria['fund_id'];
        // if ($name) {
        //     $query->whereHas('fund', function ($q) use ($name) {
        //         $q->where('name', 'ilike', '%' . $name . '%');
        //     });
        // }
        // Add Dropdwon fundname filter
        $fundId = $searchCriteria['fund_id'];
        if ($fundId) {
            $query->where('fund_id', '=', $fundId);
        }

        // Apply date range filter
        $dateRangeField = $searchCriteria['date_range'] == 'calendar' ? 'date_entered' : null;
        if ($dateRangeField) {
            //$query->whereBetween($dateRangeField, [$searchCriteria['start_date'], $searchCriteria['end_date']]);

            $query->whereDate($dateRangeField, '>=', $searchCriteria['start_date'])
            ->whereDate($dateRangeField, '<=', $searchCriteria['end_date']);

        } else if ($searchCriteria['duration']) {
            $query->where('date_entered', '>=', $this->getDateFromDuration($searchCriteria['duration']));
        }

        // Apply sorting order
        foreach ($sortingOrder as $column => $direction) {
            if ($direction === 'asc' || $direction === 'desc') {
                $query->orderBy($column, $direction);
            }
        }

        $columns = [];
        foreach ($outputColumns as $column => $selected) {
            if ($selected) {
                $columns[] = $column;
            }
        }
        
        $query->select($columns);

        // Get the report data
        $data = $query->get();

        return $data;
        
    }

    /**
     * @param $duration
     * @return array|static
     */
    private function getDateFromDuration($duration)
    {
        switch ($duration) {
            case 'last_one_day':
                return now()->subDay()->startOfDay();
            case 'last_one_week':
                return now()->subWeek()->startOfDay();
            case 'last_one_month':
                return now()->subMonth()->startOfDay();
            case 'last_one_year':
                return now()->subYear()->startOfDay();
            case 'this_calendar_year':
                return [now()->startOfYear(), now()->endOfYear()];
            default:
                return now()->subMonth()->startOfDay();
        }
    }

    /**
     * @param $configDetails
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function generateFundGrantHistoryReportData($configDetails)
    {
        $searchCriteria = $configDetails['search_criteria'];
        $sortingOrder = $configDetails['sorting_order'];
        $outputColumns = $configDetails['output_columns'];

        $query = GrantHistory::query();

        // Add Dropdwon fundname filter
        $fundId = $searchCriteria['fund_id'];
        if ($fundId) {
            $query->where('fund_id', '=', $fundId);
        }
        
       // Apply date range filter
       $dateRangeField = $searchCriteria['date_range'] == 'calendar' ? 'date_entered' : null;
       if ($dateRangeField) {
           //$query->whereBetween($dateRangeField, [$searchCriteria['start_date'], $searchCriteria['end_date']]);
           $query->whereDate($dateRangeField, '>=', $searchCriteria['start_date'])
            ->whereDate($dateRangeField, '<=', $searchCriteria['end_date']);

       } else if ($searchCriteria['duration']) {
           $query->where('date_entered', '>=', $this->getDateFromDuration($searchCriteria['duration']));
       }

        // Apply sorting order
        foreach ($sortingOrder as $column => $direction) {
            if ($direction === 'asc' || $direction === 'desc') {
                $query->orderBy($column, $direction);
            }
        }

        // Select output columns
        $columns = [];
        foreach ($outputColumns as $column => $selected) {
            if ($selected) {
                $columns[] = $column;
            }
        }
        $query->select($columns);

        // Get the report data
        $data = $query->get();

        return $data;
    }

    /**
     * @param $configDetails
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function generateServiceTicketReportData($configDetails)
    {
        $searchCriteria = $configDetails['search_criteria'];
        $sortingOrder = $configDetails['sorting_order'];
        $outputColumns = $configDetails['output_columns'];

        // dd($configDetails, $searchCriteria, $sortingOrder, $outputColumns);

        $query = Ticket::query();

        // Apply Dropdwon fundname filter
        $fundId = $searchCriteria['fund_id'];
        if ($fundId) {
            $query->where('target_id', '=', $fundId);
        }

        //Apply status search filter
        // $status = $searchCriteria['search_status'];
        // if ($status) {
        //     $query->where(function ($q) use ($name) {
        //         $q->where('status', 'ilike', '%' . $name . '%');
        //     });
        // }

       // Apply date range filter
       $dateRangeField = $searchCriteria['date_range'] == 'calendar' ? 'created_at' : null;
       if ($dateRangeField) {
           //$query->whereBetween($dateRangeField, [$searchCriteria['start_date'], $searchCriteria['end_date']]);
            $query->whereDate($dateRangeField, '>=', $searchCriteria['start_date'])
            ->whereDate($dateRangeField, '<=', $searchCriteria['end_date']);

       } else if ($searchCriteria['duration']) {
           $query->where('created_at', '>=', $this->getDateFromDuration($searchCriteria['duration']));
       }

        // Apply sorting order
        foreach ($sortingOrder as $column => $direction) {
            if ($direction === 'asc' || $direction === 'desc') {
                $query->orderBy($column, $direction);
            }
        }

        // Select output columns
        $columns = [];
        foreach ($outputColumns as $column => $selected) {
            if ($selected) {
                $columns[] = $column;
            }
        }
        $query->select($columns);

        // Get the report data
        $data = $query->get();
        // dd($data);
        return $data;
    }

    /**
     * @param $configDetails
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function generateClientReportData($configDetails)
    {
        $searchCriteria = $configDetails['search_criteria'];
        $sortingOrder = $configDetails['sorting_order'];
        $outputColumns = $configDetails['output_columns'];

        // dd($configDetails, $searchCriteria, $sortingOrder, $outputColumns);

        $query = Contact::query();


        // Apply Dropdown fundname filter
        $fundId = $searchCriteria['fund_id'];
        if ($fundId) {
            // Join the contact_fund table and filter contacts based on the fund_id
            $query->join('contact_fund', 'contact.contact_id', '=', 'contact_fund.contact_id')
            ->where('contact_fund.fund_id', $fundId);
        }

        // //Apply contact type filter
        // $type = $searchCriteria['contact_type'];
        // if ($type) {
        //     $query->whereHas('types', function ($q) use ($type) {
        //         return $q->where('contact_type_id', $type);
        //     });
        // }

        // Apply sorting order
        foreach ($sortingOrder as $column => $direction) {
            if ($direction === 'asc' || $direction === 'desc') {
                $query->orderBy($column, $direction);
            }
        }

         // removing relational columns before selecting output columns
         unset($outputColumns['phone']);
         unset($outputColumns['email']);
 
         // contact id is required for relations => contact email, phone
         $outputColumns['contact_id'] = true;

        // Select output columns
        $columns = [];
      
        foreach ($outputColumns as $column => $selected) {
            if ($selected) {
                $columns[] = 'contact.' . $column;
            }
        }
        $query->select($columns);

        // Get the report data
        $data = $query->get();
        // dd($data);
        return $data;
    }


}