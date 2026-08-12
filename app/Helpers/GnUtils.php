<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 20-10-2019
 * Time: 23:37
 */

namespace App\Helpers;


use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class GnUtils
{
    static private $breadcrumbs = [];

    /**
     * Date may be in "dd/mm/yyyy" (American) or "mm-dd-yyyy" (European) format
     * @param $date
     * @return bool|string
     */
    static public function customDate($date)
    {
        if (!$date) return "";
        return date(ClientConfig::dateFormat(), strtotime($date));
    }

    /**
     * Date is in custom format "dd-mm-yyyy" (generally entered by user)
     * Date must be converted to proper american format before passing it to date()
     */
    static public function customUIDate($date)
    {
        $date = str_replace('-', '/', $date);
        return date(ClientConfig::dateFormat(), strtotime($date));
    }

    static public function dateYMD($date)
    {
        return date("M d, Y", strtotime($date));
    }

    static public function percent($value)
    {
        $percent = number_format($value, 2);
        return $percent;
    }

   static function floatValue($val)
   {
        $val = str_replace(",","",$val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return floatval($val);
   }

    static public function StrToMoney($amount, $sign = '$')
    {
        if ($amount === null || $amount === '') return 0;
        $amount = self::floatValue($amount);
        return $sign . number_format($amount, 2);
    }

    static public function money($amount, $sign = '$')
    {
        return $sign . number_format($amount, 2);
        // money_format('$%i', 3.4)
    }

    // JCF Statement
    static public function moneyJCFS($amount, $sign = '$')
    {
        $value = number_format(abs($amount), 2);
        if ($amount < 0) $value = '(' . ($value) . ')';
        return $sign . $value;
        // money_format('$%i', 3.4)
    }

    static public function addBreadcrumb($title, $link = null)
    {
        self::$breadcrumbs[] = ['title' => $title, 'link' => $link];
    }

    static public function addBreadcrumbs($items)
    {
        self::$breadcrumbs = $items;
    }

    static public function getBreadcrumbs()
    {
        return self::$breadcrumbs;
    }

    /**
     * truncates a string to a certain char length, stopping on a word if not specified otherwise.
     *
     * @param $string
     * @param $length
     * @param bool|false $stopanywhere
     * @return string
     */
    static function textTruncate($string, $length, $stopanywhere = false)
    {
        // truncates a string to a certain char length, stopping on a word if not specified otherwise.
        if (strlen($string) > $length) {
            //limit hit!
            $string = substr($string, 0, ($length - 3));
            if ($stopanywhere) {
                //stop anywhere
                $string .= '...';
            } else {
                //stop on a word.
                $string = substr($string, 0, strrpos($string, ' ')) . '...';
            }
        }
        return $string;
    }

    public function tmp()
    {
        if (App::environment('dev')) {
            // The environment is dev
        }

        if (App::environment(['dev', 'qa', 'uat'])) {
            // The environment is either dev OR staging...
        }

        if (App::environment('prod')) {
            // The environment is production
        }

    }

    static public function showRepeatOnGrantHistoryItem()
    {
        if (ClientInfo::isJCF() && !GnUtils::isDonorSession()) return false;
        return true;
    }

    static public function isDonorSession()
    {
        $role = \Session::get(\App\Helpers\GConst::SESSION_ROLE);
        return $role == GConst::SESSION_ROLE_DONOR;
    }

    static public function isAgencySession()
    {
        $role = \Session::get(\App\Helpers\GConst::SESSION_ROLE);
        return $role == GConst::SESSION_ROLE_AGENCY;
    }

    static public function isSupportStaffSession()
    {
        $role = \Session::get(\App\Helpers\GConst::SESSION_ROLE);
        return $role == GConst::SESSION_ROLE_SUPPORT_STAFF;
    }

    static public function isSeekerSession()
    {
        $role = \Session::get(\App\Helpers\GConst::SESSION_ROLE);
        return $role == GConst::SESSION_ROLE_GRANTEE;
    }

    static public function isAdminSession()
    {
        $role = \Session::get(\App\Helpers\GConst::SUPER_SESSION);
        return $role == GConst::SUPER_SESSION_ADMIN;
    }

    /**
     * document upload directory name is based on user-role
     * @return mixed
     */
    static public function getUserRole()
    {
        return \Session::get(\App\Helpers\GConst::SESSION_ROLE);
    }

    static public function getUserView($view)
    {
        return \Session::get(\App\Helpers\GConst::SESSION_ROLE) . '.' . $view;
    }

    /**
     * @return string
     */
    static public function getSuperSessionContact() {
        $superContactId = \Session::get(\App\Helpers\GConst::SUPER_SESSION_CONTACT_ID);
        return empty($superContactId) ? null : Contact::find($superContactId);
    }

    /**
     * @return string
     */
    static public function getSuperSessionContactName() {
        $contact = self::getSuperSessionContact();
        return empty($contact) ? "" : $contact->name;
    }

    /**
     * @param $items
     * @return array
     */
    static public function configEmailsToArray($items)
    {
        if (!$items) return [];
        if (is_string($items)) return [$items];
        if (!is_array($items)) return [];

        $emails = [];
        foreach($items as $item) {
            if (is_string($item)) {
                $emails[] = $item;
            } else if (is_array($item) && isset($item['email'])) {
                $emails[] = $item['email'];
            } else {
                // ignore
            }
        }
        return $emails;
    }

    /**
     * @param $items
     * @return string
     */
    static public function configEmailsToString($items)
    {
        $emails = GnUtils::configEmailsToArray($items);
        $string = implode(", ", $emails);
        return $string;
    }

    /**
     * @return mixed
     */
    static public function userHomeUrl()
    {
        $role = request()->session()->get(GConst::SESSION_ROLE);

        if ($role == GConst::SESSION_ROLE_DONOR) {
            return route('donor-home');
        } else if ($role == GConst::SESSION_ROLE_AGENCY) {
            return route('agency-dashboard');
        } else if ($role == GConst::SESSION_ROLE_SUPPORT_STAFF) {
            return route('support-staff-ticket');
        } else if ($role == GConst::SESSION_ROLE_GRANTEE) {
            return route('gs-home');
        } else if ($role == GConst::SESSION_ROLE_ADMIN) {
            if (ClientInfo::isCCT()) {
                return route('emulation-home');
            } else {
                return route('admin-console');
            }
        } else if ($role == GConst::SESSION_ROLE_DAF) {
            return route('daf-account-home');
        } else {
            return route('login');
            // return view(ClientInfo::landingPage());
        }
    }

    static public function phoneNumbersOnly($phone)
    {
        if (!$phone) return $phone;
        return preg_replace("/[^\d]/", "", $phone);
    }

    static public function formatPhoneNumber($phone)
    {
        if (!$phone) return $phone;
        $pos = strpos($phone, "x");
        if ($pos > 0) $phone = substr($phone, 0, $pos);
        $numbers = preg_replace("/[^\d]/", "", $phone);
        return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $numbers);
    }

    static public function maskPhoneNumber($phone)
    {
        if (!$phone) return $phone;
        $phone = GnUtils::phoneNumbersOnly($phone);

        if (strlen($phone) == 10) {
            return preg_replace("/(\d{6})(\d{4})/", '(***)***-$2', intval($phone));
        }
        if (strlen($phone) == 11) {
            return preg_replace("/(\d{7})(\d{4})/", '+*(***)***-$2', intval($phone));
        }
        if (strlen($phone) == 12) {
            return preg_replace("/(\d{8})(\d{4})/", '+**(***)***-$2', intval($phone));
        }
        if (strlen($phone) == 13) {
            return preg_replace("/(\d{9})(\d{4})/", '+***(***)***-$2', intval($phone));
        }
        return "*";
    }

    static public function maskEmail($email)
    {
        $charShown = 2;

        $parts = explode("@", $email);
        $username = $parts[0];
        $len = strlen( $username );

        if( $len <= $charShown ){
            $parts[0] = str_repeat("*",$len);
            return implode("@", $parts );
        }

        // show asterisk in middle, but also show the last character before @
        $parts[0] = substr( $username, 0 , $charShown )
            . str_repeat("*", $len - $charShown - 1 )
            . substr( $username, $len - $charShown + 1 , 1  );

        return implode("@", $parts );
    }

    /**
     * for CCT only
     * @return array
     */
    static public function selectableUpcomingTuesdaysAndThursdaysCCT()
    {
        // https://giftingnetwork.atlassian.net/browse/CCT-80
        // If today is Sunday or Monday, the next available payout date is this Thursday
        // If today is Tuesday, Wednesday or Thursday, the next available payout date is the following Tuesday
        // If today is Friday or Saturday, the next available payout date is the following Thursday

        $days = 45;
        $date = Carbon::now('America/Chicago'); // 'America/Chicago' is the timezone for Central Time

        if ($date->isTuesday()) $date->addDay();

        $skip = true; // skip first 'Tuesdays' or 'Thursdays'
        $dates = [];

        $holidays = [
            "11-23-2023",
            "12/26/2023",
            "07-04-2024",
            "11-28-2024",
            "12-24-2024",
            "12-26-2024",
            "07-01-2025",
            "07-03-2025",
            "07-04-2025",
            "11-27-2025",
            "12-25-2025",
            "01-01-2026",
            "07-04-2026",
        ];

        // loop through the next '$days' days to get all the Tuesdays and Thursdays
        for ($i = 0; $i < $days; $i++) {
            // check if the date is a Tuesday or Thursday
            if ($date->isTuesday() || $date->isThursday()) {
                if ($skip) {
                    $skip = false;
                    $date->addDay();
                    continue;
                }
                $item = $date->format('m-d-Y');
                $dates[$item] = $item;
            }
            // move to the next day
            $date->addDay();
        }

        return array_diff($dates, $holidays);
    }

    /**
     * for GNA only
     * @return array
     */
    static public function selectableUpcomingGrantsDatesGNA()
    {
        $days = 45;
        $date = Carbon::now('America/Chicago'); // 'America/Chicago' is the timezone for Central Time

        $date->addDay();
        $skip = true; // skip first 'Tuesdays' or 'Thursdays'
        $dates = [];

        $holidays = [
            "11-23-2023",
            "12/26/2023"
        ];

        // loop through the next '$days' days to get all the Tuesdays and Thursdays
        for ($i = 0; $i < $days; $i++) {
            // check if the date is a Monday or Wednesday
            if ($date->isMonday() || $date->isWednesday()) {
                if ($skip) {
                    $skip = false;
                    $date->addDay();
                    continue;
                }
                $item = $date->format('m-d-Y');
                $dates[$item] = $item;
            }
            // move to the next day
            $date->addDay();
        }

        return array_diff($dates, $holidays);
    }

    static public function selectableUpcomingGrantsDatesJSV()
    {
        $date = Carbon::now('America/Chicago')->addDays(7); // Start search from 2 days in the future
        $dates = [];

        while (count($dates) < 6) {
            $day = (int) $date->format('d');

            if ($day === 10 || $day === 25) {
                $formatted = $date->format('m-d-Y');
                $dates[$formatted] = $formatted;
            }

            $date->addDay();
        }

        return $dates;
    }

    static public function selectableUpcomingGrantsDatesFFTC()
    {
        return self::selectableUpcomingGrantsDatesGNA();
    }
    /**
     * @return bool
     */
    static public function isEmulationMode()
    {
        // if this is admin session, and also donor-session
        return self::isAdminSession() && self::isDonorSession();
    }

    /**
     * NON-SSO Users of NTC: contact fed_id = BLANK AND contact affiliate_entity = ‘ntcgp’ or 'ntc' or 'ntc_cct'
     * @param $user
     * @return bool
     */
    static public function isNTCSSOUser($user=null)
    {
        if (!ClientInfo::isNTC()) return false;
        if (empty($user)) {
            $user = User::getSessionUser();
        }
        if (empty($user)) return false;

        $contact = Contact::getByUser($user);
        return GnUtils::isNTCSSOContact($contact);
    }

    /**
     * NON-SSO Users of NTC: contact fed_id = BLANK AND contact affiliate_entity = ‘ntcgp’ or 'ntc' or 'ntc_cct'
     * then these users will need to login directly to the portal
     * @param $contact
     * @return bool
     */
    static public function isNTCSSOContact($contact)
    {
        if (!ClientInfo::isNTC()) return false;
        if (empty($contact)) return false;

        // fed-id must be non-empty for SSO
        if (empty($contact->fed_id)) return false;

        // affiliate must be NTC for SSO
        $affiliate = $contact->affiliate_entity;
        if (empty($affiliate)) return false;
        if ($affiliate != 'ntc' && $affiliate != 'ntc_cct' && $affiliate != 'ntcgp') return false;

        return true;
    }

    /**
     * CCT and NTC users must on on their portal
     * @param null $user
     * @return bool
     */
    static public function isUserOnWrongPortalCCTNTC($user=null)
    {
        if (!ClientInfo::isCCTorNTC()) return false;
        if (empty($user)) {
            $user = User::getSessionUser();
        }
        $contact = Contact::getByUser($user);
        if (empty($contact)) return false;

        $affiliate = $contact->affiliate_entity;

        if (ClientInfo::isNTC()) {
            return $affiliate !== 'ntc' && $affiliate !== 'ntc_cct' && $affiliate !== 'ntcgp';
        }

        return !empty($affiliate) && $affiliate !== 'cct' && $affiliate !== 'ntc_cct';
    }


    static public function getFullSql($query)
    {
        $sql = $query->toSql();          // SQL with placeholders
        $bindings = $query->getBindings(); // Actual values

        // Inject the bindings into the SQL (escaping string values)
        $fullSql = vsprintf(
            str_replace('?', "'%s'", $sql),
            array_map(function ($value) {
                return is_numeric($value) ? $value : addslashes($value);
            }, $bindings)
        );
        return $fullSql;

    }

}
