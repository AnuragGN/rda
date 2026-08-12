<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 18-01-2020
 * Time: 13:27
 */

namespace App\Models;
use App\Models\FaPartner;

class ClientInfo
{
    static $client = null;
    static $info = [
        'gna' => [
            'name' => 'GiftingNetwork',
            'logo' => '/ma/images/gna/logo_md.png',
            'favicon' => '/ma/images/gna/favicon.png',
            'base_url' => '//www.giftingnetwork.com/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'gmf' => [
            'name' => 'Greater Milwaukee Foundation',
            'logo' => '/ma/images/gmf/logo.svg',
            'favicon' => '/ma/images/gmf/favicon.ico',
            'base_url' => '//www.greatermilwaukeefoundation.org/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'jcf' => [
            'name' => 'Jewish Community Foundation - San Diego',
            'logo' => '/ma/images/jcf/logo.png',
            'favicon' => '/ma/images/jcf/favicon.ico',
            'base_url' => '//jcfsandiego.org/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'hga' => [
            'name' => 'HighGround Advisors',
            'logo' => '/ma/images/hga/logo.png',
            'favicon' => '/ma/images/hga/favicon.ico',
            'base_url' => '//www.highgroundadvisors.org/',
            'cc' => array(
                ['email' => 'patty.weiland@highgroundadvisors.org'],
                ['email' => 'lacy.cagle@highgroundadvisors.org'],
                ['email' => 'ruth.price@highgroundadvisors.org']
            ),
        ],
        'nif' => [
            'name' => 'The Progressive Jewish Fund',
            'logo' => '/ma/images/nif/logo.png',
            'favicon' => '/ma/images/nif/favicon.ico',
            'base_url' => '//nif.org/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'jsv' => [
            'name' => 'Jewish Silicon Valley',
            'logo' => '/ma/images/jsv/logo.png',
            'favicon' => '/ma/images/jsv/favicon.png',
            'base_url' => '//www.jvalley.org/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'mercy' => [
            'name' => 'Mercy Health',
            'logo' => '/ma/images/mercy/logo.svg',
            'favicon' => '/ma/images/mercy/favicon.ico',
            'base_url' => '//mercy.com/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'cct' => [
            'name' => 'The Chicago Community Trust',
            'logo' => '/ma/images/cct/logo.svg',
            'favicon' => '/ma/images/cct/logo-32x32.png',
            'base_url' => '//cct.org/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'ntc' => [
            'name' => 'Northern Trust',
            'logo' => '/ma/images/ntc/logo.svg',
            'favicon' => '/ma/images/ntc/logo-16x16.png',
            'base_url' => '//www.northerntrust.com/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'ffp' => [
            'name' => 'Focus Financial Partners',
            'logo' => '/ma/images/ffp/fv-logo-dark.svg',
            'favicon' => '/ma/images/ffp/fav-icon.jpg',
            'base_url' => '//focusfinancialpartners.com/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'jcfla' => [
            'name' => 'Jewish Community Foundation of Los Angeles',
            'logo' => '/ma/images/jcfla/logo.png',
            'favicon' => '/ma/images/jcfla/favicon.png',
            'base_url' => '//www.jewishfoundationla.org/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'wgf' => [
            'name' => 'World Giving Foundation',
            'logo' => '/ma/images/wgf/logo.png',
            'favicon' => '/ma/images/wgf/favicon.png',
            'base_url' => '//www.worldgivingfoundation.org/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],
        'cgf' => [
            'name' => 'Charitable Gifting Fund',
            'logo' => '/ma/images/cgf/logo.png',
            'favicon' => '/ma/images/cgf/favicon.png',
            'base_url' => '//charitablegiftingfund.org/',
            'cc' => array(
                ['email' => "alkeshksingh@giftingnetwork.com"]
            ),
        ],

    ];

    static public function client() {
        if (!self::$client) self::$client = env('APP_CLIENT', 'gna');
        return self::$client;
    }
    static public function clientViews() {
        $client = self::client();
        return $client ? $client . '.' : '';
    }
    static public function clientViewFor($view, $default='') {
        $c  = self::clientViews();
        $cv = $c . $view;
        return view()->exists($cv) ? $cv : $default . $view;
    }
    static public function clientCss($file) {
        $client = self::client();
        return '/ma/css/' . $client . '/' . $file;
    }
    static public function customNav() {
        $cv = self::clientViews() . 'layouts.custom-nav';
        return view()->exists($cv) ? $cv : 'layouts.custom-nav';
    }
    static public function landingPage() {
        $cv = self::clientViews() . 'external.home';
        return view()->exists($cv) ? $cv : 'auth.login';
    }
    static public function externalPage($page) {
        $cv = self::clientViews() . 'external.' . $page;
        return view()->exists($cv) ? $cv : null;
    }

    static public function isGMF() {
        return self::client() == 'gmf';
    }
    static public function isJCF() {
        return self::client() == 'jcf';
    }
    static public function isHGA() {
        return self::client() == 'hga';
    }
    static public function isGNA() {
        return self::client() == 'gna';
    }
    static public function isNIF() {
        return self::client() == 'nif';
    }
    static public function isMercy() {
        return self::client() == 'mercy';
    }
    static public function isJSV() {
        return self::client() == 'jsv';
    }
    static public function isCCT() {
        return self::client() == 'cct';
    }
    static public function isNTC() {
        return self::client() == 'ntc';
    }
    static public function isCCTorNTC() {
        return self::client() == 'cct' || self::client() == 'ntc';
    }
    static public function isFFP() {
        return self::client() == 'ffp';
    }
    static public function isPFR() {
        return self::client() == 'pfr';
    }
    static public function isJCFLA() {
        return self::client() == 'jcfla';
    }
    static public function isWGF() {
        return self::client() == 'wgf';
    }
    static public function isCGF() {
        return self::client() == 'cgf';
    }
    static public function isGnaPLAY() {
        $flavor = env('APP_FLAVOR', null);
        return ClientInfo::isGNA() && $flavor === 'play';
    }

    /**
     * name for page title
     * @return mixed
     */
    static public function name() {
        return self::$info[self::client()]['name'];
    }

    /**
     * description for page-description
     * @return string
     */
    static public function description() {
        return '';
    }

    /**
     * main logo
     * @return mixed
     */
    static public function logo() {
        return self::$info[self::client()]['logo'];
    }

    /**
     * favicon
     * @return mixed
     */
    static public function favicon() {
        return self::$info[self::client()]['favicon'];
    }

    /**
     * base URL
     * @return mixed
     */
    static public function getBaseUrl() {
        return self::$info[self::client()]['base_url'];
    }

    static public function getEmailCC() {
        return self::$info[self::client()]['cc'];
    }

    static public function registrationImage(){
        if (ClientInfo::isHGA()) {
            return '/ma/images/hga/registration-header.jpg';
        } else {
            return '/ma/images/gna/registration-header.jpg';
        }
    }
}