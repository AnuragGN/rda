<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Donation extends BaseModel
{

    protected $fillable = [
        'amount',
        'interval',
        'start_date',
        'occurrences',
        'dedicated_to_name',
        'notify_fname',
        'notify_lname',
        'notify_email',
        'notify_phone',
        'notify_address_one',
        'notify_address_two',
        'notify_city',
        'notify_state',
        'notify_country',
        'notify_zip',
        'donor_contact_id',
        'donor_org_name',
        'guest_fname',
        'guest_lname',
        'guest_email',
        'guest_phone',
        'guest_address_one',
        'guest_address_two',
        'guest_city',
        'guest_state',
        'guest_country',
        'guest_zip',
        'note',
        // ref_id,
        // target_type,
        // target_id,
        // 'no_end',
        // 'notify_to',
        // status,
        // message,
        // account_number,
        // account_type,
        // currency,
        // transaction_id,
        // transaction_date,
        // transaction_type,
        // response,
        // response_auth_code,
    ];

    /* @var string */
    protected $table = 'donation';

    /* @var boolean */
    public $timestamps = false;


    public function getIntervalText() {
        switch($this->interval) {
            case 'one':
                return 'One-time';
            case 'monthly':
                return 'Every month';
            case 'bi-monthly':
                return 'Every other month';
            case 'quarterly':
                return 'Every 3 months';
            case 'half-yearly':
                return 'Every 6 months';
            case 'yearly':
                return 'Every 12 months';
            default:
                return '---';
        }
    }

    /**
     * @return string
     */
    public function getDisplayStatusAttribute()
    {
        if ($this->status == self::TDB_STATUS_INIT) {
            return 'Error';
        } else if ($this->status == self::TDB_STATUS_SUCCESS) {
            return 'Success';
        } else if ($this->status == self::TDB_STATUS_FAILED) {
            return 'Failed';
        } else if ($this->status == self::TDB_STATUS_NO_RESPONSE) {
            return 'Failed';
        } else {
            return 'None';
        }
    }

    public function getTargetName() {
        if ($this->target_type == "organization") {
            $model = Organization::getById($this->target_id);
            return $model ? $model->name . ' (Organization)' : 'Organization';
        } else if ($this->target_type == "fund") {
            $model = Fund::getFundById($this->target_id);
            return $model ? $model->name . ' (Fund)' : 'Organization';
        }
        return 'Unknown';
    }

    public function isOneTime() {
        return $this->interval == 'one';
    }

    static public function getInstance()
    {
        $model = new Donation();
        $model->ref_id = 0;
        $model->no_end = false;
        $model->notify_to = false;
        $model->currency = self::TCURRENCY_USD;
        $model->end_type = 'occurrences';
        // target_type,
        // target_id,
        // status,
        // message,
        // account_number,
        // account_type,
        // currency,
        // transaction_id,
        // transaction_date,
        // transaction_type,
        // response,
        // response_auth_code,
        return $model;
    }

    /**
     * @return bool
     */
    private function validateTransactionInput()
    {
        return ($this->amount > 0 &&
            strlen($this->target_type) > 0 &&
            strlen($this->target_id) > 0);
    }

    /**
     * @return string
     */
    public function getIntervalUnit()
    {
        return "months";
    }

    /**
     * @return \DateTime
     */
    public function getStartDateTime()
    {
        // new DateTime('2020-08-30')
        $date = new \DateTime($this->start_date);
        return $date;
    }

    /**
     * @return int
     */
    public function getIntervalLength()
    {
        switch($this->interval) {
            case 'monthly':
                return 1;
            case 'bi-monthly':
                return 2;
            case 'quarterly':
                return 3;
            case 'half-yearly':
                return 6;
            case 'yearly':
                return 12;
            default:
                return 0;
        }
    }

    /**
     * @param Request $request
     * @return $this|bool
     */
    public function beforePaymentProcess(Request $request)
    {
        // Set the transaction's refId
        $this->ref_id = ClientInfo::client() . '-' . time();

        $this->status = Transaction::TDB_STATUS_INIT;
        $this->message = "Transaction Initiated";
        $this->transaction_type = Transaction::TTYPE_CARD;

        $this->fill($request->all());

        if ($request->input('notify') ) {
            $this->notify_to = true;
        } else {
            $this->notify_to = false;
        }

        if ($request->input('no_end')) {
            $this->no_end = true;
            $this->occurrences = 9999;
        } else {
            $this->no_end = false;
        }

        $organizationId = $fundId = null;
        // $this->contact_id = $request->input('payContactId');
        if ($request->input('search_option') == 'orgs') {
            $this->target_id = $request->input('organization_id');
            $this->target_type = 'organization';
        } else if ($request->input('search_option') == 'funds') {
            $this->target_id = $request->input('fund_id');
            $this->target_type = 'fund';
        } else {
            return false;
        }

        // log activity
        $activity = new LogActivity(LogActivity::NAME_DONATION, LogActivity::ACTION_PAYMENT);
        if (!$this->validateTransactionInput()) {
            $activity->onModel($this)->description(LogActivity::DES_BAD_INPUT)->data($this->toArray())->add();
            return false;
        }

        // save donation info
        $this->save();

        // log activity
        $activity->onModel($this)->description(LogActivity::DES_CARD_TRANSACTION_INIT)->data($this->toArray())->add();

        return $this;
    }

    public function afterPaymentProcess($result)
    {
        // status
        $this->status = ($result['status']['code'] == self::TRS_SUCCESS) ? self::TDB_STATUS_SUCCESS : self::TDB_STATUS_FAILED;
        $this->message = $result['status']['message'];

        // response info - only for one-time payment
        if (isset($result['transactionResponse'])) {
            $tr = $result['transactionResponse'];
            $this->transaction_id = $tr['transaction_id'];
            $this->response_auth_code = $tr['auth_code'];
            $this->account_number = $tr['account_number'];
            $this->account_type = $tr['account_type'];
            // $this->transaction_date = '';
        }

        // response for debugging
        $this->response = json_encode($result);

        // save transaction info
        $this->save();

        // log activity
        $activity = new LogActivity(LogActivity::NAME_DONATION, LogActivity::ACTION_PAYMENT);
        $activity->onModel($this)->description(LogActivity::DES_CARD_TRANSACTION_RESP)->data($this->toArray())->add();
        return $this;
    }

    static public function getSampleInstance() {
        $model = new Donation();

        $model->id = 1;
        $model->ref_id  = 64;
        $model->target_type  = 'fund';
        $model->target_id = 1;
        $model->amount = 100;
        $model->interval = 'monthly';
        $model->start_date = '2020-10-10'; // new \DateTime();
        $model->end_type = 'occurrences';
        $model->no_end = false;
        $model->occurrences = 4;
        $model->dedicated_to_name = 'Dedicate Tomar';
        $model->notify_to = true;
        $model->notify_fname = 'Vijay';
        $model->notify_lname = 'Kumar';
        $model->notify_email = 'vijay@gmail.com';
        $model->notify_phone ='9898989876';
        $model->notify_address_one = 'Flat, Building';
        $model->notify_address_two = 'Address Two';
        $model->notify_city = 'Noida';
        $model->notify_state = 'UP';
        $model->notify_country = 'India';
        $model->notify_zip = 201301;
        $model->donor_contact_id = 1;
        $model->donor_org_name = 'To Organization';
        $model->guest_fname  = 'Vijay';
        $model->guest_lname = 'Kumar';
        $model->guest_email = 'vijay@gmail.com';
        $model->guest_phone ='9898989876';

        $model->guest_address_one = 'Flat, Building';
        $model->guest_address_two = 'Address Two';
        $model->guest_city = 'Noida';
        $model->guest_state = 'UP';
        $model->guest_country = 'India';
        $model->guest_zip = '201301';
        $model->status = 2;
        $model->message = 'This transaction has been approved';
        $model->account_number = 'XXXX1111';
        $model->account_type = 'Visa';
        $model->currency = 'USD';
        $model->transaction_id  = 'tx8278272';
        $model->transaction_date =  'dd'; // new \DateTime();
        $model->transaction_type = '';
        $model->response  = 'Response';
        $model->response_auth_code = 'Auth818282';
        $model->note = 'Note';

        return $model;
    }

    /**
     * @return mixed model-id
     */
    public function getModelId()
    {
        return $this->id;
    }

    /**
     * @return string, fund|transaction|etc.
     */
    public function getModelType()
    {
        return "donation";
    }

}