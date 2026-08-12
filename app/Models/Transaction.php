<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 17-05-2020
 * Time: 19:14
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Transaction extends BaseModel
{
    /* @var string */
    protected $table = 'transaction';

    /* @var boolean */
    public $timestamps = false;


    public function getModelId()
    {
        return $this->id;
    }

    public function getModelType()
    {
        return "transaction";
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

        $this->amount = $request->input('payAmount');
        $this->currency = self::TCURRENCY_USD;

        $this->target_type = $request->input('payTargetType');
        $this->target_id = $request->input('payTargetId');
        $this->contact_id = $request->input('payContactId');
        $this->note = $request->input('payNote');

        // validate input
        if (!$this->amount || !$this->target_type || !$this->target_id || !$this->contact_id) {
            return false;
        }

        /** @var Contact $contact */
        $contact = Contact::getByContactId($this->contact_id);
        if ($contact) {
            $this->contact_id = $contact->contact_id;
            $this->contact_email = $contact->email_address;

            /** @var ContactAddress $billing */
            $billing = $contact->getAddress();

            $this->bill_first_name = $contact->first_name;
            $this->bill_last_name = $contact->last_name;

            $address = '';
            if ($billing->address_1) $address .= $billing->address_1;
            if ($billing->address_2) $address .= ', ' . $billing->address_2;

            $this->bill_address = $address;
            $this->bill_city = $billing->city;
            $this->bill_state = $billing->state;
            $this->bill_country = $billing->country;
            $this->bill_zip = $billing->zip;
        }

        // log activity
        $activity = new LogActivity(LogActivity::NAME_TRANSACTION, LogActivity::ACTION_PAYMENT);
        if (!$contact || !$this->validateTransactionInput()) {
            $activity->onModel($this)->description(LogActivity::DES_BAD_INPUT)->data($this->toArray())->add();
            return false;
        }

        // save transaction info
        $this->save();

        // log activity
        $activity->onModel($this)->description(LogActivity::DES_CARD_TRANSACTION_INIT)->data($this->toArray())->add();

        return $this;
    }

    /**
     * @param Transaction $paidTransaction
     * @return $this
     */
    public function beforeRefundProcess(Transaction $paidTransaction)
    {
        // Set the transaction's refId
        $this->ref_id = ClientInfo::client() . '-' . time();

        $this->status = Transaction::TDB_STATUS_INIT;
        $this->message = "Refund Initiated";
        $this->transaction_type = Transaction::TTYPE_REFUND;

        $this->currency = self::TCURRENCY_USD;
        $this->amount = $paidTransaction->amount;
        $this->target_type = $paidTransaction->target_type;
        $this->target_id = $paidTransaction->target_id;
        $this->contact_id = $paidTransaction->contact_id;


        if ($paidTransaction->contact_email) $this->contact_email = $paidTransaction->contact_email;
        if ($paidTransaction->bill_first_name) $this->bill_first_name = $paidTransaction->bill_first_name;
        if ($paidTransaction->bill_last_name) $this->bill_last_name = $paidTransaction->bill_last_name;

        if ($paidTransaction->bill_address) $this->bill_address = $paidTransaction->bill_address;
        if ($paidTransaction->bill_city) $this->bill_city = $paidTransaction->bill_city;
        if ($paidTransaction->bill_state) $this->bill_state = $paidTransaction->bill_state;
        if ($paidTransaction->bill_country) $this->bill_state = $paidTransaction->bill_country;
        if ($paidTransaction->bill_zip) $this->bill_zip = $paidTransaction->bill_zip;

        // log activity
        $activity = new LogActivity(LogActivity::NAME_TRANSACTION, LogActivity::ACTION_REFUND);

        // save transaction info
        $this->save();

        // log activity
        $activity->onModel($this)->description(LogActivity::DES_CARD_TRANSACTION_INIT)->data($this->toArray())->add();

        return $this;
    }

    public function afterRefundProcess($result)
    {

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
     * common for payment and refund
     *
     * @param $result
     * @return $this
     */
    public function afterPaymentProcess($result)
    {
        // status
        $this->status = ($result['status']['code'] == self::TRS_SUCCESS) ? self::TDB_STATUS_SUCCESS : self::TDB_STATUS_FAILED;
        $this->message = $result['status']['message'];

        // transaction info
        $tr = $result['transactionResponse'];
        $this->transaction_id = $tr['transaction_id'];
        $this->response_auth_code = $tr['auth_code'];
        $this->account_number = $tr['account_number'];
        $this->account_type = $tr['account_type'];
        // $this->transaction_date = '';

        // response for debugging
        $this->response = json_encode($result);

        // save transaction info
        $this->save();

        // log activity
        if ($this->transaction_type == Transaction::TTYPE_REFUND) {
            $activity = new LogActivity(LogActivity::NAME_TRANSACTION, LogActivity::ACTION_REFUND);
            $activity->onModel($this)->description(LogActivity::DES_REFUND_RESP)->data($this->toArray())->add();
        } else {
            $activity = new LogActivity(LogActivity::NAME_TRANSACTION, LogActivity::ACTION_PAYMENT);
            $activity->onModel($this)->description(LogActivity::DES_CARD_TRANSACTION_RESP)->data($this->toArray())->add();
        }
        return $this;
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

    /**
     * @return string
     */
    public function getDisplayStatusLongAttribute()
    {
        if ($this->status == self::TDB_STATUS_INIT) {
            return 'The transaction could not be initiated due to system/network error.';
        } else if ($this->status == self::TDB_STATUS_SUCCESS) {
            return 'The transaction has been approved.';
        } else if ($this->status == self::TDB_STATUS_FAILED) {
            return 'This transaction has been declined, or could not be processed due to some error.';
        } else if ($this->status == self::TDB_STATUS_NO_RESPONSE) {
            return 'This transaction has been declined, or could not be processed due to some error.';
        } else {
            return 'Status unknown!';
        }
    }

    /**
     * Final displayable status
     *
     * @return null
     */
    public function getStatusMessage()
    {
        $found = false;
        $text = '';
        if ($this->response) {
            $response = json_decode($this->response);
            if (isset($response->status)) {
                $status = $response->status;
                if (isset($status->code)) {
                    if ($status->code == Transaction::TRS_NO_RESPONSE) {
                        $text = "ERROR! ";
                    } else if ($status->code == Transaction::TRS_FAILED) {
                        $text = "FAILED! ";
                    } else if ($status->code == Transaction::TRS_SUCCESS) {
                        $text = "Success: ";
                    } else {
                        $text = $status->code;
                    }
                    $text = $text . $status->message;
                    $found = true;
                }
            }

            if (!$found && isset($response->transactionResponse)) {
                $tr = $response->transactionResponse;
                if(isset($tr->message)) {
                    $message = $tr->message;
                    if(isset($message->description)) {
                        $text = $message->description;
                    }
                }
            }
        }
        return $text ? $text : $this->displayStatusLong;
    }

    /**
     * @return string
     */
    public function getPaidToAttribute()
    {
        if ($this->target_type == 'fund') {
            return $this->target_id;
        } else if ($this->target_type == 'fundname') {
            return $this->target_id;
        } else if ($this->target_type == 'organization') {
            return 'Org';
        } else {
            return 'Unknown';
        }
    }

    public function getShortAccountNumberAttribute()
    {
        return substr($this->account_number, -4);
    }

    /**
     * @param Request $request
     * @return $this|bool
     */
    public function beforeStripePayment(Request $request)
    {
        $transaction = $this->beforePaymentProcess($request);
        if ($transaction instanceof Transaction) {
            // Set the transaction's refId
            $this->ref_id .= '-' . 'st';
        }
        return $transaction;
    }

    /**
     * @param $result
     * @return $this
     */
    public function afterStripePayment($result)
    {
        $accountType = null;
        $accountNumber = null;

        if (isset($result['charges']) && isset($result['charges']['data'])) {
            $data = $result['charges']['data'];
            if (count($data)) {
                $item = $data[0];
                if (isset($item['payment_method_details'])) {
                    $tr = $item['payment_method_details'];
                    if (isset($tr['type'])) {
                        $accountType = $tr['type'];
                        if (isset($tr['card']) && isset($tr['card']['last4']))
                        $accountNumber = $tr['card']['last4'];
                    }
                    // $brand = $tr['card']['brand'];
                }
            }
        }

        if (!isset($result['status'])) {
            $this->status = self::TDB_STATUS_FAILED;
            $this->message = "The transaction has been declined, or could not be processed due to some error (1).";
        } elseif ($result['status'] == "succeeded") {
            $this->status = self::TDB_STATUS_SUCCESS;
            $this->message = "The transaction has been approved.";
        } elseif ($result['status'] == "processing") {
            $this->status = self::TDB_STATUS_NO_RESPONSE;
            $this->message = "The transaction is either being processed or has been terminated.";
        } elseif ($result['status'] == "requires_payment_method") {
            $this->status = self::TDB_STATUS_FAILED;
            $this->message = "This transaction has been declined, or could not be processed due to some error (2).";
        } else {
            $this->status = self::TDB_STATUS_FAILED;
            $this->message = "This transaction has been declined, or could not be processed due to some error (3).";
        }

        $this->response = json_encode($result);
        $this->account_number = $accountNumber;
        $this->account_type = $accountType;
        $this->save();

        $activity = new LogActivity(LogActivity::NAME_TRANSACTION, LogActivity::ACTION_PAYMENT);
        $activity->onModel($this)->description(LogActivity::DES_CARD_TRANSACTION_RESP)->data($this->toArray())->add();

        return $this;
    }

    public function beforeDAFContributionCCPayment(Request $request)
    {
        $transaction = $this->beforePaymentProcess($request);
        if ($transaction instanceof Transaction) {
            // Set the transaction's refId
            $this->ref_id .= '-' . 'daf';
        }
        return $transaction;
    }

}
