<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 4/7/2022
 * Time: 12:32 PM
 */

namespace App\Http\Controllers\Donor;

use App\Forms\FormDummy;
use App\Helpers\FileManager;
use App\Helpers\GConst;
use App\Helpers\GnUtils;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\ContactFund;
use App\Models\Docs;
use App\Models\Fund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadGrantCalendar(Request $request)
    {
        $headers = [];
        $file = FileManager::JCF_GRANT_CALENDAR_PDF;
        // $name = basename($file);
        $name = FileManager::JCF_GRANT_CALENDAR_PDF_NAME;
        return Storage::download($file, $name, $headers);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadPerformanceFlash(Request $request)
    {
        $headers = [];
        $file = FileManager::JCF_PERFORMANCE_FLASH_PDF;
        $name = basename($file);
        return Storage::download($file, $name, $headers);
    }

    /**
     * @return $this|FileController|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function myStatements()
    {
        GnUtils::addBreadcrumb('Fund Statements');
        return $this->showStatementFundSelection();
    }

    /**
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    private function showStatementFundSelection()
    {
        $model = new FormDummy();

        // contact must have view permission
        $funds = Fund::getSelectableViewable();
        $funds = array_change_key_case($funds, CASE_UPPER);

        if (!count($funds)) {
            return redirect()->back()->with('error', 'You do not have any funds');
        } else if (count($funds) > 1) {
            $fundId = null;
            $funds = ['' => 'Select..'] + $funds;
            return view('donor.documents.fund-selection', compact('model', 'funds', 'fundId'));
        } else {
            $fundIds = array_keys($funds);
            $fundId = $fundIds[0];
            return view('donor.documents.fund-selection', compact('model', 'funds', 'fundId'));
        }
    }

    /**
     * @param $fundId
     * @return array
     */
    public function myStatementAjax($fundId)
    {
        // make sure that the fundId is associated with the session contact
        ContactFund::assertViewable($fundId);

        // read all files in the bulk statements folder
        $base = FileManager::clientDocumentsPath();
        $root = $base . '/bulk/statements/';
        $allFiles = Storage::disk('nfs')->allFiles($root);

        // filter fund statements
        $search = $root . strtoupper($fundId) . '_';
        $files = array_filter($allFiles, function($file) use ($search) {
            return str_starts_with($file, $search);
        });

        $files = array_values($files);
        if (!count($files)) {
            return [ 'status' => 200, 'html' => "No data found"];
        }

        // parse statement files
        $items = [];
        foreach($files as $file) {
            $item = $this->parseStatementFile($file);
            if ($item) $items[] = $item;
        }

        // response
        $html = view('donor.documents.my-statement-list', compact('items'))->render();
        return [ 'status' => 200, 'html' => $html];
    }

    /**
     * @param $filePath
     * @return array|null
     */
    private function parseStatementFile($filePath)
    {
        $item = [];
        $item['path'] = $filePath;
        $item['file'] = pathinfo($filePath, PATHINFO_BASENAME);
        $item['link'] = route('download-statements', $item['file']);
        $parts = explode('_', $item['file']);

        if (ClientInfo::isHGA()) {
            if (count($parts) < 6) return null;
            $date = $parts[3] . '/' . $parts[4] . '/' .$parts[5];
            $item['name'] = 'Download statement of ' . $date;
            $item['fundId'] = $parts[0];
        } else {
            if (count($parts) < 5) return null;
            $date = $parts[2] . '/' . $parts[3] . '/' .$parts[4];
            $item['name'] = 'Download statement of ' . $date;
            $item['fundId'] = $parts[0];
        }
        return $item;
    }

    /**
     * download fund statement file
     * @param Request $request
     * @param $file
     * @return mixed
     */
    public function downloadStatement(Request $request, $file)
    {
        $fileInfo = $this->parseStatementFile($file);

        // make sure that the fundId is associated with the session contact
        ContactFund::assertViewable($fileInfo['fundId']);

        $base = FileManager::clientDocumentsPath();
        $filePath = $base . '/bulk/statements/' . $file;

        $headers = [];
        return Storage::disk('nfs')->download($filePath, $file, $headers);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function myDocuments(Request $request)
    {
        GnUtils::addBreadcrumb(ClientConfig::text('FUND_DOCUMENTS'));
        return view('donor.documents.my-documents');
    }

    /**
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function myDocumentList($type)
    {
        GnUtils::addBreadcrumb(ClientConfig::text('FUND_DOCUMENTS'), route('my-documents'));
        GnUtils::addBreadcrumb('Download');

        $fund = null;

        if ($type == FileManager::FT_FUND_STATEMENT) {
            return $this->showStatementFundSelection();
        }

        $readable = FileManager::getReadablePDFDocumentList();
        $name = isset($readable[$type]) ? $readable[$type] : 'Unknown';

        $name = 'Download ' . $name;

        $data = Docs::getDonorDocumentListByType($type);
        return view('donor.documents.my-document-list', compact('name', 'data'));
    }

    /**
     * @param Request $request
     * @param $key
     * @return mixed
     */
    public function downloadDocument(Request $request, $key)
    {
        // TODO: check access permissions
        // make sure that the file belongs to the logged in user
        $doc = Docs::where(['key' => $key])->first();
        if (!$doc) abort(404);

        $headers = [];
        $filename = pathinfo($doc->file_path, PATHINFO_BASENAME);

        return Storage::disk('nfs')->download($doc->file_path, $filename, $headers);
    }


    /**
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    static public function documentUpload($type)
    {
        GnUtils::addBreadcrumb(ClientConfig::text('FUND_DOCUMENTS'), route('my-documents'));
        GnUtils::addBreadcrumb('Upload');

        $writable = FileManager::getWritablePDFDocumentList();
        $name = isset($writable[$type]) ? $writable[$type] : 'Unknown';

        return view('donor.documents.upload', compact('type', 'name'));
    }

    /**
     * @param Request $request
     * @param $type
     * @return $this
     */
    public function documentUploadPost(Request $request, $type)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        $request->validate([
            'file' => 'required|mimes:pdf|max:8096',
            // 'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        ]);

        $path = FileManager::saveFile($request, $type);
        if (!$path) {
            return back()->with('danger', 'Your file could not be uploaded. Please try after sometime..');
        }

        $doc = Docs::getInstance();
        $doc->type = $type;
        $doc->file_path = $path;
        $doc->file_name = $request->file->getClientOriginalName();
        $doc->privacy = Docs::PRIVACY_PROTECTED;
        $doc->assoc_contact_id = Contact::sessionContactId();
        $doc->key = Str::random(40);
        $doc->save();

        // $fileName = time().'.'.$request->file->extension();
        // $request->file->move(public_path('uploads'), $fileName);

        return back()->with('success','Your file has been successfully uploaded.');
            // ->with('file', $fileName);
    }

}
