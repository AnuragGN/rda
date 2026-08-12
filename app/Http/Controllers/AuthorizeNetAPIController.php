<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 09-05-2020
 * Time: 21:57
 */

namespace App\Http\Controllers;


use App\Models\LogActivity;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

// API for classic

class AuthorizeNetAPIController extends AuthorizeNetController
{

    /**
     * Payment API for classic server contribution
     *
     * @param Request $request
     * @return array
     */
    public function classicPayment(Request $request)
    {
        $request->merge(['test' => 0]);

        $activity = new LogActivity(LogActivity::NAME_TRANSACTION_API, LogActivity::ACTION_PAYMENT);
        $activity->data($request->toArray())->add();

        // authenticate user
        $result = $this->authByTokenOnce($request);
        if ($result === AuthorizeNetAPIController::CONTACT_AUTHORIZED) {
            return $this->makePayment($request);
        } else {
            return ['status' => 403, 'message' => 'Authentication failed. Please refresh the page and retry.'];
        }
    }

    /**
     * Payment API for classic server donation
     *
     * @param Request $request
     * @return array
     */
    public function classicDonationPayment(Request $request)
    {
        $request->merge(['test' => 0]);

        $activity = new LogActivity(LogActivity::NAME_DONATION_API, LogActivity::ACTION_PAYMENT);
        $activity->data($request->toArray())->add();

        // authenticate user
        $result = $this->authByTokenOnce($request);
        if ($result === AuthorizeNetAPIController::GUEST_AUTHORIZED || $result === AuthorizeNetAPIController::CONTACT_AUTHORIZED) {
            return $this->donationPayment($request);
        } else {
            return ['status' => 403, 'message' => 'Authentication failed. Please refresh the page and retry.'];
        }
    }

    /**
     * For API testing only
     *
     * @param Request $request
     * @return array
     */
    public function classicPaymentTest(Request $request)
    {
        $activity = new LogActivity(LogActivity::NAME_TRANSACTION_API_TEST, LogActivity::ACTION_PAYMENT);
        $activity->data($request->toArray())->add();

        $params = [];
        if ($request->input('test') == 1) {
            $amount = $request->input('payAmount');
            if ($amount && $amount > 100) {
                $params = ['status' => '400', 'message' => 'Bad Request'];
            } else {
                $params = ['status' => '200', 'message' => 'Success'];
            }
            return array_merge($params, $request->all());
        }

        if ($request->input('test') == 2) {

            // reset test
            $request->merge(['test' => 0]);

            // authenticate user
            $result = $this->authByTokenOnce($request);
            if ($result === AuthorizeNetAPIController::CONTACT_AUTHORIZED) {
                $params = ['status' => 200, 'message' => 'Success', 'user' => $result];
            } else {
                $params = ['status' => 403, 'message' => 'Authentication failed.'];
            }
            return array_merge($params, $request->all());
        }

        if ($request->input('test') == 3) {
            // authenticate user
            $result = $this->authByTokenOnce($request);
            if ($result === AuthorizeNetAPIController::CONTACT_AUTHORIZED) {
                return $this->makePayment($request);
            } else {
                return ['status' => 403, 'message' => 'Authentication failed.'];
            }
        }

        $params = ['status' => 400, 'message' => 'Bad input value of test.'];
        return array_merge($params, $request->all());
    }

    /**
     * For API testing only
     *
     * @param Request $request
     * @return array
     */
    public function classicDonationPaymentTest(Request $request)
    {
        $activity = new LogActivity(LogActivity::NAME_DONATION_API_TEST, LogActivity::ACTION_PAYMENT);
        $activity->data($request->toArray())->add();

        $params = [];
        // 1- return params
        if ($request->input('test') == 1) {
            $amount = $request->input('amount');
            if ($amount && $amount > 100) {
                $params = ['status' => '400', 'message' => 'Bad Request'];
            } else {
                $params = ['status' => '200', 'message' => 'Success'];
            }
            return array_merge($params, $request->all());
        }

        // 2- authenticate request
        if ($request->input('test') == 2) {

            // reset test
            $request->merge(['test' => 0]);

            // authenticate user
            $result = $this->authByTokenOnce($request);
            if ($result === AuthorizeNetAPIController::CONTACT_AUTHORIZED || $result === AuthorizeNetAPIController::GUEST_AUTHORIZED) {
                $params = ['status' => 200, 'message' => 'Success', 'user' => $result];
            } else {
                $params = ['status' => 403, 'message' => 'Authentication failed.'];
            }
            return array_merge($params, $request->all());
        }

        // 3- authenticate request
        if ($request->input('test') == 3) {
            // authenticate user
            $result = $this->authByTokenOnce($request);
            if ($result === AuthorizeNetAPIController::CONTACT_AUTHORIZED || $result === AuthorizeNetAPIController::GUEST_AUTHORIZED) {
                return $this->donationPayment($request);
            } else {
                return ['status' => 403, 'message' => 'Authentication failed.'];
            }
        }

        $params = ['status' => 400, 'message' => 'Bad input value of test.'];
        return array_merge($params, $request->all());
    }

}
