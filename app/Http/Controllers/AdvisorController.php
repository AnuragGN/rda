<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/18/2023
 * Time: 3:36 PM
 */

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactFund;
use App\Models\Fund;
use Illuminate\Http\Request;

class AdvisorController extends Controller
{

    public function fundAdvisors(Request $request)
    {
        $items = [];
        $contactId = Contact::sessionContactId();
        $fundIds = ContactFund::getViewableFundIdsByContactId($contactId);

        foreach($fundIds as $fundId) {
            // get contacts related to the fund
            $models = ContactFund::getByFundId($fundId);
            $fundName = Fund::getNameById($fundId);
            foreach($models as $model) {
                $item = [];
                $contact = Contact::getByContactId($model->contact_id);
                $item['contact_name'] = $contact->name;
                $item['contact_email'] = $contact->email_address;
                $item['fund_name'] = $fundName;
                $item['viewable'] = $model->viewable == 'Y';
                $item['make_grant'] = $model->make_grant_recommendation == 'Y';
                $item['relationship'] = $model->role;
                $items[$fundName][] = $item;
            }
        }

        return view("donor.advisor.fund-relationship", compact('items'));
        // return $data;
    }

}