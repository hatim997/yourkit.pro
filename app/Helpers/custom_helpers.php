<?php

use Illuminate\Support\Str;
use App\Models\Order;

if (!function_exists('generateUUID')) {
    function generateUUID()
    {
        $uuid = Str::uuid()->toString();
        return rtrim(strtr(base64_encode(hex2bin(str_replace('-', '', $uuid))), '+/', '-_'), '=');
    }

   
}
function generateOrderID()
{
    
    $lastOrder = Order::orderByDesc('orderID')->first();
    $lastNumber = $lastOrder ? intval(str_replace('OD-', '', $lastOrder->orderID)) : 0;
    $lastNumber++; 
    $newOrderID = "OD-" . str_pad($lastNumber, 9, "0", STR_PAD_LEFT);
    return $newOrderID;
}