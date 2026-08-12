<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UserRegistration;
use App\Models\ClientInfo;

class FaPartner extends Model
{

    protected $table = 'fa_partner';
    protected $primaryKey = 'partner_id';
    public $incrementing = false;

    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        "partner_id",
        "name",
        "status",
        "branding",
        "updated_by",
    ];

    /**
     * Get partner by partner_id
     * @param string $partnerId
     * @return \App\Models\FaPartner|null
     */
    static public function getPartnerByPartnerId($partnerId)
    {
        return self::where('partner_id', $partnerId)
                ->where('status', 'active')
                ->first();
    }

    /**  
     * Get Partner Branding - Logo, Colors, Footer Info etc.        
     * @return string|null
     */

    public static function getClientHeaderLogo()
    {
        return self::getClientLogo('header');
    }

    public static function getClientFooterLogo()
    {
        return self::getClientLogo('footer');
    }

    protected static function getPartnerId()
    {
        // 1. Try from contact → SSO → partner
        $contactId = Contact::sessionContactId();
        
        if ($contactId) 
        {
            $contact = Contact::getByContactId($contactId);

            if ($contact && !empty($contact->gn_sso_id)) {
                $partnerId = UserRegistration::getPartnerIdBySSOId($contact->gn_sso_id);

                if ($partnerId) {
                    return $partnerId;
                }
            }
        }

        // 2. Fallback to session
        if (session()->has('partner_id')) {
            return session('partner_id');
        }

        // 3. Nothing found
        return null;
    }


    
    protected static function getPartnerBranding()
    {
        $partnerId = self::getPartnerId();

        if (empty($partnerId)) {
            return [];
        }

        try {
            $res = self::getPartnerByPartnerId($partnerId);
        } catch (\Throwable $e) {
            return [];
        }

        if (!$res || empty($res->branding)) {
            return [];
        }

        $branding = $res->branding;

        if (is_string($branding)) {
            $branding = json_decode($branding, true);
        }

        if (is_object($branding)) {
            $branding = (array) $branding;
        }

        return is_array($branding) ? $branding : [];
    }


    public static function getClientLogo($type = 'header')
    {
        $defaultLogo = ClientInfo::$info[ClientInfo::client()]['logo'];

        $branding = self::getPartnerBranding();
        if (empty($branding)) {
            return $defaultLogo;
        }

        if (
            isset($branding['logos']) &&
            isset($branding['logos'][$type]) &&
            !empty($branding['logos'][$type])
        ) {
            return $branding['logos'][$type];
        }

        return $defaultLogo;
    }

    public static function getClientBackgroundColor()
    {
        $branding = self::getPartnerBranding();

        if (!empty($branding['colors']['background'])) {
            return $branding['colors']['background'];
        }
        return '';
    }

    public static function getClientPrimaryColor()
    {
        $branding = self::getPartnerBranding();

        if (!empty($branding['colors']['primary'])) {
            return $branding['colors']['primary'];
        }
        return '';
    }

    public static function getClientSecondaryColor()
    {
        $branding = self::getPartnerBranding();

        if (!empty($branding['colors']['secondary'])) {
            return $branding['colors']['secondary'];
        }
        return '';
    }

    public static function getBrandBodyClasses()
    {
        $classes = [];

        if (self::getClientBackgroundColor()) {
            $classes[] = 'has-brand-bg';
        }

        if (self::getClientPrimaryColor()) {
            $classes[] = 'has-brand-accent';
        }

        if (self::getClientSecondaryColor()) {
            $classes[] = 'has-brand-secondary';
        }
        return implode(' ', $classes);
    }


    public static function getFooterBranding()
    {
        $branding = self::getPartnerBranding();

        return [
            'logo'        => self::getClientFooterLogo(),
            'address'     => $branding['footer_section']['address'] ?? '89 HEADQUARTERS PLAZA, SUITE 1446,
MORRISTOWN, NEW JERSEY, 07960',
            'email'       => $branding['footer_section']['email'] ?? 'INFO@GIFTINGNETWORK.COM',
            'phone'       => $branding['footer_section']['phone'] ?? '973.984.8200',
            'quick_links' => self::getFooterQuickLinks(),
        ];
    }

    public static function getFooterQuickLinks()
    {
        $branding = self::getPartnerBranding();

        if (
            !empty($branding['footer_section']['quick_links']) &&
            is_array($branding['footer_section']['quick_links'])
        ) {
            return $branding['footer_section']['quick_links'];
        }

        // Default quick links

        return [
            ['name' => 'Home', 'url' => 'https://giftingnetwork.com/'],
            ['name' => 'Request Demo', 'url' => 'https://giftingnetwork.com/request-a-demo/'],
            ['name' => 'About', 'url' => 'https://giftingnetwork.com/about-us/'],
            ['name' => 'Contact', 'url' => 'https://giftingnetwork.com/contact'],
        ];
    }

}