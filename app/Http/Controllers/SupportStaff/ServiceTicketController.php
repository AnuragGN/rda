<?php
/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 29-04-2024
 * Time: 15:07
 */

namespace App\Http\Controllers\SupportStaff;

use Illuminate\Http\JsonResponse; 
use App\Mail\EventTicketMail;
use Illuminate\Support\Facades\Mail;
use App\Forms\FormFundHistoryFilter;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\ContactFund;
use App\Models\EmailAddress;
use App\Models\FundRecommendation;
use App\Models\GiftHistory;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use Auth;
use App\Models\Api;
use App\Models\Fund;
use App\Models\Ticket;
use App\Models\TicketAssignee;
use App\Models\Comment;
use App\Models\TicketMedia;
use App\Models\ContactType;
use App\Helpers\GConst;
use Illuminate\Http\Request;
use League\Csv\Writer;
use PDF;
use Carbon\Carbon;

/**
 * Class ServiceTicketController
 * @package App\Http\Controllers
 */
class ServiceTicketController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    
    public function myTicket () {

        GnUtils::addBreadcrumb('Service Tickets');
        $contact = Contact::sessionContact();

        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');
        $workStatusDropdown = config('dropdown.support_staff_status');
        
        return view('support_staff.service-tickets.tickets', compact('contact', 'statusDropdown', 'priorityDropdown', 'categoryDropdown','workStatusDropdown'));
    }

    public function myTicketAjax (Request $request) {
        
        $ticket_search = $request->ticket_search;
        $status = $request->status;
        $priority = $request->priority;
        $category = $request->category;
        
        $limit = 10;
        $myTicket = Ticket::supportStaffTicket($limit, $ticket_search, $status, $priority, $category);
        $totalTicket = Ticket::myTicket($ticket_search, $status, $priority, $category);

        #print_r($myTicket);die;
        $html = '';
        if (count($myTicket) || $request->page == 1) {
            $html = view('support_staff.service-tickets.list', compact('myTicket'))->render();
        }
        return [
            'more' => ($totalTicket) > $limit  *  $request->page ? 1 : 0,
            'html' => $html,
            'totalTicket' => $totalTicket,
            'totalLimit' => $limit  *  $request->page
        ];
    }

    public function viewTicket($ticket_id) {

        try {

            $contactId = Contact::sessionContactId();

            GnUtils::addBreadcrumb('Service Tickets', route('support-staff-ticket'));
            GnUtils::addBreadcrumb('View Ticket');

            $statusDropdown = config('dropdown.status');  
            $priorityDropdown = config('dropdown.priority');
            $categoryDropdown = config('dropdown.category');
            $workStatusDropdown = config('dropdown.support_staff_status');

            $ticket = Ticket::findOrFail($ticket_id);
            $contactId = Contact::sessionContactId();
            $primaryAssign = Contact::find($ticket->assigned_to);
            $createdBy = Contact::find($ticket->created_by);

            $fund_name = '';
            $fund_id = '';
            if($ticket->target_type == 'fund'){

                $fund_id = $ticket->target_id;
                $fund_name = Fund::getFundById($fund_id)->name;
            }
            if($ticket->target_type == 'cart recommendation' || $ticket->target_type == 'grant recommendation'){

                $recommendation_id = $ticket->target_id;
                $recomm = FundRecommendation::getRecommendationById($recommendation_id);
                $fund_id = $recomm->fund_id;
                $fund_name = Fund::getFundById($fund_id)->name;
            }

            $secAssign = TicketAssignee::where('ticket_id', $ticket_id)
            ->where('assigned_id', $contactId)
            ->where('active', 1) 
            ->first();

            if(empty($secAssign)){

                return redirect('m/support-staff/ticket');
            }

            return view('support_staff.service-tickets.view', ['ticket' => $ticket, 'contact_id' => $contactId, 'primaryAssign' => $primaryAssign, 'created_by' => $createdBy,'statusDropdown'=>$statusDropdown,'categoryDropdown'=>$categoryDropdown,'priorityDropdown'=>$priorityDropdown,"workStatusDropdown"=>$workStatusDropdown,'fund_name'=>$fund_name,'fund_id'=>$fund_id,"secAssign"=>$secAssign]);

        } catch (\Exception $e) {
           return redirect()->back()->with('error', '500 Error viewing ticket');
        }
    }

    public function updateTicketDetail(Request $request) {

        try {
            $contactId = Contact::sessionContactId();
            $data = $request->all();

            $ticket_id = $data['ticket_id'];
            $ticket_assignee_id = $data['ticket_assignee_id'];
            $new_work_status_id = $data['work_status'];

            if($ticket_assignee_id > 0 )
            {
                $ticketAssignee = TicketAssignee::findOrFail($ticket_assignee_id);

                $old_work_status_id = $ticketAssignee['status'];

                $old_work_status = config('dropdown.support_staff_status')[$old_work_status_id];
                $new_work_status = config('dropdown.support_staff_status')[$new_work_status_id];

                $msg = 'Ticket modified<br>- Work Status change from <b>'.$old_work_status.'</b> to <b>'.$new_work_status.'</b>';

                TicketAssignee::where('id', $ticket_assignee_id)->update([
                    'status' => $new_work_status_id,
                    'updated_at' => now(),
                ]);
                $this->addTicketHisotry($ticket_id,$msg,$contactId);

                # Send Mail to Advisor

                $updated_by = Contact::find($contactId);    
                $updatedBy = $updated_by['first_name'].' '.$updated_by['last_name'];

                $assigned_by = Contact::find($ticketAssignee['assigned_by']);
                $assignedby = $assigned_by['first_name'].' '.$assigned_by['last_name'];

                $recipientEmail = GConst::TEST_EMAIL_IDS;
                
                $subject = "Update on ticket [Ticket No : $ticket_id] by ".$updatedBy;
                $baseUrl = url('/');
                $msg1 = 'We are pleased to inform you that the work status for Ticket <b><a href="'.$baseUrl.'/m/agency/ticket/view/'.$ticket_id.'" target="_blank">#'.$ticket_id.'</a></b> has been updated from <b>'.$old_work_status.'</b> to <b>'.$new_work_status.'</b> by <b>'.$updatedBy.'</b>';

                Mail::to($recipientEmail)->send(new EventTicketMail($assignedby,$ticket_id,$subject,$msg1));

            }
            return response()->json(['success' => true]); 
        } catch (\Exception $e) {
            // Handle the exception, return an error response
            return response()->json(['error' => 'Error closing ticket: ' . $e->getMessage()], 500);
        }
    }

    public function addTicketHisotry($ticket_id,$msg,$assigned_to) 
    {
        $contactId = Contact::sessionContactId();

        $comment = new Comment([
            'comment' => $msg,
            'ticket_id' => $ticket_id,
            'assigned_to' => $assigned_to,
            'private' =>'2',
            'created_by' => $contactId,
        ]);
        $comment->save();
        $commentId = $comment->id;
    }
}
