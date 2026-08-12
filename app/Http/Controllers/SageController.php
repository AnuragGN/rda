<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\Http\Controllers;

use App\Models\ContactAddress;
use App\Models\Fund;
use Illuminate\Support\Facades\DB;
use App\Models\GiftHistory;

class SageController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int $id
     * @return View
     */
    public function all()
    {

        $contacts = GiftHistory::where(['fund_id' => 'Abra'])->get();
        return $contacts;


        $funds = Fund::where([])->limit(2)->get();
        // return $funds;

        $models = [];
        $histories = [];
        foreach ($funds as $fund) {
            $data = $fund->toArray();
            $data['alk_fund_id'] = $data['fund_id'];

            $models[] = $data;
            // $histories[$fund->fund_id] = GiftHistory::where(['fund_id' => $fund->fund_id])->get();
        }

        return $models;

        return [
            'hitories' => $histories,
            'funds' => $funds
        ];


        // $contacts = DB::select("select * from organization");
        // $contacts = DB::select("select * from fund_gift_history where fund_id = ?", ['Abra']);

        /* @var $contacts GiftHistory */
        // $contacts = GiftHistory::where(['fund_id' => 'Abra1', '_remote_id' => "25561"])->first();
        $contacts = GiftHistory::where(['fund_id' => 'Abra'])->get();
        // $contacts->comment = "Cash Gift 5000";
        // $contacts->save();

        // $contacts = DB::select("select * from contact_address limit 2");
        $contacts = ContactAddress::where(['address_2' => 'POK'])->limit(2)->get();
//        foreach ($contacts as $contact) {
//            $contact->address_2 = "POK";
//            $contact->save();
//        }

        return $contacts;
        // return view('user.profile', ['user' => User::findOrFail($id)]);
    }

}
