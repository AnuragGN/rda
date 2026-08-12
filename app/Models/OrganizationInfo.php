<?php

namespace App\Models;

use App\Helpers\GnUtils;

class OrganizationInfo extends Organization
{
    private $object = null;
    private $story = null;
    private $interests = null;
    private $documents = null;
    private $staff = null;
    private $objectives = null;
    private $budget = null;
    private $taxInfo = null;
    private $boardMembers = null;
    private $nonDiscriminationPolicy = null;

    private $orgInfo = null;
    private $orgAddress = null;
    private $orgPhones = null;

    private function setObject()
    {
        if ($this->object) return;
        $data = json_decode($this->catalog_data);
        $this->object = $data->data;
    }

    public function getObject()
    {
        $this->setObject();
        return $this->object;
    }

    public function getOrgInfo()
    {
        $this->setObject();
        return $this->object->organization->info;
    }

    public function getOrgAddress()
    {
        $this->setObject();
        return $this->object->organization->address;
    }

    public function getOrgPhone()
    {
        $this->setObject();
        $phones = $this->object->organization->phone;
        return is_array($phones) ? $phones[0] : $phones;
    }

    public function getStory()
    {
        $this->setObject();
        return $this->object->story;
    }

    public function getInterests()
    {
        $this->setObject();
        return $this->object->interests;
    }

    public function getDocuments()
    {
        $this->setObject();
        return $this->object->documents;
    }

    public function getContact()
    {
        $this->setObject();
        return $this->object->staff;
    }

    public function getObjectives()
    {
        $this->setObject();
        return $this->object->objectives;
    }

    public function getBudget()
    {
        $this->setObject();
        return $this->object->budget;
    }

    public function getTaxInfo()
    {
        $this->setObject();
        return $this->object->tax_info;
    }

    public function getBoardMembers()
    {
        $this->setObject();
        return $this->object->board_members;
    }

    public function getNonDiscriminationPolicy()
    {
        $this->setObject();
        return $this->object->non_discrimination_policy;
    }

    public function getInfoAddressFromCity() {
        $response = '';
        $address = $this->getOrgAddress();
        if ($address->city) $response .= $address->city . ', ';
        if ($address->county) $response .= $address->county . ', ';
        if ($address->state) $response .= $address->state . ', ';
        if ($address->country) $response .= $address->country;

        return $response;
    }

    public function getInfoAddressMultiLine() {
        $response = '';
        $address = $this->getOrgAddress();
        if ($address->address_1) $response .= $address->address_1 . ',<br/>';
        if ($address->address_2) $response .= $address->address_2 . ',<br/>';
        if ($address->city) $response .= $address->city . ', ';
        if ($address->county) $response .= $address->county . ', ';
        $response .= '<br />';
        if ($address->state) $response .= $address->state;
        if ($address->country) $response .= ', ' . $address->country;
        if ($address->zip) $response .= ' - ' . $address->zip;

        return $response;
    }

    public function getInfoAddressTwoLine() {
        $response = '';
        $address = $this->getOrgAddress();
        if ($address->address_1) $response .= $address->address_1 . ', ';
        if ($address->address_2) $response .= $address->address_2 . ', ';
        if ($address->address_1 || $address->address_2) $response .= '<br />';

        if ($address->city) $response .= $address->city . ', ';
        if ($address->county) $response .= $address->county . ', ';
        if ($address->state) $response .= $address->state . ', ';
        if ($address->country) $response .= $address->country . ' ';
        if ($address->zip) $response .= '- ' . $address->zip;

        return $response;
    }

    /**
     * @param array $options ['interest_area_id'=>$id]
     * @return array
     */
    public function getCatalogViewData($options=[])
    {
        // return $this; // ->mission;

        $info = $this->getOrgInfo();

        $item = [];
        $item['title-link'] = $this->getUrl($options);
        $item['id'] = $this->organization_id;
        // $summary = GnUtils::textTruncate(strip_tags($this->mission), 200);
        // $item['description'] = $summary; //  . " <a href='" . $this->url . "'>Read more</a>";

        $item['title'] = $info->name;
        $item['sub-title'] = $this->getInfoAddressFromCity();

        $story = $this->getStory();
        $item['description'] = GnUtils::textTruncate(strip_tags($story->mission), 200);

        $item['image'] = $this->getImage();
        $item['mag-link'] = $this->getMakeGrantUrl();
        return $item;
    }

    public function getWebsite()
    {
        $info = $this->getOrgInfo();
        return trim($info->web_site);
    }

    public function getWebLink()
    {
        $info = $this->getOrgInfo();
        $url = $info->web_site;

        if (empty($url)) return '';
        $parsed = parse_url($url);

        // Check if parsed URL is empty and add http
        if (empty($parsed['scheme'])) {
            $url = 'http://' . ltrim($url, '/');
        }
        return empty($url) ? '' : $url;
    }

}