<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\User;
use App\Models\Cart;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\Payments\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class CheckoutController extends Controller
{

    protected $userRepository;
    protected $orderRepository;

    public function __construct(UserRepository $userRepository, OrderRepository $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('signin');
        }

        $cartData = session('cartData', []);

        if(empty($cartData)){
            return redirect()->route('cart');
        }

        $carts = Cart::where('sessionId', $cartData['sessionId'])->count();
        //If cart is empty, redirect to cart page 
        if($carts == 0)
        {
            return redirect()->route('cart');
        }

        $user = User::where('id', Auth::user()->id)->first();

        $taxes = Tax::where('status', 1)->get();
          $countries = \DB::table('countries')->get();

        return view('frontend.checkout', ['currency_symbol' => $cartData['currency_symbol'] ,'total' => $cartData['total'], 'sessionId' => $cartData['sessionId'], 'user' => $user, 'taxes' => $taxes,'countries'=>$countries]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            // Billing Details
            "billing_name" => "required|string|max:255",
            "company_name" => "nullable|string|max:255",
            "billing_email" => "required|email|max:255",
            "billing_mobile" => "required|numeric|digits_between:10,15",
            "country" => "required|string",
            "address" => "required|string|max:255",
            "town" => "required|string|max:255",
            "pincode" => "required|string|max:10",
        "shipping"=>"required",
            // Payment Details
            "card_number" => "required|numeric|digits:16",
            "expiry" => "required|numeric|digits:4",
            "cvv" => "required|numeric|digits:3",
        ]);
        
        
        if ($request->shipping == 2) { 
            $request->validate([
                "shipping_name" => "required|string|max:255",
                "shipping_company" => "nullable|string|max:255",
                "shipping_email" => "required|email|max:255",
                "shipping_mobile" => "required|numeric|digits_between:10,15",
                "shipping_country" => "required|string",
                "shipping_address" => "required|string|max:255",
                "shipping_town" => "required|string|max:255",
                "shipping_pincode" => "required|string|max:10",
            ]);
        }
        $input = $request->all();

        try{
            $payment = $this->orderRepository->checkout($input);

            // ----------Stripe Payment----------
            // if($payment->status == 'succeeded'){
            //     return redirect()->route('checkout.success');
            // } else {
            //     return redirect()->route('checkout.failed');
            // }

            // ----------Authorize Net Payment----------
            if($payment['status'] == "success"){
                return redirect()->route('checkout.success');
            } else if($payment['status'] == "failed") {
                return redirect()->route('checkout.failed');
            }
            else if($payment['status'] == "hold") {
                return redirect()->route('checkout.held');
            }
            else
            {
                // Toastr::error('Some of the products may be no more available added to the cart,please remove them and proceed!', 'Error');
                Toastr::error('Unknown payment error occured, try again later.', 'Error');
                return redirect()->route('cart');
            }
       } catch(\Exception $e){
            // return $e->getMessage();
            Toastr::error($e->getMessage(), 'Error');
           return redirect()->back();
       }

    }

    public function success(){
        return view('frontend.payment.success');
    }

    public function failed(){
        return view('frontend.payment.failed');
    }

    public function held(){
        return view('frontend.payment.hold');
    }
}
