<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 11-11-2019
 * Time: 11:46
 */

namespace App\Http\Controllers\Donor;


use App\Helpers\Data;
use App\Helpers\GConst;
#use App\Helpers\PushNotification;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\ContactFund;
use App\Models\Email;
use App\Models\Fund;
use App\Models\FundRecommendation;
use App\Models\FundRepType;
use App\Models\GrantForm;
use App\Models\GrantHistory;
use App\Models\GrantItem;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use App\Models\Organization;
use App\Models\OrgNeedApp;
use App\Models\ContactType;
use App\Models\Ticket;
use App\Models\BellNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/*
 * Grants are saved in MyCart, GrantItem is MyCart item
 */
class GrantController extends Controller
{

    public function myCart()
    {
        // log activity - list grant recommendations
        $activity = new LogActivity(LogActivity::NAME_GRANT, LogActivity::ACTION_LIST);
        $activity->description('cart')->add();

        GnUtils::addBreadcrumb(GrantForm::cartLabel());

        $models = GrantItem::myCartItems();
        return view('donor.cart.cart', compact('models'));
    }

    public function apiMyCart()
    {
        $models = GrantItem::myCartItems();
        return $models;
    }

    private function checkOutConfirmed() {

    }

    /**
     * FOR_JCF: Temporary object for Marjory Kaplan Foundation
     *
     * @param $mkf
     * @return GrantItem|null
     */
    private function getMkfGrantItem($mkf) {

        if (!isset($mkf) || !is_array($mkf) || !isset($mkf['fund_id']) || !isset($mkf['amount'])) {
            return null;
        }
        // session user
        $contact = Contact::sessionContact();

        $model = new GrantItem();
        $model->cart_id = null;
        $model->fund_id = $mkf['fund_id'];
        $model->contact_id = $contact->contact_id;
        $model->organization_id = 5746; // Marjory Kaplan Foundation OrgId
        $model->amount = $mkf['amount'];
        $model->grant_purpose = isset($mkf['grant_purpose']) ? $mkf['grant_purpose'] : null;
        $model->notes = isset($mkf['notes']) ? $mkf['notes'] : null;
        $model->frequency = Data::GRANTING_FREQUENCY_ONCE;
        $model->anonymous = 'N';
        $model->status = GrantItem::STATUS_CREATED;

        return $model;
    }

    /*+
     * checkout process
     */
    public function checkout(Request $request, $confirmed=null)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        GnUtils::addBreadcrumb(GrantForm::cartLabel(), route('my-cart'));
        GnUtils::addBreadcrumb('Checkout');

        $params = $request->all();

        $ids = isset($params['selected']) ? $params['selected'] : [];
        if (!count($ids)) {
            return "Data not found";
        }

        /** @var Collection $models */
        $models = GrantItem::whereIn('cart_id', $ids)->get();
        if (!count($models)) {
            return "Data not found";
        }

        // FOR_JCF: MKF handling
        if (ClientInfo::isJCF()) {
            $mkf = $request->input('mkf');
            $mkfGrant = $this->getMkfGrantItem($mkf);
            if ($mkfGrant) $models->push($mkfGrant);
        }

        if ($confirmed && $confirmed == 1) {
            // log activity - checkout
            $activity = new LogActivity(LogActivity::NAME_GRANT, LogActivity::ACTION_CONFIRM);
            $activity->data($request->all())->add();

            $data = FundRecommendation::saveFromCart($models);

            $models = $data['grants'];
            $models1 = $data['recommendations'];

            Email::grantRecommendation($models);
            
            # Raise Cash Ticket & Bell Notifications
            foreach($models1 as $model) 
            { 
                $amount = $model->amount;
                #$cart_id = $model->cart_id;
                $fund_recommendation_id = $model->fund_recommendation_id;
                
                $fund_id = $model->fund_id;
                $organization_id = $model->organization_id;

                $fundData = Fund::getFundById($fund_id);
                $balance = $fundData->balance;
                $fundName = $fundData->name;
                $orgName = Organization::getById($organization_id)->name;

                if($amount > $balance){

                    $shortageAmount = $amount - $balance;
                    $shortageAmount = GnUtils::money($shortageAmount);
                    $balance = GnUtils::money($balance);
                    $amount = GnUtils::money($amount);
                    
                    # Get Contact Type Id
                    $contactTypes = ContactType::getContactTypeId(ContactType::ROLE_AGENCY);
                    $contactTypeId = @$contactTypes->contact_type_id;

                    $to = '';
                    if($contactTypeId != '')
                    {
                        $contactFunds = ContactFund::where('fund_id', $fund_id)
                        ->where('contact_type_id', $contactTypeId)
                        ->pluck('contact_id')
                        ->implode(',');
                        $to = $contactFunds;
                    }
                    
                    $message = 'Grant recommendation details are given below - <br> <b>Fund balance:</b> '.$balance.'<br> <b>Fund Recommendation:</b>'.$amount.'<br><b>Fund Name:</b>'.$fundName.'<br><b>Organization:</b>'.$orgName;

                    $task_type = 'Raise Cash';  
                    $subject = $fundName.' Raise Cash '.$amount;    

                    $objTicket = new Ticket(); 
                    $ticket_id = $objTicket->createTicket($task_type,$fund_id,$fund_recommendation_id,$subject,$message,$to);

                    $category       = 'raise-cash';
                    $target_type    = 'ticket';
                    $target_id      = $ticket_id;

                    $notification = 'New Ticket Assigned with Ticket ID #'.$ticket_id;

                    $objNotification = new BellNotification(); 
                    $recipient = $objNotification->getNotificationTo($to);
                    if(empty($recipient))
                    {
                        $recipient = GConst::CIRCLE_ADVISOR;
                    }
                    $notification_to = [$recipient];
                    $notification_to_json = json_encode($notification_to);

                    $objNotification->sendNotification($notification_to_json,$notification,$category,$target_type,$target_id);
                }
            }
            # End
        }

        $total = 0;
        foreach($models as $model) {
            $total += $model->amount;
        }

        $total = GnUtils::money($total);
        return view('donor.cart.preview', compact('models', 'total', 'confirmed'));
    }

    /*
     *
     */
    public function create(Request $request)
    {
        // log activity - create a new grant
        $activity = new LogActivity(LogActivity::NAME_GRANT, LogActivity::ACTION_CREATE);
        $activity->data($request->all())->add();

        GnUtils::addBreadcrumb('Make a Grant');

        // get funds
        $funds = Fund::getSelectableForGrantRecommendation();
        if (!count($funds)) {
            return redirect()->back()->with('danger', 'You must have a Fund to make a grant recommendation.');
        }

        // main grant model
        $model = new GrantItem();
        $model->purpose_type = 'general';
        $model->contact_id = Contact::sessionContactId();

        $fundId = null;
        if ($request->filled('fund_id')){
            $fundId = $request->input('fund_id');
            $model->fund_id = $fundId; // 'Jaco1';
        }

        if ($request->filled('org_id')) {
            $model->organization_id = $request->input('org_id');
        }

        if ($request->filled('org_need_app_id')) {
            /** @var OrgNeedApp $program */
            $program = OrgNeedApp::getById($request->input('org_need_app_id'));
            if ($program) {
                $model->organization_id = $program->organization_id;
                if ($program->title) {
                    $model->purpose_type = GrantItem::GRANT_TYPE_SPECIAL;
                    $model->grant_purpose = 'For ' . $program->title;
                }
            }
        }

        if ($request->filled('repeat')){
            // fund_grant_history_id
            $fundGrantHistoryId = $request->input('repeat');
            $grant = GrantHistory::getById($fundGrantHistoryId);
            if ($grant) {
                $model->fund_id = $grant->fund_id;
                $model->amount = $grant->amount;

                $orgId = $grant->organization_id;
                $allowed = Organization::where([
                    'organization_id' => $orgId,
                    'allow_recommendation' => 'Y'
                ])->exists();

                if ($allowed) $model->organization_id = $orgId;

                // $model->anonymous = 'on';
                // $model->grant_purpose = 'pppo';
                // $model->note = 'nno';
            }
        }

        return view('donor.grants.make-a-grant', compact('model', 'funds'));
    }

    /*
     *
     */
    public function edit(Request $request, $id=null)
    {
        GnUtils::addBreadcrumb(GrantForm::cartLabel(), route('my-cart'));
        GnUtils::addBreadcrumb('Edit');

        /** @var GrantItem $model */
        $model = GrantItem::getById($id);
        if (!$model) abort(404);
        $model->anonymous = ($model->anonymous == 'Y' ? 'on' : null);
        $model->notify = $model->notification_info ? 'on' : null;

        $model->contact_name = $model->org_contact;
        $model->phone = $model->org_phone;
        $model->email = $model->org_email;


        // get funds
        $funds = Fund::getSelectableForGrantRecommendation();
        if (!count($funds)) {
            return redirect()->back()->with('danger', 'You must have a Fund to edit a grant recommendation');
        }

        // log activity - edit a grant recommendation
        $activity = new LogActivity(LogActivity::NAME_GRANT, LogActivity::ACTION_EDIT);
        $activity->onModel($model)->add();

        return view('donor.grants.make-a-grant', compact('model', 'funds'));
    }

    /*
     *
     */
    private function validateAndSave(Request $request)
    {
        $validator = [
            'fund_id' => 'required|min:1',
            'contact_id' => 'required|min:1',
            // 'organization_id' => 'required|min:1',
            'organization_id' => '',
            'amount' => 'numeric|min:100',
            'grant_purpose' => 'max:1000',
            'grant_dedication' => 'max:1000',
            'notes' => 'max:1000',
            'anonymous' => 'max:32',
        ];

        // set minimum grant amount
        $minGrantAmount = ClientConfig::value('MIN_GRANT_AMOUNT');
        $amount = 'numeric|min:' . $minGrantAmount;
        $validator['amount'] = $amount;

        $this->validate($request, $validator);

        $id = $request->input('cart_id');
        $model = null;
        if ($id) $model = GrantItem::find($id);
        if (!$model) $model = new GrantItem();

        $orgId = $request->input('organization_id');

        $model->fund_id = $request->input('fund_id');
        $model->contact_id = $request->input('contact_id');
        $model->organization_id = $orgId;

        if (!$orgId && !$model->cart_id) {
            $model->organization_name = $request->input('organization_name');
            $model->org_contact = $request->input('contact_name');
            $model->org_address1 = $request->input('address_one');
            $model->org_address2 = $request->input('address_two');
            $model->org_city = $request->input('city');
            $model->org_state = $request->input('state');
            $model->org_country = $request->input('country');
            $model->org_zip = $request->input('zip');
            $model->org_phone = $request->input('phone');
            $model->org_ein = $request->input('ein');
            $model->org_email = $request->input('email');
        }

        $model->frequency = Data::getGrantingFrequency($request->frequency);
        $model->amount = $request->input('amount');
        $model->purpose_type = $request->input('purpose_type');
        $model->grant_purpose = $request->input('grant_purpose');
        $model->dedication_type = $request->input('dedication_type');
        $model->grant_dedication = $request->input('grant_dedication');
        $model->notes = $request->input('notes');
        if (ClientInfo::isHGA()) {
            if (empty($model->dedication_type)) {
                $request->merge(['anonymous' => 'on']);
            }
        }
        if ($request->anonymous) {
            $model->anonymous = 'Y';
            $model->from_contact_id = null;
        } else {
            $model->anonymous = 'N';
            $model->from_contact_id = $request->from_contact_id;
        }
        $model->status = GrantItem::STATUS_CREATED;
        $model->save();

        // log activity - save a grant recommendation
        $activity = new LogActivity(LogActivity::NAME_GRANT, LogActivity::ACTION_SAVE);
        $activity->onModel($model)->add();

        return redirect()->route('my-cart');
    }

    public function saveGrantCCT(Request $request)
    {
        // fund_id, org_remote_id, org_name
        // org_address1, org_city, org_state, org_zip, contact_phone, org_email, org_ein, contact_name, org_contact_title
        // amount
        // is_closing_grant
        // requested_disbursement_date
        // frequency, occurrences, last_grant_date, grant_purpose, notes, anonymous
        // show_advisor_name, show_fund_name, show_fund_name_letterhead
        // show_advisor_address
        // from_remote_id
        // from_name, from_address1, from_address2, from_city, from_state, from_zip
        // memorial_type => dedication_type,  memorial_name => grant_dedication
        // _global_id, origin

        // amount
        $request->merge(['amount' => GnUtils::floatValue($request->amount)]);

        // grant purpose
        $request->merge(['purpose_type' => GrantItem::GRANT_TYPE_GENERAL]);
        if ($request->grant_purpose && strlen($request->grant_purpose)) {
            $request->merge(['purpose_type' => GrantItem::GRANT_TYPE_SPECIAL]);
        }

        // grant_dedication is valid only if dedication_type has a value
        if (!$request->dedication_type || $request->dedication_type == '') {
            $request->merge(['grant_dedication' => null]);
        }

        $validator = [
            'fund_id' => 'required|min:1',
            'contact_id' => 'required|min:1',
            'organization_id' => '',
            'amount' => 'numeric|min:100',
            'grant_purpose' => 'max:1000',
            'grant_dedication' => 'max:1000',
            'notes' => 'max:1000',
            'anonymous' => 'max:32',
            'requested_disbursement_date' => "required|date_format:m-d-Y"
        ];

        if ($request->is_closing_grant) {
            $validator['amount'] = '';
        } else {
            // set minimum grant amount
            $minGrantAmount = ClientConfig::value('MIN_GRANT_AMOUNT');
            $amount = 'numeric|min:' . $minGrantAmount;
            $validator['amount'] = $amount;
        }

        $this->validate($request, $validator);

        $id = $request->input('cart_id');
        $model = null;
        if ($id) $model = GrantItem::find($id);
        if (!$model) $model = new GrantItem();

        $orgId = $request->input('organization_id');

        $model->fund_id = $request->input('fund_id');
        $model->contact_id = $request->input('contact_id');
        $model->organization_id = $orgId;

        if (!$orgId && !$model->cart_id) {
            $model->organization_name = $request->input('organization_name');
            $model->org_address1 = $request->input('address_one');
            $model->org_address2 = $request->input('address_two');
            $model->org_city = $request->input('city');
            $model->org_state = $request->input('state');
            $model->org_country = $request->input('country');
            $model->org_zip = $request->input('zip');
            $model->org_ein = $request->input('ein');
        }

        $model->org_contact = $request->input('contact_name');
        $model->org_contact_title = $request->input('org_contact_title');
        $model->org_email = $request->input('email');
        $model->org_phone = $request->input('phone');

        $requestedDisbursementDate = \DateTime::createFromFormat('m-d-Y', $request->requested_disbursement_date);
        $model->start_date = $requestedDisbursementDate;
        $model->requested_disbursement_date = $requestedDisbursementDate;

        if ($request->is_closing_grant) {
            $model->is_closing_grant = true;
            $model->amount = 0;
            $model->frequency = Data::GRANTING_FREQUENCY_ONCE;

            $model->no_end = 'N';
            $model->recurring_status = null;
            $model->occurrences = null;
        } else {
            $model->is_closing_grant = false;
            $model->amount = $request->input('amount');
            $model->frequency = Data::getGrantingFrequency($request->frequency);
            if ($model->isRecurring()) {
                $model->recurring_status = 'active';
                if ($request->no_end) {
                    $model->no_end = 'Y';
                    $model->occurrences = null;
                    $model->end_date = null;
                } else {
                    $model->no_end = 'N';
                    $model->occurrences = $request->occurrences;
                    $model->setEndDate();
                }
            }
        }

        $model->purpose_type = $request->input('purpose_type');
        $model->grant_purpose = $request->input('grant_purpose');
        $model->dedication_type = $request->input('dedication_type');
        $model->grant_dedication = $request->input('grant_dedication');
        $model->notes = $request->input('notes');
        $model->notification_info = $request->notify ? $request->notification_info : null;

        // ?
        // $model->last_grant_date = null;
        // $model->_global_id = null;
        // $model->org_nickname = null;
        // $model->org_website = null;

        // set default
        $model->anonymous = 'Y';
        $model->show_fund_name = false;
        $model->show_advisor_name = false;
        $model->show_advisor_address = false;
        $model->from_contact_id = null;
        $model->from_name = null;
        $model->from_address1 = null;
        $model->from_address2 = null;
        $model->from_city = null;
        $model->from_state = null;
        $model->from_zip = null;

        if (!$request->anonymous) {
            $model->anonymous = 'N';
            $model->show_fund_name = $request->show_fund_name;
            $model->from_contact_id = $request->from_contact_id;

            if ($request->show_advisor_name) {
                $model->show_advisor_name = true;
                $model->from_name = $request->from_name;
                if ($request->show_advisor_address) {
                    $model->show_advisor_address = true;
                    $model->from_address1 = $request->from_address1;
                    $model->from_address2 = $request->from_address2;
                    $model->from_city = $request->from_city;
                    $model->from_state = $request->from_state;
                    $model->from_zip = $request->from_zip;
                }
            }
        }

        $model->status = GrantItem::STATUS_CREATED;
        $model->save();

        // log activity - save a grant recommendation
        $activity = new LogActivity(LogActivity::NAME_GRANT, LogActivity::ACTION_SAVE);
        $activity->onModel($model)->add();

        return redirect()->route('my-cart');
    }

    /*
     *
     */
    public function saveGrant(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        if (ClientInfo::isCCT()) {
            return $this->saveGrantCCT($request);
        }
        // added for NIF - this must not impact other client
        // new fields added for NIF - purpose_type, dedication_type & grant_dedication
        // if request has purpose_type and purpose_type is GRANT_TYPE_GENERAL, clear grant_purpose
        // i.e. 'general' purpose can not have special purpose described
        if (ClientInfo::isNIF()) {
            if ($request->purpose_type && $request->purpose_type == GrantItem::GRANT_TYPE_GENERAL) {
                $request->merge(['grant_purpose' => null]);
            }
        } else {
            // for other clients, set purpose_type
            if ($request->grant_purpose && strlen($request->grant_purpose)) {
                $request->merge(['purpose_type' => GrantItem::GRANT_TYPE_SPECIAL]);
            } else {
                $request->merge(['purpose_type' => GrantItem::GRANT_TYPE_GENERAL]);
            }
        }
        // similarly, grant_dedication description is valid only if dedication_type has a value
        if (!$request->dedication_type || $request->dedication_type == '') {
            $request->merge(['grant_dedication' => null]);
        }

        $request->merge(['amount' => GnUtils::floatValue($request->amount)]);

        return $this->validateAndSave($request);
    }

    /**
     * delete an item from cart
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function removeGrant(Request $request, $id)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        // log activity - delete a grant recommendations
        $model = GrantItem::find($id);
        $activity = new LogActivity(LogActivity::NAME_GRANT, LogActivity::ACTION_DELETE);
        if ($model) $activity->onModel($model);
        $activity->add();

        $result = GrantItem::deleteById($id);
        if ($result === true) {
            return ['status' => 200, 'fund-id' => $id];
        }
        return ['status' => 0, 'mesg' => $result];
    }

    public function selectGrantFrom(Request $request)
    {
        $fromDonors = ['' => 'Select donor identity..'];
        $fundId = $request->input('fund_id');
        $fromContactId = $request->input('from_contact_id');

        // if (ClientInfo::isGNA()) {
        //    $contactIds = ContactFund::getContactIdsByFundId($fundId);
        // } else {
        //     $contactIds = FundRepType::getPTAContactIdsByFundId($fundId);
        // }
        $contactIds = ContactFund::getContactIdsByFundId($fundId);
        if (count($contactIds)) {
            $contacts = Contact::whereIn('contact_id', $contactIds)->get();
            foreach ($contacts as $contact) {
                $fromDonors[$contact->contact_id] = 'From ' . $contact->name;
            }
        }

        $html = "";
        if (count($fromDonors)) {
            $html = view('donor.grants._select_from_contact', compact('fromDonors', 'fromContactId'))->render();
        }
        return [
            'status' => 200,
            'html' => $html
        ];
    }

    public function selectedGrantFrom(Request $request)
    {
        $fromContactId = $request->from_contact_id;

        /** @var Contact $contact */
        $contact = Contact::find($fromContactId);
        $address = $contact->getAnyAddress();

        $model = [];
        $model['show_advisor_name'] = false;
        $model['show_advisor_address'] = false;

        $model['from_name'] = $contact->name;
        $model['from_address1'] = $address->address_1;
        $model['from_address2'] = $address->address_2;
        $model['from_city'] = $address->city;
        $model['from_state'] = $address->state;
        $model['from_zip'] = $address->zip;

        $html = view(ClientInfo::clientViewFor('grants._selected_from_contact', 'donor.'), compact('model'))->render();
        return [
            'status' => 200,
            'html' => $html
        ];
    }

}
