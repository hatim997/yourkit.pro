<?php

namespace App\Services\Payments;

interface PaymentGatewayInterface
{
    public function createPayment(array $paymentData);

}

?>
