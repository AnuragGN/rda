<?php

namespace App\Models;

use App\Helpers\ReportManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ConfigReport extends Model
{
    /* @var string */
    protected $table = 'config_report';

    /* @var string */
    protected $primaryKey = 'id';
    public $incrementing = true;

    /* @var boolean */
    public $timestamps = true;

    protected $fillable = [
        "filter_name",
        "report_type",
        "search_criteria",
        "sorting_order",
        "output_columns"
    ];

    static public function getConfigReportById($type, $id)
    {
        $configReport = ConfigReport::where(['id' => $id, 'report_type' => $type])->first();

        if ($configReport) {
            $configReport->output_columns = json_decode($configReport->output_columns);
            $configReport->sorting_order = json_decode($configReport->sorting_order);
            $configReport->search_criteria = json_decode($configReport->search_criteria);
        }

        return $configReport;
    }

    static public function saveFilter(Request $request)
    {

        $request->validate([
            'filter_name' => 'required' . ($request->id ? '' : '|unique:config_report'),
            'report_type' => 'required',
        ]);

        $filterData = ReportManager::getReportConfigParams($request);
        $cr = $request->id ? self::findOrFail($request->id) : new ConfigReport();

        $cr->fill([
            'filter_name' => $request->filter_name,
            'report_type' => $request->report_type,
            'search_criteria' => json_encode($filterData['search_criteria'], true),
            'sorting_order' => json_encode($filterData['sorting_order'], true),
            'output_columns' => json_encode($filterData['output_columns'], true),
        ]);
        $cr->save();
        return $cr->id;

    }

}