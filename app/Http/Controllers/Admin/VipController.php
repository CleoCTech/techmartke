<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationQueue;
use App\Models\PromoCampaign;
use App\Models\StockNotification;
use App\Models\VipSubscriber;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VipController extends Controller
{
    public function index(Request $request)
    {
        $query = VipSubscriber::with('referrer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('promo_code', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => VipSubscriber::count(),
            'active' => VipSubscriber::where('status', 'active')->count(),
            'pending' => VipSubscriber::where('status', 'pending')->count(),
            'total_revenue' => VipSubscriber::where('status', 'active')->sum('payment_amount'),
        ];

        $activeCampaign = PromoCampaign::active()->first();

        return Inertia::render('Admin/Vip/Index', [
            'subscribers' => $subscribers,
            'stats' => $stats,
            'activeCampaign' => $activeCampaign,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    public function notifications()
    {
        $notifications = StockNotification::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $vipCount = VipSubscriber::active()->count();

        return Inertia::render('Admin/Vip/Notifications', [
            'notifications' => $notifications,
            'vipCount' => $vipCount,
        ]);
    }

    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|in:new_stock,deal,price_drop,general',
            'product_id' => 'nullable|exists:products,id',
            'target_audience' => 'required|in:vip_only,all',
        ]);

        $notification = StockNotification::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'product_id' => $validated['product_id'] ?? null,
            'target_audience' => $validated['target_audience'],
            'created_by' => auth()->id(),
        ]);

        $subscribers = VipSubscriber::active()->get();

        foreach ($subscribers as $subscriber) {
            NotificationQueue::create([
                'subscriber_id' => $subscriber->id,
                'notification_id' => $notification->id,
                'channel' => 'email',
                'status' => 'queued',
            ]);
        }

        $notification->update([
            'sent_count' => $subscribers->count(),
            'sent_at' => now(),
        ]);

        return redirect()->back()->with('success', "Notification queued for {$subscribers->count()} VIP subscribers.");
    }

    public function campaigns()
    {
        $campaigns = PromoCampaign::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Admin/Vip/Campaigns', [
            'campaigns' => $campaigns,
        ]);
    }

    public function storeCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code_prefix' => 'nullable|string|max:10',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'vip_price' => 'required|numeric|min:0',
            'referral_reward' => 'required|numeric|min:0',
            'referrals_to_activate' => 'required|integer|min:1',
            'max_uses' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        PromoCampaign::create($validated);

        return redirect()->back()->with('success', 'Campaign created successfully.');
    }

    public function toggleSubscriber($id)
    {
        $subscriber = VipSubscriber::findOrFail($id);

        $newStatus = $subscriber->status === 'active' ? 'pending' : 'active';

        $subscriber->update([
            'status' => $newStatus,
            'activation_method' => $newStatus === 'active' ? 'admin' : $subscriber->activation_method,
            'activated_at' => $newStatus === 'active' ? now() : $subscriber->activated_at,
            'expires_at' => $newStatus === 'active' ? now()->addYear() : $subscriber->expires_at,
        ]);

        return redirect()->back()->with('success', "Subscriber status changed to {$newStatus}.");
    }
}
