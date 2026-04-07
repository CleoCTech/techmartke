<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TradeInRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'product_id', 'custom_device_name', 'customer_name', 'customer_phone',
        'storage_capacity', 'condition', 'battery_health', 'has_cracks', 'has_repairs',
        'problems_description', 'estimated_value', 'offered_value', 'status', 'admin_notes',
    ];

    protected $casts = [
        'has_cracks' => 'boolean',
        'has_repairs' => 'boolean',
        'estimated_value' => 'decimal:2',
        'offered_value' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
