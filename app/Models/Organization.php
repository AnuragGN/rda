<?php

namespace App\Models;

use App\Helpers\GnUtils;
use Illuminate\Database\Eloquent\Model;

class Organization extends BaseModel
{
    /* @var string */
    protected $table = 'organization';

    /* @var string */
    protected $primaryKey = 'organization_id';

    /* @var boolean */
    public $timestamps = false;

    public function getId()
    {
        return $this->organization_id;
    }

    /**
     * @return mixed model-id
     */
    public function getModelId()
    {
        return $this->organization_id;
    }

    /**
     * @return string, fund|transaction|etc.
     */
    public function getModelType()
    {
        return 'organization';
    }

    public function getAddress($type=null)
    {
        if (!$type || !AddressType::isValidOrgAddressType($type)) {
            $type = AddressType::getOrgAddressTypePrimary();
        }
        $conditions = [
            'organization_id' => $this->organization_id,
            'address_type' => $type
        ];

        /** @var OrganizationAddress $address */
        $address = OrganizationAddress::where($conditions)->first();
        if (!$address) {
            $address = new OrganizationAddress();
            $address->address_type = $type;
        }
        return $address;
    }


    /**
     * @param null $type
     * @return OrganizationPhone
     */
    public function getPhone($type=null)
    {
        if (!$type || !PhoneType::isValidOrgPhoneType($type)) {
            $type = PhoneType::getOrgPhoneTypePrimary();
        }
        $conditions = [
            'organization_id' => $this->organization_id,
            'phone_type' => $type
        ];

        /** @var OrganizationPhone $phone */
        $phone = OrganizationPhone::where($conditions)->first();
        if (!$phone) {
            $phone = new OrganizationPhone();
            $phone->organization_id = $this->organization_id;
            $phone->phone_type = $type;
        }
        $phone->formatPhoneNumber();
        return $phone;
    }

    // TODO: MUST BE DELETED as org can have multiple addresses
    public function address()
    {
        return $this->hasOne('App\Models\OrganizationAddress', "organization_id");
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function getById($id) {
        return self::where(['organization_id' => $id])->first();
    }

    /*
     * Multi-line address
     */
    public function getMultiLineAddress($type)
    {
        $conditions = [
            'organization_id' => $this->organization_id,
            'address_type' => $type
        ];

        /** @var OrganizationAddress $address */
        $address = OrganizationAddress::where($conditions)->first();
        return $address ? $address->getMultiLineAddress() : null;
    }

    /**
     * @return OrganizationAddress
     */
    public function getAddressFromCity()
    {
        /** @var OrganizationAddress $address */
        $address = $this->getPrimaryAddress();
        return $address->getAddressFromCity();
    }

    /**
     * @return OrganizationAddress
     */
    public function getPrimaryAddressInline()
    {
        /** @var OrganizationAddress $address */
        $address = $this->getPrimaryAddress();
        return $address->getAddressForTypeahead();
    }

    /**
     * @return OrganizationAddress
     */
    public function getPrimaryAddress()
    {
        $conditions = [];
        $conditions['organization_id'] = $this->organization_id;

        /** @var OrganizationAddress $address */
        $conditions['address_type'] = AddressType::getOrgAddressTypePrimary();
        $address = OrganizationAddress::where($conditions)->first();
        return $address ? $address : new OrganizationAddress();
    }

    /**
     * @return OrganizationAddress
     */
    public function getAnyAddress()
    {
        $conditions = [];
        $conditions['organization_id'] = $this->organization_id;

        /** @var OrganizationAddress $address */
        $conditions['address_type'] = AddressType::getOrgAddressTypePrimary();
        $address = OrganizationAddress::where($conditions)->first();
        if ($address) return $address;

        // get any address
        unset($conditions['address_type']);
        $address = OrganizationAddress::where($conditions)->first();
        return $address ? $address : new OrganizationAddress();
    }

    public function phones()
    {
        return OrganizationPhone::where([
            'organization_id' => $this->organization_id,
            'is_fax' => 'N',
        ])->get();
        // return $this->hasMany('App\Models\ContactPhone', 'contact_id');
    }

    public function canAddPhoneTypes()
    {
        $result = [];
        $phones = $this->phones();
        $types = PhoneType::getOrgPhoneTypes();

        foreach($types as $type) {
            $flag = true;
            foreach($phones as $phone) {
                if ($phone->phone_type == $type->phone_type) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                if (!isset($type['label'])) $type['label'] = ucfirst($type->phone_type);
                $result[] = $type;
            }
        }
        return $result;
    }

    /**
     * @param $text
     * @return array
     */
    static public function searchTypeahead($text, $catalog=false)
    {
        $data = [];

        // 'status' => 'Active',
        if ($catalog) {
            $conditions = ['visible' => 'Y'];
        } else {
            $conditions = ['allow_recommendation' => 'Y'];
        }
        $models = self::where($conditions)->where('name', 'ilike', '%' . $text . '%')->orderBy('name')->limit(25)->get();

        /** @var Organization $model */
        foreach ($models as $model) {

            $oc = $contact = null;
            if (ClientInfo::isCCT()) {
                $oc = OrganizationContact::where(['organization_id' => $model->organization_id])
                    ->where(function ($q) {
                        $q->where('is_default', 'Y')
                            ->orWhere('access_level', 1);
                    })->first();

                $contact = $oc ? Contact::getByContactId($oc->contact_id) : null;
            }
            if (!$contact) {
                $contact = new Contact();
            }

            $data[] = [
                'label' => $model->name,
                'value' => "" . $model->organization_id,
                'source' => "" . $model->organization_id,
                'address' => "" . $model->getPrimaryAddressInline(),
                'id' => $model->organization_id,

                'contact_name' => $contact->name,
                'contact_email' => $contact->email_address,
                'contact_phone' => $contact->getPrimaryPhoneNumber(),
                'contact_title' => $oc ? $oc->contact_role : ''
            ];
        }
        return $data;
    }

    /**
     * SELECT contact_id, is_default, access_level, contact_role
     * FROM organization_contact WHERE organization_id = ? AND (is_default = 'Y' OR access_level = '1')
     *
     * @return array
     */
    public function primaryContact()
    {
        $dummy = ["organizationContact" => new OrganizationContact(), 'primaryContact' => new Contact()];
        $organizationContact = OrganizationContact::where(['organization_id' => $this->organization_id])
            ->where(function($q) {
                $q->where('is_default', 'Y')
                    ->orWhere('access_level', 1);
            })->first();

        if (!$organizationContact) return $dummy;
        $contact = Contact::getByContactId($organizationContact->contact_id);

        if (!$contact) return $dummy;
        return ["organizationContact" => $organizationContact, 'primaryContact' => $contact];
    }

    /**
     * @return array
     */
    public function getPrimaryContactInfoForRecommendation()
    {
        $contact = [
            'name' => '',
            'title' => '',
            'phone' => '',
            'contact_id' => '',
        ];
        $pc = $this->primaryContact();
        if ($pc['organizationContact']) {
            $contact['title'] = $pc['organizationContact']->contact_role;
        }
        if ($pc['primaryContact']) {
            $contact['contact_id'] = $pc['primaryContact']->contact_id;
            $contact['name'] = $pc['primaryContact']->name;
            $contact['phone'] = $pc['primaryContact']->getPrimaryPhoneNumber();
        }
        return $contact;
    }

	// TEST
    static public function getSelectable($id=null) {
        return Organization::where([])->limit(10)->pluck('name', 'organization_id')->toArray();
    }

    /**
     * @param bool|true $default
     * @return bool|null|string
     */
    public function getImage($default=true)
    {
        $image = null;
        if (!empty($this->logo_url)) {
            $image = $this->logo_url;
        } else if (!empty($this->photo_url)) {
            $image = $this->photo_url;
        } else if (!empty($this->image_url)) {
            $image = $this->image_url;
        }
        if (!empty($image)) {
            $server = ClientConfig::assetServer();
            return $server . $image;
        }
        if ($default === true) {
            return '/ma/images/' . ClientInfo::client() . '/placeholder-org.png';
        }
        return $default;
    }

    public function getUrl($options=[])
    {
        if (isset($options['interest_area_id'])){
            return route('organization', ['id' => $this->organization_id, 'interest_area_id' => $options['interest_area_id']]);
        } else {
            return route('organization', ['id' => $this->organization_id]);
        }
    }

    public function getMakeGrantUrl()
    {
        return route('grant-create', ['org_id' => $this->organization_id]);
    }

    public function getMatchingOrgs($page, $limit)
    {
        // cia => Contact Interest Area
        // cga => Contact Geographic Area
        // cps => Contact Population Served

        if ($page < 1) $page = 1;
        if ($limit < 3 || $limit > 25) $limit = 5;

        $ciaW = Config::getInterestAreaWeight();
        $cgaW = Config::getGeographicAreaWeight();
        $cpsW = Config::getPopulationServedWeight();

        // $contact = Contact::sessionContact();
        $ciaIds = ContactInterestArea::getInterestAreaIds();
        $cgaIds = ContactGeographicArea::getGeographicAreaIds();
        $cpsIds = ContactPopulationServed::getPopulationServedIds();

        $conditions = [];
        $clientConditions = [];

        if (ClientInfo::isHGA()) {
            // show all
        } else if (ClientInfo::isGMF()) {
            $clientConditions = ['organization.allow_recommendation' => 'Y'];
        } else {
            $clientConditions = ['organization.visible' => 'Y', 'organization.allow_recommendation' => 'Y'];
        }

        // step 1
        $ciaOrgIds = OrgInterestArea::where($conditions)
            ->whereIn('interest_area_id', $ciaIds)
            ->join('organization', 'organization.organization_id', '=', 'org_interest_area.organization_id')
            ->where($clientConditions)
            ->pluck('org_interest_area.organization_id')->toArray();

        // step 2
        $cgaOrgIds = OrgGeographicArea::where($conditions)
            ->whereIn('geographic_area_id', $cgaIds)
            ->join('organization', 'organization.organization_id', '=', 'org_geographic_area.organization_id')
            ->where($clientConditions)
            ->pluck('org_geographic_area.organization_id')->toArray();

        // step 3
        $cpsOrgIds = OrgPopulationServed::where($conditions)
            ->whereIn('population_served_id', $cpsIds)
            ->join('organization', 'organization.organization_id', '=', 'org_population_served.organization_id')
            ->where($clientConditions)
            ->pluck('org_population_served.organization_id')->toArray();

        $ciaOrgIds = array_unique($ciaOrgIds);
        $cgaOrgIds = array_unique($cgaOrgIds);
        $cpsOrgIds = array_unique($cpsOrgIds);

        $oids = [];
        foreach($ciaOrgIds as $id){
            $oids[$id] = $ciaW;
        }
        foreach($cgaOrgIds as $id){
            if (isset($oids[$id])) $oids[$id] += $cgaW;
        }
        foreach($cpsOrgIds as $id){
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
            'organizations' => OrganizationInfo::whereIn('organization_id', $ids)->get(),
            'has_more' => $hasMore,
            'total' => $total
        ];
    }

    public function getProgramsData()
    {
        return OrgNeedApp::getProgramsDataByOrgId($this->organization_id);
    }
}
