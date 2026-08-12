<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 6/8/2021
 * Time: 2:41 PM
 */

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class PerformanceData
{
    /**
     * @return array
     */
    static public function getPoolTabs()
    {
        return GhSegment::getPoolTabs();
    }

    /**
     * @param $accountId
     * @param $type
     * @return array
     */
    static public function getComposition($accountId, $type)
    {
        return $type == 'pool' ?
            GhPerformance::getPoolComposition($accountId):
            GhComposition::getFundComposition($accountId);
    }

    /**
     * same for pool & fund
     *
     * @param $accountId
     * @return array
     */
    static public function getPerformance($accountId, $type)
    {
        // prepare performance data
        $data = [];
        $data['barChartsData'] = GhPerformance::getFundPerformanceBarChartData($accountId);
        $data['barChartsTable'] = GhPerformance::getFundPerformanceTableData($accountId);
        return $data;
    }

    static public function strToFloat($str) {
        return floatval(preg_replace("/[^-0-9\.]/","",$str));
    }

    static public function strToIntX($str) {
        $newStr = str_replace(',', '', $str);
        return intval($newStr);
    }

    /**
     * Returns null or matching file name
     *
     * @param $accountId
     * @return null|String
     */
    static public function performanceFileName($accountId)
    {
        $root = 'jcf/ghp/';
        $extraLen = strlen('ddmmyyyy.pdf');
        $allFiles = Storage::allFiles($root);

        $search = $root . '2416_' . strtoupper($accountId) . '_';
        $files = array_filter($allFiles, function($file) use ($search, $extraLen) {
            return str_contains($file, $search) && strlen($search) + $extraLen == strlen($file);
        });

        $files = array_values($files);
        if (count($files) !== 1) return null;
        return $files[0];
    }

    /**
     * @param $accountId
     * @return bool
     */
    static public function performanceFileExists($accountId)
    {
        return self::performanceFileName($accountId) !== null;
    }

    /**
     * @param $accountId
     * @param $type
     * @return null|string
     */
    static public function performanceFileUrl($accountId, $type)
    {
        if ($type !== 'pool' && !ContactFund::isAssociated($accountId)) {
            return null;
        }
        return route('performance-file-download', ['id' => $accountId, 'type' => $type]);
    }

    static public function fundPerformanceFile($id=null)
    {
        return '/ma/files/GH-Fund-Performance-Mar2021.pdf';
    }

    static public function poolPerformanceFile($pool=null)
    {
        return '/ma/files/GH-Pool-Performance-Mar2021.pdf';
    }

    static public function getBaseColor()
    {
        if (ClientInfo::isJCF()) {
            return '#f07148';
        } else {
            return '#000000';
        }
    }

    /**
     * @return array
     */
    static public function getPieChartColors()
    {
        $colors = [];
        $colors[0] = '#125294'; // royal blue
        $colors[1] = '#00929F'; // new teal
        $colors[2] = '#F47521'; // orange / saffron
        $colors[3] = '#FFC753'; // anniversary yellowish
        $colors[4] = '#65696E'; // JCF grey
        $colors[5] = '#B32317'; // legacy red
        $colors[6] = '#125294'; // royal blue
        $colors[7] = '#00929F'; // new teal
        $colors[8] = '#F47521'; // orange / N
        $colors[9] = '#000000'; // black
        return $colors;
    }

    /**
     * @return array
     */
    static public function getColors()
    {
        $colors = [];
        if (ClientInfo::isJCF()) {
            $colors[0] = '#195392'; // royal blue
            $colors[1] = '#39919d'; // new teal
            $colors[2] = '#195392'; // orange / saffron
            $colors[3] = '#39919d'; // anniversary yellowish
            $colors[4] = '#195392'; // JCF grey
            $colors[5] = '#39919d'; // legacy red
            $colors[6] = '#195392'; // royal blue
            $colors[7] = '#39919d'; // new teal
            $colors[8] = '#195392'; // orange / N
            $colors[9] = '#39919d'; // black
        } else {
            $colors[0] = '#125294'; // royal blue
            $colors[1] = '#00929F'; // new teal
            $colors[2] = '#F47521'; // orange / saffron
            $colors[3] = '#FFC753'; // anniversary yellowish
            $colors[4] = '#65696E'; // JCF grey
            $colors[5] = '#B32317'; // legacy red
            $colors[6] = '#125294'; // royal blue
            $colors[7] = '#00929F'; // new teal
            $colors[8] = '#F47521'; // orange / N
            $colors[9] = '#000000'; // black
        }
        return $colors;
    }

    /**
     * @param bool|true $forFund
     * @return array
     */
    static public function getFooterNotes($forFund=true)
    {
        $notes = [
            "Fund data is on a trade date basis and income is included in the fund returns on an accrual basis",
            "Fund returns are gross of management fees",
            "All returns include the effects of all principal change and income, and returns for longer than one year are annualized"
        ];
        // "Total Fund Balanced Index (12/31/19 - 03/31/21)" =>

        $stp = "100% BofA Merrill Lynch US 3 Month T-Bill";
        $mtp = "60% Bloomberg Barclays Aggregate, 11% S&P 500, 7% MSCI EAFE, 2% MSCI Emerging Markets, " .
            "15% Bloomberg Barclays Global Aggregate (Hedged), 5% Real Assets Custom Benchmark";
        $ltesgp = "25% Bloomberg Barclays Aggregate, 36% Russell 3000, 22.5% MSCI EAFE, " .
            "7% MSCI Emerging Markets, 6% Bloomberg Barclays US Corp. IG, " .
            "3.5% MSCI World Core Infrastructure USD Hedged";
        $ltip = "25% Bloomberg Barclays US Aggregate, 41% Russell 3000, 26% MSCI EAFE, " .
            "8% MSCI Emerging Markets";
        $ip = "20% Bloomberg Barclays US Aggregate, 40% S&P 500, 15% MSCI EAFE, 10% MSCI World, " .
            "5% Bloomberg Barclays Global Aggregate (Hedged), 10% Bloomberg Barclays US Corp. IG";
        $ep = "12% Bloomberg Barclays Aggregate, 13% HFRI FOF: Conservative, 22% Russell 3000, " .
            "14% MSCI EAFE, 4% MSCI Emerging Markets, 15% MSCI World, 5% FTSE NAREIT, " .
            "10% Bloomberg Barclays US Corp. IG, 5% Real Assets Custom Benchmark";

        $pairs = [
            //"Total Fund Balanced Index" =>
            //    "28% MSCI ACWI-Net, 17% S&P 500, 2% Russell 2500, 2% Russell 2000, 7% MSCI EAFE -Net, 4% MSCI EM - Net, 1% Global LP Equity, 1% BBgBrc Gbl Ag Ex, 14% BB Aggregate, 19% WAM LAlt Mlt Str, 5% S&P Real Assets",
            "Short Policy" => $stp,
            "Mid Term Policy" => $mtp,
            "L/T ESG Policy" => $ltesgp,
            "L/T Index Policy" => $ltip,
            "Impact Policy" => $ip,
            "Endowment Policy" => $ep,
            // "Impact Invt Pool-Policy" =>
            //    "10% MSCI ACWI-Net, 40% S&P 500, 15% MSCI EAFE -Net, 5% BBgBrc Gbl Ag Ex, 20% BB Aggregate, 10% FTSE Corp",
            // "Endowment Pool-Policy" =>
            //    "15% MSCI ACWI-Net, 22% Russell 3000, 14% MSCI EAFE -Net, 4% MSCI EM - Net, 12% BB Aggregate, 10% FTSE Corp, 5% NAREIT All REIT, 13% HFRI Fnd Comp, 5% S&P Real Assets"
        ];
        $footer = [];
        $footer['notes'] = $forFund ? $notes : [];
        $footer['pairs'] = $pairs;
        return $footer;
    }

}