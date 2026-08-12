<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TicketAssignee extends Model
{
    // use SoftDeletes;

    public $table = 'ticket_assignee';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'ticket_id',
        'assigned_id',
        'assigned_by',
        'created_at',
        'updated_at',
        'active',
        'status' 
    ];

    public static function getAssignee($ticket_id) {

        return TicketAssignee::where('ticket_id', $ticket_id)->where('active','1')->first();
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'assigned_id');
    }
}
