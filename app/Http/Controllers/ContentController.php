<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 26-11-2020
 * Time: 15:25
 */

namespace App\Http\Controllers;

use App\Helpers\GnUtils;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Articles view
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $model = Content::findOrFail($id);
        return view('content.show', compact('model'));
    }

    /**
     * Added for CCT - programs list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function programs()
    {
        GnUtils::addBreadcrumb('CCT Initiatives');

        $models = Content::getPrograms();
        return view('content.programs', compact('models'));
    }

    /**
     * Added for CCT - show program info
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showProgram($id)
    {
        GnUtils::addBreadcrumb('CCT Initiatives', route('content.programs'));
        GnUtils::addBreadcrumb('Program');

        $model = Content::findOrFail($id);
        return view('content.show-program', compact('model'));
    }

}