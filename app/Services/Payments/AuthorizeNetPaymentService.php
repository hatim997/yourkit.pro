<?php

namespace App\Services\Payments;

use App\Utils\Helper;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Illuminate\Support\Facades\Log;

class AuthorizeNetPaymentService implements PaymentGatewayInterface
{
    protected $merchantAuthentication;

    private const PAYMENT_MODE_SANDBOX = "sandbox";
    private const PAYMENT_MODE_PRODUCTION = "production";

    public function __construct()
    {
        // Set up API credentials
        $this->merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        if(Helper::setting("payment_mode") == AuthorizeNetPaymentService::PAYMENT_MODE_PRODUCTION)
        {
            $this->merchantAuthentication->setName(env('AUTHORIZE_NET_PRODUCTION_API_LOGIN_ID'));
            $this->merchantAuthentication->setTransactionKey(env('AUTHORIZE_NET_PRODUCTION_TRANSACTION_KEY'));
        }
        else
        {
            $this->merchantAuthentication->setName(env('AUTHORIZE_NET_SANDBOX_API_LOGIN_ID'));
            $this->merchantAuthentication->setTransactionKey(env('AUTHORIZE_NET_SANDBOX_TRANSACTION_KEY'));
        }
        
    }

    public function createPayment($paymentData)
    {
        // Create the payment object
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($paymentData['card_number']);
        $creditCard->setExpirationDate($paymentData['expiration_date']);
        $creditCard->setCardCode($paymentData['cvv']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        // Create the transaction request
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($paymentData['amount']);
        $transactionRequestType->setPayment($payment);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setTransactionRequest($transactionRequestType);

        // Execute the transaction
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(
            // env('AUTHORIZE_NET_ENVIRONMENT') == 'sandbox' ? \net\authorize\api\constants\ANetEnvironment::SANDBOX : \net\authorize\api\constants\ANetEnvironment::PRODUCTION
            Helper::setting("payment_mode") == AuthorizeNetPaymentService::PAYMENT_MODE_SANDBOX ? \net\authorize\api\constants\ANetEnvironment::SANDBOX : \net\authorize\api\constants\ANetEnvironment::PRODUCTION
        );
        
        // Handle the response
        if ($response != null) {
            // dd($response);
            $tresponse = $response->getTransactionResponse(); // If $response is not null then getTransactionResponse() will neverf null
            if(!empty($tresponse) && $tresponse->getResponseCode() != null) 
            {
                // responseCode - type -->String.

                // One of the following:

                // 1 -- Approved
                // 2 -- Declined
                // 3 -- Error
                // 4 -- Held for Review
                if ($tresponse->getResponseCode() == "1") {
                    return [
                        'status' => "success",
                        'message' => 'Payment successful!',
                        'transaction_id' => $tresponse->getTransId(),
                    ];
                } else if($tresponse->getResponseCode() == "2"){
                    return [
                        'status' => "failed",
                        // 'message' => 'Payment failed: ' . $tresponse->getMessages()[0]->getDescription(),
                        'message' => 'Payment failed: ' . $tresponse->getMessages(),
                        'transaction_id' => $tresponse->getTransId(),
                    ];
                }
                else if($tresponse->getResponseCode() == "3"){
                    throw new \Exception("Payment error : ".$tresponse->getErrors()[0]->getErrorText());
                }
                else if($tresponse->getResponseCode() == "4"){
                    
                    return [
                        'status' => "hold",
                        'message' => 'Payment hold: Held for Review ',
                        'transaction_id' => $tresponse->getTransId(),
                    ];
                }
                else{
                    throw new \Exception("Payment error : unknown transaction response code found. Transaction Response Code : ".$tresponse->getResponseCode());
                }
            }
            else
            {
                throw new \Exception("Payment gateway exception : ".$response->getMessages()->getMessage()[0]->getText());
            }
        } else {
            throw new \Exception("Payment gateway exception : No response returned from the payment gateway.");
        }
    }
}
