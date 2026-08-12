<?php

/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 06-10-2023
 * Time: 10:07
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

#use App\Events\MessageSent;
use App\Events\NotificationEvent;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\GrantItem;
use App\Models\Contact;
$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);
use App\Models\Fund;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where(['is_read' => 'N'])
            ->orderBy('id', 'DESC')->get();
        return response()->json($notifications);
    }

    public function getNotificationList()
    {
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
            'contact.last_name as sender_last_name',
            'fund.name as fund_name',
        )
            ->leftJoin('contact', 'contact.contact_id', '=', 'push_notifications.sender_id')
            ->leftJoin('recom_cart_details', 'recom_cart_details.cart_id', '=', 'push_notifications.cart_id')
            ->leftJoin('fund', 'fund.fund_id', '=', 'recom_cart_details.fund_id')
            ->where([
                'push_notifications.is_read' => 'N',
                'push_notifications.receiver_id' => $contact_id
            ])
            ->orderBy('push_notifications.id', 'DESC')
            ->get();
        return response()->json($notifications);
    }

    public function sendNotification(Request $request)
    {
        $params       = $request->all();
        $cart_id      = $params['cart_id'];
        $notification = $params['notification'];

        $contact_id   = Contact::sessionContactId();
        $cart_arr     = GrantItem::getById($cart_id);
       
        event(new NotificationEvent($notification));

        $model = new Notification();
        $model->cart_id         = $cart_id;
        $model->fund_id         = $cart_arr['fund_id'];
        $model->sender_id       = $contact_id;
        $model->receiver_id     = $cart_arr['contact_id'];
        $model->notification    = $notification;
        $model->notification_on =  Carbon::now();
        $model->is_read         = 'N';
        $res = $model->save();
        if($res){
            return redirect('m/agency/cart')->with('success', 'Notification Sent Successfully!');
        }
        else{
            return redirect('m/agency/cart')->with('danger', 'Notification fail to sent!');
        }
        //return 'Notification Sent Successfully!';
    }

    public function notificationMarkAsRead(Request $request)
    {
        $params = $request->all();
        $notification_id = $params['notification_id'];

        $noti = Notification::findOrFail($notification_id);

        $noti->is_read    = 'Y';
        $noti->updated_at =  Carbon::now();

        $noti->save();
    }

    public function notificationLogs(Request $request)
    {
        $params  = $request->all();
        $cart_id = $params['cart_id'];

        $notifications = Notification::where(['cart_id' => $cart_id])
            ->orderBy('id', 'DESC')->get();
        return response()->json($notifications);
    }

    public function myNotification()
    {
        $interestBasedArticles = true;  
        $contact = Contact::sessionContact();
        $pendingGrants = [];

        return view('donor.notification.index', compact('contact', 'interestBasedArticles', 'pendingGrants'));
    }

    public function myNotificationAjax(Request $request)
    {
        $limit         = 10;
        $notifications = Notification::paginateMyNotification($limit);
        $html = '';
        if (count($notifications) || $request->page == 1) {
            $html = view('donor.notification.notification-list', compact('notifications'))->render();
        }
        return [
            'more' => (count($notifications) < $limit) ? 0 : 1,
            'html' => $html
        ];
    }
}