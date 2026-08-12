<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    protected $table = 'push_notifications';

    
    protected $fillable = ['cart_id','fund_id','sender_id', 'receiver_id', 'notification', 'notification_on', 
    'is_read'];

    static public function paginateMyNotification($limit)
    {
        $contact = Contact::sessionContact();
        if (!$contact) return [];
        $contact_id   = Contact::sessionContactId();
        
        $notifications = Notification::select(
            'push_notifications.id',
            'push_notifications.sender_id',
            'push_notifications.receiver_id',
            'push_notifications.notification',
            'push_notifications.notification_on',
            'push_notifications.is_read',
            'push_notifications.updated_at',
            'push_notifications.created_at',
            'push_notifications.cart_id',
            'contact.first_name as sender_first_name',
            'contact.last_name as sender_last_name'
        )
            ->leftJoin('contact', 'contact.contact_id', '=', 'push_notifications.sender_id')
            ->where([
                'push_notifications.receiver_id' => $contact_id
            ])
            ->orderBy('push_notifications.id', 'DESC')
            ->paginate($limit);

        return $notifications;
    }
}
