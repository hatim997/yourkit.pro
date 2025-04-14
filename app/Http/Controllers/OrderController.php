<?php

namespace App\Http\Controllers;

use App\Models\LogoDisplay;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\OrderRepository;
use App\Utils\Helper;

class OrderController extends Controller
{

    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    public function index()
    {

        $status = Helper::OrderStatus();

        $orders = $this->orderRepository->getOrderDetails(auth()->user()->id);
        //dd($orders);
        return view('frontend.order',compact('orders', 'status'));
    }


    public function orderDetail(string $id)
    {

        // $odr = Order::with('details')->find($id);

        // return $odr;

        $orders = OrderDetail::where('order_id', $id)->get();
        //dd( $orders);
        //$groupedOrders = $orders->getCollection()->groupBy('cart_id');
        $groupedOrders = $orders->where('bundle_id', '!=', null)->groupBy('cart_id')->map(function ($cartGroup) {
            return $cartGroup->groupBy('bundle_id');
        });
       $ecomOrders=OrderDetail::where('order_id', $id)->where('product_id', '!=', null)->get();
       //dd($ecomOrders);
        $logos=LogoDisplay::where('order_id', $id)->get();
        //return $groupedOrders;
        $odr=Order::find($id);
        //dd($orders);
        return view('frontend.orderDetail',compact('orders','odr','groupedOrders','logos','ecomOrders'));
    }




//     public function acceptLogo(Request $request)
// {
//     // Validate the request data for each logo's approval status and comment
//     $validated = $request->validate([
//         'approval_status.*' => 'nullable|boolean', // validation for each checkbox
//         'comment.*' => 'nullable|string', // validation for each comment
//     ]);

//     // Debugging: Dump the received data to ensure it includes both approval status and comment
//     //dd($request->all());

//     // Loop through each logo's ID and update the corresponding data
//     foreach ($request->approval_status as $logoId => $approvalStatus) {
//         $logo = LogoDisplay::find($logoId);  // Find the logo by ID

//         if ($logo) {
//             // Get the comment for this logo, or set it to null if not provided
//             $comment = isset($request->comment[$logoId]) ? $request->comment[$logoId] : null;

//             // If approval status is missing (unchecked), treat it as false
//             $approvalStatus = $approvalStatus ?? false;

//             // Update the logo's approval status and comment
//             $logo->approval_status = $approvalStatus;  // Update approval status
//             $logo->comment = $comment;  // Update the comment
//             $logo->save();  // Save the updated logo
//         }
//     }

//     // After processing all logos, return success message
//     return back()->with('success', 'Data saved successfully!');
// }


public function acceptLogo(Request $request)
{
    // Validate the request data
    $validated = $request->validate([
        'approval_status.*' => 'nullable|boolean', // validation for approval status
        'comment.*' => 'nullable|string', // validation for comment
    ]);

    // Loop through each logo's ID and update the corresponding data
    foreach ($request->approval_status as $logoId => $approvalStatus) {
        $logo = LogoDisplay::find($logoId);  // Find the logo by ID

        if ($logo) {
            // Get the comment for this logo, or set it to null if not provided
            $comment = $request->comment[$logoId] ?? null;

            // If approval status is missing (unchecked), treat it as false
            $approvalStatus = $approvalStatus ?? false;

            // Update the logo's approval status and comment
            $logo->approval_status = $approvalStatus;  // Update approval status
            $logo->comment = $comment;  // Update the comment
            $logo->save();  // Save the updated logo

            // Return a response with the updated status and comment
            return response()->json([
                'success' => true,
                'message' => 'Logo status updated successfully!',
                'approval_status' => $logo->approval_status,
                'comment' => $logo->comment
            ]);
        }
    }

    // If something goes wrong
    return response()->json([
        'success' => false,
        'message' => 'Failed to update logo status.'
    ]);
}









}
