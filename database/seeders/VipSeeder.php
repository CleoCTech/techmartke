<?php

namespace Database\Seeders;

use App\Models\PromoCampaign;
use App\Models\ReferralLog;
use App\Models\StockNotification;
use App\Models\VipSubscriber;
use Illuminate\Database\Seeder;

class VipSeeder extends Seeder
{
    public function run(): void
    {
        // Create active promo campaign
        PromoCampaign::create([
            'name' => 'TechMart VIP Launch',
            'code_prefix' => 'TM',
            'discount_type' => 'fixed',
            'discount_value' => 200,
            'vip_price' => 500,
            'referral_reward' => 100,
            'referrals_to_activate' => 2,
            'is_active' => true,
        ]);

        // Active subscriber via payment
        $kevin = VipSubscriber::create([
            'email' => 'kevin.omondi@gmail.com',
            'phone' => '+254712345678',
            'name' => 'Kevin Omondi',
            'promo_code' => 'TM-KEV01',
            'status' => 'active',
            'activation_method' => 'payment',
            'payment_reference' => 'MPESA-RG234KL89P',
            'payment_amount' => 500,
            'activated_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        // Active subscriber via referral (referred by Kevin)
        $wanjiku = VipSubscriber::create([
            'email' => 'wanjiku.kamau@yahoo.com',
            'phone' => '+254723456789',
            'name' => 'Wanjiku Kamau',
            'promo_code' => 'TM-WAN02',
            'status' => 'active',
            'activation_method' => 'referral',
            'referred_by_id' => $kevin->id,
            'activated_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        // Active subscriber via referral (referred by Kevin)
        $hassan = VipSubscriber::create([
            'email' => 'hassan.ali@outlook.com',
            'phone' => '+254734567890',
            'name' => 'Hassan Ali',
            'promo_code' => 'TM-HAS03',
            'status' => 'active',
            'activation_method' => 'referral',
            'referred_by_id' => $kevin->id,
            'activated_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        // Update Kevin's referral count
        $kevin->update(['referral_count' => 2, 'total_referral_earnings' => 200]);

        // Pending subscribers
        $achieng = VipSubscriber::create([
            'email' => 'achieng.otieno@gmail.com',
            'phone' => '+254745678901',
            'name' => 'Achieng Otieno',
            'promo_code' => 'TM-ACH04',
            'status' => 'pending',
        ]);

        $mwangi = VipSubscriber::create([
            'email' => 'james.mwangi@hotmail.com',
            'phone' => '+254756789012',
            'name' => 'James Mwangi',
            'promo_code' => 'TM-JAM05',
            'status' => 'pending',
            'referred_by_id' => $wanjiku->id,
        ]);

        // Referral logs
        ReferralLog::create([
            'referrer_id' => $kevin->id,
            'referred_id' => $wanjiku->id,
            'promo_code_used' => 'TM-KEV01',
            'status' => 'rewarded',
            'reward_amount' => 100,
        ]);

        ReferralLog::create([
            'referrer_id' => $kevin->id,
            'referred_id' => $hassan->id,
            'promo_code_used' => 'TM-KEV01',
            'status' => 'completed',
            'reward_amount' => 100,
        ]);

        ReferralLog::create([
            'referrer_id' => $wanjiku->id,
            'referred_id' => $mwangi->id,
            'promo_code_used' => 'TM-WAN02',
            'status' => 'pending',
        ]);

        // Stock notifications
        StockNotification::create([
            'title' => 'New iPhone 15 Stock!',
            'message' => 'Fresh stock of iPhone 15 Pro and iPhone 15 Pro Max just arrived at TechMart. As a VIP member, you get first access before the general public. Limited quantities available!',
            'type' => 'new_stock',
            'target_audience' => 'vip_only',
            'sent_count' => 3,
            'sent_at' => now()->subDays(2),
        ]);

        StockNotification::create([
            'title' => 'Flash Sale Weekend',
            'message' => 'Exclusive VIP flash sale this weekend! Get up to 30% off on Samsung Galaxy devices, MacBook Air M3, and all accessories. Use your VIP promo code at checkout.',
            'type' => 'deal',
            'target_audience' => 'vip_only',
            'sent_count' => 3,
            'sent_at' => now()->subDay(),
        ]);
    }
}
