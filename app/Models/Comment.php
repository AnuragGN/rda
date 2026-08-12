<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'comment',
        'created_at',
        'updated_at',
        'ticket_id',
        'assigned_to',
        'created_by',
        'private',
    ];

    public function ticket() {

        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    static public function getTicketComment($ticketId, $contactId, $type) {
        
        $contact_id = Contact::sessionContactId();
        $comments = Comment::select(
            'comments.id',
            'comments.comment', 
            'comments.created_at', 
            'comments.updated_at', 
            'comments.ticket_id', 
            'comments.assigned_to', 
            'comments.created_by',
            'comments.private',
        )
        ->where('comments.ticket_id', $ticketId)
        ->orderBy('comments.created_at', 'ASC')
        
        ->when($type == '', function ($query) use ($contact_id) {
            $query->where(function ($query) use ($contact_id) {
                $query->where('comments.private', 0)
                    ->orWhere(function ($query) use ($contact_id) {
                        $query->where('comments.private', 1)
                            ->where('comments.created_by', $contact_id);
                    });
            });
        })
        ->when($type == '1', function ($query) use ($contact_id) {
            $query->where(function ($query) use ($contact_id) {
                $query->where('comments.private', 2)
                    ->orWhere(function ($query) use ($contact_id) {
                        $query->where('comments.private', 1)
                            ->where('comments.created_by', $contact_id);
                    });
            });
        })
        ->when($type == '0', function ($query) use ($type) {
            $query->where('comments.private', $type);
        })
        ->get();

        return $comments;
    }
}
