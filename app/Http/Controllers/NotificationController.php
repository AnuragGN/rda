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
#use App\Models\TicketNotification;
use App\Models\BellNotification;
use App\Models\Notification;
use App\Models\ContactFund;
use App\Models\ContactType;
use App\Models\ContactTypeContact;
use Carbon\Carbon;
use App\Models\GrantItem;
use App\Models\Contact;
use App\Helpers\GnUtils;
use App\Helpers\GConst;
$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);  
use App\Models\Fund;

class NotificationController extends Controller
{
    /**
     * Send Notification
     */
    public function getNotificationList() {

        $objNotification = new BellNotification();
        $contact_id   = Contact::sessionContactId();

        $recipient = $objNotification->getNotificationTo($contact_id);
        $notification_cirlces = $objNotification->getNotificationCircle($recipient);

        $notifications = BellNotification::select('bell_notifications.*', 'contact.first_name as sender_first_name', 'contact.last_name as sender_last_name')
            ->leftJoin('contact', 'contact.contact_id', '=', 'bell_notifications.from')
            ->whereRaw("CONCAT(',', bell_notifications.read_user_ids, ',') NOT LIKE '%,$contact_id,%'")
            ->where(function ($query) use ($notification_cirlces) { 
                foreach ($notification_cirlces as $notification_to) {
                    $query->orWhereJsonContains('to', $notification_to);
                }
            })
            ->where('bell_notifications.from', '!=', $contact_id)
            ->orderBy('bell_notifications.id', 'DESC')
            ->get();

        $data = [
            'notifications' => $notifications,
            'user_role' => GnUtils::getUserRole()
        ];
        return response()->json($data);
    }

    /**
     * Send Manual Notification
     */

    public function sendManualNotification(Request $request) {

        if ($request->isMethod('post')) {   

            $data = $request->all();
            $notification = $data['notification'];
            $notification_sent_to = $data['notification_sent_to'];

            $contactId = Contact::sessionContactId();
            $fundIds = ContactFund::getFundIdsForViewByContactId($contactId);

            $objNotification = new BellNotification();

            $recipient = array();
            foreach ($notification_sent_to as $key => $sent_to) 
            {
                if($sent_to == GConst::CIRCLE_DONOR){

                    $query = ContactFund::select('contact_fund.*')
                    ->leftJoin('contact', 'contact_fund.contact_id', '=', 'contact.contact_id')
                    ->where('contact_fund.contact_type_id', 10)
                    ->whereIn('contact_fund.fund_id', $fundIds)
                    ->distinct('contact_fund.contact_id');

                    $results = $query->get();
                    foreach ($results as $key => $value) {
                        $recipient[] = $objNotification->getNotificationTo($value->contact_id);
                    }
                }
                if($sent_to == GConst::CIRCLE_ADVISOR){

                    # Get Contact Type Id
                    $contactTypes = ContactType::getContactTypeId(ContactType::ROLE_AGENCY);
                    $contactTypeId = @$contactTypes->contact_type_id;

                    if($contactTypeId != '')
                    {
                        $results = ContactTypeContact::where('contact_type_id', $contactTypeId)
                        ->where('contact_id','!=', $contactId)
                        ->pluck('contact_id');
                        foreach ($results as $key => $value) {
                            $recipient[] = $objNotification->getNotificationTo($value);
                        }
                    }
                }
                if($sent_to == GConst::CIRCLE_STAFF){

                    # Get Contact Type Id
                    $contactTypes = ContactType::getContactTypeId(ContactType::ROLE_SUPPORT_STAFF);
                    $contactTypeId = @$contactTypes->contact_type_id;

                    if($contactTypeId != '')
                    {
                        $results = ContactTypeContact::where('contact_type_id', $contactTypeId)
                        ->where('contact_id','!=', $contactId)
                        ->pluck('contact_id');
                        foreach ($results as $key => $value) {
                            $recipient[] = $objNotification->getNotificationTo($value);
                        }
                    }
                }
            }

            if(count($recipient) > 0) {

                $notification_to_json = json_encode($recipient);
                
                $category = 'manual';
                $target_type = 'notifications';
                $target_id = '0';
                $objNotification->sendNotification($notification_to_json,$notification,$category,$target_type,$target_id);
                $arr = array('status'=>'success','msg'=>'Notification sent successfully!','color'=>'green');
            }else {
                $arr = array('status'=>'error','msg'=>'Recipients not found. Please check the recipient list and try again.','color'=>'red'); 
            }
            echo json_encode($arr);

        } else {

            GnUtils::addBreadcrumb('Notifications', route('agency-notifications'));
            GnUtils::addBreadcrumb('Send Notification');
            return view('agency.agency-advisor.notification.send-notification');
        }
    }

    /**
     * Notification Mark As Read
     */
    
    public function notificationMarkAsRead(Request $request) {

        $params = $request->all();
        $notification_id = $params['notification_id'];
        $target_id = $params['ticket_id'];
        $contact_id = Contact::sessionContactId();

        $flag = 0;
        if($notification_id > 0) {

            $flag = 1;
            $notifications = BellNotification::where(['id' => $notification_id])
                ->orderBy('id', 'DESC')->get();
        }
        if($target_id > 0) {
            
            $flag = 1;
            $notifications = BellNotification::where(['target_id' => $target_id])
                ->orderBy('id', 'DESC')->get();
        }

        if($flag == 1)
        {
            foreach ($notifications as $key => $value) 
            {
                $noti = BellNotification::findOrFail($value->id);

                $read_user_ids = $value->read_user_ids;
                $read_at = $value->read_at;

                $read_at_arr = json_decode($read_at, true);
                
                if ($read_user_ids == '') 
                {
                    $read_by = $contact_id;
                    $data = [['contact_id'=>$contact_id,'read_at'=>Carbon::now()->toDateTimeString()]];
                    $json_read_at = json_encode($data);
                } 
                else 
                {
                    $read_user_id_arr = explode(',', $read_user_ids);
                    if (in_array($contact_id, $read_user_id_arr)) 
                    {
                        $read_by = $read_user_ids;
                        $json_read_at = $read_at;
                    } 
                    else 
                    {
                        $read_by = $read_user_ids . ',' . $contact_id;
                        $read_at_arr[] = ['contact_id'=>$contact_id,'read_at'=>Carbon::now()->toDateTimeString()];
                        $json_read_at = json_encode($read_at_arr);
                    }
                }

                $noti->read_user_ids = $read_by;
                $noti->read_at = $json_read_at;
                $noti->save();
            }
        }
    }

    /** Notification List Views
    */

    public function advisorNotifications()
    {
        GnUtils::addBreadcrumb('Notifications');
        $contact = Contact::sessionContact();

        $limit = 10;
        $notifications = BellNotification::paginateMyNotification($limit);

        return view('agency.agency-advisor.notification.index', [
            'notifications' => $notifications,
            'contact_id' => $contact->contact_id,
        ]);
    }
   
    /**
     * Support Staff Notifications
     */

    public function supportStaffNotifications()
    {
        GnUtils::addBreadcrumb('Notifications');
        $contact = Contact::sessionContact();

        return view('support_staff.notification.index');
    }

    /**
     * Support Staff Notifications List Ajax
     */

    public function supportStaffNotificationsListAjax(Request $request) {
        
        $contact_id   = Contact::sessionContactId();
        $limit = 10;
        $notifications = BellNotification::paginateMyNotification($limit);

        $totalNotification = BellNotification::MyNotification();

        $html = '';
        if (count($notifications) || $request->page == 1) {
            $html = view('support_staff.notification.notification-list', compact('notifications','contact_id'))->render();
        }
        return [
            'more' => ($totalNotification) > $limit  *  $request->page ? 1 : 0,
            'html' => $html,
            'totalNotification' => $totalNotification,
            'totalLimit' => $limit  *  $request->page
        ];
    }

    /**
     * Donor Notifications
     */

    public function donorNotifications()
    {
        GnUtils::addBreadcrumb('Notifications');
        $contact = Contact::sessionContact();

        return view('donor.notification.index');
    }

    /**
     * Donor Notifications List Ajax
     */

    public function donorNotificationsListAjax(Request $request) {
        
        $contact_id   = Contact::sessionContactId();
        $limit = 10;
        $notifications = BellNotification::paginateMyNotification($limit);

        $totalNotification = BellNotification::MyNotification();

        $html = '';
        if (count($notifications) || $request->page == 1) {
            $html = view('donor.notification.notification-list', compact('notifications','contact_id'))->render();
        }
        return [
            'more' => ($totalNotification) > $limit  *  $request->page ? 1 : 0,
            'html' => $html,
            'totalNotification' => $totalNotification,
            'totalLimit' => $limit  *  $request->page
        ];
    }

    /**
     * Get Bottom Notification
     */

    public function getBottomNotification() {

        $objNotification = new BellNotification();
        $contact_id   = Contact::sessionContactId();

        $lastNotification = BellNotification::select('bell_notifications.*')
        ->latest('bell_notifications.created_at') 
        ->first();

        if($lastNotification != '') {

            $recipient = $objNotification->getNotificationTo($contact_id);
            $notification_cirlces = $objNotification->getNotificationCircle($recipient);
            #print_r($notification_cirlces);

            $notifications = BellNotification::select('bell_notifications.*', 'contact.first_name as sender_first_name', 'contact.last_name as sender_last_name')
                ->leftJoin('contact', 'contact.contact_id', '=', 'bell_notifications.from')
                ->whereRaw("CONCAT(',', bell_notifications.read_user_ids, ',') NOT LIKE '%,$contact_id,%'")
                ->where(function ($query) use ($notification_cirlces) { 
                    foreach ($notification_cirlces as $notification_to) {
                        $query->orWhereJsonContains('to', $notification_to);
                    }
                })
                ->where('bell_notifications.id', '=', $lastNotification->id)
                ->where('bell_notifications.from', '!=', $contact_id)
                ->orderBy('bell_notifications.id', 'DESC')
                ->first();
        }
        else
        {
            $notifications = null;
        }       
        $data = [
            'notifications' => $notifications,
            'user_role' => GnUtils::getUserRole()
        ];
        return response()->json($data);
    }
}