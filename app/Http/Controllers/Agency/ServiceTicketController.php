<?php
/**
 * Created by PhpStorm.
 * User: Anurag
 * Date: 29-08-2023
 * Time: 15:07
 */

namespace App\Http\Controllers\Agency;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Facades\Cache;
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
use App\Models\Task;
use App\Models\Fund;
use App\Models\Ticket;
use App\Models\TicketAssignee;
use App\Models\Comment;
use App\Models\TicketMedia;
use App\Models\ContactType;
use App\Models\ContactTypeContact;
use App\Models\UserPreference;
use App\Models\Charity;
use App\Models\CharityFundMapping;
use App\Models\TicketNotification;
use App\Models\BellNotification;
use App\Helpers\GConst;
#use App\Helpers\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use PDF;
use Carbon\Carbon;
use App\Models\FaSponser;
use App\Models\DAFAccount;
use Illuminate\Support\Facades\Http;

$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);

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
    
    public function myTicket1() 
    {
        GnUtils::addBreadcrumb('Tickets');

        $contact = Contact::sessionContact();
        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');

        $charities = Charity::all();

        $contactId = Contact::sessionContactId();
        $userPreference = UserPreference::where('contact_id', $contactId)->first();

        $preferredCharityId = null;
        $preferredChartType = null;
        if ($userPreference && !empty($userPreference->preferences)) {
            $preferences = $userPreference->preferences;
            $preferredCharityId = $preferences['top_charity_id'] ?? null;
            $preferredChartType = $preferences['chart_type'] ?? null;
        }

        $charts = config('dropdown.chart_dropdown');
        if ($preferredChartType) {
            $charts = array_merge(
                [$preferredChartType => $charts[$preferredChartType]],
                array_diff_key($charts, [$preferredChartType => ''])
            );
        }
        $sponsors   = FaSponser::getDafSponsors();
        $dafAccounts = [];
        // dd($preferredCharityId);

         $authUserId = auth()->id();
        $sponsors   = FaSponser::getDafSponsors();

        $query = DAFAccount::with('sponsor')
            ->where('auth_user_id', $authUserId)
            ->orderBy('created_at', 'desc');

        $dafAccounts = $query->paginate(10)->withQueryString();

        $ticketsQuery = Ticket::query();
        if (!empty($status)) {
            $ticketsQuery->where('status', $status);
        }
        
        if (!empty($priority)) {
            $ticketsQuery->where('priority', $priority);
        }
        
        if (!empty($category)) {
            $ticketsQuery->where('category', $category);
        }
        
        // Paginate the filtered results
        $myTicket = $ticketsQuery->paginate(10);
        $totalTicket = $ticketsQuery->count();

        return view('agency.agency-advisor.service-tickets.tickets1', compact('contact', 'statusDropdown', 'priorityDropdown', 'categoryDropdown', 'charities', 'preferredCharityId', 'charts', 'preferredChartType', 'sponsors','dafAccounts', 'myTicket', 'totalTicket'));
    } 
	
    public function myTicket(Request $request)
    {
        GnUtils::addBreadcrumb('Tickets');

        $contact    = Contact::sessionContact();
        $contactId  = Contact::sessionContactId();

        /* ---------------------------------------------------------
        USER PREFERENCES
        ---------------------------------------------------------- */
        $userPreference = UserPreference::where('contact_id', $contactId)->first();

        $preferredCharityId = null;
        $preferredChartType = null;

        if ($userPreference && !empty($userPreference->preferences)) {
            $preferences         = $userPreference->preferences;
            $preferredCharityId  = $preferences['top_charity_id'] ?? null;
            $preferredChartType  = $preferences['chart_type'] ?? null;
        }

        /* ---------------------------------------------------------
        DROPDOWNS
        ---------------------------------------------------------- */
        $statusDropdown   = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');
        $charts           = config('dropdown.chart_dropdown');

        if ($preferredChartType && isset($charts[$preferredChartType])) {
            $charts = array_merge(
                [$preferredChartType => $charts[$preferredChartType]],
                array_diff_key($charts, [$preferredChartType => ''])
            );
        }

        $sponsors = FaSponser::getDafSponsors();

        /* ---------------------------------------------------------
        FILTER INPUTS
        ---------------------------------------------------------- */
        $sponsorId     = $request->sponsor_id;
        $ticketSearch  = $request->ticket_search;
        $status        = $request->status;
        $priority      = $request->priority;
        $category      = $request->category;

        $limit = 10;

        /* ---------------------------------------------------------
        BUILD QUERY
        ---------------------------------------------------------- */
        $ticketsQuery = Ticket::query();

        /* ---------------------------------------------------------
        Only My Tickets (Assigned or Created By Me)
        ---------------------------------------------------------- */
        $ticketsQuery->where(function ($query) use ($contactId) {
            $query->where('tickets.assigned_to', $contactId)
                ->orWhere('tickets.created_by', $contactId);
        });

        // Filter by Charity
        /*if (!empty($sponsorId)) {
            $fundIds = CharityFundMapping::where('charity_id', $sponsorId)
                            ->pluck('fund_id')
                            ->toArray();

            $ticketsQuery->where(function ($query) use ($fundIds) {
                $query->whereIn('target_id', $fundIds)
                    ->orWhereIn(DB::raw('CAST(target_id AS TEXT)'), function ($subQuery) use ($fundIds) {
                        $subQuery->select(DB::raw('CAST(fund_recommendation_id AS TEXT)'))
                                ->from('fund_recommendation')
                                ->whereIn('fund_id', $fundIds);
                    });
            });
        }*/

        // Search
        if (!empty($ticketSearch)) {
            $ticketsQuery->where(function ($query) use ($ticketSearch) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($ticketSearch) . '%'])
                    ->orWhere('id', 'LIKE', '%' . $ticketSearch . '%');
            });
        }

        // Filters
        if (!empty($status)) {
            $ticketsQuery->where('status', $status);
        }

        if (!empty($priority)) {
            $ticketsQuery->where('priority', $priority);
        }

        if (!empty($category)) {
            $ticketsQuery->where('category', $category);
        }

        $statusCountsRaw = (clone $ticketsQuery)
                        ->reorder()
                        ->select('status', DB::raw('COUNT(*) as total'))
                        ->groupBy('status')
                        ->pluck('total', 'status')
                        ->toArray();

        $statusCounts = [];

        foreach ($statusDropdown as $key => $label) {
            $statusCounts[$key] = $statusCountsRaw[$key] ?? 0;
        }
        
        /* ---------------------------------------------------------
        PAGINATION (Laravel Standard)
        ---------------------------------------------------------- */
        $myTicket = $ticketsQuery
                        ->latest()                // Order by latest
                        ->paginate($limit)
                        ->withQueryString();      // Keep filters in pagination

        $totalTicket = $myTicket->total();        // Correct total count

        /* ---------------------------------------------------------
        RETURN VIEW
        ---------------------------------------------------------- */
        return view(
            'agency.agency-advisor.service-tickets.tickets',
            compact(
                'contact',
                'statusDropdown',
                'priorityDropdown',
                'categoryDropdown',
                'preferredCharityId',
                'charts',
                'preferredChartType',
                'sponsors',
                'myTicket',
                'totalTicket',
                'statusCounts'
            )
        );
    }


    public function myTicketAjax(Request $request) 
    {
        $charity_id = $request->charity_id;
        $ticket_search = $request->ticket_search;
        $status = $request->status;
        $priority = $request->priority;
        $category = $request->category;
        
        $limit = 10;
        
        // Initialize the query
        $ticketsQuery = Ticket::query();
        
        // Filter by charity ID if provided
        if (!empty($charity_id)) {
            $fundIdsArray = CharityFundMapping::where('charity_id', $charity_id)->pluck('fund_id')->toArray();
            $ticketsQuery->where(function($query) use ($fundIdsArray) {
                $query->whereIn('target_id', $fundIdsArray)
                      ->orWhereIn(DB::raw('CAST(target_id AS TEXT)'), function($subQuery) use ($fundIdsArray) {
                          $subQuery->select(DB::raw('CAST(fund_recommendation_id AS TEXT)'))
                                   ->from('fund_recommendation')
                                   ->whereIn('fund_id', $fundIdsArray);
                      });
            });
        }
    
        // Apply search filter if provided
        if (!empty($ticket_search)) {
            $ticketsQuery->where(function($query) use ($ticket_search) {
                $query->where(DB::raw('LOWER(title)'), 'LIKE', '%' . strtolower($ticket_search) . '%')
                ->orWhere('id', 'LIKE', '%' . $ticket_search . '%');
            });
        }
        
        // Apply other filters
        if (!empty($status)) {
            $ticketsQuery->where('status', $status);
        }
        
        if (!empty($priority)) {
            $ticketsQuery->where('priority', $priority);
        }
        
        if (!empty($category)) {
            $ticketsQuery->where('category', $category);
        }
        
        // Paginate the filtered results
        $myTicket = $ticketsQuery->paginate($limit);
        $totalTicket = $ticketsQuery->count();
        
        // Get preferred chart type
        $contactId = Contact::sessionContactId();
        $userPreference = UserPreference::where('contact_id', $contactId)->first();
        $preferredChartType = null;
        if ($userPreference && !empty($userPreference->preferences)) {
            $preferences = $userPreference->preferences;
            $preferredChartType = $preferences['chart_type'] ?? null;
        }
        
        $html = '';
        if (count($myTicket) || $request->page == 1) {
            $html = view('agency.agency-advisor.service-tickets.list', compact('myTicket'))->render();
        }
        
        return [
            'more' => ($totalTicket) > $limit * $request->page ? 1 : 0,
            'html' => $html,
            'totalTicket' => $totalTicket,
            'totalLimit' => $limit * $request->page,
            'preferred_chart' => $preferredChartType,
            'charity_id' => $charity_id
        ];
    }

    public function myTicketChart () {

        GnUtils::addBreadcrumb('Service Tickets Chart');
        $contact = Contact::sessionContact();

        return view('agency.agency-advisor.service-tickets.chart');
    }

    /* 

    */
    public function create() {

        try {

            GnUtils::addBreadcrumb('Service Tickets', route('agency-ticket'));
            GnUtils::addBreadcrumb('Create Ticket');

            $recomm_data = '';
           
            if(request('recommendation_id') != '')
            {
                $recomm_data = FundRecommendation::getRecommendationById(request('recommendation_id'));

                $contact_arr = Contact::find($recomm_data['contact_id']);
                $fund_arr = Fund::where('fund_id', $recomm_data['fund_id'])->first();
                
                $recomm_data['amount'] = GnUtils::money($recomm_data['amount']);
                $recomm_data['contact_name'] = $contact_arr['first_name'].' '.$contact_arr['last_name'];
                $recomm_data['fund_name'] = $fund_arr->name;
                $recomm_data['status'] = 'N';
                $recomm_data['date_submitted'] = GnUtils::customDate($recomm_data['date_submitted']);
                $recomm_data['approved_date'] = GnUtils::customDate($recomm_data['approved_date']);
            }
            #echo '<pre>';print_r($recomm_data);die;

            $contactId = Contact::sessionContactId();

            $statusDropdown = config('dropdown.status');
            $priorityDropdown = config('dropdown.priority');
            $categoryDropdown = config('dropdown.category');

            $contactFunds = Fund::getSelectableForGrantRecommendation();
            $charities = Charity::all();
            $sponsors = FaSponser::getDafSponsors();
            
            return view('agency.agency-advisor.service-tickets.create-ticket', compact('statusDropdown', 'priorityDropdown', 'categoryDropdown', 'contactFunds', 'contactId','recomm_data', 'charities', 'sponsors'));
        } catch (\Exception $e) {
            // Handle the exception, return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* 

    */
    public function store(Request $request) {

        $contactId = Contact::sessionContactId();

        // ✅ Validation
        $validatedData = $request->validate([
            'category' => 'required|not_in:0',
            'title'    => 'required|string|max:255',
            'priority' => 'required|not_in:0',
        ], [
            'category.required' => 'Please select ticket type.',
            'category.not_in'   => 'Please select ticket type.',
            'title.required'    => 'Please enter title.',
            'priority.required' => 'Please select priority.',
            'priority.not_in'   => 'Please select priority.',
        ]);

        // ❌ REMOVE (not needed anymore)
        // if ($validatedData['priority'] == '0') { ... }
        // if ($validatedData['category'] == '0') { ... }

        // ✅ Get inputs safely
        $recommendation_id         = (int) $request->input('recommendation_id', 0);
        $recommendation_contact_id = $request->input('recommendation_contact_id');
        $assigned_to               = $request->input('donor_id');
        $fund_id                   = $request->input('fund_id');

        $target_type = 'fund';
        $target_id   = $fund_id;

        // ✅ Business logic
        if ($recommendation_id > 0) {
            $assigned_to = $recommendation_contact_id;
            $target_type = GConst::GRANT_RECOMMENDATION_TICKET;
            $target_id   = $recommendation_id;
        }

        // ✅ Prepare data (clean way)
        $data = [
            'title'       => $validatedData['title'],
            'description' => $request->input('content'),
            'priority'    => $validatedData['priority'],
            'category'    => $validatedData['category'],
            'status'      => 'open',
            'created_by'  => $contactId,
            'assigned_to' => $assigned_to,
            'target_type' => $target_type,
            'target_id'   => $target_id,
            'start_date'  => now(),
        ];

        // ✅ Create ticket
        $ticket = Ticket::create($data);

        // ✅ Redirect
        if ($recommendation_id > 0) {

            return redirect()->route('agency-service-ticket-view', $ticket->id)
            ->with('success', 'Ticket created successfully');
        }

        return redirect()->route('agency-ticket', $ticket->id)
            ->with('success', 'Ticket created successfully');
    }

    public function viewTicket($ticket_id) {

        try {
            GnUtils::addBreadcrumb('Service Tickets', route('agency-ticket'));
            GnUtils::addBreadcrumb('View Ticket');

            $ticket        = Ticket::findOrFail($ticket_id);
            $contact_id    = Contact::sessionContactId();
            $primaryAssign = Contact::find($ticket->assigned_to);
            $created_by    = Contact::find($ticket->created_by);

            $statusDropdown   = config('dropdown.status');
            $priorityDropdown = config('dropdown.priority');
            $categoryDropdown = config('dropdown.category');

            $fund_id   = '';
            $fund_name = '';

            if ($ticket->target_type === 'fund') {
                $fund_id   = $ticket->target_id;
                $fund_name = Fund::getFundById($fund_id)->name ?? '';
            } elseif (in_array($ticket->target_type, ['cart recommendation', 'grant recommendation'])) {
                $recomm    = FundRecommendation::getRecommendationById($ticket->target_id);
                $fund_id   = $recomm->fund_id;
                $fund_name = Fund::getFundById($fund_id)->name ?? '';
            }

            $contactTypes = ContactType::getContactTypeId(ContactType::ROLE_SUPPORT_STAFF);
            $contactIds   = ContactTypeContact::where('contact_type_id', $contactTypes->contact_type_id)
                                ->pluck('contact_id')
                                ->toArray();
            $assigneeList = Contact::whereIn('contact_id', $contactIds)->get();

            $secAssign             = TicketAssignee::where('ticket_id', $ticket->id)->where('active', '1')->first();
            $secondary_assigned_id = $secAssign->assigned_id ?? null;

            return view('agency.agency-advisor.service-tickets.view', compact(
                'ticket', 'contact_id', 'primaryAssign', 'created_by',
                'statusDropdown', 'priorityDropdown', 'categoryDropdown',
                'assigneeList', 'secondary_assigned_id', 'fund_name', 'fund_id'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('agency-ticket')->with('error', 'Ticket not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading the ticket. Please try again.');
        }
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

            if($field_type == 'status')   
            {
                $status_id = $field_value;

                # Send Mail to Created By User When Ticket Status will update by Advisor

                $ticket = Ticket::findOrFail($ticket_id);
                $created_by = Contact::find($ticket->created_by);
                $createdBy = $created_by['first_name'].' '.$created_by['last_name'];

                $assigned_to = Contact::find($ticket->assigned_to);
                $assignedTo = $assigned_to['first_name'].' '.$assigned_to['last_name'];

                $old_status = config('dropdown.status')[$ticket->status];
                $new_status = config('dropdown.status')[$status_id];

                $recipientEmail = GConst::TEST_EMAIL_IDS;
                
                $subject = "Update on ticket [Ticket No : $ticket_id] by ".$assignedTo;
                $baseUrl = url('/');
                $msg1 = 'We are pleased to inform you that the ticket status for Ticket <b><a href="'.$baseUrl.'/m/ticket/view/'.$ticket_id.'" target="_blank">#'.$ticket_id.'</a></b> has been updated from <b>'.$old_status.'</b> to <b>'.$new_status.'</b> by <b>'.$assignedTo.'</b>';

                Mail::to($recipientEmail)->send(new EventTicketMail($createdBy,$ticket_id,$subject,$msg1));
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

            if($field_type != 'assigned_to') {

                $msg = $this->tickeEditLogsCheck($ticket_id,$status_id,$priority_id,$category_id,'','');
                $this->addTicketHisotry($ticket_id,$msg,$contactId);

                Ticket::where('id', $ticket_id)->update([
                    $field_type => $field_value,
                    'updated_by' => $contactId,
                    'updated_at' => now(),
                ]);
            }

            if($field_type == 'assigned_to') {

                $assignee = TicketAssignee::where('ticket_id', $ticket_id)->where('active', 1)->first();
                $old_assigned_id = @$assignee['assigned_id'];

                if($old_assigned_id != $field_value)
                {
                    TicketAssignee::where('ticket_id', $ticket_id)
                    ->update([
                        'active' => 0,
                        'updated_at' => now(),
                    ]);

                    if($field_value > 0)
                    {
                        $msg = $this->tickeEditLogsCheck($ticket_id,$status_id,$priority_id,$category_id,$field_value,$old_assigned_id);

                        $ticketAssignee = new TicketAssignee();
                        $ticketAssignee->ticket_id = $ticket_id;
                        $ticketAssignee->assigned_id = $field_value;
                        $ticketAssignee->assigned_by = $contactId;
                        $ticketAssignee->active = 1;
                        $ticketAssignee->status = 'pending';
                        $ticketAssignee->created_at = now();
                        $ticketAssignee->updated_at = now();
                        $ticketAssignee->save();


                        # Bell Notification
                        $objNotification = new BellNotification(); 
                        
                        $to = $field_value;
                        $notification = 'New Ticket Assigned with Ticket ID #'.$ticket_id;
                        $category = 'ticket-assign';
                        $target_type = 'ticket';
                        $target_id = $ticket_id;
                        $notification_to = $objNotification->getNotificationTo($to);

                        $notification_to = [$notification_to];
                        $notification_to_json = json_encode($notification_to);
                        $notification_type = GConst::DEFAULT_NOTIFICATION_TYPE;
                        $notification_type_json = json_encode($notification_type);
                        $notification_disappear = GConst::DEFAULT_NOTIFICATION_DISAPPEAR;
                        $objNotification->sendNotification($notification_to_json,$notification,$category,$target_type,$target_id,$notification_type_json,$notification_disappear);
                        # End

                        # Send Mail to Assignee

                        $assigned_by = Contact::find($field_value);
                        $assignedBy = $assigned_by['first_name'].' '.$assigned_by['last_name'];

                        $recipientEmail = GConst::TEST_EMAIL_IDS;
                        
                        $subject = "You've Been Assigned a Service Ticket: [Ticket No : $ticket_id]";
                        $baseUrl = url('/');
                        $msg1 = 'We are pleased to inform you that a new service ticket has been assigned to you with Ticket ID <b><a href="'.$baseUrl.'/m/support-staff/ticket/view/'.$ticket_id.'" target="_blank">#'.$ticket_id.'</a></b>.';

                        Mail::to($recipientEmail)->send(new EventTicketMail($assignedBy,$ticket_id, $subject,$msg1));
                    }
                    else
                    {
                        $msg = $this->tickeEditLogsCheck($ticket_id,$status_id,$priority_id,$category_id,$field_value,$old_assigned_id);
                    }
                    $this->addTicketHisotry($ticket_id,$msg,$contactId);
                }
            }

            return response()->json(['success' => true]); 
        } catch (\Exception $e) {
            // Handle the exception, return an error response
            return response()->json(['error' => 'Error closing ticket: ' . $e->getMessage()], 500);
        }
    }

    public function ticketDelete(Request $request) {  

        try {
            $data = $request->all();
            $ticketId = $data['ticket_id'];

            $ticket = Ticket::findOrFail($ticketId);

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

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
      
    public function ticketClose(Request $request) {

        try {
            $contactId = Contact::sessionContactId();
            $data = $request->all();
            $ticketId = $data['ticket_id'];

            Ticket::where('id', $ticketId)->update([
                'status' => 'closed',
                'closed_by' => $contactId,
                'closed_at' => now(),
            ]);

            $msg = 'Ticket closed';
            $this->addTicketHisotry($ticketId,$msg,$contactId);

            return response()->json(['success' => true]); 
        } catch (\Exception $e) {
            // Handle the exception, return an error response
            return response()->json(['error' => 'Error closing ticket: ' . $e->getMessage()], 500);
        }
    }

    public function editTicket($ticket_id) {

        try {
            GnUtils::addBreadcrumb('Service Tickets', route('agency-ticket'));
            GnUtils::addBreadcrumb('Edit Ticket');

            $ticket = Ticket::findOrFail($ticket_id);

            $contactFunds   = Fund::getSelectableForGrantRecommendation();
            $statusDropdown   = config('dropdown.status');
            $priorityDropdown = config('dropdown.priority');
            $categoryDropdown = config('dropdown.category');
            $sponsors         = FaSponser::getDafSponsors();

            // Derive page segment from URL path (e.g. /m/agency/... → 'agency')
            $page    = explode('/', parse_url(url()->current(), PHP_URL_PATH))[3] ?? '';
            $fund_id   = '';
            $fund_name = '';

            if ($ticket->target_type === 'fund') {
                $fund_id   = $ticket->target_id;
                $fund_name = Fund::getFundById($fund_id)->name ?? '';
            } elseif (in_array($ticket->target_type, ['cart recommendation', 'grant recommendation'])) {
                $recomm  = FundRecommendation::getRecommendationById($ticket->target_id);
                $fund_id = $recomm->fund_id;
            }

            return view('agency.agency-advisor.service-tickets.edit-ticket',
                compact('ticket', 'contactFunds', 'statusDropdown', 'priorityDropdown', 'categoryDropdown', 'page', 'fund_id', 'fund_name', 'sponsors')
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('agency-ticket')->with('error', 'Ticket not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading the ticket. Please try again.');
        }
    }

    /* 

    */

    public function updateTicket(Request $request, $ticket_id) {

        // Validate outside try so Laravel handles redirect + $errors natively
        $request->validate([
            'title'       => 'required|string|max:255',
            'status_id'   => 'required|not_in:0',
            'priority_id' => 'required|not_in:0',
            'category_id' => 'required|not_in:0',
        ], [
            'status_id.not_in'   => 'Please select status.',
            'priority_id.not_in' => 'Please select priority.',
            'category_id.not_in' => 'Please select ticket type.',
        ]);

        try {
            $contactId   = Contact::sessionContactId();
            $ticket      = Ticket::findOrFail($ticket_id);
            $source_page = $request->input('source_page');

            $msg = $this->tickeEditLogsCheck(
                $ticket_id,
                $request->input('status_id'),
                $request->input('priority_id'),
                $request->input('category_id'),
                '', ''
            );

            $ticket->title       = $request->input('title');
            $ticket->description = $request->input('content');
            $ticket->status      = $request->input('status_id');
            $ticket->priority    = $request->input('priority_id');
            $ticket->category    = $request->input('category_id');
            $ticket->updated_by  = $contactId;

            if ($request->input('status_id') === 'closed') {
                $ticket->closed_by = $contactId;
                $ticket->closed_on = now();
            }

            $ticket->save();

            $this->addTicketHisotry($ticket_id, $msg, $contactId);

            $redirect = $source_page === 'dashboard-ticket'
                ? route('agency-home')
                : route('agency-ticket');

            return redirect($redirect)->with('success', 'Ticket updated successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('agency-ticket')->with('error', 'Ticket not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the ticket. Please try again.')->withInput();
        }
    }
    
    public function getDonorEmail(Request $request) {
        
        try {
            $data = $request->all();
            $donorId = $data['donor_id'];

            $donorEmails = ContactFund::join('email_address', 'email_address.contact_id', '=', 'contact_fund.contact_id')
                ->join('contact', 'contact_fund.contact_id', '=', 'contact.contact_id')
                ->join('contact_type_contact', 'contact_fund.contact_id', '=', 'contact_type_contact.contact_id')
                ->where('contact_fund.fund_id', $donorId)
                ->where('contact_type_contact.contact_type_id', 10)
                ->select('contact.contact_id', 'contact.first_name', 'contact.last_name')
                ->distinct()
            ->get();

            return response()->json(['donorEmails' => $donorEmails]);
        } catch (\Exception $e) {
            
            return response()->json(['error' => 'Error getting donor emails: ' . $e->getMessage()], 500);
        }
    }

    public function tickeEditLogsCheck($ticket_id,$new_status_id,$new_priority_id,$new_category_id,$new_assigned_to,$old_assigned_id) {

        $contactId = Contact::sessionContactId();
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
        if($new_assigned_to > 0) {

            if($old_assigned_id !=''){

                $old_assigned_to = $old_assigned_id;
            }
            if($old_assigned_to != $new_assigned_to) {

                $old_contact = Contact::find($old_assigned_to);
                $old_assignee = $old_contact['first_name'].' '.$old_contact['last_name'];

                $new_contact = Contact::find($new_assigned_to);
                $new_assignee = $new_contact['first_name'].' '.$new_contact['last_name'];
                
                $msg .= '<br>- Ticket reassigned to <b>'.$new_assignee.'</b> from <b>'.$old_assignee.'</b>';
            }
        }
        if($new_assigned_to == 0) {

            if($old_assigned_id !='')
            {
                $remove_contact = Contact::find($old_assigned_id);
                $remove_assignee = $remove_contact['first_name'].' '.$remove_contact['last_name'];
                
                $msg .= '<br>- Ticket removed from <b>'.$remove_assignee.'</b>';
            }
        }
        return $msg;
    }

    
    
    public function getComment(Request $request) {  

        try {

            $contactId = Contact::sessionContactId();
            $data = $request->all();
            $ticketId = $data['ticket_id'];
            $type = $data['type'];
            $ticketComment = Comment::getTicketComment($ticketId, $contactId,$type);
    
            $result = array();
            foreach ($ticketComment as $key => $value) {

                $contact_arr = Contact::find($value['created_by']);
                $value['comment_added_by'] = $contact_arr['first_name'].' '.$contact_arr['last_name'];

                $contact_type_id = ContactTypeContact::where('contact_id', $value['created_by'])
                                  ->first()->contact_type_id;

                $value['comment_added_by_role'] = '';
                if($contact_type_id){

                    $value['comment_added_by_role'] = ContactType::where('contact_type_id', $contact_type_id)
                                  ->first()->contact_type;
                }

                $value['created_at_format'] = \App\Helpers\GnUtils::customDate($value['created_at']).' '.date('h:i', strtotime($value['created_at']));

                $ticket_id = $value['ticket_id'];
                $comment_id = $value['id'];
                $ticketCommentmedia = TicketMedia::getTicketAttachment(GConst::TICKET_COMMENT, $comment_id);
                $value['media'] = $ticketCommentmedia;
                $result[] = $value;
            }
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error getting ticket comments: ' . $e->getMessage()], 500);
        }
    }
    
    public function addComment(Request $request) {

        try {
            $contactId = Contact::sessionContactId();
            $data = $request->all();
            
            $own_hisotry = '0';
            if(@$data['own_hisotry'] == 'on'){
                $own_hisotry = '1';
            }
            $files = $request->file('files');
            
            $ticket_id = $data['ticket_id'];

            $comment = new Comment([
                'comment' => $data['comment_text'],
                'ticket_id' => $ticket_id,
                'assigned_to' => $data['created_by'],
                'private' => $own_hisotry,
                'created_by' => $contactId,
            ]);
            $comment->save();
            $commentId = $comment->id;
            
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

                    if($value != $contactId) {

                        $filename = 'active_tickets/user_' . $value . '.txt';

                        $storedTicketId = '';
                        if (Storage::exists($filename)) {
                            $storedTicketId = Storage::get($filename);
                        }
                        if ($storedTicketId != $ticket_id) {

                            # $recipient = $objNotification->getNotificationTo($value);
                            # $notification_to[] = $recipient;
                        }

                        #$recipient = $objNotification->getNotificationTo($value);
                        #$notification_to[] = $recipient;
                    }
                }
                
                if(count($notification_to) > 0) {

                    $notification_to_json = json_encode($notification_to);

                    $notification   = 'New comment on the Ticket ID #'.$ticket_id;
                    $category       = 'ticket-comment';
                    $target_type    = 'ticket';
                    $target_id      = $ticket_id;
                    $notification_type = GConst::DEFAULT_NOTIFICATION_TYPE;
                    $notification_type_json = json_encode($notification_type);
                    $notification_disappear = GConst::DEFAULT_NOTIFICATION_DISAPPEAR;
                   # $objNotification->sendNotification($notification_to_json,$notification,$category,$target_type,$target_id,$notification_type_json,$notification_disappear);
                }
            }

            if ($request->hasFile('files')) {

                $files = $request->file('files');

                // If single file, convert to array
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {

                    if ($file->isValid()) {

                        $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

                        $file->move(public_path('uploads/tickets'), $filename);

                        TicketMedia::create([
                            'target_type' => GConst::TICKET_COMMENT,
                            'target_id'   => $commentId,
                            'file_name'   => $filename,
                            'file_path'   => $filename,
                            'name'        => $file->getClientOriginalName(),
                            'created_by'  => $contactId,
                        ]);
                    }
                }
            }

            $message = ($own_hisotry == 1)
                ? 'Your record has been successfully added and is available in the Ticket History.'
                : 'Comment added successfully.';

            return response()->json([
                'status'  => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {

            \Log::error('Add Comment Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Contact::sessionContactId()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while adding the comment. Please try again.'
            ], 500);
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
