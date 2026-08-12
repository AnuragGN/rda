<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 06-04-2020
 * Time: 23:29
 */

namespace App\Models;


use App\Helpers\GnUtils;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    const TCURRENCY_USD = "USD";

    // transaction type
    const TTYPE_CARD = 'card';
    const TTYPE_BANK = 'bank';
    const TTYPE_REFUND = 'refund';

    // transaction db status
    const TDB_STATUS_INIT = 1;
    const TDB_STATUS_SUCCESS = 2;
    const TDB_STATUS_FAILED = 3;
    const TDB_STATUS_NO_RESPONSE = 4;

    // transaction response (from AuthNet) status
    const TRS_NO_RESPONSE = 0;
    const TRS_SUCCESS = 1;
    const TRS_FAILED = 2;

    // transaction response messages
    const TRM_FAILED = "Transaction failed";
    const TRM_APPROVED = "Transaction successful";
    const TRM_NO_RESPONSE = "No response from payment gateway";
    const TRM_NO_TRESPONSE = "No transaction-response from payment gateway";

    /**
     * @return mixed model-id
     */
    abstract public function getModelId();

    /**
     * @return string, fund|transaction|etc.
     */
    abstract public function getModelType();

    /**
     * a class must override is model-id is not integer
     * @return bool
     */
    public function isModelIdInteger() {
        return true;
    }

    public function getShortText($text, $length=140)
    {
        return GnUtils::textTruncate(strip_tags($text), $length);
    }

}
