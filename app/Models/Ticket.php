<?php
namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\GrantItem;  
use App\Models\Contact;
use App\Helpers\GConst;

$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);

class Ticket extends Model
{
    use SoftDeletes;

    public $table = 'tickets';

    protected $appends = [
        'attachments',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'description',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'priority',
        'category',
        'assigned_to',
        'created_by',
        'updated_by',
        'target_type',
        'target_id',
        'start_date',
        'end_date',
        'cart_id',
        'closed_on',
        'closed_by', 
    ];
    
    # Raise Case Ticket
    public function createTicket($task_type,$fund_id,$fund_recommendation_id,$subject,$notification,$receiver_id)
    {
        $contact_id = Contact::sessionContactId();
        $tasks = new Ticket();
        $tasks->target_type = GConst::CART_RECOMMENDATION_TICKET;
        $tasks->target_id = $fund_recommendation_id;
        $tasks->title = $subject;
        $tasks->description = $notification;
        $tasks->created_at =  Carbon::now();
        $tasks->start_date =  Carbon::now();
        $tasks->end_date =  null;
        $tasks->assigned_to = $receiver_id;
        $tasks->category = GConst::DEFAULT_TICKET_TYPE;
        $tasks->status = GConst::DEFAULT_TICKET_STATUS;
        $tasks->priority = GConst::DEFAULT_TICKET_PRIORITY;
        $tasks->created_by = $contact_id;
        $res = $tasks->save();
        if ($res) {
            $ticket_id = $tasks->id;
            return $ticket_id;
        } else {
            return null;
        }
    }

    public function comments() {

        return $this->hasMany(Comment::class, 'ticket_id', 'id');
    }

    public function primaryAssignee()
    {
        return $this->belongsTo(Contact::class, 'assigned_to');
    }

    public function secondaryAssignee()
    {
        return $this->hasOne(TicketAssignee::class, 'ticket_id');
    }

    public function getCategoryLabelAttribute()
    {
        return config('dropdown.category.' . $this->category);
    }

    public function getStatusLabelAttribute()
    {
        return config('dropdown.status.' . $this->status);
    }

    public function getPriorityLabelAttribute()
    {
        return config('dropdown.priority.' . $this->priority);
    }

    public function getWorkStatusLabelAttribute()
    {
        if ($this->secondaryAssignee && $this->secondaryAssignee->status) {
            return config('dropdown.support_staff_status.' . $this->secondaryAssignee->status);
        }

        return null;
    }

    public function getAttachmentsAttribute() {

        // return $this->getMedia('attachments');
    }

    public static function OpenTicketCount() {

        $contact_id   = Contact::sessionContactId();

        return self::where(function ($query) use ($contact_id) {
            $query->where('tickets.assigned_to', $contact_id)
                ->orWhere('tickets.created_by', $contact_id);
        })
        ->where('status', 'open')
        ->count();
    }

    public static function paginateMyTicket($limit, $ticket_search, $status, $priority, $category) {
        
        $contact = Contact::sessionContact();
        if (!$contact) return [];
        $contact_id = Contact::sessionContactId();

        $query = Ticket::query();

        $query->select('tickets.*', 'fund.name as fund_name')
            ->leftJoin('fund', 'tickets.target_id', '=', 'fund.fund_id')
            ->where(function ($query) use ($contact_id) {
                $query->where('tickets.assigned_to', $contact_id)
                      ->orWhere('tickets.created_by', $contact_id);
            })
            ->orderBy('tickets.id', 'DESC');

        if ($ticket_search !== null) {
            $query->where(function ($query) use ($ticket_search) {
                $query->where('tickets.id', 'like', "%$ticket_search%")
                      ->orWhere('tickets.title', 'like', "%$ticket_search%")
                      ->orWhere('tickets.description', 'like', "%$ticket_search%");
            });
        }

        if ($status) {
            $query->where('tickets.status', $status);
        }
        if ($priority) {
            $query->where('tickets.priority', $priority);
        }
        if ($category) {
            $query->where('tickets.category', $category);
        }
        
        $result = $query->paginate($limit);

        return $result;
    }
    public static function myTicket($ticket_search, $status, $priority, $category) {
        $contact = Contact::sessionContact();
        if (!$contact) return [];
        $contact_id = Contact::sessionContactId();
    
        $query = Ticket::query();

            $query->select('tickets.*', 'fund.name as fund_name')
                ->leftJoin('fund', 'tickets.target_id', '=', 'fund.fund_id')
                ->where(function ($query) use ($contact_id) {
                $query->where('tickets.assigned_to', $contact_id)
                    ->orWhere('tickets.created_by', $contact_id);
                })
            ->orderBy('tickets.id', 'DESC');

            if ($ticket_search !== null) {
                $query->where(function ($query) use ($ticket_search) {
                    $query->where('tickets.id', 'like', "%$ticket_search%")
                          ->orWhere('tickets.title', 'like', "%$ticket_search%")
                          ->orWhere('tickets.description', 'like', "%$ticket_search%");
                });
            }
            if ($status) {
                $query->where('tickets.status', $status);
            }
            if ($priority) {
                $query->where('tickets.priority', $priority);
            }
            if ($category) {
                $query->where('tickets.category', $category);
            }
            $result = $query->count();
            
        return $result;
    }


    public static function supportStaffTicket($limit, $ticket_search, $status, $priority, $category) {
        
        $contact = Contact::sessionContact();
        if (!$contact) return [];
        $contact_id = Contact::sessionContactId();
        //print_r($contact_id);die;
        $query = Ticket::query();

        $query->select('tickets.*', 'fund.name as fund_name','ticket_assignee.assigned_id AS secondary_assignee_id','ticket_assignee.assigned_by AS secondary_assigned_by','ticket_assignee.created_at AS secondary_created_at','ticket_assignee.status AS secondary_status')
            ->leftJoin('fund', 'tickets.target_id', '=', 'fund.fund_id')
            ->leftJoin('ticket_assignee', 'ticket_assignee.ticket_id', '=', 'tickets.id')
            ->where(function ($query) use ($contact_id) {
                $query->where('ticket_assignee.assigned_id', $contact_id)
                      ->where('ticket_assignee.active', 1);
            })
            ->orderBy('tickets.id', 'DESC');

        if ($ticket_search !== null) {
            $query->where(function ($query) use ($ticket_search) {
                $query->where('tickets.id', 'like', "%$ticket_search%")
                      ->orWhere('tickets.title', 'like', "%$ticket_search%")
                      ->orWhere('tickets.description', 'like', "%$ticket_search%");
            });
        }

        if ($status) {
            $query->where('tickets.status', $status);
        }
        if ($priority) {
            $query->where('tickets.priority', $priority);
        }
        if ($category) {
            $query->where('tickets.category', $category);
        }
        
        $result = $query->paginate($limit);

        return $result;
    }

    public static function checkTicketRecommendationWise($fund_recommendation_id) {

        return self::where('tickets.target_id', $fund_recommendation_id)
        ->get();
    }
     
    public static function getTicketCountStatusWise() {

        $contact_id = Contact::sessionContactId();

        $result = self::query()
            ->select('status', DB::raw('count(*) as total'))
            ->where('assigned_to', $contact_id)
            ->groupBy('status')
            ->get();

        return $result;
    }

    
    public static function getDashboardTickets()
{
    /* --------------------------------------------
    | Base Query (Eloquent Only)
    -------------------------------------------- */
    $contactId = Contact::sessionContactId();
    
    $tickets = Ticket::query()
        ->whereNull('deleted_at')
        ->whereIn('target_type', [
            'cart recommendation',
            'grant recommendation',
            'advisor registration',
        ])
        ->where(function ($query) use ($contactId) {
            $query->where('assigned_to', $contactId)
                ->orWhere('created_by', $contactId);
        })
        ->get();
        
    // $tickets = Ticket::query()
    //     ->whereNull('deleted_at')
    //     ->whereIn('target_type', [
    //         'cart recommendation',
    //         'grant recommendation',
    //         'advisor registration',
    //     ])
    //     ->get();

    /* --------------------------------------------
    | Total Tickets Count
    -------------------------------------------- */
    $totalTickets = $tickets->count();

    /* --------------------------------------------
    | Status Priority Map
    -------------------------------------------- */
    $statusPriority = [
        'open'        => 1,
        'in progress' => 2,
        'hold'        => 3,
        'closed'      => 4,
    ];

    /* --------------------------------------------
    | Sorted Top 5 Tickets (Status → Date)
    -------------------------------------------- */
    $sortedTickets = $tickets
        ->sortBy(function ($ticket) use ($statusPriority) {
            return $statusPriority[$ticket->status] ?? 99;
        })
        ->sortByDesc('created_at')
        ->take(5)
        ->values();

    /* --------------------------------------------
    | Status-wise Totals (Default 0)
    -------------------------------------------- */
    $statusWiseTotals = [];

    foreach ($statusPriority as $status => $order) {
        $statusWiseTotals[] = [
            'status'      => $status,
            'status_name' => config('dropdown.status')[$status] ?? ucfirst($status),
            'total'       => $tickets->where('status', $status)->count(),
        ];
    }

    return [
        'tickets'            => $sortedTickets,      // Top 5 tickets
        'total_tickets'      => $totalTickets,       // Overall count
        'status_wise_totals' => $statusWiseTotals,   // Open / In progress / Hold / Closed
    ];
}

}
