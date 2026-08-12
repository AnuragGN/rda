<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::post('/m/envestnet/okta/cb/login', ['as' => 'saml.acs-login-envestnet', 'uses' => 'Saml2Controller@acsLogin']);
Route::get('/m/advisor-firm-not-found', ['as' => 'advisor-firm-not-found', 'uses' => 'Saml2Controller@advisorFirmNotFound']); // firmNotFound
Route::get('/m/advisor-firm-user-not-found', ['as' => 'advisor-firm-user-not-found', 'uses' => 'Saml2Controller@advisorFirmUserNotFound']); // firmUserNotFound

Route::get('/m/registration/advisor-account', ['as' => 'advisor-account', 'uses' => 'Agency\UserRegistrationController@advisorFormAccount']);
Route::post('m/registration/advisor-account', ['as' => 'post-advisor-account', 'uses' => 'Agency\UserRegistrationController@advisorSaveAccount']);
Route::get('m/registration/advisor-account-created', ['as' => 'advisor-account-created', 'uses' => 'Agency\UserRegistrationController@advisorAccountCreated']);
Route::get('m/registration/advisor-account-exist', ['as' => 'advisor-account-exist', 'uses' => 'Agency\UserRegistrationController@advisorAccountExist']);


Route::get('/m/notification-list', ['as' => 'notification-list', 'uses' => 'NotificationController@getNotificationList']);
Route::get('/m/notification-mark-as-read', ['as' => 'notification-mark-as-read', 'uses' => 'NotificationController@notificationMarkAsRead']);
Route::get('/m/bottom-notification', ['as' => 'bottom-notification', 'uses' => 'NotificationController@getBottomNotification']);

/* AI Tools */
Route::post('/ai/process', ['as' => 'ai.process', 'uses' => 'AIAssistantController@process']);

Route::get('/run-command', function(Request $request) {

    $params = $request->all();
    return \App\Models\GrantHistory::getInterestAreasSelectable();

    // return \App\Models\OrgInterestArea::addOrgInterestAreas();
    // return \App\Models\OrgInterestArea::getPrimaryInterestName(25);
    // return \App\Models\Organization::where([])->pluck('organization_id', 'name');
    // return \App\Models\Organization::where('name', "Doctors Without Borders USA INC")->get();

    return \App\Models\GrantItem::find(182);

    return \App\Models\PhoneType::where([''])->orderBy('is_org_phone')->get();

    return \App\Models\PhoneType::where([])->orderBy('is_org_phone')->get();

    $models = \App\Models\CallStatsCandid::all();
    return $models;

    $models = \App\Models\ContactFund::where(['viewable' => 'N', 'make_grant_recommendation' => 'Y'])->get();
    return [count($models), $models];

    return \App\Models\Docs::get();

    return [\App\Models\ClientConfig::feature('GUIDE_STAR_CANDID')];

    return [Illuminate\Support\Str::random(40)];

    $time = time();
    return date('mdY', $time);

    return [\App\Helpers\FileManager::isNFSMounted()];

    return \App\Helpers\FileManager::clientUserDocumentsPath();

    return \App\Helpers\FileManager::getReadablePDFDocumentList();

    $data = [];
    $models =\App\Models\FundStatement::where('fund_114_misccashreciptsytd','<>', "0.00")->limit(10)->get();
    foreach ($models as $model) {
        $data[] = $model->only('fund_id', 'thru_date', 'fund_114_misccashreciptsytd', 'cashytd');
    }
    return $data;


    $api = new \App\Models\Api();

    return $api->apiGiftHistoryGroupedByMonthlyGrantDate('CUMMIFAM');

    $contact = \App\Models\Contact::sessionContact();
    $clone = $contact->replicate();
    $clone->first_name = "Alkesh";
    $clone->last_name = "Singh";
    $changes = \App\Helpers\ChangeNotifier::onContactProfileUpdate($contact, $clone);
    return [$changes, $contact, $clone];


    return ['mail sent.. '];
});



Route::get('/get-client-name', ['uses' => 'HomeController@getClientNameFromEnv']);

Route::get('/fig-mail-donation', function() {

    $donation = \App\Models\Donation::where([])->first();
    // return $donation;
    return view(\App\Models\ClientInfo::clientViewFor('emails.donation'), ['name' => 'alk', 'donation' => $donation]);

    // \App\Models\Email::transaction($transaction);
    return ['mail sent.. '];
});


Route::get('/test-mail-grant-reminder', function() {
    $model = new \App\Console\Commands\GrantReminder();
    return [$model->handle()];
});


Route::get('/donor', function() {

    /** @var \App\Models\OrganizationInfo $model */
    $model = \App\Models\OrganizationInfo::whereNotNull('web_site')->first();
    return $model->hasWebsite();
    return $model;

    return [\App\Models\Organization::whereNotNull('web_site')->limit(10)->get()];
    $types = \App\Models\ContactType::all();
    return $types;
    return ['donor'];
});

Route::get('/not-agency', function() {
    return ['not-agency'];
});
Route::get('/not-donor', function() {
    return ['not-donor'];
});
Route::get('/not-seeker', function() {
    return ['not-seeker'];
});
Route::get('/not-donor-or-agency', function() {
    return ['not-donor-or-agency'];
});
Route::get('/console', function() {
    return ['Work in Progress.. Admin console..'];
})->name('admin-console');

/* Authorized user : I think 'auth' (along with 'admin') is required for Redirect::intended()*/
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'm'], function() {
    Route::get('/emulation', function () { return view("emulation.emulation-mode-limited"); })->name('emulation-home');
    Route::get('/emulation/404', function () { return view("emulation.404"); })->name('emulation-404');
    Route::get('/emulate/donor', ['as' => 'emulation', 'uses' => 'Auth\EmulationController@onStartEmulation']);
});

Route::prefix('m')->group(function () {
    /* login */
    // Route::get('/login', function () { return view('auth.login'); })->name('login')->middleware('guest');
    // Route::get('/login2', function () { return view('auth.login2'); });
    // Route::get('/login-nif', function () { return view('auth.fa-login'); });

    Route::get('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@loginPage'])->middleware('guest');

    Route::post('/login', ['as' => 'post-login', 'uses' => 'Auth\LoginController@onLogin']);
    Route::post('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

    /* 2fa */
    Route::get('/2fa/resend', ['as' => '2fa-resend', 'uses' => 'Auth\LoginController@get2FAResend']);
    Route::get('/2fa', ['as' => '2fa-form', 'uses' => 'Auth\LoginController@get2FAForm']);
    Route::post('/2fa', ['as' => '2fa-post', 'uses' => 'Auth\LoginController@auth2FA']);

    /* password */
    Route::get('/password/forgot', ['as' => 'forgot-password-form', 'uses' => 'Auth\ForgotPasswordController@forgotPasswordForm']);
    Route::post('/password/forgot', ['as' => 'forgot-password', 'uses' => 'Auth\ForgotPasswordController@forgotPassword']);
    Route::get('/password/reset/{token}', ['as' => 'reset-password-form', 'uses' => 'Auth\ResetPasswordController@resetPasswordForm']);
    Route::post('/password/reset', ['as' => 'reset-password', 'uses' => 'Auth\ResetPasswordController@resetPassword']);
    Route::get('/password/change', ['as' => 'change-password-form', 'uses' => 'Auth\ChangePasswordController@changePasswordForm']);
    Route::post('/password/change', ['as' => 'change-password', 'uses' => 'Auth\ChangePasswordController@changePassword']);

    /* change username/email confirmation */
    Route::get('/confirm-new-email', ['as' => 'confirm-change-email-form', 'uses' => 'Auth\ChangeEmailController@confirmChangeEmailForm']);
    Route::post('/confirm-new-email', ['as' => 'confirm-change-email', 'uses' => 'Auth\ChangeEmailController@confirmChangeEmail']);

    /* donation */
    Route::get('/donation', ['as' => 'donation.create', 'uses' => 'AuthorizeNetController@donationCreate']);
    Route::post('/donation', ['as' => 'donation.store', 'uses' => 'AuthorizeNetController@donationPayment']);
    Route::get('/donation/view', ['as' => 'donation.view', 'uses' => 'AuthorizeNetController@donationView']);

    /* search */
    Route::get('/search/organizations', 'SearchController@searchOrgs');
    Route::get('/search/funds', 'SearchController@searchFunds');

    /* file download */
    Route::get('/performance/download/{type}/{id}', ['as' => 'performance-file-download', 'uses' => 'Donor\PerformanceController@fundPerformanceDownload']);

    /* articles */
    Route::get('/content/{id}', ['as' => 'content.show', 'uses' => 'ContentController@show']);

    /* guide-star / candid search */
    Route::post('/search-candid', ['as' => 'candid-search', 'uses' => 'SearchController@searchCandidOrgs']);

    // activity logs
    Route::get('/console/log-activities', 'LogActivityController@activities');
    Route::get('/console/log-data', 'LogActivityController@getData');
});


/* api for classic */
Route::prefix('/api/classic/authorize-net')->group(function () {
    /* classic payment */
    Route::get('/test-make-payment', ['as' => 'test-classic-anet-payment', 'uses' => 'AuthorizeNetAPIController@classicPaymentTest']);
    Route::post('/test-make-payment', ['as' => 'test-classic-anet-payment', 'uses' => 'AuthorizeNetAPIController@classicPaymentTest']);
    Route::post('/make-payment', ['as' => 'classic-anet-payment', 'uses' => 'AuthorizeNetAPIController@classicPayment']);

    /* classic donation */
    Route::get('/test-donation-payment', ['as' => 'test-classic-donation-payment', 'uses' => 'AuthorizeNetAPIController@classicDonationPaymentTest']);
    Route::post('/test-donation-payment', ['as' => 'test-classic-donation-payment', 'uses' => 'AuthorizeNetAPIController@classicDonationPaymentTest']);
    Route::post('/donation-payment', ['as' => 'classic-anet-payment', 'uses' => 'AuthorizeNetAPIController@classicDonationPayment']);
});

// ANURAG SINHA
/* Chatbot — any authenticated user */
Route::group(['middleware' => ['auth'], 'prefix' => 'm'], function () {
    Route::get('/chatbot',               ['as' => 'chatbot.index',       'uses' => 'ChatbotController@index']);
    Route::post('/chatbot/send',         ['as' => 'chatbot.send',        'uses' => 'ChatbotController@send']);
    Route::post('/chatbot/accept-terms', ['as' => 'chatbot.acceptTerms', 'uses' => 'ChatbotController@acceptTerms']);
    Route::get('/chatbot/document-token', ['as' => 'chatbot.documentToken', 'uses' => 'ChatbotController@getDocumentToken']);
});

/* Any user */
Route::group(['middleware' => ['auth'], 'prefix' => 'm'], function() {

    Route::get('/ajax-keep-alive', function() { return 'keep-alive';});

    // change email request
    Route::get('/email/change', ['as' => 'change-email-form', 'uses' => 'Auth\ChangeEmailController@changeEmailForm']);
    Route::post('/email/change', ['as' => 'change-email', 'uses' => 'Auth\ChangeEmailController@changeEmail']);

    // profile
    Route::get('/profile/test', ['as' => 'profile1', 'uses' => 'ProfileController@viewTest']);
    Route::get('/profile', ['as' => 'profile', 'uses' => 'ProfileController@view']);
    Route::get('/profile/edit', ['as' => 'profile-edit', 'uses' => 'ProfileController@editProfile']);
    Route::post('/profile/save', ['as' => 'profile-save', 'uses' => 'ProfileController@saveProfile']);
    // Profile Image
    Route::get('/profile-picture-edit', 'ProfileController@editProfilePicture')->name('profile-picture-edit');
    Route::post('/profile-picture-save', 'ProfileController@saveProfilePicture')->name('profile-picture-save');
    Route::post('/profile-picture-delete', 'ProfileController@deleteProfilePicture')->name('profile-picture-delete');

    // Contact Address
    Route::get('/profile/address/edit', ['as' => 'profile-address-edit', 'uses' => 'AddressController@editProfileAddress']);
    Route::post('/profile/address/save', ['as' => 'profile-address-save', 'uses' => 'AddressController@saveProfileAddress']);
    Route::post('/profile/address/delete', ['as' => 'profile-address-delete', 'uses' => 'AddressController@deleteProfileAddress']);

    // Contact Phone
    Route::get('/profile/phone/edit', ['as' => 'profile-phone-edit', 'uses' => 'PhoneController@editProfilePhone']);
    Route::post('/profile/phone/save', ['as' => 'profile-phone-save', 'uses' => 'PhoneController@saveProfilePhone']);
    Route::post('/profile/phone/delete', ['as' => 'profile-phone-delete', 'uses' => 'PhoneController@deleteProfilePhone']);

    // List of funds
    Route::get('/list/funds/ajax', ['as' => 'ajax-list-funds', 'uses' => 'Donor\FundController@ajaxMyFunds']);
});


/* Agency */
/*Route::group(['middleware' => ['auth', 'agency'], 'prefix' => 'm'], function() {
    Route::get('/agency/home', ['as' => 'agency-home', 'uses' => 'Donor\FundController@index']);
    Route::get('/agency/funds', ['as' => 'agency-funds', 'uses' => 'Donor\FundController@myFunds']);
    Route::get('/agency/pool-performance', ['as' => 'agency-pool-performance', 'uses' => 'Agency\PerformanceController@poolPerformance']);
    Route::get('/agency/fund-performance/{fund_id}', ['as' => 'agency-fund-performance', 'uses' => 'Agency\PerformanceController@fundPerformance']);
});*/

/* Agency */


Route::group(['middleware' => ['auth', 'agency'], 'prefix' => 'm'], function() {
     Route::post('agency/home/test', function () {
    return 'POST request received.';
});

    Route::get('/agency/home',['as' => 'agency-home', 'uses' => 'Agency\AgencyAdvisorController@index']);
    Route::get('/agency/home/grant', ['as' => 'agency-grants','uses' => 'Agency\AgencyAdvisorController@grantDetail']);
    Route::get('/agency/home/gift', ['as' => 'agency-gifts', 'uses' => 'Agency\AgencyAdvisorController@giftDetail']);
    Route::get('/agency/home/grant-ajax', ['as' => 'agency-grants-ajax', 'uses' => 'Agency\AgencyAdvisorController@grantDetailAjax']);
    Route::get('/agency/home/gift-ajax', ['as' => 'agency-gift-ajax', 'uses' => 'Agency\AgencyAdvisorController@giftDetailAjax']);

    Route::get('/agency/funds', ['as' => 'agency-funds', 'uses' => 'Agency\AgencyAdvisorController@fund']);

    Route::get('/agency/list/funds/ajax', ['as' => 'agency-fund-list-ajax', 'uses' => 'Agency\AgencyAdvisorController@ajaxMyFunds']);

    Route::get('/agency/list/funds/ajax-graph', ['as' => 'agency-fund-list-ajax-graph', 'uses' => 'Agency\AgencyAdvisorController@ajaxMyFundsForGraph']);

    Route::get('/agency/fund/{id?}', ['as' => 'agency-fund', 'uses' => 'Donor\FundStatementController@fundStatement']);

    Route::get('/agency/grant-history/{id?}', ['as' => 'agency-grant-history', 'uses' => 'Donor\FundController@grantHistory']);

    Route::get('/agency/grant-history/{id?}/print', ['as' => 'agency-print-grant-history', 'uses' => 'Donor\FundController@printGrantHistory']);

    Route::get('/agency/grant-history/{id?}/csv', ['as' => 'agency-csv-grant-history', 'uses' => 'Donor\FundController@csvGrantHistory']);

    Route::get('/agency/gift-history/{id?}', ['as' => 'agency-gift-history', 'uses' => 'Donor\FundController@giftHistory']);

    Route::get('/agency/gift-history/{id?}/print', ['as' => 'agency-print-gift-history', 'uses' => 'Donor\FundController@printGiftHistory']);

    Route::get('/agency/gift-history/{id?}/csv', ['as' => 'agency-csv-gift-history', 'uses' => 'Donor\FundController@csvGiftHistory']);


    Route::get('/agency/client',['as' => 'agency-client', 'uses' => 'Agency\AgencyAdvisorController@client']);

    Route::get('/agency/client/ajax', ['as' => 'agency-client-ajax', 'uses' => 'Agency\AgencyAdvisorController@ajaxMyclient']);

    Route::get('/agency/client/{id}', ['as' => 'agency-client-detail', 'uses' => 'Agency\AgencyAdvisorController@clientDetail']);

    Route::get('/agency/today-agenda', ['as' => 'agency-today-agenda', 'uses' => 'Agency\AgencyAdvisorController@todaysAgenda']);

    Route::get('/agency/services', ['as' => 'agency-services', 'uses' => 'Agency\AgencyAdvisorServiceController@index']);

    Route::get('/agency/services/create-task', ['as' => 'agency-services-create-task', 'uses' => 'Agency\AgencyAdvisorServiceController@createTask']);

    Route::post('/agency/services/store-task', 'Agency\AgencyAdvisorServiceController@store')->name('storeTask');

    Route::get('/agency/task-list', ['as' => 'agency-task-list', 'uses' => 'Agency\AgencyAdvisorServiceController@taskList']);
    Route::get('/agency/delete-task', ['as' => 'agency-delete-task', 'uses' => 'Agency\AgencyAdvisorServiceController@taskDelete']);
    Route::get('/agency/task-detail', ['as' => 'agency-task-detail', 'uses' => 'Agency\AgencyAdvisorServiceController@taskDetail']);
    Route::get('/agency/task-update', ['as' => 'agency-task-update', 'uses' => 'Agency\AgencyAdvisorServiceController@taskUpdate']);
        
    #Route::get('/agency/funds', ['as' => 'agency-funds', 'uses' => 'Donor\FundController@myFunds']);

    Route::get('/agency/pool-performance', ['as' => 'agency-pool-performance', 'uses' => 'Agency\PerformanceController@poolPerformance']);
    Route::get('/agency/fund-performance/{fund_id}', ['as' => 'agency-fund-performance', 'uses' => 'Agency\PerformanceController@fundPerformance']);

    Route::get('/agency/services', ['as' => 'agency-services', 'uses' => 'Agency\AgencyAdvisorServiceController@index']);
    Route::get('/agency/services/create-task/{id?}', ['as' => 'agency-services-create-task', 'uses' => 'Agency\AgencyAdvisorServiceController@createTask']);
    Route::post('/agency/services/store-task', ['as' => 'agency-services-store-task', 'uses' => 'Agency\AgencyAdvisorServiceController@storeTask']);
    Route::post('/agency/services/store-task', 'Agency\AgencyAdvisorServiceController@store')->name('storeTask');
    
    Route::get('/agency/services/edit-task/{task_id}', 'Agency\AgencyAdvisorServiceController@editTask')->name('agency-services-edit-task');
    Route::put('/agency/all-task-update/{task_id}', 'agency\agencyadvisorservicecontroller@updatetask')->name('task.update');
    // Route::get('/agency/services/donor-email', ['as' => 'agency-donor-email', 'uses' => 'Agency\AgencyAdvisorServiceController@getDonorEmail']);

    Route::get('/agency/cart', ['as' => 'agency-cart', 'uses' => 'Agency\AgencyAdvisorCartController@index']);
    Route::get('/agency/cart-list', ['as' => 'agency-cart-list', 'uses' => 'Agency\AgencyAdvisorCartController@cartListAjax']);
    Route::get('/agency/cart-detail/{cart_id}', ['as' => 'agency-cart-detail', 'uses' => 'Agency\AgencyAdvisorCartController@cartdetail']);

    Route::get('/agency/cart-detail-ajax', ['as' => 'agency-cart-detail-ajax', 'uses' => 'Agency\AgencyAdvisorCartController@cartdetailAjax']);

    Route::post('/agency/sent-notification', ['as' => 'agency-sent-notification', 'uses' => 'NotificationController@sendNotification']);

    Route::get('/agency/notification-logs', ['as' => 'agency-notification-logs', 'uses' => 'NotificationController@notificationLogs']);


    

    Route::get('/agency/recommendation', ['as' => 'agency-recommendation','uses' => 'Agency\AgencyAdvisorController@recommendation']);
    
    Route::get('/agency/recommendation-ajax', ['as' => 'agency-recommendation-ajax', 'uses' => 'Agency\AgencyAdvisorController@ajaxRecommendation']);

    Route::get('/agency/recommendation-graph-ajax', ['as' => 'agency-recommendation-graph-ajax', 'uses' => 'Agency\AgencyAdvisorController@ajaxRecommendationGraph']);
    //Route::get('/agency/home/recommendation-ajax', ['as' => 'agency-recommendation-ajax', 'uses' => 'Agency\AgencyAdvisorController@recommendationAjax']);

     //Service-Tickets
  /*  Route::get('/agency/service-tickets', 'Agency\ServiceTicketController@ticketList')->name('agency-service-ticket');
    Route::get('/agency/service-tickets/create', 'Agency\ServiceTicketController@create')->name('agency-service-ticket-create');
    Route::post('/agency/service-tickets/store-tickets', 'agency\serviceticketcontroller@store')->name('tickets.store');
    Route::get('/agency/service-tickets/view/{ticket_id}', 'Agency\ServiceTicketController@viewTicket')->name('agency-service-ticket-view');
    // Route::get('/agency/service-tickets/ticket-detail', ['as' => 'agency-ticket-detail', 'uses' => 'Agency\ServiceTicketController@ticketDetail']);
    // Route::get('/agency/service-tickets/get-contact-info', 'Agency\ServiceTicketController@getContactInfo')->name('get-contact-info');
    Route::get('/agency/delete-ticket', ['as' => 'agency-delete-ticket', 'uses' => 'Agency\ServiceTicketController@ticketDelete']);
    Route::get('/agency/ticket-update-status', ['as' => 'agency-ticket-update', 'uses' => 'Agency\ServiceTicketController@ticketstatusUpdate']);
    Route::get('/agency/service-tickets/edit-ticket/{ticket_id}', 'Agency\ServiceTicketController@editTicket')->name('agency-services-edit-ticket');*/

    Route::get('/agency/ticket', ['as' => 'agency-ticket', 'uses' => 'Agency\ServiceTicketController@myTicket']);
    Route::get('/agency/ticket-ajax', ['as' => 'agency-ticket-ajax', 'uses' => 'Agency\ServiceTicketController@myTicketAjax']);

    //Route::get('/agency/ticket', 'Agency\ServiceTicketController@ticketList')->name('agency-service-ticket');
    Route::get('/agency/ticket/create', 'Agency\ServiceTicketController@create')->name('agency-service-ticket-create');
    Route::post('/agency/ticket/store-tickets', 'Agency\ServiceTicketController@store')->name('tickets.store');
    Route::get('/agency/ticket/view/{ticket_id}', 'Agency\ServiceTicketController@viewTicket')->name('agency-service-ticket-view');
    Route::get('/agency/delete-ticket', ['as' => 'agency-delete-ticket', 'uses' => 'Agency\ServiceTicketController@ticketDelete']);
    Route::get('/agency/ticket-update-status', ['as' => 'agency-ticket-update', 'uses' => 'Agency\ServiceTicketController@ticketClose']);

    Route::get('/agency/dashboard-ticket/edit/{ticket_id}', 'Agency\ServiceTicketController@editTicket')->name('agency-dashboard-edit-ticket');

    Route::get('/agency/ticket/edit/{ticket_id}', 'Agency\ServiceTicketController@editTicket')->name('agency-services-edit-ticket');

    Route::get('/agency/service-tickets/donor-email', ['as' => 'agency-donor-email', 'uses' => 'Agency\ServiceTicketController@getDonorEmail']);
    Route::put('/agency/ticket/update/{ticket_id}', 'Agency\ServiceTicketController@updateticket')->name('agency.ticket.update');
    Route::get('/agency/ticket/get-comment', ['as' => 'service-ticket-get-comment', 'uses' => 'Agency\ServiceTicketController@getComment']);
    Route::post('/agency/ticket/add-comment', ['as' => 'service-ticket-add-comment', 'uses' => 'Agency\ServiceTicketController@addComment']);

    Route::get('/agency/ticket/update-ticket-detail', ['as' => 'update-agency-ticket-detail', 'uses' => 'Agency\ServiceTicketController@updateTicketDetail']);

    #Reports
    Route::get('/agency/reports', ['as' => 'report-home', 'uses' => 'Agency\ReportsController@index']);
    Route::get('/report/{type}', ['as' => 'report-filter', 'uses' => 'Agency\ReportsController@getReportFilterForm']);
    Route::get('/report/{type}/{id}', ['as' => 'report-config', 'uses' => 'Agency\ReportsController@getReportConfig']);
    Route::get('/reports/view', ['as' => 'view-report', 'uses' => 'Agency\ReportsController@viewFilteredReports']);
    Route::post('/reports/export-csv', ['as' => 'export-report-csv', 'uses' => 'Agency\ReportsController@exportCsv']);

    Route::get('/support-staff/ticket', ['as' => 'support-staff-ticket', 'uses' => 'SupportStaff\ServiceTicketController@myTicket']);

    Route::get('/support-staff/ticket-ajax', ['as' => 'support-staff-ticket-ajax', 'uses' => 'SupportStaff\ServiceTicketController@myTicketAjax']);
    Route::get('/support-staff/ticket/view/{ticket_id}', 'SupportStaff\ServiceTicketController@viewTicket')->name('support-staff-ticket-view');

    Route::get('/support-staff/ticket/update-ticket-detail', ['as' => 'update-support-staff-ticket-detail', 'uses' => 'SupportStaff\ServiceTicketController@updateTicketDetail']);


    #meetings
    Route::get('/agency/googlemeet/authenticate', 'Agency\GoogleMeetController@Authenticate')->name('authentication.page');
    Route::get('/agency/googlemeet/view', 'Agency\GoogleMeetController@viewMeeting')->name('view.meeting');
    Route::get('/agency/googlemeet/create', 'Agency\GoogleMeetController@createMeetingForm')->name('create.meeting');
    Route::get('/agency/auth/google', 'Agency\GoogleMeetController@redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback', 'Agency\GoogleMeetController@handleGoogleCallback')->name('handle.callback');
    Route::get('/agency/google/calender', ['as' => 'view.calendar', 'uses' => 'Agency\GoogleMeetController@viewCalendar']);
    Route::post('/agency/calendar/event', ['as' => 'create.event', 'uses' => 'Agency\GoogleMeetController@createEventOnGoogleCalendar']);
    Route::get('/agency/google/refresh-token', 'Agency\GoogleMeetController@generateAccessTokenWithRefreshToken')->name('exchange.refresh.token');
    Route::post('/agency/delete-event', 'Agency\GoogleMeetController@deleteEvent')->name('delete.event');
    Route::post('/agency/google/update-event', 'Agency\GoogleMeetController@updateEvent')->name('update.event');
    Route::get('/email-suggestions', 'Agency\GoogleMeetController@getEmailSuggestions');

    //Task
   Route::post('/agency/calendar/task', ['as' => 'create.task', 'uses' => 'Agency\GoogleMeetController@storeTaskOnCalendar']);
   Route::post('/agency/calendar/task-delete', ['as' => 'delete.task', 'uses' => 'Agency\GoogleMeetController@deleteTaskFromCalendar']);
   Route::post('/agency/google/update-task', 'Agency\GoogleMeetController@updateTaskOnCalendar')->name('update.task');

   
    # Bell Notifications Advisor
    Route::get('/agency/notifications', ['as' => 'agency-notifications', 'uses' => 'NotificationController@advisorNotifications']);

    Route::get('/agency/notifications-ajax', ['as' => 'agency-notifications-ajax', 'uses' => 'NotificationController@advisorNotificationsListAjax']);

    # Manual Notifications
    Route::match(['get', 'post'], '/agency/send-manual-notification',['as' => 'agency-send-manual-notification',
        'uses' => 'NotificationController@sendManualNotification']);

    # Bell Notifications Support Staff
    Route::get('/support-staff/notifications', ['as' => 'support-staff-notifications', 'uses' => 'NotificationController@supportStaffNotifications']);

    Route::get('/support-staff/notifications-ajax', ['as' => 'support-staff-notifications-ajax', 'uses' => 'NotificationController@supportStaffNotificationsListAjax']);
    #new chairty
/*    Route::get('/agency/dashboard',['as' => 'agency-dashboard', 'uses' => 'Agency\AgencyAdvisorController@dashboard']);
    Route::get('/agency/preferences', ['as' => 'agency-preferences', 'uses' => 'Agency\AgencyAdvisorController@yourPreferences']);

    Route::get('/agency/charity/{id?}', ['as' => 'agency-charity', 'uses' => 'Agency\AgencyAdvisorController@charity']);

    Route::get('/agency/charity/{id?}/{fund_id?}', ['as' => 'agency-charity-fund-client', 'uses' => 'Agency\AgencyAdvisorController@charityFundClients']); */
    
    Route::get('/agency/dashboard',['as' => 'agency-dashboard', 'uses' => 'Agency\DashboardController@dashboard']);
    Route::get('/agency/dashboard/upgrad',['as' => 'agency-upgrad-dashboard', 'uses' => 'Agency\UpradDashboardController@index']);

    Route::get('/agency/dashboard/old',['as' => 'agency-old-dashboard', 'uses' => 'Agency\DashboardController@Olddashboard']);
    Route::get('/agency/preferences', ['as' => 'agency-preferences', 'uses' => 'Agency\DashboardController@yourPreferences']);
    Route::get('/agency/charity/{id?}', ['as' => 'agency-charity', 'uses' => 'Agency\DashboardController@charity']);


    # Open DAF  - Rajan
    Route::get('/agency/charity/daf/{id?}',['as' => 'agency-charity-daf', 'uses' => 'Agency\AgencyAdvisorController@agencyCharityDaf']);
    Route::post('/agency/charity/new-daf',['as' => 'agency-charity-new-daf', 'uses' => 'Agency\AgencyAdvisorController@agencyCharityNewdDaf']);

    
    Route::get('/agency/charity/{id?}/{fund_id?}', ['as' => 'agency-charity-fund-client', 'uses' => 'Agency\DashboardController@charityFundClients']);

    Route::post('/agency/user/preferences/save',['as' => 'user.preferences.save', 'uses' => 'Agency\DashboardController@savePreferences']);
    Route::post('/agency/save-chart-preference', 'Agency\DashboardController@saveChartPreferenceFromDashboard')->name('save.chart.preference'); 

});


/* DONOR OR AGENCY */
Route::group(['middleware' => ['auth', 'donor_or_agency'], 'prefix' => 'm'], function() {

    Route::get('/fund/{id?}', ['as' => 'fund', 'uses' => 'Donor\FundStatementController@fundStatement']);

    Route::get('/gift-history/{id?}', ['as' => 'gift-history', 'uses' => 'Donor\FundController@giftHistory']);
    Route::get('/grant-history/{id?}', ['as' => 'grant-history', 'uses' => 'Donor\FundController@grantHistory']);
    Route::get('/grant-history/{id?}/print', ['as' => 'print-grant-history', 'uses' => 'Donor\FundController@printGrantHistory']);
    Route::get('/grant-history/{id?}/csv', ['as' => 'csv-grant-history', 'uses' => 'Donor\FundController@csvGrantHistory']);
    Route::get('/gift-history/{id?}/print', ['as' => 'print-gift-history', 'uses' => 'Donor\FundController@printGiftHistory']);
    Route::get('/gift-history/{id?}/csv', ['as' => 'csv-gift-history', 'uses' => 'Donor\FundController@csvGiftHistory']);

    /* jcf file download */
    Route::get('/download/grant-calendar', ['as' => 'download-grant-calendar', 'uses' => 'Donor\FileController@downloadGrantCalendar']);
    Route::get('/download/performance-flash', ['as' => 'download-performance-flash', 'uses' => 'Donor\FileController@downloadPerformanceFlash']);

});


/* DONOR */
Route::group(['middleware' => ['auth', 'donor'], 'prefix' => 'm'], function() {
    /*
     * NOTE: pull out common features as and when required
     */

    Route::get('/donor/investments/{id?}', ['as' => 'get-investments', 'uses' => 'Donor\InvestmentsController@getInvestments']);
    Route::get('/donor/investments/{id?}/edit', ['as' => 'edit-investments', 'uses' => 'Donor\InvestmentsController@editInvestments']);
    Route::post('/donor/investments', ['as' => 'save-investments', 'uses' => 'Donor\InvestmentsController@saveInvestments']);

    // for testing
    Route::get('/donor/ajax-pool-performance', ['as' => 'ajax-donor-pool-performance', 'uses' => 'Donor\PerformanceController@ajaxPoolPerformance']);
    Route::get('/donor/pool-performance', ['as' => 'donor-pool-performance', 'uses' => 'Donor\PerformanceController@poolPerformance']);
    Route::get('/donor/fund-performance/{fund_id}', ['as' => 'donor-fund-performance', 'uses' => 'Donor\PerformanceController@fundPerformance']);

    // Route::get('/', function () {})->withoutMiddleware(['donor']);
    // Route::get('/', function () { return view('auth.login'); });

    Route::get('/home', ['as' => 'donor-home', 'uses' => 'Donor\FundController@index']);
    Route::get('/api/home', ['as' => 'api-home', 'uses' => 'Donor\FundController@apiMyFunds']);

    Route::get('/ajax/pending-grants', ['as' => 'pending-grants', 'uses' => 'Donor\FundController@ajaxPendingGrants']);

    Route::get('/pending-disbursements/{id?}', ['as' => 'pending-disbursements', 'uses' => 'Donor\FundController@pendingDisbursements']);
    Route::get('api/pending-disbursements/{id?}', ['uses' => 'Donor\FundController@apiPendingDisbursements']);

    Route::get('/make-a-grant/create', ['as' => 'grant-create', 'uses' => 'Donor\GrantController@create']);
    Route::get('/make-a-grant/edit/{id?}', ['as' => 'grant-edit', 'uses' => 'Donor\GrantController@edit']);
    Route::post('/make-a-grant/save', ['as' => 'add-to-cart', 'uses' => 'Donor\GrantController@saveGrant']);
    Route::get('/make-a-grant/remove/{id}', ['as' => 'grant-remove', 'uses' => 'Donor\GrantController@removeGrant']);
    Route::get('/ajax/make-a-grant/select-grant-from', ['uses' => 'Donor\GrantController@selectGrantFrom']);
    Route::get('/ajax/make-a-grant/selected-grant-from', ['uses' => 'Donor\GrantController@selectedGrantFrom']);

    # Bell Notifications 
    Route::get('/notifications', ['as' => 'donor-notifications', 'uses' => 'NotificationController@donorNotifications']);

     Route::get('/notifications-ajax', ['as' => 'donor-notification-ajax', 'uses' => 'NotificationController@donorNotificationsListAjax']);


    Route::get('/ticket', ['as' => 'ticket', 'uses' => 'Donor\MyTicketController@myTicket']);
    Route::get('/ticket-ajax', ['as' => 'ticket-ajax', 'uses' => 'Donor\MyTicketController@myTicketAjax']);

    Route::get('/ticket/view/{ticket_id}', ['as' => 'ticket-view', 'uses' => 'Donor\MyTicketController@viewMyTicket']);

    Route::get('/ticket/get-comment', ['as' => 'ticket-get-comment', 'uses' => 'Donor\MyTicketController@getTicketComment']);

    Route::post('/ticket/add-comment', ['as' => 'ticket-add-comment', 'uses' => 'Donor\MyTicketController@addTicketComment']);

    Route::get('/ticket/create', 'Donor\MyTicketController@create')->name('donor-service-ticket-create');
    Route::post('/ticket/store', 'Donor\MyTicketController@store')->name('donor.tickets.store');
    Route::get('/ticket/delete', ['as' => 'ticket-delete', 'uses' => 'Donor\MyTicketController@ticketDelete']);
    Route::get('/ticket/advisor-list', ['as' => 'agency-advisor-list', 'uses' => 'Donor\MyTicketController@getAdvisorlist']);
    Route::get('/ticket/close', ['as' => 'ticket-close', 'uses' => 'Donor\MyTicketController@ticketClose']);
    Route::get('/ticket/edit/{ticket_id}', 'Donor\MyTicketController@editTicket')->name('edit-ticket');
    Route::put('/ticket/update/{ticket_id}', 'Donor\MyTicketController@updateticket')->name('ticket.update.donor');
    Route::get('/ticket/update-ticket-detail', ['as' => 'update-ticket-detail', 'uses' => 'Donor\MyTicketController@updateTicketDetail']);




    Route::post('/my-cart/checkout/{confirmed?}', ['as' => 'checkout', 'uses' => 'Donor\GrantController@checkout']);

    Route::get('/my-cart', ['as' => 'my-cart', 'uses' => 'Donor\GrantController@myCart']);
    Route::get('/search/organizations', 'SearchController@searchOrgs');
    Route::get('/search/catalog/organizations', 'SearchController@searchCatalogOrgs');

    Route::get('/api/gift-history/{id?}', 'Donor\FundController@apiGiftHistory');
    Route::get('/api/grant-history/{id?}', 'Donor\FundController@apiGrantHistory');
    Route::get('/api/fund/{id?}', ['as' => 'api-fund', 'uses' => 'Donor\FundStatementController@apiFundStatement']);

    // contribute - transactions - payment - authorise.net
    Route::get('/transaction/response/{rid}', ['as' => 'transaction-response', 'uses' => 'Donor\TransactionController@response']);
    Route::get('/transactions', ['as' => 'transactions', 'uses' => 'Donor\TransactionController@index']);
    Route::get('/contribute', ['as' => 'contribute', 'uses' => 'Donor\TransactionController@contribute']);
    Route::post('/authorize-net/make-payment', ['as' => 'authorize-net-payment', 'uses' => 'AuthorizeNetController@makePayment']);
    Route::post('/authorize-net/make-refund', ['as' => 'authorize-net-refund', 'uses' => 'AuthorizeNetController@makeRefund']);

    // stripe payment
    Route::post('/stripe/payment-intent', ['as' => 'stripe.make-payment', 'uses' => 'Donor\StripeController@createPaymentIntent']);
    Route::get('/stripe/payment-status', ['as' => 'payment-status', 'uses' => 'Donor\StripeController@afterPayment']);
    Route::get('/stripe/payment-error', ['as' => 'on-payment-error', 'uses' => 'Donor\StripeController@onPaymentError']);

    // Contact - Profile - Donor Interests
    // Route::get('/profile/interests', ['as' => 'profile-interests', 'uses' => 'ProfileController@profileInterests']);
    Route::get('/profile/interests', ['as' => 'profile-interests', 'uses' => 'ProfileController@profileInterests']);
    Route::get('/profile/interests/edit', ['as' => 'profile-interests-edit', 'uses' => 'ProfileController@editProfileInterests']);
    Route::post('/profile/interests/save', ['as' => 'profile-interests-save', 'uses' => 'ProfileController@saveProfileInterests']);

    // charitable catalog
    Route::get('/catalog', ['as' => 'charitable-catalog', 'uses' => 'CharityController@index']);
    Route::get('/catalog/programs', ['as' => 'cc-program-matches', 'uses' => 'CharityController@index']);

    Route::get('/catalog/orgs-catalog', ['as' => 'organizations-catalog', 'uses' => 'CharityController@organizationsCatalog']);
    Route::get('/catalog/orgs/search', ['as' => 'search-organizations', 'uses' => 'CharityController@searchedOrganizations']);
    Route::get('/catalog/orgs-by-interest-area', ['as' => 'orgs-by-interest-area', 'uses' => 'CharityController@organizationsByInterestArea']);


    Route::get('/catalog/orgs/ajax', ['as' => 'ajax-orgs-catalog', 'uses' => 'CharityController@ajaxOrganizationCatalog']);

    Route::get('/catalog/orgs/matches', ['as' => 'organization-matches', 'uses' => 'CharityController@organizationMatches']);
    Route::get('/catalog/orgs/matches/ajax', ['uses' => 'CharityController@ajaxOrganizationMatches']);
    Route::get('/catalog/programs/matches', ['as' => 'programs-matches', 'uses' => 'CharityController@projectsMatches']);
    Route::get('/catalog/programs/matches/ajax', ['uses' => 'CharityController@ajaxProjectsMatches']);

    Route::get('/catalog/organization/{id}', ['as' => 'organization', 'uses' => 'CharityController@showOrganization']);
    Route::get('/catalog/organization/{id}/print', ['as' => 'print-organization', 'uses' => 'CharityController@printOrganization']);

    Route::get('/catalog/programs-catalog', ['as' => 'programs-catalog', 'uses' => 'CharityController@programsCatalog']);
    Route::get('/catalog/programs-by-interest-area', ['as' => 'programs-by-interest-area', 'uses' => 'CharityController@programsByInterestArea']);
    Route::get('/catalog/program/{id}', ['as' => 'program', 'uses' => 'CharityController@showProgram']);
    Route::get('/catalog/program/{id}/print', ['as' => 'print-program', 'uses' => 'CharityController@printProgram']);

    // Route::get('/project-matches', ['as' => 'project-matches', 'uses' => 'ProfileController@projectMatches']);
    Route::get('/project/{id}', ['as' => 'project', 'uses' => 'ProfileController@project']);

    // Route::get('/organization-matches', ['as' => 'organization-matches', 'uses' => 'ProfileController@organizationMatches']);

    Route::get('/api/catalog', ['as' => 'api-charitable-catalog', 'uses' => 'CharityController@apiIndex']);
    // Route::get('/api/project-matches', ['as' => 'api-project-matches', 'uses' => 'ProfileController@apiProjectMatches']);
    // Route::get('/api/organization-matches', ['as' => 'api-organization-matches', 'uses' => 'ProfileController@apiOrganizationMatches']);

    Route::post('/request-info', ['as' => 'request-info', 'uses' => 'CharityController@requestInfo']);


    // document download
    Route::get('/my-documents', ['as' => 'my-documents', 'uses' => 'Donor\FileController@myDocuments']);
    Route::get('/my-documents/list/{type}', ['as' => 'my-document-list', 'uses' => 'Donor\FileController@myDocumentList']);
    Route::get('/my-documents/download/{key}', ['as' => 'download-documents', 'uses' => 'Donor\FileController@downloadDocument']);

    // statement download
    Route::get('/my-statements', ['as' => 'my-statements', 'uses' => 'Donor\FileController@myStatements']);
    Route::get('/my-statements/{fund_id}/ajax', ['as' => 'my-statements-ajax', 'uses' => 'Donor\FileController@myStatementAjax']);
    Route::get('/my-statements/download/{file}', ['as' => 'download-statements', 'uses' => 'Donor\FileController@downloadStatement']);

    // document upload
    Route::get('/my-documents/upload/{type}', ['as' => 'document-upload', 'uses' => 'Donor\FileController@documentUpload']);
    Route::post('/my-documents/upload/{type}', ['as' => 'document-upload-post', 'uses' => 'Donor\FileController@documentUploadPost']);

    // forms
    Route::get('/hga/forms', ['as' => 'forms', 'uses' => 'Donor\FormsController@index']);
    Route::get('/hga/investment-fund-performance', ['as' => 'investment-fund-performance', 'uses' => 'Donor\InvestmentsController@investmentFundPerformance']);
    Route::get('/hga/research-investment-options', ['as' => 'research-investment-options', 'uses' => 'Donor\InvestmentsController@researchInvestmentOptions']);

    // existing donor opens a new daf-application
    Route::get('open-daf', ['as' => 'new-daf-application', 'uses' => 'Donor\DAFAccountController@newDafApplication']);

    //Recurring grants
    Route::get('/recurring-grants/{id?}', ['as' => 'recurring-grants', 'uses' => 'Donor\FundRecommendationController@recurringGrants']);
    Route::get('/recurring-grants/{id}/cancel', ['as' => 'cancel-recurring-grant', 'uses' => 'Donor\FundRecommendationController@cancel']);

    // Advisors
    Route::get('/fund-advisors', ['as' => 'fund-advisors', 'uses' => 'AdvisorController@fundAdvisors']);

    // program-articles
    Route::get('/initiatives', ['as' => 'content.programs', 'uses' => 'ContentController@programs']);
    Route::get('/initiatives/{id}', ['as' => 'content.programs.show', 'uses' => 'ContentController@showProgram']);

});


//Route::post('/save-grant', 'Donor\FundController@saveGrant');
//Route::get('api/fund/make-a-grant', 'Donor\FundController@apiMakeGrant');
//Route::get('/api/my-cart', ['as' => 'api-my-cart', 'uses' => 'Donor\GrantController@apiMyCart']);


// Route::domain('{account}.myapp.com')->group(function () {
Route::namespace('Admin')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
});


//Route::group(['middleware' => ['auth', 'seeker']], function() {

Route::group(['middleware' => ['auth'], 'prefix' => 'm'], function() {
    Route::get('/gs/home', function() {
        return view('seeker.home.dashboard');
    })->name('gs-home');

    Route::get('/gs/account/contact-profile', ['as' => 'gs-account-contact-profile', 'uses' => 'Seeker\AccountController@contactProfile']);
    Route::get('/gs/account/add-profile', ['as' => 'gs-account-add-profile', 'uses' => 'Seeker\AccountController@addProfile']);
    Route::post('/gs/account/save-profile', ['as' => 'gs-account-profile-save', 'uses' => 'Seeker\AccountController@saveProfile']);
    Route::get('/gs/account/edit-profile', ['as' => 'gs-account-edit-profile', 'uses' => 'Seeker\AccountController@editProfile']);

    Route::get('/gs/account/my-profile', ['as' => 'gs-account-my-profile', 'uses' => 'Seeker\AccountController@myProfile']);
    Route::get('/gs/account/info', ['as' => 'gs-account-info', 'uses' => 'Seeker\AccountController@info']);

    Route::get('gs/assistant', ['as' => 'gs-assistant', 'uses' => 'Seeker\AccountController@getAssistant']);
    Route::post('gs/assistant', ['uses' => 'Seeker\AccountController@saveAssistant']);

    Route::get('/gs/org/profile', ['as' => 'gs-org-profile', 'uses' => 'Seeker\OrganizationController@profile']);
    Route::get('/gs/org/edit-profile', ['as' => 'gs-org-edit-profile', 'uses' => 'Seeker\OrganizationController@editProfile']);
    Route::post('/gs/org/edit-profile', ['as' => 'gs-org-save-profile', 'uses' => 'Seeker\OrganizationController@saveProfile']);

    Route::get('/gs/org/staff-management', ['as' => 'gs-org-staff-management', 'uses' => 'Seeker\OrganizationController@staffManagement']);
    Route::post('/gs/org/update-staff-access-level', ['as' => 'gs-org-update-staff-access-level', 'uses' => 'Seeker\OrganizationController@updateStaffAccessLevel']);
    Route::post('/gs/org/update-org-default-contact', ['as' => 'gs-org-update-org-default-contact', 'uses' => 'Seeker\OrganizationController@updateOrgDefaultContact']);
    Route::post('/gs/org/update-contact-receive-email', ['as' => 'gs-org-update-contact-receive-email', 'uses' => 'Seeker\OrganizationController@updateContactReceiveEmail']);
    Route::post('/gs/org/update-staff-status', ['as' => 'gs-org-update-staff-status', 'uses' => 'Seeker\OrganizationController@updateStaffStatus']);

    Route::get('/gs/org/organization-story', ['as' => 'gs-org-organization-story', 'uses' => 'Seeker\OrganizationController@organizationStory']);
    Route::post('/gs/org/organization-story', ['as' => 'gs-org-organization-story-save', 'uses' => 'Seeker\OrganizationController@saveOrganizationStory']);

    Route::get('/gs/org/interest-areas', ['as' => 'gs-org-interest-areas', 'uses' => 'Seeker\OrganizationController@interestAreas']);
    Route::post('/gs/org/interest-areas', ['as' => 'gs-org-interest-areas-save', 'uses' => 'Seeker\OrganizationController@saveInterestAreas']);

    Route::get('/gs/org/budget', ['as' => 'gs-org-budget', 'uses' => 'Seeker\OrganizationController@budget']);
    Route::post('/gs/org/budget', ['as' => 'gs-org-budget-save', 'uses' => 'Seeker\OrganizationController@saveBudget']);

    Route::get('/gs/org/tax-information', ['as' => 'gs-org-tax-information', 'uses' => 'Seeker\OrganizationController@taxInformation']);
    Route::post('/gs/org/tax-information', ['as' => 'gs-org-tax-information-save', 'uses' => 'Seeker\OrganizationController@saveTaxInformation']);


    Route::get('/gs/org/documentation', ['as' => 'gs-org-documentation', 'uses' => 'Seeker\OrganizationController@documentation']);


    Route::get('/gs/org/goals', ['as' => 'gs-org-goals', 'uses' => 'Seeker\OrganizationController@goals']);
    Route::post('/gs/org/goals', ['as' => 'gs-org-goals-save', 'uses' => 'Seeker\OrganizationController@saveGoals']);

    Route::get('/gs/org/board-members', ['as' => 'gs-org-board-members', 'uses' => 'Seeker\OrganizationController@boardMembers']);
    Route::post('/gs/org/board-members', ['as' => 'gs-org-board-members-save', 'uses' => 'Seeker\OrganizationController@saveBoardMembers']);

    Route::get('/gs/org/population-served', ['as' => 'gs-org-population-served', 'uses' => 'Seeker\OrganizationController@populationServed']);
    Route::post('/gs/org/population-served', ['as' => 'gs-org-population-served-save', 'uses' => 'Seeker\OrganizationController@savePopulationServed']);


    Route::get('/gs/org/certifications', ['as' => 'gs-org-certifications', 'uses' => 'Seeker\OrganizationController@certifications']);
    Route::post('/gs/org/certifications', ['as' => 'gs-org-certifications-save', 'uses' => 'Seeker\OrganizationController@saveCertifications']);


    Route::get('/gs/org/edit-staff', ['as' => 'gs-org-edit-staff', 'uses' => 'Seeker\OrganizationController@editStaff']);
    Route::get('/gs/org/edit-story', ['as' => 'gs-org-edit-story', 'uses' => 'Seeker\OrganizationController@editStory']);


    // Organization Phone
    Route::get('/gs/org/phone/edit/{organization_id}/{phone_type}', ['as' => 'gs-org-phone-edit', 'uses' => 'PhoneController@editOrgPhone']);
    Route::post('/gs/org/phone/save', ['as' => 'gs-org-phone-save', 'uses' => 'PhoneController@saveOrgPhone']);
    Route::post('/gs/org/phone/delete', ['as' => 'gs-org-phone-delete', 'uses' => 'PhoneController@deleteOrgPhone']);

    // Contact Address
    Route::get('/gs/org/address/edit/{organization_id}/{address_type}', ['as' => 'gs-org-address-edit', 'uses' => 'AddressController@editOrgAddress']);
    Route::post('/gs/org/address/save', ['as' => 'gs-org-address-save', 'uses' => 'AddressController@saveOrgAddress']);
    Route::post('/gs/org/address/delete', ['as' => 'gs-org-address-delete', 'uses' => 'AddressController@deleteOrgAddress']);

    // Organization Basic Info
    Route::get('/gs/org/info/edit/{id}', ['as' => 'gs-org-profile-edit', 'uses' => 'Seeker\OrganizationController@editOrgProfile']);
    Route::post('/gs/org/save', ['as' => 'gs-org-profile-save', 'uses' => 'Seeker\OrganizationController@saveOrgProfile']);
});


Route::group(['middleware' => 'auth', 'prefix' => 'm/super-admin'], function() {

    Route::get('/orgs-with-image', function($id) {
        $models = \App\Models\Organization::get();
        $model = \App\Models\Organization::find($id);
        return ["organization_id" => $model->organization_id, "photo_url" => $model->photo_url, "logo_url" => $model->logo_url];
    });

    Route::get('/org-info/{id}', function($id) {
        $model = \App\Models\Organization::find($id);
        return ["organization_id" => $model->organization_id, "photo_url" => $model->photo_url, "logo_url" => $model->logo_url];
    });

    Route::get('/send-test-mail', function() {

        return \App\Helpers\GnUtils::configEmailsToString(\App\Models\ClientEnv::value('EMAIL_CLIENT_ADMIN'));

        $to = explode(',', \App\Helpers\GnUtils::configEmailsToString(\App\Models\ClientEnv::value('EMAIL_CLIENT_ADMIN')));
        return $to;

        // $to = 'alkesh@sageite.com';
        $to = [
            ["email" => "alkeshksingh@giftingnetwork.com"],
            ["email" => "alkeshkumar@gmail.com"]
        ];
        // $to = ["alkeshksingh@giftingnetwork.com", "alkesh@sageite.com"];
        $name = 'Alkesh Kr Singh';
        // return new \App\Mail\SendTestMail($to, $name);
        $result =  \App\Models\Email::sendTestEmail($to, $name);
        return [$result];
    });
    Route::get('/paginate-jobs', function() {
        return \App\Models\Jobs::getPaginated();
    });
    Route::get('/paginate-failed-jobs', function() {
        return \App\Models\FailedJobs::getPaginated();
    });
    Route::get('/paginate-email-archive', function() {
        return \App\Models\EmailArchive::getPaginated();
    });

    Route::get('/pool-performance-data', function() {

        $accountId = '14007';
        $accountType = 'pool';

        $composition = \App\Models\PerformanceData::getComposition($accountId, $accountType);
        return $composition;
    });

});

// external pages - FIG
// Route::get('/{page}', ['as' => 'external-page', 'uses' => 'HomeController@externalPage']);

/* Common */
Route::get('/m/welcome', ['as' => 'welcome', 'uses' => 'HomeController@root']);
Route::any('/m', ['uses' => 'HomeController@root']);
Route::get('/', ['as' => 'root', 'uses' => 'HomeController@root']);

Route::get('/demo', function() {
    return view('demo.registration');
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

Route::prefix('/m/registration')->group(function () {

    Route::get('login', function () {
        return view('demo.login');
    });
    Route::get('thank-you', function () {
        return view('demo.thank-you');
    });

    // Daf registration
    Route::get('daf-account', ['as' => 'daf-account', 'uses' => 'Donor\DAFAccountController@formAccount']);
    Route::post('daf-account', ['as' => 'post-daf-account', 'uses' => 'Donor\DAFAccountController@saveAccount']);
    Route::get('/activate/account/{token}', ['as' => 'activate-daf-form', 'uses' => 'Donor\DAFAccountController@activateDafAccount']);
    Route::get('account-activation/{id}', ['as' => 'get-account-activation-link', 'uses' => 'Donor\DAFAccountController@getAccountActivationLink']);
    Route::post('resend-activation-link', ['as' => 'resend-account-activation-link', 'uses' => 'Donor\DAFAccountController@resendAccountActivationLink']);

});

//Route::group(['middleware' => ['auth'], 'prefix' => 'm/agency/daf'], function() {
Route::group(['middleware' => ['auth', 'agency'], 'prefix' => 'm'], function() {

    Route::post('agency/create-daf-account', ['as' => 'post-agency-create-daf-account', 'uses' => 'Agency\DAFAccountController@createDafAccount']);

    Route::get('/agency/daf-accounts', ['as' => 'agency-daf-accounts', 'uses' => 'Agency\DAFAccountController@dafAccounts']);

    Route::get('agency/daf-account/{id?}', ['as' => 'agency-daf-account', 'uses' => 'Agency\DAFAccountController@formAccount']);
    Route::post('agency/daf-account', ['as' => 'post-agency-daf-account', 'uses' => 'Agency\DAFAccountController@saveAccount']);

    Route::post('daf-account', ['as' => 'post-daf-account', 'uses' => 'Agency\DAFAccountController@saveAccount']);

    Route::get('agency/daf/{id?}', ['as' => 'daf-account-home', 'uses' => 'Agency\DAFAccountController@index']);

    Route::get('agency/account-info/{id?}', ['as' => 'agency-daf-account-info', 'uses' => 'Agency\DAFAccountController@formDonorInfo']);
    Route::post('agency/account-info/{id}', ['as' => 'post-agency-daf-account-info', 'uses' => 'Agency\DAFAccountController@saveDonorInfo']);

    Route::get('agency/additional-donor/{id}', ['as' => 'agency-daf-additional-donor', 'uses' => 'Agency\DAFAccountController@formAdditionalDonor']);
    Route::post('agency/additional-donor/{id}', ['as' => 'post-agency-daf-additional-donor', 'uses' => 'Agency\DAFAccountController@saveAdditionalDonor']);
    Route::post('agency/delete-additional-donor/{id}', ['as' => 'delete-agency-additional-donor', 'uses' => 'Agency\DAFAccountController@deleteAdditionalDonor']);

    Route::get('agency/daf-type/{id?}', ['as' => 'agency-daf-type', 'uses' => 'Agency\DAFAccountController@formDAFType']);
    Route::post('agency/save-daf-type/{id}', ['as' => 'agency-save-daf-type', 'uses' => 'Agency\DAFAccountController@storeDAFType']);

    Route::get('agency/successors/{id}', ['as' => 'agency-daf-successors', 'uses' => 'Agency\DAFAccountController@formSuccessors']);
    Route::post('agency/successors/{id}', ['as' => 'post-agency-daf-successors', 'uses' => 'Agency\DAFAccountController@saveSuccessors']);
    
    Route::get('agency/successors-individuals/{id}', ['as' => 'agency-daf-successors-individuals', 'uses' => 'Agency\DAFAccountController@formSuccessorsIndividuals']);
    Route::post('agency/successors-individuals/{id}', ['as' => 'post-agency-daf-successors-individuals', 'uses' => 'Agency\DAFAccountController@saveSuccessorsIndividuals']);
    Route::get('agency/successors-individual/delete/{id}', ['as' => 'delete-agency-successors-individual', 'uses' => 'Agency\DAFAccountController@deleteSuccessorsIndividual']);

    Route::get('agency/individual-form-error/{id}', ['as' => 'agency-daf-individual-form-errors', 'uses' => 'Agency\DAFAccountController@individualFormErrors']);

    Route::get('agency/successors-organizations/{id}', ['as' => 'agency-daf-successors-organizations', 'uses' => 'Agency\DAFAccountController@formSuccessorsOrganizations']);
    Route::post('agency/successors-organizations/{id}', ['as' => 'post-agency-daf-successors-organizations', 'uses' => 'Agency\DAFAccountController@saveSuccessorsOrganizations']);
    Route::get('agency/successors-organization/delete/{id}', ['as' => 'delete-agency-successors-organization', 'uses' => 'Agency\DAFAccountController@deleteSuccessorOrganization']);
    Route::get('agency/org-form-error/{id}', ['as' => 'agency-daf-org-form-errors', 'uses' => 'Agency\DAFAccountController@orgFormErrors']);

    Route::get('agency/contributions/{id}', ['as' => 'agency-daf-contributions', 'uses' => 'Agency\DAFAccountController@formContributions']);
    Route::get('agency/contributions-cash/{id}', ['as' => 'agency-daf-contributions-cash', 'uses' => 'Agency\DAFAccountController@formContributionsCash']);
    Route::post('agency/contributions-cash/{id}', ['as' => 'post-agency-daf-contributions-cash', 'uses' => 'Agency\DAFAccountController@saveContributionsCash']);

    Route::get('agency/contributions-securities/{id}', ['as' => 'agency-daf-contributions-securities', 'uses' => 'Agency\DAFAccountController@formContributionsSecurities']);
    Route::post('agency/contributions-securities/{id}', ['as' => 'post-agency-daf-contributions-securities', 'uses' => 'Agency\DAFAccountController@saveContributionsSecurities']);
    Route::get('agency/contributions-security/delete/{id}', ['as' => 'delete-agency-contributions-security', 'uses' => 'Agency\DAFAccountController@deleteContributionSecurity']);
    Route::get('agency/security-form-error/{id}', ['as' => 'agency-daf-security-form-errors', 'uses' => 'Agency\DAFAccountController@securityFormErrors']);

    Route::get('agency/contributions-stocks/{id}', ['as' => 'agency-daf-contributions-stocks', 'uses' => 'Agency\DAFAccountController@formContributionsStocks']);
    Route::post('agency/contributions-stocks/{id}', ['as' => 'post-agency-daf-contributions-stocks', 'uses' => 'Agency\DAFAccountController@saveContributionsStocks']);
    Route::get('agency/contributions-stock/delete/{id}', ['as' => 'delete-agency-contributions-stock', 'uses' => 'Agency\DAFAccountController@deleteContributionsStock']);
                                                        
    Route::get('agency/contributions-others/{id}', ['as' => 'agency-daf-contributions-others', 'uses' => 'Agency\DAFAccountController@formContributionsOthers']);
    Route::post('agency/contributions-others/{id}', ['as' => 'post-agency-daf-contributions-others', 'uses' => 'Agency\DAFAccountController@saveContributionsOthers']);

    Route::get('agency/investments/{id}', ['as' => 'agency-daf-investments', 'uses' => 'Agency\DAFAccountController@formInvestments']);
    Route::post('agency/investments/{id}', ['as' => 'post-agency-daf-investments', 'uses' => 'Agency\DAFAccountController@saveInvestments']);
    
    Route::get('agency/authorization/{id}', ['as' => 'agency-daf-authorization', 'uses' => 'Agency\DAFAccountController@formAuthorization']);
    Route::post('agency/authorization/{id}', ['as' => 'post-agency-daf-authorization', 'uses' => 'Agency\DAFAccountController@saveAuthorization']);

    Route::get('agency/application-status/{id}', ['as' => 'agency-daf-application-status', 'uses' => 'Agency\DAFAccountController@formReviewApplication']);
    Route::get('agency/application-download/{id}', ['as' => 'agency-daf-application-download', 'uses' => 'Agency\DAFAccountController@downloadApplication']);




    Route::get('account-info/{id?}', ['as' => 'daf-account-info', 'uses' => 'Agency\DAFAccountController@formDonorInfo']);
    Route::post('account-info/{id}', ['as' => 'post-daf-account-info', 'uses' => 'Donor\DAFAccountController@saveDonorInfo']);

    Route::get('additional-donor/{id}', ['as' => 'daf-additional-donor', 'uses' => 'Donor\DAFAccountController@formAdditionalDonor']);
    Route::post('additional-donor/{id}', ['as' => 'post-daf-additional-donor', 'uses' => 'Donor\DAFAccountController@saveAdditionalDonor']);
    Route::post('delete-additional-donor/{id}', ['as' => 'delete-additional-donor', 'uses' => 'Donor\DAFAccountController@deleteAdditionalDonor']);

    Route::get('successors/{id}', ['as' => 'daf-successors', 'uses' => 'Donor\DAFAccountController@formSuccessors']);
    Route::post('successors/{id}', ['as' => 'post-daf-successors', 'uses' => 'Donor\DAFAccountController@saveSuccessors']);

    Route::get('individual-form-error/{id}', ['as' => 'daf-individual-form-errors', 'uses' => 'Donor\DAFAccountController@individualFormErrors']);
    Route::get('org-form-error/{id}', ['as' => 'daf-org-form-errors', 'uses' => 'Donor\DAFAccountController@orgFormErrors']);
    Route::get('security-form-error/{id}', ['as' => 'daf-security-form-errors', 'uses' => 'Donor\DAFAccountController@securityFormErrors']);

    Route::get('successors-individuals/{id}', ['as' => 'daf-successors-individuals', 'uses' => 'Donor\DAFAccountController@formSuccessorsIndividuals']);
    //Route::post('successors-individuals', ['as' => 'daf-successors-individuals', 'uses' => 'Donor\DAFAccountController@saveSuccessorsIndividuals']);
    Route::post('successors-individuals/{id}', ['as' => 'post-daf-successors-individuals', 'uses' => 'Donor\DAFAccountController@saveSuccessorsIndividuals']);
    Route::get('successors-individual/delete/{id}', ['as' => 'delete-successors-individual', 'uses' => 'Donor\DAFAccountController@deleteSuccessorsIndividual']);

    Route::get('successors-organizations/{id}', ['as' => 'daf-successors-organizations', 'uses' => 'Donor\DAFAccountController@formSuccessorsOrganizations']);
    Route::post('successors-organizations/{id}', ['as' => 'post-daf-successors-organizations', 'uses' => 'Donor\DAFAccountController@saveSuccessorsOrganizations']);
    Route::get('successors-organization/delete/{id}', ['as' => 'delete-successors-organization', 'uses' => 'Donor\DAFAccountController@deleteSuccessorOrganization']);

    Route::post('successors-endowment/{id}', ['as' => 'post-daf-successors-endowment', 'uses' => 'Donor\DAFAccountController@saveSuccessorsEndowment']);

    //Route::get('/search/organizations', 'SearchController@searchOrgs');

    Route::get('contributions/{id}', ['as' => 'daf-contributions', 'uses' => 'Donor\DAFAccountController@formContributions']);

    Route::get('contributions-cash/{id}', ['as' => 'daf-contributions-cash', 'uses' => 'Donor\DAFAccountController@formContributionsCash']);
    Route::post('contributions-cash/{id}', ['as' => 'post-daf-contributions-cash', 'uses' => 'Donor\DAFAccountController@saveContributionsCash']);

    Route::get('contributions-securities/{id}', ['as' => 'daf-contributions-securities', 'uses' => 'Donor\DAFAccountController@formContributionsSecurities']);
    Route::post('contributions-securities/{id}', ['as' => 'post-daf-contributions-securities', 'uses' => 'Donor\DAFAccountController@saveContributionsSecurities']);
    Route::get('contributions-security/delete/{id}', ['as' => 'delete-contributions-security', 'uses' => 'Donor\DAFAccountController@deleteContributionSecurity']);


    Route::get('contributions-stocks/{id}', ['as' => 'daf-contributions-stocks', 'uses' => 'Donor\DAFAccountController@formContributionsStocks']);
    Route::post('contributions-stocks/{id}', ['as' => 'post-daf-contributions-stocks', 'uses' => 'Donor\DAFAccountController@saveContributionsStocks']);
    Route::get('contributions-stock/delete/{id}', ['as' => 'delete-contributions-stock', 'uses' => 'Donor\DAFAccountController@deleteContributionsStock']);

    Route::get('contributions-others/{id}', ['as' => 'daf-contributions-others', 'uses' => 'Donor\DAFAccountController@formContributionsOthers']);
    Route::post('contributions-others/{id}', ['as' => 'post-daf-contributions-others', 'uses' => 'Donor\DAFAccountController@saveContributionsOthers']);


    // DAF CC PAYMENTS
    Route::post('contributions-cc/{id}', ['as' => 'post-daf-contributions-cc', 'uses' => 'Donor\DAFAccountController@saveCCPayments']);
    Route::post('authorize-net/contribution-cc', ['as' => 'authorize-net-cc-payment', 'uses' => 'AuthorizeNetController@makeDAFContributionPayment']);
    Route::get('transaction/response/{rid}', ['as' => 'daf-transaction-response', 'uses' => 'Donor\DAFAccountController@dafPaymentResponse']);


    Route::get('investments/{id}', ['as' => 'daf-investments', 'uses' => 'Donor\DAFAccountController@formInvestments']);
    Route::post('investments/{id}', ['as' => 'post-daf-investments', 'uses' => 'Donor\DAFAccountController@saveInvestments']);

    Route::get('authorization/{id}', ['as' => 'daf-authorization', 'uses' => 'Donor\DAFAccountController@formAuthorization']);
    Route::post('authorization/{id}', ['as' => 'post-daf-authorization', 'uses' => 'Donor\DAFAccountController@saveAuthorization']);

    Route::get('application-status/{id}', ['as' => 'daf-application-status', 'uses' => 'Donor\DAFAccountController@formReviewApplication']);
    Route::get('application-download/{id}', ['as' => 'daf-application-download', 'uses' => 'Donor\DAFAccountController@downloadApplication']);

    //Route::get('/password/change/{id}', ['as' => 'daf-change-password-form', 'uses' => 'Auth\ChangePasswordController@changeDAFUserPasswordForm']);
    //Route::post('/password/change/{id}', ['as' => 'daf-change-password', 'uses' => 'Auth\ChangePasswordController@changeDAFUserPassword']);

    Route::get('signature', function () {
        return view('demo.signature');
    });


    Route::get('primary', function () {
        return view('donor.registration.primary');
    });
    Route::get('secondary', function () {
        return view('donor.registration.secondary');
    });
    Route::get('account-name', function () {
        return view('demo.account-name');
    });
    Route::get('account-auth', function () {
        return view('demo.account-auth');
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

    Route::get('dashboard', function () {
        return view('demo.dashboard');
    });
    Route::get('statement', function () {
        return view('demo.statement_hga');
    });

});
