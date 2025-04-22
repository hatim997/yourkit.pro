<?php

namespace App\Repositories;

use App\Events\SendInvoiceEmailEvent;
use App\Events\SendInvoiceEmailAdminEvent;
use App\Mail\AdminDisplayLogoUpload;
use App\Mail\SendInvoiceEmail;
use App\Models\Bundle;
use App\Models\Cart;
use App\Models\CartContent;
use App\Models\LogoDisplay;
use App\Models\Order;
use App\Models\OrderTax;
use App\Models\PositionImage;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\Tax;
use App\Services\InvoiceService;
use App\Services\Payments\StripePaymentService;
use App\Services\Payments\AuthorizeNetPaymentService;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Utils\Helper;
use Brian2694\Toastr\Facades\Toastr;

class OrderRepository extends BaseRepository
{

    use FileUploadTraits;

    protected $stripePaymentService;
    protected $authorizeNetPaymentService;
    protected $invoiceService;

    public function __construct(Order $model, StripePaymentService $stripePaymentService, AuthorizeNetPaymentService $authorizeNetPaymentService, InvoiceService $invoiceService)
    {
        parent::__construct($model);
        $this->stripePaymentService = $stripePaymentService;
        $this->authorizeNetPaymentService = $authorizeNetPaymentService;
        $this->invoiceService = $invoiceService;
    }

    public function checkout(array $data)
    {

        // dd($data);

        $total = 0;
        $finalAmount = 0;
        $totalTaxAmount = 0;
        $totalDiscount = 0;
        $image='';
        $data['user_id'] = Auth::user()->id;

        $totalDiscount = $data['discount'];
        unset($data['amount']);

        $order = Order::create($data);

        $carts = Cart::where('sessionId', $data['sessionId'])->get();

        if ($carts->isNotEmpty()) {
            foreach ($carts as $cart) {

                $image = $cart->logo ?? '';

                $total = $total + $cart->total_cost;
                if ($cart->table == 'bundles') {


                    foreach ($cart->contents as $content) {
                        $bundle=Bundle::find($cart->bundle->id);
                        foreach($bundle->products as $prod){
                            if($prod->status !== 1){

                                return $prod;
                            }
                        }
                        $order->details()->create([
                            'bundle_id' => $cart->bundle->id,
                            'cart_id' => $cart->cartId,
                            'amount' => $cart->bundle->price,
                            'final_amount' => $cart->bundle->price,
                            'attributes' => $content->contents,
                            'positions' => $content->positions,
                            'is_email_checked' => $cart->is_email_checked,
                            'is_phone_checked' => $cart->is_phone_checked,

                        ]);
                    }
                } else{
                    foreach ($cart->contents as $content) {
                        //dd($cart);
                        $produ=Product::find($cart->table_id);
                        if($produ->status !== 1){

                            return $produ;
                        }
                        $order->details()->create([
                            'product_id' => $cart->table_id,
                            'cart_id' => $cart->cartId,
                            'amount' => $cart->total_cost,
                            'final_amount' => $cart->total_cost,
                            'attributes' => $content->contents,
                            // 'positions' => $content->positions,
                            //'is_email_checked' => $cart->is_email_checked,
                            //'is_phone_checked' => $cart->is_phone_checked,
                            //'comments' => $cart->comment
                        ]);
                    }
                }

                // $cart->contents()->delete();
                // $cart->delete();
            }
        }

        // dd($total);
        if(isset($data['promo_code_id'])){
            $promoCode = PromoCode::findOrFail($data['promo_code_id']);
            $finalAmount = $total - $totalDiscount;
            $promoCode->incrementUsage();
        }

        /** Tax Calculation **/
        if(isset($data['country']) && $data['country'] === 'Canada'){
            $taxes = Tax::where('status', 1)->get();

            foreach ($taxes as $tax) {

                $taxAmount = isset($tax->percentage) ? ($tax->percentage * $total) / 100 : 0;
                $totalTaxAmount = $totalTaxAmount + $taxAmount;
                // dd($taxAmount);

                OrderTax::create([
                    'order_id' => $order->id,
                    'tax_type' => $tax->tax_type,
                    'tax_percentage' => $tax->percentage,
                    'taxable_amount' => $taxAmount,
                ]);
            }

            $finalAmount = $total + $totalTaxAmount;
        }

        // dd(round($finalAmount, 2));

        $order->orderID = generateOrderID();
        $order->logo = $image;
        $order->comment=$cart->comment;
        $order->amount = $total;
        $order->final_amount = $finalAmount;
        $order->taxable_amount = $totalTaxAmount;
        $order->discount_amount = $totalDiscount;
        $order->save();

        // ----------Stripe Payment----------
        // $paymentData = [
        //     'amount' => round($finalAmount, 2),
        //     'currency' => 'inr',
        //     'source' => $data['stripeToken'],
        //     'description' => 'Order Payment'
        // ];

        // $charge = $this->stripePaymentService->createPayment($paymentData);

        // if ($charge->status == 'succeeded') {
        //     $order->payment_status = 2;
        // }

        // $order->transaction_id = $charge->id;
        // $order->save();

        // ----------Authorize Net Payment----------
        $paymentData = [
            'amount' => round($finalAmount, 2),
            'card_number' => $data["card_number"],
            'expiration_date' => $data["expiry"],
            'cvv' => $data["cvv"]
        ];

        $charge = $this->authorizeNetPaymentService->createPayment($paymentData);

        if ($charge['status'] == "success") {
            $order->payment_status = 2;
        }
        else if($charge['status'] == "failed")
        {
            $order->payment_status = 3;
        }
        else if($charge['status'] == "hold")
        {
            $order->payment_status = 4;
        }

        $order->transaction_id = $charge['transaction_id'];
        $order->save();

        // Cleariing Cart
        CartContent::whereHas("cart", function($q) use($data){
            $q->where('sessionId', $data['sessionId']);
        })->delete();
        Cart::where('sessionId', $data['sessionId'])->delete();

        if (session()->has('cartData')) {
            session()->forget('cartData');
        }

        // getting invoice

        $invoice = $this->invoiceService::getInvoicePath($order);


        // calling event for sending mail to customer
        event(new SendInvoiceEmailEvent($order->user->email, 'Order confirmation from '.Helper::setting("title"),  $invoice['file_path'], $order->user, $order));

        // calling event for sending mail to operational email ID
        event(new SendInvoiceEmailAdminEvent(Helper::getOperationalContacts()["email"], 'New order placed by '.$order->user->name,  $invoice['file_path'], $order->user, $order));

        return $charge;
    }

    public function getOrderDetails(string $user_id)
    {
        $orders = Order::where('user_id', $user_id)->with('details')->orderByDesc('created_at')->paginate(10);

        //return $carts;

        if (!empty($orders)) {
            foreach ($orders as $order) {
                foreach ($order->details as $content) {
                    $positions = json_decode($content->positions, true);
                    $position_image = [];
                    if (is_array($positions)) {
                        foreach ($positions as $position) {
                            $image = PositionImage::where('id', $position)->pluck('image')->first();
                            $position_image[] = !is_null($image) ? url('assets/frontend/' . $image) : '';
                        }
                    }
                    $content->position_images = $position_image;
                }
            }
        }


        return $orders;
    }

    public function uploadlogoDisplay(Request $request, string $id)
    {

        $order = $this->model->findOrFail($id);

        if ($request->has('image')) {
            foreach ($request->image as $image) {
                $file = $this->uploadFile($image, 'logo-display');
                LogoDisplay::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::guard('admin')->user()->id,
                    'image' => $file['file_path']
                ]);
            }
        }

        return $order;
    }

    function generateOrderID()
{

    $lastOrder = Order::latest('id')->first();


    $nextNumber = $lastOrder ? ((int) str_replace('OD-', '', $lastOrder->orderID)) + 1 : 1;


    return 'OD-' . str_pad($nextNumber, 9, '0', STR_PAD_LEFT);
}
}
