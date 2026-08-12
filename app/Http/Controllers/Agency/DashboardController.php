<?php
/**
 * Created by PhpStorm.
 * User: Anurag Sinha
 * Date: 10-07-2024
 * Time: 21:55
 */

namespace App\Http\Controllers\Agency;

use App\Forms\FormFundHistoryFilter;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\ContactFund;
use App\Models\FundRecommendation;
use App\Models\GiftHistory;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use Auth;
use App\Models\Fund;
use App\Models\Ticket;
use App\Models\Organization;
use App\Models\Charity;
use App\Models\CharityFundMapping;
use App\Models\UserPreference;
use App\Models\DAFAccount;
use App\Models\FaSponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use PDF;
use Carbon\Carbon;

// Funds = 'JCFEX', 'Abra';
/**
 * Class FundController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    
    public function dashboard_bkp(Request $request) 
    {
        $limit = 5;

        // Get all charities 
        $charities = Charity::all();
        $charities   = FaSponser::getDafSponsors();
        $sponsors   = FaSponser::getDafSponsors();

        // Fetch the user's preferred charity & chart from the user preferences
        $contactId = Contact::sessionContactId();
        $userPreference = UserPreference::where('contact_id', $contactId)->first();

        $preferredCharityId = null;
        $preferredChartType = null;
        if ($userPreference && !empty($userPreference->preferences)) {
            $preferences = $userPreference->preferences;
            $preferredCharityId = $preferences['top_charity_id'] ?? null;
            $preferredChartType = $preferences['chart_type'] ?? null;
        }

        // Call the function to organize widgets by preference
        $orderedWidgets = $this->organizeWidgetsByPreference($preferences);

        // Sort charities based on preferred charity ID
        if ($preferredCharityId) {
            $charityById = $charities->keyBy('id')->toArray();
            $sortedCharities = [];
            if (isset($charityById[$preferredCharityId])) {
                $sortedCharities[] = $charityById[$preferredCharityId];
            }
            foreach ($charities as $charity) {
                if ($charity->id != $preferredCharityId) {
                    $sortedCharities[] = $charityById[$charity->id];
                }
            }
            $charities = collect($sortedCharities);
        }

        // Fetch balances, pending grants and fund details for each fund associated with charities
        $fundBalances = [];
        $charities = $charities->map(function ($charity) {
            $charity_id = $charity['id'];
            $fundIds = CharityFundMapping::where('charity_id', $charity_id)->pluck('fund_id');
            $totalBalance = Fund::whereIn('fund_id', $fundIds)->sum('balance');
            $funds = Fund::whereIn('fund_id', $fundIds)->get();
            $pendingGrantBalance = FundRecommendation::whereIn('fund_id', $fundIds)
                                    ->where('is_approved', 'N')
                                    ->sum('amount');  
            // dd($pendingGrantBalance);

            $charity['funds'] = $funds;
            $charity['total_balance'] = $totalBalance;
            $charity['pending_grants_balance'] = $pendingGrantBalance;
            return $charity;
        });

        // Fetch preferred chart types
        $charts = config('dropdown.chart_dropdown');
        if ($preferredChartType) {
            $charts = array_merge(
                [$preferredChartType => $charts[$preferredChartType]],
                array_diff_key($charts, [$preferredChartType => ''])
            );
        }

        // Get URL parameters
        $preferredCharityId1 = $preferredCharityId;
        $preferredCharityId2 = $preferredCharityId;
        if ($request->query('charity_id') != '') {
            $preferredCharityId1 = $request->query('charity_id');
        }
        if ($request->query('recom_charity_id') != '') {
            $preferredCharityId2 = $request->query('recom_charity_id');
        }
        
        // Fetch open tickets related to the funds associated with the preferred charity
        $fundIds = CharityFundMapping::where('charity_id', $preferredCharityId1)->pluck('fund_id');
        $fundIdsArray = $fundIds->toArray();

        $openTickets = DB::table('tickets AS t')
            ->leftJoin('fund_recommendation AS fr', DB::raw('CAST(fr.fund_recommendation_id AS TEXT)'), '=', DB::raw('CAST(t.target_id AS TEXT)'))
            ->where(function($query) use ($fundIdsArray) {
                $query->where(function($query) use ($fundIdsArray) {
                    $query->where('t.target_type', 'fund')
                        ->whereIn('t.target_id', $fundIdsArray);
                })
                ->orWhere(function($query) use ($fundIdsArray) {
                    $query->whereIn('t.target_type', ['cart recommendation', 'grant recommendation'])
                        ->whereIn('fr.fund_id', $fundIdsArray);
                });
            })
            ->where('t.status', 'open')
            ->whereNull('t.deleted_at') // Exclude soft-deleted tickets
            ->select('t.*')
            ->get();

            // dd($openTickets);




        $totalTicketStatus = DB::table('tickets AS t')
            ->leftJoin('fund_recommendation AS fr', DB::raw('CAST(fr.fund_recommendation_id AS TEXT)'), '=', DB::raw('CAST(t.target_id AS TEXT)'))
            ->where(function($query) use ($fundIdsArray) {
                $query->where(function($query) use ($fundIdsArray) {
                    $query->where('t.target_type', 'fund')
                        ->whereIn('t.target_id', $fundIdsArray);
                })
                ->orWhere(function($query) use ($fundIdsArray) {
                    $query->whereIn('t.target_type', ['cart recommendation', 'grant recommendation'])
                        ->whereIn('fr.fund_id', $fundIdsArray);
                });
            })
            ->select('t.status', DB::raw('count(*) as total'))
            ->whereNull('t.deleted_at') 
            ->groupBy('t.status')
            ->get();

        $ticketArr = [];
        foreach ($totalTicketStatus as $item) {
            $ticketArr[] = [
                'status' => $item->status,
                'status_name' => config('dropdown.status')[$item->status],
                'total' => $item->total,
            ];
        }

        $openTicketsHTML = view('agency.agency-advisor.dashboard-partials.open_tickets', compact('openTickets'))->render();

        $recommendation = $this->getRecommendationForDashboard($preferredCharityId2);
        $pendingRecommendationsHTML = view('agency.agency-advisor.dashboard-partials.pending_recommendations', compact('recommendation'))->render();

        $dafAccounts = $this->getDAFAccountByAdvisor($preferredCharityId2);
        $dafAccountsHTML = view('agency.agency-advisor.dashboard-partials.daf_accounts', compact('dafAccounts'))->render();
        
        if ($request->ajax()) {
            return response()->json([
                'openTicketsHTML' => $openTicketsHTML,
                'chartType' => $preferredChartType,
                'ticketData' => $ticketArr,
                'pendingRecommendationsHTML' => $pendingRecommendationsHTML,
                'dafAccountsHTML' => $dafAccountsHTML,
            ]);
        }

       # echo '<pre>';print_r($charities);echo '</pre>';exit;
        return view('agency.agency-advisor.new_dashboard', 
                    compact(
                        'openTickets', 
                        'recommendation', 
                        'ticketArr', 
                        'charities', 
                        'charts', 
                        'fundBalances', 
                        'preferredChartType', 
                        'preferredCharityId1', 
                        'preferredCharityId2', 
                        'orderedWidgets',
                        'dafAccounts',
                        'sponsors'
                    )
                );
    }


    public function Olddashboard(Request $request)
    {
        $limit = 5;

        /* -------------------------------------------------
        | Sponsors & Charities
        ------------------------------------------------- */
        $sponsors  = FaSponser::getDafSponsors();
        # $charities = Charity::all();
        $charities = FaSponser::getDafSponsors();

        /* -------------------------------------------------
        | User Preferences
        ------------------------------------------------- */
        $contactId      = Contact::sessionContactId();
        $userPreference = UserPreference::where('contact_id', $contactId)->first();

        $preferences = [];
        if ($userPreference && !empty($userPreference->preferences)) {
            $preferences = $userPreference->preferences;
        }

        $preferredCharityId = isset($preferences['top_charity_id'])
            ? $preferences['top_charity_id']
            : null;

        $preferredChartType = isset($preferences['chart_type'])
            ? $preferences['chart_type']
            : null;

        $orderedWidgets = $this->organizeWidgetsByPreference($preferences);

        /* -------------------------------------------------
        | Sort Charities (Preferred First)
        ------------------------------------------------- */
        if ($preferredCharityId) {
            $charities = $charities->sortByDesc(function ($charity) use ($preferredCharityId) {
                return $charity->id == $preferredCharityId;
            })->values();
        }

        /* -------------------------------------------------
        | Fund & Balance Mapping
        ------------------------------------------------- */
        $charities = $charities->map(function ($charity) {

            $fundIds = CharityFundMapping::where('charity_id', $charity->id)
                ->pluck('fund_id');

            $funds = Fund::whereIn('fund_id', $fundIds)->get();

            $charity->funds = $funds;
            $charity->total_balance = $funds->sum('balance');

            $charity->pending_grants_balance = FundRecommendation::whereIn('fund_id', $fundIds)
                ->where('is_approved', 'N')
                ->sum('amount');

            return $charity;
        });

        /* -------------------------------------------------
        | Charts Dropdown
        ------------------------------------------------- */
        $charts = config('dropdown.chart_dropdown');

        if ($preferredChartType && isset($charts[$preferredChartType])) {
            $charts = array_merge(
                [$preferredChartType => $charts[$preferredChartType]],
                array_diff_key($charts, [$preferredChartType => ''])
            );
        }

        /* -------------------------------------------------
        | Charity Selection (Query Param > Preference)
        ------------------------------------------------- */
        $preferredCharityId1 = $request->query('charity_id')
            ? $request->query('charity_id')
            : $preferredCharityId;

        $preferredCharityId2 = $request->query('recom_charity_id')
            ? $request->query('recom_charity_id')
            : $preferredCharityId;

        $preferredSponsor = $request->query('sponsor_id');

        if ($preferredSponsor === '' || is_null($preferredSponsor)) {
            $preferredSponsor = $preferredCharityId;
        }

        $fundIds = CharityFundMapping::where('charity_id', $preferredCharityId1)
            ->pluck('fund_id')
            ->toArray();

        /* -------------------------------------------------
        | Ticket Base Query
        ------------------------------------------------- */
        $ticketBaseQuery = DB::table('tickets AS t')
            ->leftJoin(
                'fund_recommendation AS fr',
                DB::raw('CAST(fr.fund_recommendation_id AS TEXT)'),
                '=',
                DB::raw('CAST(t.target_id AS TEXT)')
            )
            ->where(function ($query) use ($fundIds) {

                // $query->where(function ($q) use ($fundIds) {
                //     $q->where('t.target_type', 'fund')
                //     ->whereIn('t.target_id', $fundIds);
                // });

                $query->orWhere(function ($q) use ($fundIds) {
                    $q->whereIn('t.target_type', ['cart recommendation', 'grant recommendation', 'advisor registration']);
                    //->whereIn('fr.fund_id', $fundIds);
                });
            })
            ->whereNull('t.deleted_at');

        /* -------------------------------------------------
        | Open Tickets
        ------------------------------------------------- */
        $openTickets = (clone $ticketBaseQuery)
            ->where('t.status', 'open')
            ->select('t.*')
            ->get();

        /* -------------------------------------------------
        | Ticket Status Summary
        ------------------------------------------------- */
        $ticketArr = [];

        $ticketStatus = (clone $ticketBaseQuery)
            ->select('t.status', DB::raw('COUNT(*) as total'))
            ->groupBy('t.status')
            ->get();

        foreach ($ticketStatus as $item) {
            $ticketArr[] = [
                'status'      => $item->status,
                'status_name' => config('dropdown.status')[$item->status] ?? $item->status,
                'total'       => $item->total,
            ];
        }

        /* -------------------------------------------------
        | Widgets Data
        ------------------------------------------------- */
        $recommendation = $this->getRecommendationForDashboard($preferredCharityId2);
        $dafAccounts    = DAFAccount::getDAFApplicationsforFADashboard($preferredSponsor);

        $openTicketsHTML = view(
            'agency.agency-advisor.dashboard-partials.open_tickets',
            compact('openTickets')
        )->render();

        $pendingRecommendationsHTML = view(
            'agency.agency-advisor.dashboard-partials.pending_recommendations',
            compact('recommendation')
        )->render();

        $dafAccountsHTML = view(
            'agency.agency-advisor.dashboard-partials.daf_accounts',
            compact('dafAccounts')
        )->render();

        /* -------------------------------------------------
        | AJAX Response
        ------------------------------------------------- */
        if ($request->ajax()) {
            return response()->json([
                'openTicketsHTML'            => $openTicketsHTML,
                'chartType'                  => $preferredChartType,
                'ticketData'                 => $ticketArr,
                'pendingRecommendationsHTML' => $pendingRecommendationsHTML,
                'dafAccountsHTML'            => $dafAccountsHTML,
            ]);
        }

        /* -------------------------------------------------
        | Final View
        ------------------------------------------------- */
        return view(
            'agency.agency-advisor.new_dashboard',
            compact(
                'openTickets',
                'recommendation',
                'ticketArr',
                'charities',
                'charts',
                'preferredChartType',
                'preferredCharityId1',
                'preferredCharityId2',
                'orderedWidgets',
                'dafAccounts',
                'sponsors'
            )
        );
    }


    public function dashboard(Request $request)
    {
        $limit = 5;

        /* -------------------------------------------------
        | Sponsors & Charities
        ------------------------------------------------- */
        $sponsors  = FaSponser::getDafSponsors();
        $charities = FaSponser::getDafSponsors();

        /* -------------------------------------------------
        | User Preferences
        ------------------------------------------------- */
        $contactId      = Contact::sessionContactId();
        $userPreference = UserPreference::where('contact_id', $contactId)->first();

        $preferences = [];
        if ($userPreference && !empty($userPreference->preferences)) {
            $preferences = $userPreference->preferences;
        }

        $preferredCharityId = isset($preferences['top_charity_id'])
            ? $preferences['top_charity_id']
            : null;

        $preferredChartType = isset($preferences['chart_type'])
            ? $preferences['chart_type']
            : null;

        $orderedWidgets = $this->organizeWidgetsByPreferenceNew($preferences);

        /* -------------------------------------------------
        | Sort Charities (Preferred First)
        ------------------------------------------------- */
        if ($preferredCharityId) {
            $charities = $charities->sortByDesc(function ($charity) use ($preferredCharityId) {
                return $charity->id == $preferredCharityId;
            })->values();
        }

        /* -------------------------------------------------
        | Fund & Balance Mapping
        ------------------------------------------------- */
        $charities = $charities->map(function ($charity) {

            $fundIds = CharityFundMapping::where('charity_id', $charity->id)
                ->pluck('fund_id');

            $funds = Fund::whereIn('fund_id', $fundIds)->get();

            $charity->funds = $funds;
            $charity->total_balance = $funds->sum('balance');

            $charity->pending_grants_balance = FundRecommendation::whereIn('fund_id', $fundIds)
                ->where('is_approved', 'N')
                ->sum('amount');

            return $charity;
        });

        /* -------------------------------------------------
        | Charts Dropdown
        ------------------------------------------------- */
        $charts = config('dropdown.chart_dropdown');

        if ($preferredChartType && isset($charts[$preferredChartType])) {
            $charts = array_merge(
                [$preferredChartType => $charts[$preferredChartType]],
                array_diff_key($charts, [$preferredChartType => ''])
            );
        }

        /* -------------------------------------------------
        | Charity Selection (Query Param > Preference)
        ------------------------------------------------- */
        $preferredCharityId1 = $request->query('charity_id')
            ? $request->query('charity_id')
            : $preferredCharityId;

        $preferredCharityId2 = $request->query('recom_charity_id')
            ? $request->query('recom_charity_id')
            : $preferredCharityId;

        $preferredSponsor = $request->query('sponsor_id');

        if ($preferredSponsor === '' || is_null($preferredSponsor)) {
            $preferredSponsor = $preferredCharityId;
        }

        $fundIds = CharityFundMapping::where('charity_id', $preferredCharityId1)
            ->pluck('fund_id')
            ->toArray();

        /* -------------------------------------------------
        | Widgets Data
        ------------------------------------------------- */
        $recommendation = $this->getRecommendationForDashboard($preferredCharityId2);
        $dafAccounts    = DAFAccount::getDAFApplicationsforFADashboard($preferredSponsor);

        $allTickets    = Ticket::getDashboardTickets($fundIds);
       
        $ticketArr = $allTickets['status_wise_totals'];
       
        $openTicketsHTML = view(
            'agency.agency-advisor.dashboard-partials-new.open_tickets',
            compact('allTickets')
        )->render();

        $pendingRecommendationsHTML = view(
            'agency.agency-advisor.dashboard-partials-new.pending_recommendations',
            compact('recommendation')
        )->render();

        $dafAccountsHTML = view(
            'agency.agency-advisor.dashboard-partials-new.daf_accounts',
            compact('dafAccounts')
        )->render();

        /* -------------------------------------------------
        | AJAX Response
        ------------------------------------------------- */
        if ($request->ajax()) {
            return response()->json([
                'openTicketsHTML'            => $openTicketsHTML,
                'chartType'                  => $preferredChartType,
                'ticketData'                 => $ticketArr,
                'pendingRecommendationsHTML' => $pendingRecommendationsHTML,
                'dafAccountsHTML'            => $dafAccountsHTML,
            ]);
        }

        /* -------------------------------------------------
        | Final View
        ------------------------------------------------- */
        return view(
            'agency.agency-advisor.latest_dashboard',
            compact(
                'allTickets',
                'recommendation',
                'ticketArr',
                'charities',
                'charts',
                'preferredChartType',
                'preferredCharityId1',
                'preferredCharityId2',
                'orderedWidgets',
                'dafAccounts',
                'sponsors'
            )
        );
    }

    public function upgradDashboard(Request $request)
    {
        $limit = 5;

        /* -------------------------------------------------
        | Sponsors & Charities
        ------------------------------------------------- */
        $sponsors  = FaSponser::getDafSponsors();
        $charities = FaSponser::getDafSponsors();

        /* -------------------------------------------------
        | User Preferences
        ------------------------------------------------- */
        $contactId      = Contact::sessionContactId();
        $userPreference = UserPreference::where('contact_id', $contactId)->first();

        $preferences = [];
        if ($userPreference && !empty($userPreference->preferences)) {
            $preferences = $userPreference->preferences;
        }

        $preferredCharityId = isset($preferences['top_charity_id'])
            ? $preferences['top_charity_id']
            : null;

        $preferredChartType = isset($preferences['chart_type'])
            ? $preferences['chart_type']
            : null;

        $orderedWidgets = $this->organizeWidgetsByPreferenceNew($preferences);

        /* -------------------------------------------------
        | Sort Charities (Preferred First)
        ------------------------------------------------- */
        if ($preferredCharityId) {
            $charities = $charities->sortByDesc(function ($charity) use ($preferredCharityId) {
                return $charity->id == $preferredCharityId;
            })->values();
        }

        /* -------------------------------------------------
        | Fund & Balance Mapping
        ------------------------------------------------- */
        $charities = $charities->map(function ($charity) {

            $fundIds = CharityFundMapping::where('charity_id', $charity->id)
                ->pluck('fund_id');

            $funds = Fund::whereIn('fund_id', $fundIds)->get();

            $charity->funds = $funds;
            $charity->total_balance = $funds->sum('balance');

            $charity->pending_grants_balance = FundRecommendation::whereIn('fund_id', $fundIds)
                ->where('is_approved', 'N')
                ->sum('amount');

            return $charity;
        });

        /* -------------------------------------------------
        | Charts Dropdown
        ------------------------------------------------- */
        $charts = config('dropdown.chart_dropdown');

        if ($preferredChartType && isset($charts[$preferredChartType])) {
            $charts = array_merge(
                [$preferredChartType => $charts[$preferredChartType]],
                array_diff_key($charts, [$preferredChartType => ''])
            );
        }

        /* -------------------------------------------------
        | Charity Selection (Query Param > Preference)
        ------------------------------------------------- */
        $preferredCharityId1 = $request->query('charity_id')
            ? $request->query('charity_id')
            : $preferredCharityId;

        $preferredCharityId2 = $request->query('recom_charity_id')
            ? $request->query('recom_charity_id')
            : $preferredCharityId;

        $preferredSponsor = $request->query('sponsor_id');

        if ($preferredSponsor === '' || is_null($preferredSponsor)) {
            $preferredSponsor = $preferredCharityId;
        }

        $fundIds = CharityFundMapping::where('charity_id', $preferredCharityId1)
            ->pluck('fund_id')
            ->toArray();

        /* -------------------------------------------------
        | Widgets Data
        ------------------------------------------------- */
        $recommendation = $this->getRecommendationForDashboard($preferredCharityId2);
        $dafAccounts    = DAFAccount::getDAFApplicationsforFADashboard($preferredSponsor);

        $allTickets    = Ticket::getDashboardTickets($fundIds);
       
        $ticketArr = $allTickets['status_wise_totals'];
       
        $openTicketsHTML = view(
            'agency.agency-advisor.dashboard-partials-new.open_tickets',
            compact('allTickets')
        )->render();

        $pendingRecommendationsHTML = view(
            'agency.agency-advisor.dashboard-partials-new.pending_recommendations',
            compact('recommendation')
        )->render();

        $dafAccountsHTML = view(
            'agency.agency-advisor.dashboard-partials-new.daf_accounts',
            compact('dafAccounts')
        )->render();

        /* -------------------------------------------------
        | AJAX Response
        ------------------------------------------------- */
        if ($request->ajax()) {
            return response()->json([
                'openTicketsHTML'            => $openTicketsHTML,
                'chartType'                  => $preferredChartType,
                'ticketData'                 => $ticketArr,
                'pendingRecommendationsHTML' => $pendingRecommendationsHTML,
                'dafAccountsHTML'            => $dafAccountsHTML,
            ]);
        }

        /* -------------------------------------------------
        | Final View
        ------------------------------------------------- */
        return view(
            'agency.agency-advisor.new-dashboard.index',
            compact(
                'allTickets',
                'recommendation',
                'ticketArr',
                'charities',
                'charts',
                'preferredChartType',
                'preferredCharityId1',
                'preferredCharityId2',
                'orderedWidgets',
                'dafAccounts',
                'sponsors'
            )
        );
    }
    public function charity($id, Request $request)
    {
        $charities = Charity::all();
        $charity = Charity::find($id);
        
        if ($charity) {
            $charity_id = $charity->id;
            $fundIds = CharityFundMapping::where('charity_id', $charity_id)->pluck('fund_id');
            // $totalBalance = Fund::whereIn('fund_id', $fundIds)->sum('balance');
            $funds = Fund::whereIn('fund_id', $fundIds)->get();
        
            $charity->funds = $funds;
            // $charity->total_balance = $totalBalance;
        }

        GnUtils::addBreadcrumb('Charity', route('agency-dashboard'));
        GnUtils::addBreadcrumb($charity['name']);

        return view('agency.agency-advisor.charity.view', compact('charity','charities'));
    }

    public function charityFundClients($id,$fund_id, Request $request)
    {
        $contact = Contact::sessionContact();

        $charity = Charity::find($id);
        $fund = Fund::where('fund_id', $fund_id)->first();

        $fundIds = CharityFundMapping::where('charity_id', $id)->pluck('fund_id');
        $funds = Fund::whereIn('fund_id', $fundIds)->get();
        
        //find contact related to funds associted with charities
        $contactIds = ContactFund::where('fund_id', $fund_id)->pluck('contact_id');
        // dd($contactIds);
        $clients = Contact::whereIn('contact.contact_id', $contactIds)
                    ->join('contact_type_contact', 'contact.contact_id', '=', 'contact_type_contact.contact_id')
                    ->where('contact_type_contact.contact_type_id', 10)
                    ->get();

                    // dd($clients);
        
        GnUtils::addBreadcrumb('Charity', route('agency-dashboard'));
        GnUtils::addBreadcrumb($charity['name'], route('agency-charity',[$id]));
        GnUtils::addBreadcrumb($fund['name']);
        
        return view('agency.agency-advisor.charity.clients', compact('charity','fund','funds', 'clients'));
    }

    public function getRecommendationForDashboard($preferredCharityId2) 
    {
        // Check if 'All Charity' is selected
        if ($preferredCharityId2 == 0) {
            // Fetch all fund IDs across all charities
            $fundIds = CharityFundMapping::pluck('fund_id');
        } else {
            // Fetch fund IDs for the selected charity
            $fundIds = CharityFundMapping::where('charity_id', $preferredCharityId2)->pluck('fund_id');
        }

        // Convert $fundIds to an array
        $fundIdsArray = $fundIds->toArray();

        if (empty($fundIdsArray)) {
            return []; // If no fund IDs are found, return an empty array
        }

        $recommendation = FundRecommendation::getRecommendationList($fundIdsArray);
        $newArray = [];
        $overallArray = [];

        foreach ($recommendation as $key => $val) {

            $contact_arr = Contact::find($val['contact_id']);
            $fund_arr = Fund::where('fund_id', $val['fund_id'])->first();

            $fund_name = $fund_arr ? $fund_arr->name : 'NA';

            $ticketDetail = Ticket::checkTicketRecommendationWise($val['fund_recommendation_id']);

            $newArray['fund_recommendation_id'] = $val['fund_recommendation_id'];
            $newArray['fund_id'] = $val['fund_id'];
            $newArray['fund_name'] = $fund_name;
            $newArray['contact_id'] = $val['contact_id'];
            $newArray['contact_name'] = $contact_arr['first_name'].' '.$contact_arr['last_name'];
            $newArray['amount'] = GnUtils::money($val['amount']);
            $newArray['org_name'] = $val['org_name'];
            $newArray['date_submitted'] = GnUtils::customDate($val['date_submitted']);
            $newArray['approved_date'] = GnUtils::customDate($val['approved_date']);
            $newArray['status'] = 'N';
            $newArray['ticket'] = @$ticketDetail['0']['id'];

            $overallArray[] = $newArray;
        }
        
        return $overallArray;
    }

    # Your Preferences  
    public function yourPreferences(Request $request)
    {
        $contactId = Contact::sessionContactId();
        $userPreference = UserPreference::where('contact_id', $contactId)->first();
        
        $selectedCharityId = '';
        $selectedChartType = '';
        $widgetOrder = [];

        if (!empty($userPreference)) {
            $preferences = $userPreference->preferences;
            if (!empty($preferences)) {
                $selectedCharityId = $preferences['top_charity_id'];
                $selectedChartType = $preferences['chart_type'];
                if (isset($preferences['widget_order'])) {
                    $widgetOrder = explode(',', $preferences['widget_order']);
                }
            }
        }

        // If widget order is empty, get the default order from config
        if (empty($widgetOrder)) {
            $widgetOrder = config('dropdown.default_widget_order'); 
        }

        $charities = Charity::all();
        $charts = config('dropdown.chart_dropdown');
        $sponsors   = FaSponser::getDafSponsors();

        return view('agency.agency-advisor.preferences.view', 
                compact(
                    'charities', 
                    'charts', 
                    'selectedCharityId', 
                    'selectedChartType',
                     'widgetOrder', 
                     'sponsors'
                    )
                );
    }

    public function savePreferences(Request $request)
    {
        $contactId = Contact::sessionContactId();
        $validatedData = $request->validate([
            'charity' => 'nullable|integer',
            'chart' => 'nullable|string',
            'widget_order' => 'nullable|string',
        ]);
    
        // Fetch existing UserPreference record
        $userPreference = UserPreference::where('contact_id', $contactId)->first();
    
        // Prepare preferences data
        $preferences = $userPreference ? $userPreference->preferences : [];
    
        // Update only the preferences provided in the request
        if (isset($validatedData['charity'])) {
            $preferences['top_charity_id'] = $validatedData['charity'];
        }
    
        if (isset($validatedData['chart'])) {
            $preferences['chart_type'] = $validatedData['chart'];
        }
    
        if (isset($validatedData['widget_order'])) {
            $preferences['widget_order'] = $validatedData['widget_order'];
        }
    
        // If UserPreference record exists, update it; otherwise, create a new one
        if ($userPreference) {
            $userPreference->update([
                'preferences' => $preferences,
            ]);
        } else {
            UserPreference::create([
                'contact_id' => $contactId,
                'preferences' => $preferences,
            ]);
        }
    
        // Optionally, redirect back with a success message
        return redirect()->back()->with('success', 'Preferences saved successfully!');
    }
    
    public function saveChartPreferenceFromDashboard(Request $request)
    {
        $contactId = Contact::sessionContactId();

        $validatedData = $request->validate([
            'chart_type' => 'required|string',
        ]);

        $userPreference = UserPreference::where('contact_id', $contactId)->first();

        $preferences = $userPreference->preferences ?? [];
        $preferences['chart_type'] = $validatedData['chart_type'];

        if ($userPreference) {
            $userPreference->update([
                'preferences' => $preferences,
            ]);
        } 
        else {
            UserPreference::create([
                'contact_id' => $contactId,
                'preferences' => $preferences,
            ]);
        }

        return response()->json(['success' => 'Preference saved successfully']);
    }

    private function organizeWidgetsByPreference($preferences)
    {
        // Convert comma-separated string to an array
        $preferredWidgetOrder = isset($preferences['widget_order']) ? explode(',', $preferences['widget_order']) : [];
        // dd($preferredWidgetOrder);
    
        // Ensure it's an array
        if (!is_array($preferredWidgetOrder)) {
            $preferredWidgetOrder = [];
        }
    
        $availableWidgets = [
            'donor_fund_balance' => 'agency.agency-advisor.dashboard-partials.donor_fund_balance',
            'service_requests' => 'agency.agency-advisor.dashboard-partials.service_requests',
            'pending_client_recommendation' => 'agency.agency-advisor.dashboard-partials.pending_client_recommendation',
            'institutional_client' => 'agency.agency-advisor.dashboard-partials.institutional_client',
            'daf_account_summary' => 'agency.agency-advisor.dashboard-partials.daf_account_summary',
        ];
    
        $orderedWidgets = [];
    
        // Arrange widgets based on user preference
        foreach ($preferredWidgetOrder as $widgetKey) {
            if (isset($availableWidgets[$widgetKey])) {
                $orderedWidgets[$widgetKey] = $availableWidgets[$widgetKey];
            }
        }

        // dd($orderedWidgets);
    
        // Add any remaining widgets that are not in the preferred order
        foreach ($availableWidgets as $widgetKey => $widgetView) {
            if (!isset($orderedWidgets[$widgetKey])) {
                $orderedWidgets[$widgetKey] = $widgetView;
            }
        }
    
        return $orderedWidgets;
    }


    private function organizeWidgetsByPreferenceNew($preferences)
    {
        // Convert comma-separated string to an array
        $preferredWidgetOrder = isset($preferences['widget_order']) ? explode(',', $preferences['widget_order']) : [];
        // dd($preferredWidgetOrder);
    
        // Ensure it's an array
        if (!is_array($preferredWidgetOrder)) {
            $preferredWidgetOrder = [];
        }
    
        $availableWidgets = [
            'donor_fund_balance' => 'agency.agency-advisor.dashboard-partials-new.donor_fund_balance',
            'service_requests' => 'agency.agency-advisor.dashboard-partials-new.service_requests',
            'pending_client_recommendation' => 'agency.agency-advisor.dashboard-partials-new.pending_client_recommendation',
            'institutional_client' => 'agency.agency-advisor.dashboard-partials-new.institutional_client',
            'daf_account_summary' => 'agency.agency-advisor.dashboard-partials-new.daf_account_summary',
        ];
    
        $orderedWidgets = [];
    
        // Arrange widgets based on user preference
        foreach ($preferredWidgetOrder as $widgetKey) {
            if (isset($availableWidgets[$widgetKey])) {
                $orderedWidgets[$widgetKey] = $availableWidgets[$widgetKey];
            }
        }

        // dd($orderedWidgets);
    
        // Add any remaining widgets that are not in the preferred order
        foreach ($availableWidgets as $widgetKey => $widgetView) {
            if (!isset($orderedWidgets[$widgetKey])) {
                $orderedWidgets[$widgetKey] = $widgetView;
            }
        }
    
        return $orderedWidgets;
    }


}
