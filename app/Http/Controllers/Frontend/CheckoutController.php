<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use App\Models\User;
use App\Models\Cart;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\Payments\StripePaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            return redirect()->route('frontend.signin');
        }

        $cartData = session('cartData', []);

        if(empty($cartData)){
            return redirect()->route('frontend.cart');
        }

        $carts = Cart::where('sessionId', $cartData['sessionId'])->count();
        //If cart is empty, redirect to cart page
        if($carts == 0)
        {
            return redirect()->route('frontend.cart');
        }

        $user = User::where('id', Auth::user()->id)->first();

        $taxes = Tax::where('status', 1)->get();
          $countries = DB::table('countries')->get();

        return view('frontend.checkout', ['currency_symbol' => $cartData['currency_symbol'] ,'total' => $cartData['total'], 'sessionId' => $cartData['sessionId'], 'user' => $user, 'taxes' => $taxes,'countries'=>$countries, 'discount' => $cartData['discount']]);
    }

    public function submit(Request $request)
    {
        // dd($request->all());
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
            // dd('kk');
            $payment = $this->orderRepository->checkout($input);

            // ----------Stripe Payment----------
            // if($payment->status == 'succeeded'){
            //     return redirect()->route('frontend.checkout.success');
            // } else {
            //     return redirect()->route('frontend.checkout.failed');
            // }

            // ----------Authorize Net Payment----------
            if($payment['status'] == "success"){
                // dd('ss');
                return redirect()->route('frontend.checkout.success');
            } else if($payment['status'] == "failed") {
                // dd('ff');
                return redirect()->route('frontend.checkout.failed');
            }
            else if($payment['status'] == "hold") {
                // dd('hh');
                return redirect()->route('frontend.checkout.held');
            }
            else
            {
                // dd('ee');
                // Toastr::error('Some of the products may be no more available added to the cart,please remove them and proceed!', 'Error');

                return redirect()->route('frontend.cart')->with('error','Unknown payment error occured, try again later.');
            }
       } catch(\Exception $e){
            // return $e->getMessage();
           return redirect()->back()->with('error', $e->getMessage());
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
