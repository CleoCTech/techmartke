<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VipSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'name',
        'promo_code',
        'status',
        'activation_method',
        'payment_reference',
        'payment_amount',
        'referred_by_id',
        'referral_count',
        'total_referral_earnings',
        'notify_new_stock',
        'notify_deals',
        'notify_price_drops',
        'last_notified_at',
        'activated_at',
        'expires_at',
    ];

    protected $casts = [
        'notify_new_stock' => 'boolean',
        'notify_deals' => 'boolean',
        'notify_price_drops' => 'boolean',
        'payment_amount' => 'decimal:2',
        'total_referral_earnings' => 'decimal:2',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_notified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->promo_code)) {
                $model->promo_code = 'TM-' . strtoupper(Str::random(5));
            }
        });
    }

    public function referrer()
    {
        return $this->belongsTo(VipSubscriber::class, 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(VipSubscriber::class, 'referred_by_id');
    }

    public function referralLogs()
    {
        return $this->hasMany(ReferralLog::class, 'referrer_id');
    }

    public function notifications()
    {
        return $this->hasMany(NotificationQueue::class, 'subscriber_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVip($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function isVip(): bool
    {
        return $this->status === 'active'
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function activate(string $method): void
    {
        $this->update([
            'status' => 'active',
            'activation_method' => $method,
            'activated_at' => now(),
            'expires_at' => now()->addYear(),
        ]);
    }
}
