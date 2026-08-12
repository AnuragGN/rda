<?php
/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 06-10-2023
 * Time: 09:55
 */

namespace App\Http\Controllers\Agency;

use App\Forms\FormFundHistoryFilter;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\FundRecommendation;
use App\Models\GiftHistory;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use App\Models\GrantItem;
use Auth;
use App\Models\Api;
use App\Models\Fund;
use App\Models\ContactFund;
use App\Models\Task;
use Illuminate\Http\Request;
use League\Csv\Writer;
use PDF;
use Carbon\Carbon;

// Funds = 'JCFEX', 'Abra';
/**
 * Class FundController
 * @package App\Http\Controllers
 */
class AgencyAdvisorCartController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $contact = Contact::sessionContact();
        $models = GrantItem::myCartItems();
        $funds = Fund::getSelectableViewable();
        $models = [];
        return view('agency.agency-advisor.cart.index', compact('models','funds'));
    }

    public function cartListAjax(Request $request)
    {
        $data = $request->all();
        #$fund_id = $data['fund_id'];
        $contact_id = Contact::sessionContactId();
        $contactfund = ContactFund::getFundIdsByContactId($contact_id);
        $limit = 10;
        $html = '';
        if($contactfund > 0)
        {
            $fund_ids = implode(',',$contactfund);
            $cart = GrantItem::advisorCartItems($fund_ids,$limit);
            if (count($cart)) {
                $html = view('agency.agency-advisor.cart.list', compact('cart'))->render();
            }
        }
        return [
            'more' => (count($cart) < $limit) ? 0 : 1,
            'html' => $html
        ];
    }

    public function cartdetail()
    {
        $contact = Contact::sessionContact();
        $models = [];
        return view('agency.agency-advisor.cart.cart-detail', compact('models'));
    }

    public function cartdetailAjax(Request $request)
    {
        $data = $request->all();
        $cart_id = $data['cart_id'];
        $contact_id = Contact::sessionContactId();

        $cart = GrantItem::advisorCartDetailItems($cart_id);
        $html = '';
        if (count($cart)) {
            $html = view('agency.agency-advisor.cart.cart-detail-list', compact('cart'))->render();
        }
        return [
            'more' => 0,
            'html' => $html
        ];
    }
}
