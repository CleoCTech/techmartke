<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'question',
        'asked_by',
        'is_answered',
        'is_featured',
    ];

    protected $casts = [
        'is_answered' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function answers()
    {
        return $this->hasMany(CommunityAnswer::class, 'question_id');
    }

    public function scopeAnswered($query)
    {
        return $query->where('is_answered', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
