<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'search_type',
        'budget',
        'results_count',
        'ip_address',
        'user_agent',
        'device_type',
        'matched_product_ids',
    ];

    protected $casts = [
        'matched_product_ids' => 'array',
        'budget' => 'decimal:2',
    ];
}
