<?php
/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 29-08-2023
 * Time: 15:07 
 */


namespace App\Http\Controllers\Agency;
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
use Illuminate\Http\Request;
use League\Csv\Writer;
use PDF;
use Carbon\Carbon;



// Funds = 'JCFEX', 'Abra';
/**
 * Class FundController
 * @package App\Http\Controllers
 */
class ServiceTicketController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    
    public function ticketList(Request $request){

        $contact_id = Contact::sessionContactId();



        if ($request->ajax()) {

            $data = $request->all();
            $status = @$data['statusVal'];
            $priority = @$data['priorityVal'];
            $category = @$data['categoryVal'];

            $query = Ticket::query();

            
            if ($status) {
                $query->where('status_id', $status);
            }

            if ($priority) {
                $query->where('priority_id', $priority);
            }

            if ($category) {
                $query->where('category_id', $category);
            }

            $query->select(
                'tickets.id',
                'tickets.title',
                'tickets.fund_id',
                'tickets.status_id',
                'tickets.priority_id',
                'tickets.category_id',
                'fund.name as fund_name'
            )->leftJoin('fund', 'tickets.fund_id', '=', 'fund.fund_id');

            $result = $query->get();

            
            return response()->json($result);
        }

        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');


        return view('agency.agency-advisor.service-tickets.tickets', compact('contact_id', 'priorityDropdown', 'statusDropdown', 'categoryDropdown'));
    }


    public function create()
    {
        $contact_id = Contact::sessionContactId();

        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');

        $contactFunds = Fund::getSelectableForGrantRecommendation();

        return view('agency.agency-advisor.service-tickets.create_ticket', compact('statusDropdown', 'priorityDropdown', 'categoryDropdown', 'contactFunds', 'contact_id'));
        
    }

    public function store(Request $request)
    {
        $contact_id = Contact::sessionContactId();

        // Validate the form data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'fund_id' => 'required',
        ]);

        $validatedData['status_id'] = 1;
        $validatedData['created_by'] = $contact_id;
        $validatedData['assigned_to_user_id'] = $request->input('donor_id');
        $validatedData['fund_id'] = $request->input('fund_id');
        $validatedData['start_date'] = $request->input('start_date');
        $validatedData['end_date'] = $request->input('end_date');


        $ticket = Ticket::create($validatedData);

        return redirect('m/agency/service-tickets')->with('success', 'Ticket created successfully');
    }


    
    public function viewTicket($ticket_id) {
        // Fetch the ticket data using Eloquent
        $ticket = Ticket::find($ticket_id);

        $assignedToContact = Contact::find($ticket->assigned_to_user_id);
        // dd($assignedToContact);
        
        // You can pass the $ticket object to your view
        return view('agency.agency-advisor.service-tickets.view', ['ticket' => $ticket, 'assignedToContact' => $assignedToContact]);
    }


    public function ticketDelete(Request $request)
    {
        $data = $request->all();
        $ticketId = $data['ticketId'];

        $ticket = Ticket::find($ticketId);

        if ($ticket) {
            $ticket->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function ticketstatusUpdate(Request $request)
    {
        $data       = $request->all();
        $ticketId  = $data['ticketId'];
        Ticket::where('id', $ticketId)->update(['status_id' => 2]);
    } 

    public function editTicket($ticket_id) {
        
        $ticket = Ticket::find($ticket_id);
        $contact_id = Contact::sessionContactId();
        $contactFunds = Fund::getSelectableForGrantRecommendation();

        // dd($contactFunds);

        $statusDropdown = config('dropdown.status');
        $priorityDropdown = config('dropdown.priority');
        $categoryDropdown = config('dropdown.category');
        
        return view('agency.agency-advisor.service-tickets.edit_ticket', ['ticket' => $ticket, 'contactFunds' => $contactFunds, 'statusDropdown' => $statusDropdown, 'priorityDropdown' => $priorityDropdown, 'categoryDropdown' => $categoryDropdown]);
    }
    

}
