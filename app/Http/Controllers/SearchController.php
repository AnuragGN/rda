<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\Http\Controllers;

use App\Helpers\CandidManager;
use App\Models\ContactAddress;
use App\Models\Fund;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GiftHistory;

class SearchController extends Controller
{
    /**
     * Search organizations
     */
    public function searchOrgs(Request $request) {

        $text = $request->input('q');
        if (strlen($text) < 1) return [];

        $models = Organization::searchTypeahead($text);
        return $models;

        $val = [];
        $val[] = [
            'label' => '1 best-pictures',
            'value' => '1 value1',
            'source' => '1 bestPictures'
        ];
        $val[] = [
            'label' => '2 best-pictures',
            'value' => '2 value1',
            'source' => '2 bestPictures'
        ];
        return $val;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function searchCatalogOrgs(Request $request)
    {
        $text = $request->input('q');
        if (strlen($text) < 1) return [];

        $models = Organization::searchTypeahead($text, true);
        return $models;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function searchCandidOrgs(Request $request)
    {
        $text = $request->input('query');
        if (strlen($text) < 3) {
            return ['status' => 202, 'message' => 'Please enter 3 or more characters'];
        }

        return CandidManager::search($text);
    }

    /**
     * Search funds
     */
    public function searchFunds(Request $request) {

        $text = $request->input('q');
        if (strlen($text) < 1) return [];

        $models = Fund::searchTypeahead($text);
        return $models;

        $val = [];
        $val[] = [
            'label' => '1 best-pictures',
            'value' => '1 value1',
            'source' => '1 bestPictures'
        ];
        $val[] = [
            'label' => '2 best-pictures',
            'value' => '2 value1',
            'source' => '2 bestPictures'
        ];
        return $val;
    }

}
