<?php
/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 11/27/2023
 * Time: 04:39 PM
 */

namespace App\Helpers;
use App\Events\NotificationEvent;
use App\Models\BellNotification;
#use App\Models\TicketNotification;
#use App\Models\Notification;
use App\Models\Task;
use App\Models\Ticket;
use Carbon\Carbon;
use App\Models\GrantItem;  
use App\Models\Contact;
use App\Helpers\GConst;

$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);
use App\Models\Fund;


class PushNotification {

    /*public function sendNotification($to,$notification,$category,$target_type,$target_id) {

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
    }*/

    /*public function createTicket($task_type,$fund_id,$fund_recommendation_id,$subject,$notification,$receiver_id)
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
        $tasks->category = 'raise cash';
        $tasks->status = 'open';
        $tasks->priority = 'high';
        $tasks->created_by = $contact_id;
        $res = $tasks->save();
        if ($res) {
            $ticket_id = $tasks->id;
            return $ticket_id;
        } else {
            return null;
        }
    }*/
}
