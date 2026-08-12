<?php
/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 11-12-2023
 * Time: 15:07
 */


namespace App\Http\Controllers\Donor;
use Illuminate\Http\JsonResponse; 
use App\Mail\EventCreated;
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
use App\Models\Task;
use App\Models\Fund;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\TicketMedia;
use App\Models\ContactType;
use App\Models\ContactTypeContact;
use App\Models\BellNotification;
use App\Models\TicketAssignee;
#use App\Helpers\PushNotification;
use App\Helpers\GConst;
use Illuminate\Http\Request;
use League\Csv\Writer;  
use PDF;
use Carbon\Carbon;

$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);
/**
 * Class MyTicketController
 * @package App\Http\Controllers
 */
class MyTicketController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    
    public function myTicket() {

        GnUtils::addBreadcrumb('Tickets');

        $contact = Contact::sessionContact();

        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');
        
        return view('donor.ticket.index', compact('contact', 'statusDropdown', 'priorityDropdown', 'categoryDropdown'));
    }

    public function myTicketAjax(Request $request) {

        $ticket_search = $request->ticket_search;
        $status = $request->status;
        $priority = $request->priority;
        $category = $request->category;
    
        $limit = 10;
        $myTicket = Ticket::paginateMyTicket($limit,$ticket_search,$status, $priority, $category);
        $totalTicket = Ticket::myTicket($ticket_search,$status, $priority, $category);

        $html = '';
        if (count($myTicket) || $request->page == 1) {
            $html = view('donor.ticket.list', compact('myTicket'))->render();
        }
        return [
            'more' => ($totalTicket) > $limit  *  $request->page ? 1 : 0,
            'html' => $html,
            'totalTicket' => $totalTicket,
            'totalLimit' => $limit  *  $request->page
        ];
    }
    

    public function viewMyTicket($ticket_id) {

        GnUtils::addBreadcrumb('Tickets', route('ticket'));
        GnUtils::addBreadcrumb('View Ticket');

        $ticket = Ticket::find($ticket_id);
        $contact_id = Contact::sessionContactId();
        $ticketCreatedBy = Contact::find($ticket->created_by);
        
        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');
        
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

        $contactTypes = ContactType::getContactTypeId(ContactType::ROLE_SUPPORT_STAFF);

        $contactTypesContact = ContactTypeContact::where('contact_type_id', $contactTypes->contact_type_id)->get();

        $contactIds = [];
        foreach ($contactTypesContact as $contact) {
            $contactIds[] = $contact->contact_id; 
        }

        $assigneeList = Contact::whereIn('contact_id', $contactIds)->get();

        $secAssignee = TicketAssignee::where('ticket_id', $ticket->id)->where('active','1')->first();

        $assigned_id = $ticket->assigned_to;
        if($secAssignee){
            $assigned_id = $secAssignee->assigned_id;
        }
        $assignedToContact = Contact::find($assigned_id);

        return view('donor.ticket.view', ['contact_id'=>$contact_id,'ticket' => $ticket, 'assignedToContact' => $assignedToContact,'ticketCreatedBy'=>$ticketCreatedBy,'statusDropdown'=>$statusDropdown,'priorityDropdown'=>$priorityDropdown,'categoryDropdown'=>$categoryDropdown,'assigneeList'=>$assigneeList,'fund_name'=>$fund_name,'fund_id'=>$fund_id]);
    }

    public function getTicketComment(Request $request) {

        try {
            $contact_id = Contact::sessionContactId();
            $data = $request->all();
            $ticket_id = $data['ticket_id'];
            $type = $data['type'];   

            $ticketComment = Comment::getTicketComment($ticket_id,$contact_id,$type);

            $result = array();
            foreach ($ticketComment as $key => $value) 
            {
                $contact_arr = Contact::find($value['created_by']);
                $value['comment_added_by'] = $contact_arr['first_name'].' '.$contact_arr['last_name'];
                
                $value['created_at_format'] = \App\Helpers\GnUtils::customDate($value['created_at']).' '.date('h:i', strtotime($value['created_at']));

                $ticket_id = $value['ticket_id'];
                $comment_id = $value['id'];
                $ticketCommentmedia = TicketMedia::getTicketAttachment(GConst::TICKET_COMMENT,$comment_id);
                $value['media'] = $ticketCommentmedia;
                $result[] = $value;
            }
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error getting ticket comments: ' . $e->getMessage()], 500);
        }
    }

    public function addTicketComment(Request $request) {  

        try {

            $contact_id = Contact::sessionContactId();
            $data = $request->all();
            $ticket_id = $data['ticket_id'];

            $own_hisotry = '0';
            if(@$data['own_hisotry'] == 'on'){
                $own_hisotry = '1';
            }

            $files = $request->file('files');

            $comment = new Comment([
                'comment' => $data['comment_text'],
                'ticket_id' => $data['ticket_id'],
                'assigned_to' => $data['created_by'],
                'private' => $own_hisotry,
                'created_by' => $contact_id,
            ]);

            $comment->save();
            $comment_id = $comment->id;

            # Bell Notification
            if($own_hisotry == 0) {

                $ticket = Ticket::findOrFail($ticket_id);
                $ticket_assigned_to = $ticket->assigned_to;
                $ticket_created_by  = $ticket->created_by;

                $secAssign = TicketAssignee::where('ticket_id', $ticket_id)->where('active', '1')->first();
                $support_staff_contact_id = $secAssign->assigned_id ?? null;

                $ticket_associated_contact_ids = [$ticket_assigned_to, $ticket_created_by];

                if ($support_staff_contact_id) {
                    $ticket_associated_contact_ids[] = $support_staff_contact_id;
                }

                $objNotification = new BellNotification();   

                $notification_to = [];
                foreach ($ticket_associated_contact_ids as $key => $value) {

                    if($value != $contact_id) {
                        $recipient = $objNotification->getNotificationTo($value);
                        $notification_to[] = $recipient;
                    }
                }
                $notification_to_json = json_encode($notification_to); 
                
                $notification   = 'New comment on the Ticket ID #'.$ticket_id;
                $category       = 'ticket-comment';
                $target_type    = 'ticket';
                $target_id      = $ticket_id;
                
                $objNotification->sendNotification($notification_to_json,$notification,$category,$target_type,$target_id);
            }
            # end

            if ($files !== null) {

                $fileCount = count($files);
                foreach ($files as $file) {

                    $filename = time() . '_' .str_replace(' ', '_', $file->getClientOriginalName());
                    $file->move(public_path('uploads/tickets'), $filename);

                    $media = new TicketMedia([
                        'target_type' => GConst::TICKET_COMMENT,
                        'target_id' => $comment_id,
                        'file_name' => $filename,
                        'file_path' => $filename,
                        'name' => $file->getClientOriginalName(),
                        'created_by' => $contact_id,
                    ]);
                    $media->save();
                }
            }
            if($own_hisotry == 1){ 
                return response()->json(['success' => '&nbsp;&nbsp;<span style="color:green">Your record have been successfully added and will be accessible in the  <b>Ticket Hisotry</b></span>']);
            }else{
                return '';
            }

        } catch (\Exception $e) {
            // Handle the exception, return an error response
            return response()->json(['error' => 'Error adding comment: ' . $e->getMessage()], 500);
        }
    }

    public function create() {

        GnUtils::addBreadcrumb('Tickets', route('ticket'));
        GnUtils::addBreadcrumb('Create Ticket');

        $contact_id = Contact::sessionContactId();

        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');

        $contactFunds = Fund::getSelectableForGrantRecommendation();

        return view('donor.ticket.create_ticket', compact('statusDropdown', 'priorityDropdown', 'categoryDropdown', 'contactFunds', 'contact_id'));
        
    }

    public function getAdvisorlist(Request $request) {

        $data = $request->all();

        $fundId = $data['fundId'];

        $advisorList = ContactFund::join('email_address', 'email_address.contact_id', '=', 'contact_fund.contact_id')
        ->join('contact', 'contact_fund.contact_id', '=', 'contact.contact_id')
        ->join('contact_type_contact', 'contact_fund.contact_id', '=', 'contact_type_contact.contact_id')
        ->where('contact_fund.fund_id', $fundId)
        ->where('contact_type_contact.contact_type_id', 18)
        ->select('email_address.email_address', 'contact.contact_id', 'contact.first_name', 'contact.last_name')
        ->distinct()
        ->get();

        return response()->json(['advisorList' => $advisorList]);
    }

    public function store(Request $request) {

        $contact_id = Contact::sessionContactId();
        $fund_id = $request->input('fund_id');

        // Validate the form data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|string',
            'category' => 'required|string',
            'fund_id' => 'required',
        ]);

        $validatedData['status'] = 'open';
        $validatedData['created_by'] = $contact_id;
        $validatedData['description'] =  $request->input('content');
        $validatedData['assigned_to'] =  $request->input('advisor_id');
        $validatedData['target_type'] = 'fund';
        $validatedData['target_id'] = $request->input('fund_id');
        $validatedData['start_date'] = now();
        

        // Create a new ticket with the validated data
        $ticket = Ticket::create($validatedData);

        return redirect('/m/ticket')->with('success', 'Ticket created successfully');
    }

    public function ticketDelete(Request $request) {

        $data = $request->all();
        $ticketId = $data['ticketId'];

        #$ticket = Ticket::find($ticketId);
        $ticket = Ticket::findOrFail($ticketId);    

        // Check if the ticket exists
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket not found.']);
        }

        $contactId = Contact::sessionContactId();
        $msg = 'The Ticket ID '.$ticketId.' archived successfully.';
        $this->addTicketHisotry($ticketId,$msg,$contactId);

        $ticket->delete();

        if ($ticket->trashed()) {
            return response()->json(['success' => true, 'message' => 'The ticket has been successfully archived. If needed, it can be restored at any time.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to soft delete.'], 500);
        }
    }

    public function ticketClose(Request $request) {

        $contact_id = Contact::sessionContactId();
        $data       = $request->all();
        $ticketId  = $data['ticket_id'];
        Ticket::where('id', $ticketId)->update(['status' => 'closed', 'closed_by' => $contact_id, 'closed_at' => now()]);
    }

    public function editTicket($ticket_id) {
        
        GnUtils::addBreadcrumb('Tickets', route('ticket'));
        GnUtils::addBreadcrumb('Edit Ticket');

        $ticket = Ticket::find($ticket_id);
        $contact_id = Contact::sessionContactId();
        $contactFunds = Fund::getSelectableForGrantRecommendation();

        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');
        
        $fund_id = '';
        if($ticket->target_type == 'fund'){

            $fund_id = $ticket->target_id;
            $fund_name = Fund::getFundById($fund_id)->name;
        }
        if($ticket->target_type == 'cart recommendation' || $ticket->target_type == 'grant recommendation'){

            $recommendation_id = $ticket->target_id;
            $recomm = FundRecommendation::getRecommendationById($recommendation_id);
            $fund_id = $recomm->fund_id;
        }

        return view('donor.ticket.edit', ['ticket' => $ticket, 'contactFunds' => $contactFunds, 'statusDropdown' => $statusDropdown, 'priorityDropdown' => $priorityDropdown, 'categoryDropdown' => $categoryDropdown,'fund_id'=>$fund_id]);
    }

    public function updateTicket(Request $request, $ticket_id) {

        $contact_id = Contact::sessionContactId();
        $ticket = Ticket::findOrFail($ticket_id);

        $msg = $this->tickeEditLogsCheck($ticket_id,$request->input('status_id'),$request->input('priority_id'),$request->input('category_id'),'');

        $ticket->title = $request->input('title');
        $ticket->description = $request->input('content');
        $ticket->status = $request->input('status_id');
        $ticket->priority = $request->input('priority_id');
        $ticket->category = $request->input('category_id');
        $ticket->updated_by = $contact_id;
        
        if ($request->input('status_id') == 'closed') {
            $ticket->closed_by = $contact_id;
            $ticket->closed_at = now();
        }

        $ticket->save();
        
        # Ticket Hisotry
        $this->addTicketHisotry($ticket_id,$msg,$contact_id);

        return redirect('m/ticket')->with('success', 'Ticket updated successfully');
    }

    public function updateTicketDetail(Request $request) {

        try {
            $contactId = Contact::sessionContactId();
            $data = $request->all();
            $ticket_id = $data['ticket_id'];
            $field_type = $data['field_type'];
            $field_value = $data['field_value'];

            $status_id = '';
            $priority_id = '';
            $category_id = '';
            $assigned_to = '';

            if($field_type == 'status') {
                $status_id = $field_value;
            }

            if($field_type == 'priority') {
                $priority_id = $field_value;
            }

            if($field_type == 'category') {
                $category_id = $field_value;
            }
            if($field_type == 'assigned_to') {
                $assigned_to = $field_value;
            }

            $msg = $this->tickeEditLogsCheck($ticket_id,$status_id,$priority_id,$category_id,$assigned_to);

            if($field_type != 'assigned_to') {

                Ticket::where('id', $ticket_id)->update([
                    $field_type => $field_value,
                    'updated_by' => $contactId,
                    'updated_at' => now(),
                ]);
            }

            if ($field_type == 'assigned_to') {

                TicketAssignee::where('ticket_id', $ticket_id)->update(['active' => '0']);

                TicketAssignee::updateOrCreate(
                    ['ticket_id' => $ticket_id, 'assigned_id' => $assigned_to],
                    [
                        'active' => '1',
                        'assigned_by' => $contactId,
                        'status' => 'Open',
                        'updated_at' => now(),
                    ]
                );
            }

            $this->addTicketHisotry($ticket_id,$msg,$contactId);

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

    public function tickeEditLogsCheck($ticket_id,$new_status_id,$new_priority_id,$new_category_id,$new_assigned_to) {

        $ticket = Ticket::findOrFail($ticket_id);

        $old_status_id = $ticket['status'];
        $old_priority_id = $ticket['priority'];
        $old_category_id = $ticket['category'];
        $old_assigned_to = $ticket['assigned_to'];

        $msg = 'Ticket modified';
        if($new_status_id != '') {

            if($old_status_id != $new_status_id) {

                $old_status = config('dropdown.status')[$old_status_id];
                $new_status = config('dropdown.status')[$new_status_id];

                $msg .= '<br>- Status change from <b>'.$old_status.'</b> to <b>'.$new_status.'</b>';
            }
        }
        if($new_priority_id != '') {
            if($old_priority_id != $new_priority_id) {

                $old_priority = config('dropdown.priority')[$old_priority_id];
                $new_priority = config('dropdown.priority')[$new_priority_id];

                $msg .= '<br>- Priority change from <b>'.$old_priority.'</b> to <b>'.$new_priority.'</b>';
            }
        }
        if($new_category_id != '') {
            if($old_category_id != $new_category_id) {

                $old_category = config('dropdown.category')[$old_category_id];
                $new_category = config('dropdown.category')[$new_category_id];

                $msg .= '<br>- Type change from <b>'.$old_category.'</b> to <b>'.$new_category.'</b>';
            }
        }
        if($new_assigned_to != '') {
            if($old_assigned_to != $new_assigned_to) {

                $old_contact = Contact::find($old_assigned_to);
                $old_assignee = $old_contact['first_name'].' '.$old_contact['last_name'];

                $new_contact = Contact::find($new_assigned_to);
                $new_assignee = $new_contact['first_name'].' '.$new_contact['last_name'];

                $msg .= '<br>- Ticket re-assigned to <b>'.$new_assignee.'</b> from <b>'.$old_assignee.'</b>';
            }
        }
        return $msg;

    }
}
