<?php

namespace App\Services;

use App\Helpers\Helper as HelpersHelper;
use App\Models\Order;
use App\Utils\Helper;
use PDF;
// use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\File;
class InvoiceService
{

    public static function getInvoicePath($order)
    {
        try {

            $fileName = "invoice_{$order->orderID}.pdf";
            $filePath = public_path("invoices/{$fileName}");

            if (File::exists($filePath)) {
                return [
                    'message' => 'Invoice already exists.',
                    'file_path' => url("invoices/{$fileName}"),
                ];
            }

            return self::generateInvoicePdf($order);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function generateInvoicePdf($order)
    {

        try {
            $order->details = $order->details->groupBy('cart_id');
            $currency_symbol = HelpersHelper::getCurrencySymbol() ?? '$';

            $data = [
                'order' => $order,
                'currency_symbol' => $currency_symbol
            ];

            $fileName = "invoice_{$order->orderID}.pdf";
            $filePath = public_path("invoices/{$fileName}");

            if (!File::exists(public_path('invoices'))) {
                File::makeDirectory(public_path('invoices'), 0755, true);
            }

            $pdf = PDF::loadView('pdf.invoice', $data);
            $pdf->save($filePath);

            return [
                'message' => 'Invoice generated successfully.',
                'file_path' => url("invoices/{$fileName}"),
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
