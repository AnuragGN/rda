<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 09-05-2020
 * Time: 21:57
 */

namespace App\Http\Controllers;


use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Donation;
use App\Models\Email;
use App\Helpers\GConst;
use App\Models\Transaction;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

//
//  define("AUTHORIZENET_LOG_FILE", "phplog");
// require 'vendor/autoload.php';
// require_once 'constants/SampleCodeConstants.php';

// https://developer.authorize.net/api/reference/features/acceptjs.html
// Embed our hosted, mobile-optimized payment information form in your page to collect the card information in a PCI-DSS SAQ A compliant way.

class AuthorizeNetController extends AuthorizeNetBaseController
{

    /**
     * Main function to make payment (for classic and responsive server)
     *
     * @param Request $request
     * @return array
     */
    public function makePayment(Request $request)
    {
        // external request
        $external = $request->input('otToken') ? true : false;

        /** @var Transaction $transaction */
        $transaction = new Transaction();

        // 1. before process
        $result = $transaction->beforePaymentProcess($request);
        // $before = $transaction->toArray();

        if (!$result) {
            $message = 'Your transaction could not be processed';
            if ($external) {
                return ['status' => 400, 'message' => $message];
            } else {
                return redirect()->route('contribute')->with('danger', $message);
            }
        }

        // 2. process request
        /** @var AnetAPI\ANetApiResponseType $response */
        $response = $this->processPayment($request, $transaction);

        // 3. process response (common for payment and refund)
        $result = $this->processPaymentResponse($response);

        // 4. after process (common for payment and refund)
        $transaction->afterPaymentProcess($result);
        // $after = $transaction->toArray();

        // email and response
        $code = $result['status']['code'];
        $message = $result['status']['message'];

        // email to donor
        if (!ClientInfo::isGNA()) {
            if ($code == Transaction::TRS_SUCCESS) Email::transaction($transaction);
        } else {
            Email::transaction($transaction);
        }

        if ($external) {
            $status = ($code == Transaction::TRS_SUCCESS) ? 200 : 400;
            return ['status' => $status, 'message' => $message, 'transaction' => $transaction->toArray()];
        } else {
            if (ClientConfig::feature('RECENT_CONTRIBUTIONS')) {
                $type = ($code == Transaction::TRS_SUCCESS) ? 'success' : 'danger';
                return redirect()->route('transactions')->with($type, $message);
            } else {
                return redirect()->route('transaction-response', ['rid' => $transaction->ref_id]);
            }
        }

        // return ['before' => $before, 'after' => $after];

        //$dataDescriptor = $request->input('dataDescriptor');
        // $dataValue = $request->input('dataValue');
        // return [$amount, $dataDescriptor, $dataValue];
        // return [$request->all()];

        /** @var AnetAPI\ANetApiResponseType $response */
        // $response = $this->processPayment($request);
        // $result = $this->processResponse($response);
        // return [$result];
        // return redirect()->back()->with('success', "Your Gift request has been accepted.");
    }

    public function makeDAFContributionPayment(Request $request)
    {
        /** @var Transaction $transaction */
        $transaction = new Transaction();

        // 1. before process
        $result = $transaction->beforeDAFContributionCCPayment($request);
        // $before = $transaction->toArray();

        if (!$result) {
            $message = 'Your transaction could not be processed';
            return redirect()->route('contribute')->with('danger', $message);
        }

        // 2. process request
        /** @var AnetAPI\ANetApiResponseType $response */
        $response = $this->processPayment($request, $transaction);

        // 3. process response (common for payment and refund)
        $result = $this->processPaymentResponse($response);

        // 4. after process (common for payment and refund)
        $transaction->afterPaymentProcess($result);
        // $after = $transaction->toArray();

        // email and response
        $code = $result['status']['code'];
        $message = $result['status']['message'];

        // email to donor
        if (!ClientInfo::isGNA()) {
            if ($code == Transaction::TRS_SUCCESS) Email::dafTransaction($transaction);
        } else {
            Email::dafTransaction($transaction);
        }

        return redirect()->route('daf-transaction-response',['rid' => $transaction->ref_id]);
    }

    public function donationCreate() {
        if (!ClientConfig::feature('PUBLIC_DONATIONS')) {
            return redirect()->route('root')->with('danger', GConst::M_PAGE_NOT_FOUND);
        }
        return view('donation.create');
    }

    public function donationView() {
        if (!ClientConfig::feature('PUBLIC_DONATIONS')) {
            return redirect()->route('root')->with('danger', GConst::M_PAGE_NOT_FOUND);
        }
        return view('donation.view');
    }

    /**
     * Main function to make donation payment (for classic and responsive server)
     *
     * AJAX
     * @param Request $request
     * @return array
     */
    public function donationPayment(Request $request)
    {
        if (!ClientConfig::feature('PUBLIC_DONATIONS')) {
            return redirect()->route('root')->with('danger', GConst::M_PAGE_NOT_FOUND);
        }
        // $html = view('donation.donation')->render();
        // return ['status' => 200, 'html' => $html];

        // return $request->all();

        /** @var Donation $donation */
        $donation = Donation::getInstance();

        // 1. before process
        $result = $donation->beforePaymentProcess($request);
        // $before = $transaction->toArray();

        if (!$result) {
            $message = 'Your transaction could not be processed';
            return ['status' => 400, 'message' => $message];
        }

        if ($donation->isOneTime()){
            // 2. process request
            /** @var AnetAPI\ANetApiResponseType $response */
            $response = $this->processDonationPayment($request, $donation);

            // 3. process response
            $result = $this->processPaymentResponse($response);

        } else {
            // 2. process request
            /** @var AnetAPI\ANetApiResponseType $response */
            $response = $this->processARBDonationPayment($request, $donation);
            // $response = $this->processARBDonationPaymentTEST($request, $donation);

            // 3. process response
            $result = $this->processARBDonationResponse($response);
        }

        // 4. after process (common for payment and refund)
        $donation->afterPaymentProcess($result);
        // $after = $transaction->toArray();

        // email to donor
        Email::donation($donation);

        // external response
        $code = $result['status']['code'];
        $message = $result['status']['message'];
        if ($code == Transaction::TRS_SUCCESS) {
            $html = view('donation.donation', ['model' => $donation])->render();
            return ['status' => 200, 'html' => $html];
        } else {
            return ['status' => 400, 'message' => $message];
        }

    }

    public function makeRefund(Request $request)
    {
        // external request
        $external = $request->input('otToken') ? true : false;

        // get the transaction for refund
        $transaction = new Transaction();

        //tr_row_id - transaction table row id
        $rowId = $request->input('tr_row_id');
        $paidTransaction = Transaction::find($rowId);

        // validate input
        if (!$paidTransaction) {
            $message = "The transaction doesn't exit";
            if ($external) {
                return ['status' => 400, 'message' => $message];
            } else {
                return redirect()->route('transactions')->with('danger', $message);
            }
        }

        // 1. before process
        $result = $transaction->beforeRefundProcess($paidTransaction);

        if (!$result) {
            $message = 'Refund could not be processed';
            if ($external) {
                return ['status' => 400, 'message' => $message];
            } else {
                return redirect()->route('transactions')->with('danger', $message);
            }
        }

        // 2. process request
        /** @var AnetAPI\ANetApiResponseType $response */
        $response = $this->processRefund($transaction, $paidTransaction->ref_id);

        // 3. process response (common for payment and refund)
        $result = $this->processPaymentResponse($response);

        // 4. after process (common for payment and refund)
        $transaction->afterPaymentProcess($result);
        // $after = $transaction->toArray();

        // email to donor
        // Email::transaction($transaction);

        // external response
        $code = $result['status']['code'];
        $message = $result['status']['message'];
        if ($external) {
            $status = ($code == Transaction::TRS_SUCCESS) ? 200 : 400;
            return ['status' => $status, 'message' => $message];
        } else {
            $type = ($code == Transaction::TRS_SUCCESS) ? 'success' : 'danger';
            return redirect()->route('transactions')->with($type, $message);
        }

    }
}
