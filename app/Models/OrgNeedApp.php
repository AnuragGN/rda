<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use App\Helpers\GnUtils;
use Illuminate\Database\Eloquent\Model;

class OrgNeedApp extends Model
{
    /* @var string */
    protected $table = 'org_need_app';

    protected $primaryKey = 'org_need_app_id';
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    static public function TEST()
    {
        return self::where([])->limit(100)->get();
    }

    static public function getById($id)
    {
        return self::where(['org_need_app_id' => $id])->first();
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id');
    }

    /**
     * @param bool|true $default
     * @return bool|null|string
     */
    public function getImage($default=true)
    {
        if (!empty($this->img_url)) {
            $server = ClientConfig::assetServer();
            return $server . $this->img_url;
        } else {
            return $this->organization->getImage(true);
        }
    }

    public function getMakeGrantUrl()
    {
        return route('grant-create', ['org_need_app_id' => $this->org_need_app_id]);;
    }

    /**
     * @param array $options ['interest_area_id'=>$id]
     * @return array
     */
    public function getCatalogViewData($options=[])
    {
        $item = [];
        if (isset($options['interest_area_id'])){
            $orgLink = route('program', ['id' => $this->org_need_app_id, 'interest_area_id' => $options['interest_area_id']]);
        } else {
            $orgLink = route('program', ['id' => $this->org_need_app_id]);
        }
        $item['title'] = $this->title;
        $item['sub-title'] = 'By ' . $this->organization->name;
        $item['sub-title-url'] = $this->organization->url;
        $item['title-link'] = $orgLink;
        $item['id'] = $this->org_need_app_id;
        $summary = GnUtils::textTruncate(strip_tags($this->summary), 200);
        $item['description'] = $summary; //  . " <a href='" . $orgLink . "'>Read more</a>";

        $item['image'] = $this->getImage();
        $item['mag-link'] = $this->getMakeGrantUrl();
        return $item;
    }

    public function getMatchingPrograms($page, $limit)
    {
        if ($page < 1) $page = 1;
        if ($limit < 3 || $limit > 25) $limit = 5;

        $ciaW = Config::getInterestAreaWeight();
        $cgaW = Config::getGeographicAreaWeight();
        $cpsW = Config::getPopulationServedWeight();

        // $contact = Contact::sessionContact();
        $ciaIds = ContactInterestArea::getInterestAreaIds();
        $cgaIds = ContactGeographicArea::getGeographicAreaIds();
        $cpsIds = ContactPopulationServed::getPopulationServedIds();

        //$date = today()->format('Y-m-d');
        if (ClientInfo::isGNA()) {
            $date = date("Y-m-d", strtotime("-10 years"));
        } else if (ClientInfo::isGMF()) { // TODO: UndoGMF
            $date = date("Y-m-d", strtotime("-2 years"));
        } else {
            $date = date('Y-m-d', strtotime("-1 days"));
        }
        $conditions = [];
        $clientConditions = [];

        if (ClientInfo::isGMF()) {
            $clientConditions = ['organization.allow_recommendation' => 'Y'];
        } else {
            $clientConditions = ['organization.visible' => 'Y', 'organization.allow_recommendation' => 'Y'];
        }

        $ciaOrgNeedAppIds = OrgNeedAppInterestArea::where($conditions)
            ->join('org_need_app', 'org_need_app.org_need_app_id', '=', 'org_need_app_interest_area.org_need_app_id')
            ->where('org_need_app.campaign_end', '>', $date)
            ->join('organization', 'org_need_app.organization_id', '=', 'organization.organization_id')
            ->where($clientConditions)
            ->whereIn('org_need_app_interest_area.interest_area_id', $ciaIds)
            ->pluck('org_need_app_interest_area.org_need_app_id')->toArray();

        $cgaOrgNeedAppIds = OrgNeedAppGeographicArea::where($conditions)
            ->join('org_need_app', 'org_need_app.org_need_app_id', '=', 'org_need_app_geographic_area.org_need_app_id')
            ->where('org_need_app.campaign_end', '>', $date)
            ->join('organization', 'org_need_app.organization_id', '=', 'organization.organization_id')
            ->where($clientConditions)
            ->whereIn('geographic_area_id', $cgaIds)
            ->pluck('org_need_app_geographic_area.org_need_app_id')->toArray();

        $cpsOrgNeedAppIds = OrgNeedAppPopulationServed::where($conditions)
            ->join('org_need_app', 'org_need_app.org_need_app_id', '=', 'org_need_app_population_served.org_need_app_id')
            ->where('org_need_app.campaign_end', '>', $date)
            ->join('organization', 'org_need_app.organization_id', '=', 'organization.organization_id')
            ->where($clientConditions)
            ->whereIn('population_served_id', $cpsIds)
            ->pluck('org_need_app_population_served.org_need_app_id')->toArray();

        $ciaOrgNeedAppIds = array_unique($ciaOrgNeedAppIds);
        $cgaOrgNeedAppIds = array_unique($cgaOrgNeedAppIds);
        $cpsOrgNeedAppIds = array_unique($cpsOrgNeedAppIds);

        $oids = [];
        foreach($ciaOrgNeedAppIds as $id){
            $oids[$id] = $ciaW;
        }
        foreach($cgaOrgNeedAppIds as $id){
            if (isset($oids[$id])) $oids[$id] += $cgaW;
        }
        foreach($cpsOrgNeedAppIds as $id){
            if (isset($oids[$id])) $oids[$id] += $cpsW;
        }
        $total = count($oids);

        arsort($oids);

        // TEST: return [count($oids), $oids];

        $offset = ($page-1)*$limit;
        $oids = array_slice($oids, $offset, $limit, true);
        $ids = array_keys($oids);

        $hasMore = ($offset + $limit) < $total;
        return [
            'programs' => OrgNeedApp::whereIn('org_need_app_id', $ids)->get(),
            'has_more' => $hasMore,
            'total' => $total
        ];

    }

    static public function getProgramsDataByOrgId($id)
    {
        $programs = [];
        $models = self::where(['organization_id' => $id])->get();

        /** @var OrgNeedApp $model */
        foreach($models as $model) {
            $programs[] = $model->getCatalogViewData();
        }

        return $programs;
    }

}