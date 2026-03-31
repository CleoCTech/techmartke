<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PromoCampaign;
use App\Models\ReferralLog;
use App\Models\VipSubscriber;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VipController extends Controller
{
    public function index()
    {
        $activeCampaign = PromoCampaign::active()->first();
        $vipCount = VipSubscriber::active()->count();

        return Inertia::render('Customer/Vip/Index', [
            'activeCampaign' => $activeCampaign,
            'vipCount' => $vipCount,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:vip_subscribers,email',
            'phone' => 'required|string',
            'name' => 'required|string',
            'promo_code_used' => 'nullable|string',
        ]);

        $subscriber = VipSubscriber::create([
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'name' => $validated['name'],
            'status' => 'pending',
        ]);

        if (!empty($validated['promo_code_used'])) {
            $referrer = VipSubscriber::where('promo_code', $validated['promo_code_used'])->first();

            if ($referrer) {
                $subscriber->update(['referred_by_id' => $referrer->id]);

                $referralLog = ReferralLog::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $subscriber->id,
                    'promo_code_used' => $validated['promo_code_used'],
                    'status' => 'pending',
                ]);

                $referrer->increment('referral_count');

                $campaign = PromoCampaign::active()->first();

                if ($campaign && $referrer->referral_count >= $campaign->referrals_to_activate && $referrer->status !== 'active') {
                    $referrer->activate('referral');
                    $referralLog->update(['status' => 'completed']);
                }
            }
        }

        return redirect()->back()->with('success', 'You have been registered! Your promo code is: ' . $subscriber->promo_code);
    }

    public function activate(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|exists:vip_subscribers,email',
            'payment_reference' => 'required|string',
            'payment_amount' => 'required|numeric|min:500',
        ]);

        $subscriber = VipSubscriber::where('email', $validated['email'])->firstOrFail();

        $subscriber->update([
            'status' => 'active',
            'activation_method' => 'payment',
            'payment_reference' => $validated['payment_reference'],
            'payment_amount' => $validated['payment_amount'],
            'activated_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        if ($subscriber->referred_by_id) {
            $referralLog = ReferralLog::where('referred_id', $subscriber->id)->first();

            if ($referralLog) {
                $campaign = PromoCampaign::active()->first();
                $rewardAmount = $campaign ? $campaign->referral_reward : 100;

                $referralLog->update([
                    'status' => 'rewarded',
                    'reward_amount' => $rewardAmount,
                ]);

                $referrer = $subscriber->referrer;
                if ($referrer) {
                    $referrer->increment('total_referral_earnings', $rewardAmount);
                }
            }
        }

        return redirect()->back()->with('success', 'Your VIP membership has been activated!');
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = VipSubscriber::where('email', $request->email)->first();
        $campaign = PromoCampaign::active()->first();

        if (!$subscriber) {
            return response()->json([
                'found' => false,
            ]);
        }

        return response()->json([
            'found' => true,
            'status' => $subscriber->status,
            'is_vip' => $subscriber->isVip(),
            'promo_code' => $subscriber->promo_code,
            'referral_count' => $subscriber->referral_count,
            'referrals_needed' => $campaign ? $campaign->referrals_to_activate : 1,
        ]);
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
        ]);

        $subscriber = VipSubscriber::where('promo_code', $request->promo_code)
            ->where('status', 'active')
            ->first();

        $campaign = PromoCampaign::active()->first();

        if (!$subscriber || !$campaign) {
            return response()->json([
                'valid' => false,
                'error' => 'Invalid or inactive promo code.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'discount_type' => $campaign->discount_type,
            'discount_value' => $campaign->discount_value,
            'promo_code' => $subscriber->promo_code,
        ]);
    }
}
