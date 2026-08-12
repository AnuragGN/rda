<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 4/26/2022
 * Time: 1:35 PM
 */

namespace App\Helpers;

use App\Models\CallStatsCandid;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CandidManager
{
    const URL_V2 = 'https://api.candid.org/essentials/v2';

    /**
     * main function
     * @param $query
     * @return array
     */
    static public function search($query)
    {
        if (env('APP_ENV') != 'prod' && env('APP_ENV') != 'uat') {
            return CandidManager::getCachedCopy($query);
        }

        // search on candid / guide-star
        $response = CandidManager::getCandidResponse($query);
        $jsonResponse = $response->body();

        return CandidManager::getSearchResponseFromJson($jsonResponse);
    }

    /**
     * private helper
     *
     * @param $jsonResponse
     * @return array
     */
    static private function getSearchResponseFromJson($jsonResponse)
    {
        $data = json_decode($jsonResponse);

        $orgs = CandidManager::getOrgListFromData($data);
        if (count($orgs) < 1) {
            return ['status' => 204, 'message' => 'No matching results'];
        }
        $html = view('candid.list-org', compact('orgs'))->render();
        return ['status' => 200, 'data' => $orgs, 'html' => $html];
    }
    /**
     * search on candid / guide-star
     * @param $query
     */
    static private function getCandidResponse($query)
    {
        $key = config('app.candid_key','');

        // before candid call
        $model = CallStatsCandid::getEssentialInstance();
        $model->keyword = json_encode(["text" => $query]);
        $model->save();

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'text/json',
            'Subscription-Key' => $key
        ])->post(CandidManager::URL_V2, [
            "search_terms" => $query,
        ]);

        // after candid call
        if ($response->successful()) {
            $model->status = CallStatsCandid::STATUS_SUCCESS;
        } else {
            $model->status = CallStatsCandid::STATUS_FAILED;
            $error = [
                'status' => $response->status(),
                'body' => $response->body()
            ];
            $model->response = json_encode(['error' => $error]);
        }
        $model->save();
        return $response;
    }

    /**
     * helper to avoid candid calls in DEV and QA env
     * @param $query
     * @return array
     */
    static private function getCachedCopy($query)
    {
        $file = "candid.json";
        $isExists = Storage::exists($file);
        if (!$isExists) {
            // $query = "america";
            // search on candid / guide-star
            $response = CandidManager::getCandidResponse($query);
            // save response in file for later use
            $jsonResponse = $response->body();
            Storage::put($file, json_encode(['data' => $jsonResponse]));
        }
        $json = Storage::get($file);
        $content = json_decode($json);
        $jsonResponse = $content->data;

        return CandidManager::getSearchResponseFromJson($jsonResponse);
    }

    /**
     * helper to get org-list from candid-response-data
     * @param $data
     * @return array
     */
    static private function getOrgListFromData($data)
    {
        $list = [];
        if (!$data || !$data->data || !$data->data->hits) return $list;

        foreach($data->data->hits as $hit) {
            $org['ein'] = $hit->ein;
            $org['name'] = $hit->organization_name;
            $org['address_line_1'] = $hit->address_line_1;
            $org['address_line_2'] = $hit->address_line_2;
            $org['city'] = $hit->city;
            $org['state'] = $hit->state;
            $org['zip'] = substr($hit->zip,0,5) ;
            $org['county'] = $hit->county;
            $org['ntee_code'] = $hit->ntee_code;

            $org['address'] = $hit->address_line_1 . ', ' . $hit->city . ', ' . $hit->state . '-' . $hit->zip;

            $org['contact_name'] = $hit->contact_name;
            $org['contact_phone'] = GnUtils::formatPhoneNumber($hit->contact_phone);
            $org['contact_email'] = $hit->contact_email;
            $org['contact_title'] = $hit->contact_title;

            $org['mission'] = $hit->mission;

            $list[] = $org;
        }

        return $list;
    }

}
