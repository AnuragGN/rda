<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 26-11-2020
 * Time: 15:25
 */

namespace App\Http\Controllers;

use App\Helpers\GConst;
use App\Models\Api;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\ContactUs;
use App\Models\Email;
use App\Helpers\GnUtils;
use App\Models\InterestArea;
use App\Models\Organization;
use App\Models\OrganizationInfo;
use App\Models\OrgInterestArea;
use App\Models\OrgNeedApp;
use App\Models\OrgNeedAppInterestArea;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDF;

class CharityController extends Controller
{

    static $options = array(
        'jcf' => [
            'a' => "A call/meeting with a representative from the nonprofit to learn more about it",
            'b' => "A call/meeting with JCF staff to discuss this nonprofit",
            'c' => "Research regarding this nonprofit or nonprofit sector",
            'd' => "Something else"
        ],
        'default' => [
            'a' => "A call/meeting with a representative from the nonprofit to learn more about it",
            'b' => "A call/meeting with our staff to discuss this nonprofit",
            'c' => "Research regarding this nonprofit or nonprofit sector",
            'd' => "Something else"
        ]
    );

    static function getRequestInfoOptions() {
        if (ClientInfo::isJCF()) {
            return CharityController::$options['jcf'];
        }
        return CharityController::$options['default'];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        GnUtils::addBreadcrumb(ClientConfig::text('CHARITABLE_CATALOG'));

        $params = $request->all();
        $query = '';
//        foreach($params as $key => $param) {
//            $query .= $key . $param[$key];
//        }
        $match = $request->match;
        if (!$match) $match = 'organizations';
        return view('charity.catalog-index', compact('query', 'match'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function searchedOrganizations(Request $request)
    {
        GnUtils::addBreadcrumb('Catalog', route('charitable-catalog'));
        GnUtils::addBreadcrumb('Organizations', route('organizations-catalog'));
        GnUtils::addBreadcrumb('Search Results');

        // $params = $request->all();
        // $query = $request->getRequestUri(); // $request->query();
        $query = '';
        $params = $request->query();
        foreach ($params as $key => $value) {
            $query .= $key . '=' . $value;
        }
        return view('charity.searched-organizations', compact('query'));
    }

    public function organizationsByInterestArea(Request $request)
    {
        $interestAreaId = $request->interest_area_id;
        $interestArea = InterestArea::where(['interest_area_id' => $interestAreaId])->first();
        if (!$interestArea) abort(404, 'Bad URL! Data not found.');
        $title = $interestArea->interest_area;

        GnUtils::addBreadcrumb('Catalog', route('charitable-catalog'));
        GnUtils::addBreadcrumb('Organizations', route('organizations-catalog'));
        GnUtils::addBreadcrumb($title);

        $parent = InterestArea::where(['interest_area_id' => $interestArea->parent_interest_area_id])->first();
        if ($parent) {
            $title = $parent->interest_area . ' - ' . $interestArea->interest_area;
        }

        $orgIds = OrgInterestArea::where(['interest_area_id' => $interestAreaId])->pluck('organization_id');
        // TODO: UndoGMF
        if (ClientInfo::isGMF()) {
            $orgs = OrganizationInfo::where(['allow_recommendation' => 'Y'])->whereIn('organization_id', $orgIds)->get();
        } else {
            $orgs = OrganizationInfo::where(['allow_recommendation' => 'Y', 'visible' => 'Y'])->whereIn('organization_id', $orgIds)->get();
        }
        $items = [];
        /** @var Organization $org */
        foreach($orgs as $org) {
            if (!$org->catalog_data) continue;
            $items[] = $org->getCatalogViewData(['interest_area_id' => $request->interest_area_id]);
        }

        return view('charity.organizations-of-interest-area', compact('items', 'title'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function programsByInterestArea(Request $request)
    {
        $interestAreaId = $request->interest_area_id;
        $interestArea = InterestArea::where(['interest_area_id' => $interestAreaId])->first();
        if (!$interestArea) abort(404, 'Bad URL! Data not found.');
        $title = $interestArea->interest_area;

        GnUtils::addBreadcrumb('Catalog', route('charitable-catalog'));
        GnUtils::addBreadcrumb('Programs', route('programs-catalog'));
        GnUtils::addBreadcrumb($title);

        // $date = today()->format('Y-m-d');
        $date = date('Y-m-d', strtotime("-1 days"));
        $onaias = OrgNeedAppInterestArea::where(['interest_area_id' => $interestAreaId])->pluck('org_need_app_id');

        // TODO: UndoGMF
        if (ClientInfo::isGMF()) {
            $programs = OrgNeedApp::whereIn('org_need_app_id', $onaias)
                ->join('organization', 'org_need_app.organization_id', '=', 'organization.organization_id')
                ->where(['organization.allow_recommendation' => 'Y'])
                ->where('org_need_app.campaign_end', '>', $date)
                ->get();
        } else {
            $programs = OrgNeedApp::whereIn('org_need_app_id', $onaias)
                ->join('organization', 'org_need_app.organization_id', '=', 'organization.organization_id')
                ->where(['organization.allow_recommendation' => 'Y', 'organization.visible' => 'Y'])
                ->where('org_need_app.campaign_end', '>', $date)
                ->get();
        }
        // return $programs;

        $items = [];
        /** @var OrgNeedApp $program*/
        foreach($programs as $program) {
            $items[] = $program->getCatalogViewData(['interest_area_id' => $request->interest_area_id]);
        }

        return view('charity.programs-of-interest-area', compact('items', 'title'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function apiIndex(Request $request)
    {
        $api = new Api();
        return $api->apiCharitableCatalog($request);
    }

    public function organizationMatches(Request $request)
    {
        //        GnUtils::addBreadcrumb('Catalog', route('charitable-catalog'));
        GnUtils::addBreadcrumb('Organization Matches');
        return view('donor.catalog.matching-orgs', ['query' => '']);
    }

    public function ajaxOrganizationMatches(Request $request)
    {
        /** @var Organization $model */
        $model = new Organization();
        $data = $model->getMatchingOrgs($request->page, $request->limit);

        $items = [];
        /** @var Organization $org */
        foreach($data['organizations'] as $org) {
            if (!$org->catalog_data) continue;
            $items[] = $org->getCatalogViewData();
        }

        // return $items;

        $html = view('charity.organization-list-items', compact('items'))->render();
        return [
            'more' => $data['has_more'],
            'html' => $html
        ];
    }

    static public function projectsMatches(Request $request)
    {
        GnUtils::addBreadcrumb('Program Matches');
        return view('donor.catalog.matching-programs', ['query' => '']);
    }

    static public function ajaxProjectsMatches(Request $request)
    {
        /** @var OrgNeedApp $org */
        $program = new OrgNeedApp();
        $data = $program->getMatchingPrograms($request->page, $request->limit);

        $items = [];
        /** @var OrgNeedApp $program */
        foreach($data['programs'] as $program) {
            $items[] = $program->getCatalogViewData();
        }

        $html = view('charity.program-list-items', compact('items'))->render();
        return [
            'more' => $data['has_more'],
            'html' => $html
        ];
    }

    public function ajaxOrganizationCatalog(Request $request)
    {
        $limit = 5;
        // $query = Organization::where(['status' => 'Active']);

        // TODO: UndoGMF
        if (ClientInfo::isGMF()) {
            $query = OrganizationInfo::where(['allow_recommendation' => 'Y']);
        } else {
            $query = OrganizationInfo::where(['allow_recommendation' => 'Y', 'visible' => 'Y']);
        }

        // user query
        $name = $request->query('name');
        if ($name && strlen($name)) {
            $query->where('name', 'ilike', '%' . $name . '%');
        }
        $orgs = $query->orderBy('name', 'ASC')->paginate($limit);

        $items = [];
        /** @var OrganizationInfo $org */
        foreach($orgs as $org) {
            if (!$org->catalog_data) continue;
            $items[] = $org->getCatalogViewData();
        }

        $html = view('charity.organization-list-items', compact('items'))->render();
        return [
            'more' => (count($orgs) < $limit) ? 0 : 1,
            'html' => $html
        ];
    }

    /**
     * show organization details
     *
     * @param $id
     * @return mixed
     */
    public function showOrganization($id, Request $request)
    {
        GnUtils::addBreadcrumb('Catalog', route('charitable-catalog'));
        GnUtils::addBreadcrumb('Organizations', route('organizations-catalog'));
        if ($request->interest_area_id) {
            $interestArea = InterestArea::where(['interest_area_id' => $request->interest_area_id])->first();
            if ($interestArea) {
                GnUtils::addBreadcrumb($interestArea->interest_area,
                    route('orgs-by-interest-area', ['interest_area_id' => $request->interest_area_id]));
            }
        }
        GnUtils::addBreadcrumb('Info');

        /** @var OrganizationInfo $org */
        $org = OrganizationInfo::find($id);
        if (!$org) abort('404', 'Organization not found');

        $org->image = $org->getImage();
        return view('org.view', compact('org'));
    }

    /**
     * @param Request $request
     * @param null $id, fund id
     * @return PDF View
     */
    public function printOrganization($id, Request $request)
    {
        /** @var OrganizationInfo $org */
        $org = OrganizationInfo::find($id);
        if (!$org) abort('404', 'Organization not found');

        $imageUrl = $org->getImage();
        $org->image = str_replace("https", "http", $imageUrl);

        // Set extra option
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView('org.print', compact('org'));
        return $pdf->stream();
        // download pdf => return $pdf->download('pdfview.pdf');
    }

    // organizations-catalog
    public function organizationsCatalog()
    {
        GnUtils::addBreadcrumb('Catalog', route('charitable-catalog'));
        GnUtils::addBreadcrumb('Organizations');

        $items = OrgInterestArea::getOrganizationCatalog();
        return view('charity.catalog-organizations', compact('items'));
    }

    public function showProgram($id, Request $request)
    {
        GnUtils::addBreadcrumb('Catalog', route('charitable-catalog'));
        GnUtils::addBreadcrumb('Programs', route('programs-catalog'));
        if ($request->interest_area_id) {
            $interestArea = InterestArea::where(['interest_area_id' => $request->interest_area_id])->first();
            if ($interestArea) {
                GnUtils::addBreadcrumb($interestArea->interest_area,
                    route('programs-by-interest-area', ['interest_area_id' => $request->interest_area_id]));
            }
        }
        GnUtils::addBreadcrumb('Info');

        /** @var OrgNeedApp $program */
        $program = OrgNeedApp::find($id);
        if (!$program) abort('404', 'Organization not found');
        // return $program;

        $program->image = $program->getImage();
        return view('org.program-view', compact('program'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return PDF View
     */
    public function printProgram($id, Request $request)
    {
        /** @var OrgNeedApp $program */
        $program = OrgNeedApp::find($id);
        if (!$program) abort('404', 'Organization not found');

        $imageUrl = $program->organization->getImage();
        $program->image = str_replace("https", "http", $imageUrl);

        // Set extra option
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView('org.print-program', compact('program'));
        return $pdf->stream();
        // download pdf return $pdf->download('pdfview.pdf');
    }

    // programs-catalog
    public function programsCatalog()
    {
        GnUtils::addBreadcrumb('Catalog', route('charitable-catalog'));
        GnUtils::addBreadcrumb('Programs');

        $items = OrgNeedAppInterestArea::getProgramsCatalog();
        return view('charity.catalog-programs', compact('items'));
    }

    // request more info about organization (or program/project!)
    public function requestInfo(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        // 1. save in database, 2. send email
        $options = CharityController::getRequestInfoOptions();
        $selectedOptions = array_map(function($action) use ($options) { return $options[$action]; }, $request->actions);

        $params = $request->all();
        $params['selected_items'] = $selectedOptions;

        $model = new ContactUs();
        $model->saveFromReqMoreInfo($params);

        // send email
        Email::requestInfo($model);

        return ['status' => 200, 'model' => $model];
    }

}
