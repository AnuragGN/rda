<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 4/5/2022
 * Time: 11:17 PM
 */

namespace App\Helpers;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class FileManager
{
    // file type
    const FT_FUND_STATEMENT = "FUND-STATEMENT";

    const JCF_GRANT_CALENDAR_PDF = "/jcf/grant_calendar.pdf";
    const JCF_GRANT_CALENDAR_PDF_NAME = "Quarterly Grant Schedule.pdf";
    const JCF_PERFORMANCE_FLASH_PDF = "/jcf/performance_flash.pdf";

    /**
     * For JCF only
     * @return bool
     */
    static public function hasGrantCalendarJCF()
    {
        $file = FileManager::JCF_GRANT_CALENDAR_PDF;
        $isExists = Storage::exists($file);
        return $isExists;
    }

    /**
     * For JCF only
     * @return bool
     */
    static public function hasPerformanceFlashJCF()
    {
        $file = FileManager::JCF_PERFORMANCE_FLASH_PDF;
        $isExists = Storage::exists($file);
        return $isExists;
    }

    //
    static public function clientDocumentsPath()
    {
        return ClientInfo::client();
    }

    /**
     * path where user uploaded documents are stored
     * @return string
     */
    static public function clientUserDocumentsPath()
    {
        $role = GnUtils::getUserRole();
        if (!$role) $role = 'unknown';

        return FileManager::clientDocumentsPath() . '/' . $role;
    }

    /**
     * @return array|\Illuminate\Config\Repository|mixed
     */
    static public function getReadablePDFDocumentList()
    {
        if (GnUtils::isDonorSession()) {
            return ClientConfig::getReadablePDFDocumentListForDonor();
        } else {
            return [];
        }
    }

    /**
     * @return array|\Illuminate\Config\Repository|mixed
     */
    static public function getWritablePDFDocumentList()
    {
        if (GnUtils::isDonorSession()) {
            return ClientConfig::getWritablePDFDocumentListForDonor();
        } else {
            return [];
        }
    }

    /**
     * check if NFS for document storage is mounted
     * @return bool
     */
    static public function isNFSMounted()
    {
        $path = FileManager::clientDocumentsPath();
        $file = 'read.txt';
        $filePath = $path . '/' . $file;
        return Storage::disk('nfs')->exists($filePath);
    }

    /**
     * @param Request $request
     * @param $type
     * @return bool
     */
    static public function saveFile(Request $request, $type)
    {
        if (GnUtils::isDonorSession()) {
            $contactId = Contact::sessionContactId();

            $time = time();
            $date = date('m_d_Y', $time);
            $fileName = $contactId . '_' . $type . '_' . $date . '_' . $time . '.' . $request->file->extension();
            $path = FileManager::clientUserDocumentsPath();
            $filePath = $request->file->storeAs($path, $fileName, 'nfs');
            return $filePath;
        } else if (GnUtils::isAdminSession()) {
            return false;
        } elseif (GnUtils::isAgencySession()) {
            return false;
        } else {
            return false;
        }
    }

}