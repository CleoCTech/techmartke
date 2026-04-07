<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SearchLog;
use App\Models\TradeInRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
    }

    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $recentOrders = Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $lowStockProducts = ProductVariant::with('product')
            ->where('stock_quantity', '<', 5)
            ->where('is_active', true)
            ->get();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Search Analytics
        $totalSearches = SearchLog::count();
        $todaySearches = SearchLog::whereDate('created_at', today())->count();
        $searchesByType = SearchLog::select('search_type', DB::raw('count(*) as count'))
            ->groupBy('search_type')
            ->pluck('count', 'search_type')
            ->toArray();
        $searchesByDevice = SearchLog::select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();
        $topSearches = SearchLog::select('query', DB::raw('count(*) as count'))
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
        $zeroResultSearches = SearchLog::where('results_count', 0)
            ->select('query', DB::raw('count(*) as count'))
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
        $recentSearches = SearchLog::orderByDesc('created_at')
            ->limit(15)
            ->get();
        // Daily search volume for the last 14 days
        $dailySearchVolume = SearchLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Trade-In stats
        $pendingTradeIns = TradeInRequest::where('status', 'pending')->count();
        $totalTradeIns = TradeInRequest::count();
        $recentTradeIns = TradeInRequest::with('product:id,name')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        $totalTradeInValue = TradeInRequest::whereIn('status', ['quoted', 'accepted', 'completed'])
            ->sum('estimated_value');

        return Inertia::render('Admin/Pages/Dashboard', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'pendingOrders' => $pendingOrders,
            'tradeIns' => [
                'pending' => $pendingTradeIns,
                'total' => $totalTradeIns,
                'totalValue' => $totalTradeInValue,
                'recent' => $recentTradeIns,
            ],
            'searchAnalytics' => [
                'totalSearches' => $totalSearches,
                'todaySearches' => $todaySearches,
                'byType' => $searchesByType,
                'byDevice' => $searchesByDevice,
                'topSearches' => $topSearches,
                'zeroResultSearches' => $zeroResultSearches,
                'recentSearches' => $recentSearches,
                'dailyVolume' => $dailySearchVolume,
            ],
        ]);
    }
}
