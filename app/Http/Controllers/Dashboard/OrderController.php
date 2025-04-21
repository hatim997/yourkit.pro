<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LogoDisplay;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\InvoiceService;
use App\Utils\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminDisplayLogoUpload;
use App\Traits\FileUploadTraits;

class OrderController extends Controller
{
    use FileUploadTraits;
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService){
        $this->invoiceService = $invoiceService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view order');
            $orders = Order::with('user','details')->latest()->get();
            return view('dashboard.order.index', compact('orders'));
        } catch (\Throwable $th) {
            Log::error('Order Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $this->authorize('view order');
            // $order = Order::with('user','details')->findOrFail($id);
            $orderss = OrderDetail::where('order_id', $id)->get();
            $groupedOrders = $orderss->where('bundle_id', '!=', null)->groupBy('cart_id')->map(function ($cartGroup) {
                return $cartGroup->groupBy('bundle_id');
            });
            $ecomOrders=OrderDetail::where('order_id', $id)->where('product_id', '!=', null)->get();
            $logos=LogoDisplay::where('order_id', $id)->get();
            $odr=Order::find($id);

            $status = Helper::OrderStatus();
            return view('dashboard.order.show', compact('orderss','odr','groupedOrders','logos', 'status','ecomOrders'));
        } catch (\Throwable $th) {
            Log::error('Order Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update order');
        $validator = Validator::make($request->all(), [
            'image' => 'required|array',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max_size',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $order = Order::findOrFail($id);
            if ($request->has('image')) {
                foreach ($request->image as $image) {
                    $file = $this->uploadFile($image, 'logo-display');
                    LogoDisplay::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::user()->id,
                        'image' => $file['file_path']
                    ]);
                }
            }

            if($order){
                try {
                    Mail::to($order->user->email)->send(new AdminDisplayLogoUpload($order));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            DB::commit();
            return redirect()->route('dashboard.orders.show', $order->id)->with('success', 'Images uploaded successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Images Upload Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getInvoice($id)
    {
        try {
            $this->authorize('view order');
            $order = Order::with('user', 'details')->findOrFail($id);
            $invoice =  $this->invoiceService::getInvoicePath($order);
            return redirect($invoice['file_path']);
            // return view('dashboard.order.invoice', compact('order'));
        } catch (\Throwable $th) {
            Log::error('Order Invoice Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateOrderStatus(Request $request)
    {
        try {
            $this->authorize('update order');
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'order_status' => 'required',
            ]);

            $status = Helper::OrderStatus();

            $order = Order::find($request->order_id);
            $order->order_status = $request->order_status;
            $order->save();

            return response()->json([
                'success' => true,
                'status' => $status[$order->order_status],
                'message' => "Order Status Updated Successfully"
            ]);
        } catch (\Throwable $th) {
            Log::error('Order Status Update Failed', ['error' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => "Something went wrong! Please try again later"
            ]);
            throw $th;
        }

    }
}
