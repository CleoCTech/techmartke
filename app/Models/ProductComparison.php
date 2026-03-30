<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_1_id',
        'product_2_id',
        'comparison_data',
        'similarity_score',
        'price_difference',
        'category_winner',
    ];

    protected $casts = [
        'comparison_data' => 'array',
        'similarity_score' => 'decimal:2',
        'price_difference' => 'decimal:2',
    ];

    public function product1()
    {
        return $this->belongsTo(Product::class, 'product_1_id');
    }

    public function product2()
    {
        return $this->belongsTo(Product::class, 'product_2_id');
    }
}
