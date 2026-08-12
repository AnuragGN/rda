<?php
/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 29-08-2023
 * Time: 15:07
 */

namespace App\Http\Controllers\Agency;

use App\Forms\FormFundHistoryFilter;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\ContactFund;
use App\Models\FundRecommendation;
use App\Models\GiftHistory;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use Auth;
use App\Models\Api;
use App\Models\Task;
use App\Models\Fund;
use Illuminate\Http\Request;
use League\Csv\Writer;
use PDF;
use Carbon\Carbon;
// Funds = 'JCFEX', 'Abra';
/**
 * Class FundController
 * @package App\Http\Controllers
 */
class AgencyAdvisorServiceController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()   
    {
        return view('agency.agency-advisor.services'); 
    }

    public function createTask()   
    {
        $contact_id = Contact::sessionContactId();
        $funds = Fund::getSelectableForGrantRecommendation();
        return view('agency.agency-advisor.create_task', [
            'contact_id' => $contact_id,
            'contactFunds' => $funds,
        ]);
    }

    public function store(Request $request)
    {
        $contact_id = Contact::sessionContactId();
        // Validate the incoming request data
        $validatedData = $request->validate([
            'fund_id' => 'required', // Change 'fund_id' to match your form field name
            'task_type_id' => 'required', // Change 'task_type_id' to match your form field name
            'subject' => 'required',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            // 'is_send_mail' => 'boolean', // Assuming 'is_send_mail' is a checkbox
        ]);

        // Create a new task instance with the validated data
        $task = new Task([
            'contact_id' => $contact_id,
            'fund_id' => $validatedData['fund_id'],
            'task_type' => $validatedData['task_type_id'],
            'subject' => $validatedData['subject'],
            'description' => $validatedData['description'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'status' => 'Pending',
            // 'is_send_mail' => $validatedData['is_send_mail'] ? 1 : 0, // Convert boolean to integer
            // Add other fields as needed
        ]);

        // Save the task to the database
        $task->save();
        $tasks = Task::all();

        // Optionally, you can return a response or redirect to a success page
        return view('agency.agency-advisor.services', compact('tasks'))->with('success', 'Task created successfully');
    }

    public function filter(Request $request)
    {
        $taskType = $request->input('task_type');
        $contact_id = Contact::sessionContactId();

        // Query the tasks based on the selected task type
        $tasks = Task::when($taskType, function ($query) use ($taskType) {
            return $query->where('task_type', $taskType);
        })->get();

        return view('agency.agency-advisor.services', compact('tasks'));
    }

    public function tasklist(Request $request)
    {
        $data       = $request->all();
        $task_type  = $data['seach_type'];
        $contact_id = Contact::sessionContactId();
        $count = 0;
        if($task_type)
        {
            $dataset = Task::select('task_id','fund_id','contact_id','task_type','subject','description','start_date','end_date','reminds_on','status')
            ->where('task_type', $task_type)
            ->where('contact_id', $contact_id)
            ->orderBy("end_date", "asc")
            ->get();
        }
        else
        {
            $dataset = Task::select('task_id','fund_id','contact_id','task_type','subject','description','start_date','end_date','reminds_on','status')
           ->where('contact_id', $contact_id)
           ->orderBy("end_date", "asc")
           ->get(); 
        }

        $html = '<tr>
            <th style="text-align: left !important;">Task</th>
            <th style="text-align: left !important;">Fund Name</th>
            <th style="text-align: left !important;">Subject</th>
            <th style="text-align: left !important;">Task End On</th>
            <th style="text-align: left !important;">Status</th>
            <th style="text-align: left !important;">Action</th>
        </tr>';

        foreach ($dataset as $property => $value) 
        {
            $fund_id     = $value->fund_id; 
            $end_date    = date('m-d-Y', strtotime($value->end_date));
            $count = 1;

            if($fund_id)
            {
                $fund = Fund::getFundById($fund_id);
                $fund_name = $fund->name;
            }
            else
            {
                $fund_name = 'NA';
            }
            
            $dt             = Carbon::now();
            $current_date   = $dt->format('Y-m-d');
            $color          = '';

            if($value->status == 'Pending')
            {
                if($value->end_date < $current_date)
                {
                    $value->status = 'Over Draft';
                    $color         = 'red';
                }
            }
            
            $html .= '<tr>
                <td style="text-align: left !important;">'. $value->task_type.'</td>
                <td style="text-align: left !important;">'.$fund_name.'</td>
                <td style="text-align: left !important;">'. $value->subject.'</td>
                <td style="text-align: left !important;color:'.$color.'">'. $end_date.'</td>
                <td style="text-align: left !important;color:'.$color.'">'.$value->status.'</td>
                <td style="text-align: left !important;"><a style="color:#fff;" class="btn btn-accent btn-sm" onclick="getTaskDetail('.$value->task_id.');">View</a>&nbsp;<a style="color:#fff;" class="btn btn-danger btn-sm" onclick="deleteTask('.$value->task_id.');">Delete</a></td>
            </tr>';
        }
        if(!$count)
        {
            $html .= '<tr>
                <td colspan=6 style="text-align: center !important;">No data available.</td>
            </tr>';
        }
        echo $html;
    }

    public function taskDelete(Request $request)
    {
        $data       = $request->all();
        $taskId  = $data['taskId'];
        Task::where('task_id',$taskId)->delete();
    }  

    public function taskDetail(Request $request)
    {
        $data       = $request->all();
        $taskId  = $data['taskId'];
        $task_data = Task::where('task_id', $taskId)->first();

        $fund_id = $task_data->fund_id;
        if($fund_id)
        {
            $fund = Fund::getFundById($fund_id);
            $fund_name = $fund->name;
        }
        else
        {
            $fund_name = 'NA';
        }
        $start_date    = date('m-d-Y', strtotime($task_data->start_date));
        $end_date      = date('m-d-Y', strtotime($task_data->end_date));
        $created_at    = date('m-d-Y H:i:s', strtotime($task_data->created_at));

        $dt           = Carbon::now();
        $current_date = $dt->format('Y-m-d');
        $color        = '';

        if($task_data->status == 'Pending')
        {
            if($task_data->end_date < $current_date)
            {
                $task_data->status = 'Over Draft';
                $color             = 'red';
            }
        }

        $html = '<div class="mb-2" style="display: flex; justify-content: space-between;">
                <span>
                    <div><label>Fund Name  : </label> '.$fund_name.'</div>
                    <div><label>Task Type  : </label> '.$task_data->task_type.'</div>
                    <div><label>Subject    : </label> '.$task_data->subject.'</div>
                    <div><label>Description: </label> '.$task_data->description.'</div>
                    <div><label>Start Date : </label> '.$start_date.'</div>
                    <div><label>End Date   : </label> '.$end_date.'</div>
                    <div><label>Created On : </label> '.$created_at.'</div>
                    <div><label>Status     : </label><span style="color:'.$color.';"> '.$task_data->status.'<span></div>
                </span>
            </div><input type="hidden" id="hyd_task_id" value="'.$task_data->task_id.'"><input type="hidden" id="hyd_status_id" value="'.$task_data->status.'">';
        echo $html;
    }

    public function taskUpdate(Request $request)
    {
        $data       = $request->all();
        $taskId  = $data['taskId'];
        Task::where('task_id',$taskId)->update(['status'=>'Close']);
    } 
}
