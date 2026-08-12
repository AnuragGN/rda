<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Events\NotificationEvent;
use App\Models\ContactTypeContact;
use App\Helpers\GConst;

$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);

class BellNotification extends Model
{
    protected $table = 'bell_notifications';

    
    protected $fillable = ['from', 'to', 'notification','read_user_ids','category','target_type','target_id'];


    public function sendNotification($to,$notification,$category,$target_type,$target_id) {

        $contact_id = Contact::sessionContactId();
        event(new NotificationEvent($notification));

        $model = new BellNotification();
        $model->from = $contact_id;
        $model->to = $to;
        $model->notification = $notification;
        $model->category = $category;
        $model->target_type = $target_type;
        $model->target_id = $target_id;
        $model->read_user_ids = '';
        $model->read_at = '{}';
        $res = $model->save();     
    }

    public function getNotificationTo($contactId) {

        if(isset($contactId)){
            
            $contactType = ContactTypeContact::where('contact_id', $contactId)->get();
            
            if($contactType[0]['contact_type_id'] == 10) {

                return $to_circle = GConst::CIRCLE_DONOR.'_'.$contactId;
            }
            if($contactType[0]['contact_type_id'] == 18) {

                return $to_circle = GConst::CIRCLE_ADVISOR.'_'.$contactId;
            }
            if($contactType[0]['contact_type_id'] == 19) {

                return $to_circle = GConst::CIRCLE_STAFF.'_'.$contactId;
            }
        }   
    }
    public function getNotificationCircle($recipient) {

        $circle = '';
        if(isset($recipient)){
            
            $recipient_arr = explode('_',$recipient);
            if($recipient_arr[0] == 'donor'){

                $circle = [$recipient, "donor", "public"];
            }

            if($recipient_arr[0] == 'advisor'){
                
                $circle = [$recipient, "advisor", "public"];
            }

            if($recipient_arr[0] == 'staff'){
                
                $circle = [$recipient, "staff", "public"];
            }
        } 
        return $circle;  
    }

    public static function paginateMyNotification($limit) {
        
        $contact_id   = Contact::sessionContactId();

        $objNotification = new BellNotification();
        $recipient = $objNotification->getNotificationTo($contact_id);
        $notification_cirlces = $objNotification->getNotificationCircle($recipient);

        $notifications = BellNotification::select('bell_notifications.*', 'contact.first_name as sender_first_name', 'contact.last_name as sender_last_name')
            ->leftJoin('contact', 'contact.contact_id', '=', 'bell_notifications.from')
            ->where(function ($query) use ($notification_cirlces) {
                foreach ($notification_cirlces as $notification_to) {
                    $query->orWhereJsonContains('to', $notification_to);
                }
            })
            ->orderBy('bell_notifications.id', 'DESC');
        
        $result = $notifications->paginate($limit)->withQueryString();
        return $result;
    }

    public static function MyNotification() {

        $contact_id   = Contact::sessionContactId();

        $objNotification = new BellNotification();
        $recipient = $objNotification->getNotificationTo($contact_id);
        $notification_cirlces = $objNotification->getNotificationCircle($recipient);
        
        $notifications = BellNotification::select('bell_notifications.*', 'contact.first_name as sender_first_name', 'contact.last_name as sender_last_name')
            ->leftJoin('contact', 'contact.contact_id', '=', 'bell_notifications.from')
            ->where(function ($query) use ($notification_cirlces) {
                foreach ($notification_cirlces as $notification_to) {
                    $query->orWhereJsonContains('to', $notification_to);
                }
            })
            ->orderBy('bell_notifications.id', 'DESC');
        
        $result = $notifications->count();
        return $result;
    }
}
