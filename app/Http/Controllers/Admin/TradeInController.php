<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeInRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TradeInController extends Controller
{
    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
    }

    public function index(Request $request)
    {
        $query = TradeInRequest::with('product:id,name');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%")
                  ->orWhere('custom_device_name', 'like', "%{$s}%");
            });
        }

        $tradeIns = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'pending' => TradeInRequest::where('status', 'pending')->count(),
            'quoted' => TradeInRequest::where('status', 'quoted')->count(),
            'accepted' => TradeInRequest::where('status', 'accepted')->count(),
            'completed' => TradeInRequest::where('status', 'completed')->count(),
            'rejected' => TradeInRequest::where('status', 'rejected')->count(),
        ];

        return Inertia::render('Admin/TradeIn/Index', [
            'tradeIns' => $tradeIns,
            'filters' => $request->only(['status', 'search']),
            'stats' => $stats,
        ]);
    }

    public function show($uuid)
    {
        $tradeIn = TradeInRequest::with('product')->where('uuid', $uuid)->firstOrFail();

        return Inertia::render('Admin/TradeIn/Show', [
            'tradeIn' => $tradeIn,
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $tradeIn = TradeInRequest::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:pending,quoted,accepted,rejected,completed',
            'offered_value' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $tradeIn->update($validated);

        return redirect()->back()->with('success', 'Trade-in request updated.');
    }

    public function destroy($uuid)
    {
        TradeInRequest::where('uuid', $uuid)->delete();
        return redirect()->route('admin.trade-in')->with('success', 'Trade-in request deleted.');
    }
}
