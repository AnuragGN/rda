<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\Http\Controllers;

use App\Helpers\GConst;
use App\Helpers\GnUtils;
use App\Models\ClientEnv;
use App\Models\ClientInfo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @return mixed
     */
    public function root()
    {
        return redirect(GnUtils::userHomeUrl());
    }

    /**
     * @param Request $request
     * @param string $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function externalPage(Request $request, $page='a')
    {
        // TODO: return 404 if external page is not found
        if (auth()->user()) {
            return redirect('/'); // internal
        }
        $view = ClientInfo::externalPage($page);
        return $view ? view($view) : redirect('/');
    }

    public function getClientNameFromEnv(Request $request)
    {
        return [
            'ClientInfo::client' => ClientInfo::$client,
            'ClientEnv::clientEnv()' => ClientEnv::clientEnv()
        ];
    }

}
