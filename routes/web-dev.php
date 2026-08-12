<?php

// TEST START
Route::get('/alkesh/test', function () {

    return \App\ContactUs::all();

    $model = new \App\ContactUs();
    $contact = \App\Contact::sessionContact();

    $model->contact_id = $contact->getModelId();

    $model->target_type = 'organization';
    $model->target_value = 1624;
    $model->contact_name = 'AKS';
    $model->contact_address = null;
    $model->contact_phone = '987-174-5111';
    $model->contact_email = 'ak@gmail.com';
    $model->comment = 'The comment, i.e. Notes!';
    $model->target_id = 1624;
    $model->additional_info_array = ['Research regarding this nonprofit or nonprofit sector', 'Something else'];

    // return $model;

    \App\Email::requestInfo($model);
    return ['mail sent.. '];


    return \App\ContactUs::all();

    /** @var \App\Contact $contact */
    $contact = \App\Contact::find(2);
    return $contact->canAddPhoneTypes();

    return \App\PhoneType::getContactPhoneTypePrimary();
    // return \App\PhoneType::getContactPhoneTypes();
    return \App\PhoneType::getAll();

    return \App\Contact::find(2);
    return \App\AddressType::getContactAddressTypes();
    return \App\AddressType::getAll();
});

Route::get('/login-once', function (Illuminate\Http\Request $request) {
    return [\Illuminate\Support\Facades\Auth::onceUsingId(1)];
    // return [\Illuminate\Support\Facades\Auth::loginUsingId(2)];
});
Route::get('/password-link-user/{token?}', function ($token=1) {
    return [\App\Models\PasswordLink::getLinkUser($token)];
});
// TEST END

Route::get('/kishan/test', function () {
    return \App\InterestArea::getAll();
});


Route::get('/client/env', function () {

    return \App\ClientEnv::value('EMAIL_CC');
// return \App\ClientEnv::object();

});

Route::get('/rajeev/grant-history', function () {

    return \App\ContactPopulationServed::getPopulationServeds(true);
    return \App\ContactGeographicArea::getGeographicAreas();

    return \App\PopulationServed::getAll();
    return \App\GeographicArea::getAll();
    return \App\ContactInterestArea::getInterestAreas(2);

    return \App\ContactUs::where([])->orderBy('contact_us_id', 'desc')->limit(10)->get();
// $contact = \App\Contact::sessionContact();

    return \App\Content::where([])->limit(10)->get();

    $contact = \App\Contact::sessionContact();

    return \App\ContactAddress::where(['contact_id' => $contact->contact_id])->get();

    return \App\User::getSessionUser();

    return [\App\Organization::find(5609)];
    return [\App\Content::find(436)];

    $collection = [436, 437, 439, 460];
    $indices = array_rand( $collection, 3);
    shuffle($indices);
    $ids = [];
    foreach($indices as $i) $ids[] = $collection[$i];
    $models = \App\Content::find($ids);
    return $models;

    return [\App\Content::find(144)];
// return [\App\Content::where('title', 'ilike', '%SOCIAL ISOLATION%')->limit(450)->orderBy('content_id')->get()];

    return [\App\Content::limit(450)->orderBy('content_id')->get()];
    return [\App\Content::count()];

    $model = new \App\GrantHistory();
    $model->fund_grant_history_id = 63397;
    $model->amount = "5000";
    $model->date_entered = "2015-12-13 13:39:47.755917";
    $model->donor_id = null; //************
    $model->fund_id = "Vitea";
    $model->grantee = "American Friends of Magens David Adom";
    $model->grant_date = "2009-05-01";
    $model->grant_num = "20094331"; //************
    $model->organization_id = 259;
    $model->org_need_app_id = null;
    $model->payment_date = "2009-05-08";
    $model->payment_no = "1";
    $model->proposal_id = null;
    $model->proposal_name = null;
    $model->status = "";
    $model->created_on = "2015-12-13 13:39:47.755917";
    $model->grant_id = "20094331"; //************
    $model->amount_paid = null;
    $model->grant_description = "Marla Bennett Friends Award Honoring Laura Galinson";
    $model->grant_line_sum = "5000";
    $model->appay_sum = "5000";
    $model->org_remote_id = null;
// $model->save();
// return ::first();
});


Route::get('/alka/nginx/create-conf', function () {
    return \App\Other\NginxConf::create();
// \Illuminate\Support\Facades\File::put('./nginx-conf/abc.conf', 'contents .. 1');
});


Route::get('/post/json', function () {
    // return [1];

    $data = request()->all();
    return ['data' => $data];
});

Route::get('/alka/jcf-req-info-modal', function () { return view('test.jcf-req-info-modal'); });
Route::get('/alka/tmp-modal', function () { return view('test.tmp-modal'); });
Route::get('/alka/test', function () { return view('test.test'); });
Route::get('/alka/pdf', function () { return view('test.tmp-pdf'); });
Route::get('/alka/investment', function () { return view('test.tmp-investment'); });
Route::get('/test/alkesh/test-js', 'FundController@testJs');
Route::get('/test/alkesh/pass', 'FundController@password');
Route::get('/test/alkesh/sage/contacts', 'SageController@all');


Route::get('/alka/data/fund/{fid?}', function ($fid=null) {

    // return \App\JCF\JCFFunds::getFundStatementFull('Silb');
    // $fund = \App\FundStatement::where(['fund_id' => 'Silb'])->orderBy('date_entered', 'DESC')->first();
    // return \App\JCF\JCFFunds::getHeldAwayAssetPoolsTotal($fund);
    // return \App\JCF\JCFFunds::getHeldAwayImpactFundsByFundId('Silb');
    // get my funds
    // $contact = \App\Contact::sessionContact();
    return [1];
});

Route::get('/alka/data/contact', function () {
    // $contact = \App\Contact::sessionContact();
    // $address = $contact->getAnyAddress();
    // return ['email' => $contact->email_address, 'contact' => $contact, 'address' => $address];
    // $phones = \App\ContactPhone::where(['contact_id' => $contact->contact_id])->get(); // 42431
    // return [$contact->email_address, 'contact' => $contact, 'phones' => $contact->phones, 'address' => $contact->getAddress()];
    return [1];
});


Route::get('/alka/data/org-contact', function () {
    return \App\OrganizationContact::where([])->first();
});

Route::get('/alka/send-mail', function () {

//    $models = \App\GrantItem::where([])->limit(3)->get();
//    foreach($models as $key => $model) {
//        $data[$key] = $model->fund;
//    }
//    \App\Email::grantRecommendation($models);
//    return ['mail sent.. '];

    $transaction = \App\Transaction::first();
    return $transaction;

    \App\Email::transaction($transaction);
    return ['mail sent.. '];

    \App\Email::resetPassword("alkesh@sageite.com", "Alkesh Kr Singh", "http://www.abc.com/pw/121");
    return ['mail sent.. '];
});

Route::get('alka/tmp', function (Illuminate\Http\Request $request) {

    return \App\Country::getList();

//    return view('tmp-iframe');
//    return view('tmp-pdf');
//    return view('tmp');
    // return [1];

    $today = new \DateTime();
    $tomorrow = new \DateTime();
    $tomorrow->modify('+1 day');
    return [$today->format('Y-m-d'), $tomorrow->format('Y-m-d')];

    $now = new \DateTime();
    $date = new \DateTime();
    $date->modify('-15 minutes');
    // $date = $date->format('Y-m-d H:i:s');
    return ['now' => $now, '15' => $date->format('Y-m-d H:i:s')];

    return \App\ClientConfig::dateFormat();
    return \App\ClientConfig::name();
    return \App\ClientConfig::clientConfig();

    return [config('custom.jcf_config.name', 'none')];


    $date = date("Y/m/d");

    return [\App\Helpers\GnUtils::customDate($date)];
    return [\App\Helpers\GnUtils::dateYMD($date)];

    return view('tmp');
});

Route::get('/alka/reset-password-mail', function () { return view('emails.reset-password', ['name' => 'Alkesh Kumar', 'url' => 'http://www.abc.com']); });

Route::get('/alka/grant-recommendation2', function () {
    $model = new \App\Mail\GrantRecommendation();
    return $model->build();
});

Route::get('/alka/alkesh/home2', function () {
    return \App\ContactFund::where(['contact_id' => 2])->get();
    return \App\Fund::where([])->get();
    return \App\Fund::whereIn('account_id', ['20018011063', '20018003811'])->pluck('name', 'fund_id');

    return view('home');
});

Route::get('/alka/admin-login', function () {
    $username = 'cliffs';
    // $password = $this->encrypt('chestercap');

    $user = \App\User::where(['username' => $username])->first();
    $user = \App\User::find(5);
    \Illuminate\Support\Facades\Auth::login($user);

    return [auth()->user()];
});

Route::get('/alka/alkesh/user', function () {
    return [auth()->user()];
});

Route::get('/alka/organization', function() {
    return \App\Organization::where([])->limit(5)->get();
});

/**********************************************************************************************************************/
Route::get('/alka/InterestArea-test', function() {
    return \App\InterestArea::TEST();
});

/**********************************************************************************************************************/
Route::get('/alka/OrgInterestArea-test', function() {
    return \App\OrgInterestArea::TEST();
});
Route::get('/alka/OrgInterestArea-catalog', function() {
    return \App\OrgInterestArea::getCatalog();
});

/**********************************************************************************************************************/
Route::get('/alka/OrgNeedAppInterestArea-test', function() {
    return \App\OrgNeedAppInterestArea::TEST();
});
Route::get('/alka/OrgNeedAppInterestArea-catalog', function() {
    return \App\OrgNeedAppInterestArea::getCatalog();
});

/**********************************************************************************************************************/
Route::get('/alka/OrgNeedApp-get', function() {
    return \App\OrgNeedApp::TEST();
});
Route::get('/alka/OrgNeedApp-catalog', function() {
    return \App\OrgNeedApp::getCatalog();
});
/**********************************************************************************************************************/

Route::get('/alka/test/test', function() {
    return view('test.test-phone');
    return view('test.test-email-template');
    return view('test.test-program-link');

    return [\App\ClientConfig::value('MIN_GRANT_AMOUNT')];
    // return [\App\ClientConfig::clientConfig()];
    return [\App\ClientConfig::value('FS_POOL_COLLAPSED', false)];

    // return [env('MERCHANT_LOGIN_ID', 'a121')];
    // return [config('MERCHANT_LOGIN_ID', 'x1'), env('MERCHANT_LOGIN_ID', 'x2')];

    // return [env('GOOGLE_ANALYTICS_ID', 'a121')];
    return [config('client.googleAnalyticsId', 'x2')];
    return [config('MERCHANT_LOGIN_ID', 'x1'), env('MERCHANT_LOGIN_ID', 'x2')];
    return [env('MAIL_FROM_ADDRESS', 'x1')];

    return [\App\ClientConfig::text('GIFT_HISTORY')];
    return [\App\ClientConfig::message('DATA_NOT_FOUND')];


    return \App\Fund::where(['fund_id' => 'JCFEX'])->first();
    {
        $org = \App\Organization::where(['organization_id' => 1])->first();
        return $org->primaryContact();
    }

    return \App\OrganizationContact::where([])->limit(3)->get();

    {
        $model = \App\GrantItem::find(21);
        return [$model, $model->contact()];
    }
    return \App\Config::getRecommendationRequireApprovalAll();


    return \App\Http\Controllers\FundStatementController::fundStatement();

    return \App\FundStatement::where(['fund_id' => 'JCFEX'])->orderBy('date_entered', 'DESC')->limit(1)->get();
    return \App\Contact::sessionContact();
});

Route::get('/sage', function () {
    return view('sage');
});

Route::get('/alka/donations', function () {
    // $model = \App\Donation::getSampleInstance(); //  find(18);
    // return view('emails.donation', ['name' => 'Alkesh Kumar', 'donation' => $model]);
    $model = \App\Donation::find(12);
    return view('donation.view', compact('model'));
    return \App\Donation::all();
});

Route::get('/alka/authorize-net/sandbox-init', function() {
    return view('payment.sandbox-init');
});


Route::get('/test/alkesh/my-cart', function () {
    return \App\Transaction::all();
    $model = \App\Transaction::find(4);
    $model->response = json_decode($model->response);
    return $model;

    return \App\Transaction::all();
    // return Illuminate\Support\Facades\Schema::getColumnListing('fund_recommendation');
    // return Illuminate\Support\Facades\Schema::getColumnListing('recom_cart_details');
    //return \App\FundRecommendation::where([])->orderBy('last_updated')->limit(10)->get();
    return \App\GrantItem::myCartItems();
});

Route::get('/alka/config/{type}/{key}', function ($type, $key) {
    return [\App\ClientConfig::$type($key)];
});

Route::get('/alka/a123', function () {
    $model = \App\FundStatementHeldAway::getByFundId("Haza");
    $model = $model->toArray();
    ksort($model);
    return [$model];

});

// DONOR REGISTRATION

Route::prefix('registration')->group(function () {

    Route::get('login', function () {
        return view('demo.login');
    });
    Route::get('thank-you', function () {
        return view('demo.thank-you');
    });
    Route::get('registration', function () {
        return view('demo.registration');
    });
    Route::get('primary', function () {
        return view('demo.primary');
    });
    Route::get('secondary', function () {
        return view('demo.secondary');
    });
    Route::get('account-name', function () {
        return view('demo.account-name');
    });
    Route::get('account-auth', function () {
        return view('demo.account-auth');
    });
    Route::get('successor', function () {
        return view('demo.successor');
    });
    Route::get('successor-select', function () {
        return view('demo.successor-select');
    });
    Route::get('successor-v-radio', function () {
        return view('demo.successor-v-radio');
    });
    Route::get('successor-h-radio', function () {
        return view('demo.successor-h-radio');
    });
    Route::get('contribution', function () {
        return view('demo.contribution');
    });
    Route::get('pools', function () {
        return view('demo.pools');
    });
    Route::get('signature', function () {
        return view('demo.signature');
    });

    Route::get('dashboard', function () {
        return view('demo.dashboard');
    });
    Route::get('statement', function () {
        return view('demo.statement_hga');
    });

});

