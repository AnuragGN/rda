<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Contact;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeController extends Controller
{
    /**
     * @param Transaction $transaction
     * @return static
     */
    public function getPaymentIntent(Transaction $transaction)
    {
        // Stripe Secret
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Convert amount into cents
        $amount = ($transaction->amount)*100;

        $intent = PaymentIntent::create(
            [
                'amount' => $amount,
                'currency' => 'usd',
                // 'payment_method_types' => ['card'],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]
        );
        return $intent;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createPaymentIntent (Request $request)
    {
        $contact = Contact::sessionContact();
        if (!$contact) abort(403);

        /** @var Transaction $transaction */
        $transaction = new Transaction();

        $result = $transaction->beforeStripePayment($request);
        if (!$result) {
            $message = 'Your transaction could not be initiated';
            return redirect()->route('contribute')->with('danger', $message);
        }

        // create paymentIntent -initiating
        $intent = $this->getPaymentIntent($transaction);
        if (!$intent->id) {
            $message = 'Your transaction could not be initiated';
            return redirect()->route('contribute')->with('danger', $message);
        }

        // save transaction_id - payment intent
        $result->transaction_id = $intent->id;
        $result->response = json_encode($intent);
        $result->save();

        // client secret
        $clientSecret = $intent->client_secret;

        return view('stripe.make-payment',compact('clientSecret'));
    }

    /**
     * request params
     * payment_intent=pi_3LQAOGHow1G3GPNX07oSbNjR&
     * payment_intent_client_secret=pi_3LQAOGHow1G3GPNX07oSbNjR_secret_r439NJJVtbHM3xtyq48kaqw9E
     * &redirect_status=succeeded
     *
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function afterPayment (Request $request)
    {
        $paymentIntent = $request->payment_intent;

        /** @var Transaction $transaction */
        $transaction = Transaction::where('transaction_id', $paymentIntent)->first();
        if (!$transaction) {
            $message = 'We encountered an error while processing your request. Please check your bank account, or contact site admin to know the transaction status.';
            return redirect()->route('contribute')->with('danger', $message);
        }

        // payment details
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $result =  $stripe->paymentIntents->retrieve(
            $paymentIntent,
            []
        );

        // update transaction status
        $transaction->afterStripePayment($result);

        // send email
        Email::transaction($transaction);
        return view('stripe.payment-status', compact('transaction'));
    }

    /**
     * callback from Stripe Server
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function onPaymentError (Request $request)
    {
        if ($request->error) {
            $error = $request->error;
            if (isset($error['payment_intent']) && isset($error['payment_intent']['id'])) {

                $transactionId = $error['payment_intent']['id'];
                $transaction = Transaction::where('transaction_id', $transactionId)->first();
                if ($transaction) {
                    $transaction->message = isset($error['message']) ? $error['message'] : "No message";
                    $transaction->status = Transaction::TDB_STATUS_FAILED;;
                    $transaction->response = json_encode($request->error);
                    $transaction->save();
                }
                return $error['message'];
            }
        } else {
            return true;
        }
    }

}