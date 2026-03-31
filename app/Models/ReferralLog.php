<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'promo_code_used',
        'status',
        'reward_amount',
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
    ];

    public function referrer()
    {
        return $this->belongsTo(VipSubscriber::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(VipSubscriber::class, 'referred_id');
    }
}
