<?php
/**
 * Created by PhpStorm.
 * User: Rajan
 * Date: 11-11-2025
 * Time: 14:16
 */

namespace App\Models;

use App\Helpers\Data;
use App\Helpers\GConst;
use App\Models\DAF\DAFAdditionalDonor;
use App\Models\DAF\DAFContributions;
use App\Models\DAF\DAFDonor;
use App\Models\DAF\DAFSecurity;
use App\Models\DAF\DAFStocks;
use App\Models\DAF\DAFSuccessorOrganizations;
use App\Models\DAF\DAFSuccessorIndividuals;
use App\Models\DAF\DAFUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;


/**
 * Class UserRegistration - User Registration
 * @package App
 */
class UserRegistration extends Model
{
    /* @var string */
    protected $table = 'user_registration';


    static public function createAdvisorTicket(Request $request, $advisorRegistrationId)
    {
        $ticket = new Ticket();
        $ticket->target_type = GConst::ADVISOR_REGISTRATION;
        $ticket->target_id = $advisorRegistrationId;
        $ticket->title = 'Advisor Onboarding Ticket - '.$request->first_name;
        
        // Build a well-formatted description containing all request values
        // Exclude common framework fields, checkbox/button names and other UI-only inputs
        $excludeKeys = ['_token', '_method', 'files', 'accept_advisor', 'id_accept_advisor', 'save', 'id_save_btn'];
        $parts = [];
        $data = $request->all();

        foreach ($data as $key => $value) 
        {
            if (in_array($key, $excludeKeys, true)) 
            {
                continue;
            }
            if ($value === null || $value === '') 
            {
                continue;
            }

            // Make a human-friendly label from the key
            $label = ucwords(str_replace(['_', '-'], ' ', $key));

            // Normalize the value for display
            if (is_array($value)) {
                $valueStr = implode(', ', array_map(function ($v) {
                    if (is_scalar($v)) return trim((string)$v);
                    return trim(json_encode($v));
                }, $value));
            } elseif (is_bool($value)) {
                $valueStr = $value ? 'Yes' : 'No';
            } else {
                $valueStr = trim((string)$value);
            }

            $parts[] = sprintf("%s: %s", $label, $valueStr);
        }

        $ticket->description = implode("\n", $parts);
        $ticket->created_at =  Carbon::now();
        $ticket->start_date =  Carbon::now();
        $ticket->end_date =  null;
        $ticket->category = 'advisor onboarding';
        # $ticket->assigned_to = 19;
        $ticket->status = GConst::DEFAULT_TICKET_STATUS;
        $ticket->priority = GConst::DEFAULT_TICKET_PRIORITY;
        $ticket->created_by = null;
        $ticket->save();
        return $ticket;
    }

    static public function getSSOUserRegistrationRecord($partner_id, $sso_id)
    {
        return self::where('affiliate_id', $partner_id)
                ->where('sso_id', trim($sso_id))
                ->first();
    }

    static public function checkUserRegistered($sso_id)
    {
        return self::where('sso_id', trim($sso_id))
                ->first();
    }

    public static function getPartnerIdBySSOId($ssoId)
    {
        if (empty($ssoId)) {
            return null;
        }

        return self::where('sso_id', trim($ssoId))
                ->value('affiliate_id'); // returns only partner_id
    }
}
