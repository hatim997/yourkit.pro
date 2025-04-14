<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Webhook;

class StripePaymentService implements PaymentGatewayInterface
{

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPayment($paymentData)
    {
       //dd($paymentData);
        try {
            $charge = Charge::create([
                'amount' => ($paymentData['amount'] * 100),
                'currency' => $paymentData['currency'],
                'source' => $paymentData['source'],
                'description' => $paymentData['description'],
            ]);
            return $charge;
        } catch (\Exception $e) {
            throw new \Exception("Payment failed: " . $e->getMessage());
        }
    }

    public function webhook($data)
    {

        $transactionId = '';

        $payload = $data->getContent();
        $sig_header = $data->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $secret);

            if ($event->type === 'charge.succeeded') {
                $charge = $event->data->object;
                $transactionId = $charge->id;
            }

            return $transactionId;

        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'failed'], 400);
        }
    }
}
