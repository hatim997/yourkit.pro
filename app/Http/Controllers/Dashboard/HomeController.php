<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductBundle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->hasRole('user')) {
                return redirect()->route('frontend.dashboard');
            }
            $orders = Order::all();
            $totalOrders = $orders->count();

            $statusCounts = [
                'underReview' => $orders->where('order_status', 1)->count(),
                'designApproved' => $orders->where('order_status', 2)->count(),
                'waitingGarments' => $orders->where('order_status', 3)->count(),
                'sentToDesigner' => $orders->where('order_status', 4)->count(),
                'inProduction' => $orders->where('order_status', 5)->count(),
            ];

            // Get profits per day for last month
            $start = Carbon::now()->subMonth()->startOfMonth();
            $end = Carbon::now()->subMonth()->endOfMonth();

            // Calculate profit from last month
            $lastMonthProfit = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->sum('final_amount');

            $dailyProfit = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(final_amount) as total')
                )
                ->whereBetween('created_at', [$start, $end])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            $dates = $dailyProfit->pluck('date')->toArray();
            $profits = $dailyProfit->pluck('total')->map(fn($value) => round($value, 2))->toArray();
            // dd($orders);

            return view('dashboard.index', compact(
                'totalOrders',
                'statusCounts',
                'orders',
                'profits',
                'dates',
                'lastMonthProfit',
            ));
        } catch (\Throwable $th) {
            Log::error('Dashboard View Failed', ['error' => $th->getMessage()]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
