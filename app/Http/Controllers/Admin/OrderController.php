<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrderDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UploadDisplayLogoRequest;
use App\Mail\AdminDisplayLogoUpload;
use App\Models\LogoDisplay;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\OrderRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Traits\FileUploadTraits;
use App\Utils\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use FileUploadTraits;

    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.index');
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
        //
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
    public function update(UploadDisplayLogoRequest $request, string $id)
    {

        DB::beginTransaction();

        try{
            $order = $this->orderRepository->uploadlogoDisplay($request, $id);

            if($order){
                Mail::to($order->user->email)->send(new AdminDisplayLogoUpload($order));
            }

            Toastr::success('Images uploaded successfully ', 'Success');
            DB::commit();

            return back()->withInput();

        } catch(\Exception $e){

            DB::rollBack();

            return $e->getMessage();
            Toastr::error('Something went wrong! ', 'Success');
            return back();
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function detail(string $id)
    {
        $orderss = OrderDetail::where('order_id', $id)->get();
        $groupedOrders = $orderss->where('bundle_id', '!=', null)->groupBy('cart_id')->map(function ($cartGroup) {
            return $cartGroup->groupBy('bundle_id');
        });
        $ecomOrders=OrderDetail::where('order_id', $id)->where('product_id', '!=', null)->get();
        $logos=LogoDisplay::where('order_id', $id)->get();
        $odr=Order::find($id);

        $status = Helper::OrderStatus();

        return view('admin.order.orderDetail',compact('orderss','odr','groupedOrders','logos', 'status','ecomOrders'));
    }



    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_status' => 'required',
        ]);

        $status = Helper::OrderStatus();
    
        $order = Order::find($request->order_id);
        $order->order_status = $request->order_status;
        $order->save();
    
        return response()->json(['success' => true, 'status' => $status[$order->order_status]]);
    }
}
