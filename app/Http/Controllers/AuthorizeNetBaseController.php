<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 09-05-2020
 * Time: 21:57
 */

namespace App\Http\Controllers;


use App\Models\ClientInfo;
use App\Models\Donation;
use App\Http\Traits\AuthOnce;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

//
//  define("AUTHORIZENET_LOG_FILE", "phplog");
// require 'vendor/autoload.php';
// require_once 'constants/SampleCodeConstants.php';

// https://developer.authorize.net/api/reference/features/acceptjs.html
// Embed our hosted, mobile-optimized payment information form in your page to collect the card information in a PCI-DSS SAQ A compliant way.

abstract class AuthorizeNetBaseController extends Controller
{
    const CONTACT_AUTHORIZED = 'contact-authorized';
    const GUEST_AUTHORIZED = 'guest-authorized';

    use AuthOnce;

    /**
     * MERCHANT_LOGIN_ID
     * @return mixed
     */
    protected function getMerchantLoginId() {
        return env('MERCHANT_LOGIN_ID', 'none');
    }

    /**
     * MERCHANT_TRANSACTION_KEY
     * @return mixed
     */
    protected function getMerchantTransactionKey() {
        return env('MERCHANT_TRANSACTION_KEY', 'none');
    }

    /**
     * Create a merchantAuthenticationType object with authentication details
     * retrieved from the constants file
     *
     * @return AnetAPI\MerchantAuthenticationType
     */
    private function getMerchantAuthentication()
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->getMerchantLoginId());
        $merchantAuthentication->setTransactionKey($this->getMerchantTransactionKey());
        return $merchantAuthentication;
    }

    /**
     * @param Request $request
     * @return AnetAPI\PaymentType
     */
    private function getPaymentType(Request $request)
    {
        $dataDescriptor = $request->input('dataDescriptor'); // COMMON.ACCEPT.INAPP.PAYMENT
        $dataValue = $request->input('dataValue'); // "119eyJjb2RlIjoiNTBfMl8wNjAwMDUyN0JEODE4RjQxOUEyRjhGQkIxMkY0MzdGQjAxQUIwRTY2NjhFNEFCN0VENzE4NTUwMjlGRUU0M0JFMENERUIwQzM2M0ExOUEwMDAzNzlGRDNFMjBCODJEMDFCQjkyNEJDIiwidG9rZW4iOiI5NDkwMjMyMTAyOTQwOTk5NDA0NjAzIiwidiI6IjEuMSJ9"

        // Create the payment object for a payment nonce
        $opaqueData = new AnetAPI\OpaqueDataType();
        $opaqueData->setDataDescriptor($dataDescriptor);
        $opaqueData->setDataValue($dataValue);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setOpaqueData($opaqueData);
        return $paymentOne;
    }

    /**
     * DO NOT USE - Fails with "Invalid OTS Token." for ARB
     * @param Donation $donation
     * @return AnetAPI\CustomerAddressType
     */
    private function getBillToForDonation(Donation $donation)
    {
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($donation->guest_fname);
        $billTo->setLastName($donation->guest_lname);
        $billTo->setAddress($donation->guest_address_one);
        $billTo->setCity($donation->guest_city);
        $billTo->setState($donation->guest_state);
        $billTo->setCountry($donation->guest_country);
        $billTo->setZip($donation->guest_zip);
        return $billTo;
    }

    protected function processARBDonationPaymentTEST(Request $request, Donation $donation)
    {
        // *** Prepare PaymentOne
        $dataDescriptor = $request->input('dataDescriptor');
        $dataValue = $request->input('dataValue');

        // Create the payment object for a payment nonce
        $opaqueData = new AnetAPI\OpaqueDataType();
        // $opaqueData->setDataDescriptor("COMMON.ACCEPT.INAPP.PAYMENT");
        $opaqueData->setDataDescriptor($dataDescriptor);
        $opaqueData->setDataValue($dataValue);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setOpaqueData($opaqueData);

        // *** Prepare MerchantAuthenticationType
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->getMerchantLoginId());
        $merchantAuthentication->setTransactionKey($this->getMerchantTransactionKey());

        // *** Prepare PaymentScheduleType
        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($donation->getIntervalLength());
        $interval->setUnit($donation->getIntervalUnit());

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate($donation->getStartDateTime());
        $paymentSchedule->setTotalOccurrences($donation->occurrences);
        $paymentSchedule->setTrialOccurrences("0");

        // *** Prepare OrderType
        $order = new AnetAPI\OrderType();
        // $order->setInvoiceNumber("1234354");
        // $order->setDescription("Description of the subscription");

        // *** Prepare NameAndAddressType
        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($donation->guest_fname);
        $billTo->setLastName($donation->guest_lname);
        $billTo->setAddress($donation->guest_address_one);
        $billTo->setCity($donation->guest_city);
        $billTo->setState($donation->guest_state);
        $billTo->setCountry($donation->guest_country);
        $billTo->setZip($donation->guest_zip);

        // *** Prepare Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName("Donation");
        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($donation->amount);
        $subscription->setTrialAmount("0.00");
        $subscription->setPayment($paymentOne);
        $subscription->setOrder($order);
        $subscription->setBillTo($billTo);

        // *** Prepare Subscription Request
        $subscriptionRequest = new AnetAPI\ARBCreateSubscriptionRequest();
        $subscriptionRequest->setmerchantAuthentication($merchantAuthentication);
        $subscriptionRequest->setRefId($donation->ref_id);
        $subscriptionRequest->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($subscriptionRequest);

        // wait introduced to avoid "Invalid OTS Token" error
        sleep(10);

        $response = $controller->executeWithApiResponse(
            App::environment('prod') ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
        );
        return $response;
    }

    /**
     * @param Request $request
     * @param Donation $donation
     * @return mixed
     */
    protected function processARBDonationPayment(Request $request, Donation $donation)
    {
        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($donation->getIntervalLength());
        $interval->setUnit($donation->getIntervalUnit());

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate($donation->getStartDateTime());
        $paymentSchedule->setTotalOccurrences($donation->occurrences);
        $paymentSchedule->setTrialOccurrences("0");

        $order = new AnetAPI\OrderType();
        // $order->setInvoiceNumber("1234354");
        // $order->setDescription("Description of the subscription");

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName("Donation");
        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($donation->amount);
        $subscription->setTrialAmount("0.00");
        $subscription->setPayment($this->getPaymentType($request));
        $subscription->setOrder($order);

		// NOTE: Do not call helper - Fails with "Invalid OTS Token." for AR
//        $billTo = new AnetAPI\NameAndAddressType();
//        $billTo->setFirstName($donation->guest_fname);
//        $billTo->setLastName($donation->guest_lname);
//        $billTo->setAddress($donation->guest_address_one);
//        $billTo->setCity($donation->guest_city);
//        $billTo->setState($donation->guest_state);
//        $billTo->setCountry($donation->guest_country);
//        $billTo->setZip($donation->guest_zip);
//        $subscription->setBillTo($billTo);

        $subscription->setBillTo($this->getBillToForDonation($donation));
        $subscriptionRequest = new AnetAPI\ARBCreateSubscriptionRequest();
        $subscriptionRequest->setmerchantAuthentication($this->getMerchantAuthentication());
        $subscriptionRequest->setRefId($donation->ref_id);
        $subscriptionRequest->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($subscriptionRequest);

        // wait introduced to avoid "Invalid OTS Token" error
        sleep(10);

        $response = $controller->executeWithApiResponse(
            App::environment('prod') ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
        );
        return $response;
    }

    /**
     * @param $response
     * @return array
     */
    protected function processARBDonationResponse($response)
    {
        /* if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
            echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
        } else {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
        } */

        $result = [];
        $result['refId'] = 0;
        $result['status'] = [
            'code' => Transaction::TRS_NO_RESPONSE,
            'message' => Transaction::TRM_NO_RESPONSE
        ];
        $result['subscriptionId'] = 0;
        $result['profile'] = [
            'customerProfileId' => 0,
            'customerPaymentProfileId' => 0,
            'customerAddressId' => 0
        ];

        if ($response == null) {
            return $result;
        }

        // step 1 - top level
        // prepare result array
        $resultMessage['resultCode'] = $response->getMessages()->getResultCode();
        $resultMessage['code'] = $response->getMessages()->getMessage()[0]->getCode();
        $resultMessage['text'] = $response->getMessages()->getMessage()[0]->getText();
        $result['message'] = $resultMessage;


        // set the final status
        if ($resultMessage['resultCode'] == "Ok") {
            $result['status']['code'] = Transaction::TRS_SUCCESS;
            $result['status']['message'] = Transaction::TRM_APPROVED;
            $result['subscriptionId'] = $response->getSubscriptionId();
            return $result;
        }

        // transaction failed
        $result['status']['code'] = Transaction::TRS_FAILED;
        $result['status']['message'] = $resultMessage['text'];

        return $result;
    }


    /**
     * @param Request $request
     * @param Donation $donation
     * @return AnetAPI\ANetApiResponseType
     */
    protected function processDonationPayment(Request $request, Donation $donation)
    {
        // Create order information
        $order = new AnetAPI\OrderType();
        // $order->setInvoiceNumber("10101");
        // $order->setDescription("DAF");

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        // $customerData->setType("individual");
        // $customerData->setId($transaction->contact_id);
        // $customerData->setEmail($transaction->contact_email);

        // Add values for transaction settings
        // Time in seconds to check for subsequent duplicate requests of this transaction. Use to help prevent accidental double-billing.
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("120");

        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        // $merchantDefinedField1 = new AnetAPI\UserFieldType();
        // $merchantDefinedField1->setName("customerLoyaltyNum");
        // $merchantDefinedField1->setValue("1128836273");

        // $merchantDefinedField2 = new AnetAPI\UserFieldType();
        // $merchantDefinedField2->setName("favoriteColor");
        // $merchantDefinedField2->setValue("blue");

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($donation->amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($this->getPaymentType($request));

        // NOTE: Type must be CustomerAddressType
//        $billTo = new AnetAPI\CustomerAddressType();
//        $billTo->setFirstName($donation->guest_fname);
//        $billTo->setLastName($donation->guest_lname);
//        $billTo->setAddress($donation->guest_address_one);
//        $billTo->setCity($donation->guest_city);
//        $billTo->setState($donation->guest_state);
//        $billTo->setCountry($donation->guest_country);
//        $billTo->setZip($donation->guest_zip);

        // $transactionRequestType->setBillTo($billTo);
        $transactionRequestType->setBillTo($this->getBillToForDonation($donation));
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        // $transactionRequestType->addToUserFields($merchantDefinedField1);
        // $transactionRequestType->addToUserFields($merchantDefinedField2);

        // Assemble the complete transaction request
        $transactionRequest = new AnetAPI\CreateTransactionRequest();
        $transactionRequest->setMerchantAuthentication($this->getMerchantAuthentication());
        $transactionRequest->setRefId($donation->ref_id);
        $transactionRequest->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($transactionRequest);
        /** @var AnetAPI\ANetApiResponseType $response */

        $response = $controller->executeWithApiResponse(
            App::environment('prod') ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
        );
        return $response;
    }

    // function createAnAcceptPaymentTransaction($amount)
    /**
     * @param Request $transactionRequest
     * @param Transaction $transaction
     * @return AnetAPI\ANetApiResponseType
     */
    protected function processPayment(Request $request, Transaction $transaction)
    {
        // Create order information
        $order = new AnetAPI\OrderType();
        // $order->setInvoiceNumber("10101");
        // $order->setDescription("DAF");

        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        // $customerAddress->setCompany("Souveniropolis");

        $customerAddress->setFirstName($transaction->bill_first_name);
        $customerAddress->setLastName($transaction->bill_last_name);

        if ($transaction->bill_address) $customerAddress->setAddress($transaction->bill_address);
        if ($transaction->bill_city) $customerAddress->setCity($transaction->bill_city);
        if ($transaction->bill_state) $customerAddress->setState($transaction->bill_state);
        if ($transaction->bill_country) $customerAddress->setCountry($transaction->bill_country);
        if ($transaction->bill_zip) $customerAddress->setZip($transaction->bill_zip);

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        // $customerData->setType("individual");
        $customerData->setId($transaction->contact_id);
        $customerData->setEmail($transaction->contact_email);

        // Add values for transaction settings
        // Time in seconds to check for subsequent duplicate requests of this transaction. Use to help prevent accidental double-billing.
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("120");

        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        // $merchantDefinedField1 = new AnetAPI\UserFieldType();
        // $merchantDefinedField1->setName("customerLoyaltyNum");
        // $merchantDefinedField1->setValue("1128836273");

        // $merchantDefinedField2 = new AnetAPI\UserFieldType();
        // $merchantDefinedField2->setName("favoriteColor");
        // $merchantDefinedField2->setValue("blue");

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($transaction->amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($this->getPaymentType($request));
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        // $transactionRequestType->addToUserFields($merchantDefinedField1);
        // $transactionRequestType->addToUserFields($merchantDefinedField2);

        // Assemble the complete transaction request
        $transactionRequest = new AnetAPI\CreateTransactionRequest();
        $transactionRequest->setMerchantAuthentication($this->getMerchantAuthentication());
        $transactionRequest->setRefId($transaction->ref_id);
        $transactionRequest->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($transactionRequest);
        /** @var AnetAPI\ANetApiResponseType $response */
        $response = $controller->executeWithApiResponse(
            App::environment('prod') ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
        );

        return $response;
    }

    /**
     * common for payment, donation and refund
     *
     * @param $response
     * @return mixed
     */
    protected function processPaymentResponse($response)
    {
        $statusMessage = null;

        $result = [];
        $result['message'] = [];
        $result['transactionResponse'] = [
            'response_code' => 0,
            'transaction_id' => 'NA',
            'auth_code' => 0,
            'account_number' => 'NA',
            'account_type' => ''
        ];
        $result['status'] = [
            'code' => Transaction::TRS_NO_RESPONSE,
            'message' => Transaction::TRM_NO_RESPONSE
        ];

        if ($response == null) {
            return $result;
        }

        // step 1 - top level
        // prepare result array
        $resultMessage['resultCode'] = $response->getMessages()->getResultCode();
        $resultMessage['code'] = $response->getMessages()->getMessage()[0]->getCode();
        $resultMessage['text'] = $response->getMessages()->getMessage()[0]->getText();
        $result['message'] = $resultMessage;

        // step 2 - transaction level
        // get transaction response
        $tresponse = $response->getTransactionResponse();
        if ($tresponse == null) {
            $result['status']['code'] = Transaction::TRS_FAILED;
            $result['status']['message'] = Transaction::TRM_NO_TRESPONSE;
            return $result;
        }

        // get transaction details
        $transactionResponse['response_code'] = $tresponse->getResponseCode();

        $responseTransactionId = $tresponse->getTransId();
        if (!$responseTransactionId) $responseTransactionId = 'NA';
        $transactionResponse['transaction_id'] = $responseTransactionId;
        $transactionResponse['auth_code'] = $tresponse->getAuthCode();

        $responseAccountNumber = $tresponse->getAccountNumber();
        if (!$responseAccountNumber) $responseAccountNumber = 'NA';
        $transactionResponse['account_number'] = $responseAccountNumber;
        $transactionResponse['account_type'] = $tresponse->getAccountType();
        $result['transactionResponse'] = $transactionResponse;

        // check if transaction is successful
        if ($tresponse->getMessages() != null) {
            $message['code'] = $tresponse->getMessages()[0]->getCode();
            $message['description'] = $tresponse->getMessages()[0]->getDescription();
            $result['transactionResponse']['message'] = $message;
            $statusMessage = $message['description'];
        }

        // check if transaction has error
        if ($tresponse->getErrors() != null) {
            $error['code'] = $tresponse->getErrors()[0]->getErrorCode();
            $error['text'] = $tresponse->getErrors()[0]->getErrorText();
            $result['transactionResponse']['error'] = $error;
            $statusMessage = $error['text'];
        }

        // set the final status
        if ($resultMessage['resultCode'] == "Ok") {
            if ($tresponse->getMessages() != null) {
                $result['status']['code'] = Transaction::TRS_SUCCESS;
                $result['status']['message'] = $statusMessage ? $statusMessage : Transaction::TRM_APPROVED;
                return $result;
            }
        }

        // transaction failed
        $result['status']['code'] = Transaction::TRS_FAILED;
        $result['status']['message'] = $statusMessage ? $statusMessage : Transaction::TRM_FAILED;

        return $result;
    }

    public function processRefund($transaction, $refTransId)
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->getMerchantLoginId());
        $merchantAuthentication->setTransactionKey($this->getMerchantTransactionKey());

        // Set the transaction's refId
        // $refId = 'gn' . time();
        $refId = ClientInfo::client() . '-'. time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($transaction->shortAccountNumber);
        $creditCard->setExpirationDate('XXXX');
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($transaction->amount);
        $transactionRequest->setPayment($paymentOne);
        $transactionRequest->setRefTransId($refTransId);

        $theRequest = new AnetAPI\CreateTransactionRequest();
        $theRequest->setMerchantAuthentication($merchantAuthentication);
        $theRequest->setRefId($refId);
        $theRequest->setTransactionRequest($transactionRequest);
        $controller = new AnetController\CreateTransactionController($theRequest);
        $response = $controller->executeWithApiResponse(
            App::environment('prod') ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
        );

        return $response;
    }

}
