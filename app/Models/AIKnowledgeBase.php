<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIKnowledgeBase extends Model
{
    use HasFactory;

    protected $table = 'ai_knowledge_base';

    protected $fillable = [
        'topic',
        'content',
        'keywords',
        'category',
        'is_active',
        'usage_count',
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
