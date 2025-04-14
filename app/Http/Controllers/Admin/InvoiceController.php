<?php

namespace App\Http\Controllers\Admin;

use App\Events\SendInvoiceEmailEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InvoiceService;
use App\Utils\Helper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService){
        $this->invoiceService = $invoiceService;
    }

    public function view(string $id){
        $order = Order::findOrFail($id);
        $invoice =  $this->invoiceService::getInvoicePath($order);
        // event(new SendInvoiceEmailEvent($order->user->email, 'Invoice',  $invoice['file_path']));
        return redirect($invoice['file_path']);
    }
}
