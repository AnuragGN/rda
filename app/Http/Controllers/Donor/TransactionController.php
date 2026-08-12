<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 13-05-2020
 * Time: 15:55
 */

namespace App\Http\Controllers\Donor;


use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Forms\FormPayTo;
use App\Models\Fund;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use App\Models\Transaction;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        GnUtils::addBreadcrumb(ClientConfig::text('RECENT_CONTRIBUTIONS'));

        $date = new \DateTime(); //Today
        $date = $date->modify("-1 months"); // 1 months ago

        $contact = Contact::sessionContact();
        if (!$contact) abort(403);

        // show transaction of last month (max 25)
        $models = Transaction::where(['contact_id' => $contact->contact_id])
            ->where('created_on', '>', $date)->limit(25)
            ->orderBy('created_on', 'DESC')->get();
        return view('donor.transactions.index', compact('models'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contribute(Request $request)
    {
        // log activity - create a new grant
        $activity = new LogActivity(LogActivity::NAME_TRANSACTION, LogActivity::ACTION_CONTRIBUTE);
        $activity->data($request->all())->add();

        GnUtils::addBreadcrumb(ClientConfig::text('MAKE_A_GIFT'));

        // TODO: add NT
        if (ClientInfo::isNIF()) {
            return view('nif.transactions.gift.contribute');
        } elseif (ClientInfo::isCCT()) {
            return view('cct.transactions.gift.contribute');
        }

        /** @var Contact $contact */
        $contact = Contact::sessionContact();
        $funds = Fund::getSelectableViewable($contact->id);

        // main grant model
        $model = new FormPayTo();
        $model->contact_id = $contact->contact_id;

        if ($request->filled('repeat')){}

        return view('donor.transactions.gift.make-a-gift', compact('model', 'funds'));
    }

    public function response($rid)
    {
        /** @var Transaction $model */
        $model = Transaction::where(['ref_id' => $rid])->first();
        if (!$model) abort('404', 'This transaction does not exist!');

        return view('donor.transactions.response', compact('model'));
    }
}
