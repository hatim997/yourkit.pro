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

            $directProductOrders = DB::table('order_details')
                ->select('products.id as product_id', 'products.name', DB::raw('COUNT(*) as total_orders'))
                ->join('products', function ($join) {
                    $join->on('order_details.product_id', '=', 'products.id')
                        ->whereNull('order_details.bundle_id');
                })
                ->groupBy('products.id', 'products.name');

            $bundleProductOrders = DB::table('order_details')
                ->select('products.id as product_id', 'products.name', DB::raw('COUNT(*) as total_orders'))
                ->join('product_bundles', 'order_details.bundle_id', '=', 'product_bundles.bundle_id')
                ->join('products', 'product_bundles.product_id', '=', 'products.id')
                ->whereNull('order_details.product_id')
                ->groupBy('products.id', 'products.name');

            $mostOrderedProductsRaw = $directProductOrders
                ->unionAll($bundleProductOrders)
                ->get();

            // Aggregate total orders per product
            $mostOrderedProducts = $mostOrderedProductsRaw
                ->groupBy('product_id')
                ->map(function ($items) {
                    return [
                        'product_id' => $items[0]->product_id,
                        'name' => $items[0]->name,
                        'total_orders' => $items->sum('total_orders'),
                    ];
                })
                ->sortByDesc('total_orders')
                ->take(10)
                ->values();

            // Load attributes for top products
            $productIds = $mostOrderedProducts->pluck('product_id')->toArray();
            $productAttributes = DB::table('product_attributes')
                ->join('attributes', 'product_attributes.attribute_id', '=', 'attributes.id')
                ->where('product_attributes.attribute_id', 2)
                ->whereIn('product_attributes.product_id', $productIds)
                ->select('product_attributes.product_id', 'attributes.type as attribute_name', 'product_attributes.value', 'product_attributes.image')
                ->orderBy('product_attributes.id') // optional: get the first created
                ->get()
                ->groupBy('product_id')
                ->map(function ($group) {
                    return $group->first(); // only take the first one per product
                });


            // Merge attributes into product list
            $mostOrderedProducts->transform(function ($product) use ($productAttributes) {
                $product['attribute'] = $productAttributes[$product['product_id']] ?? null;
                return $product;
            });



            return view('dashboard.index', compact(
                'totalOrders',
                'statusCounts',
                'orders',
                'profits',
                'dates',
                'lastMonthProfit',
                'mostOrderedProducts',
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
