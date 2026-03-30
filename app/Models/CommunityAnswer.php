<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'answer',
        'answered_by',
        'is_staff',
        'is_accepted',
        'helpful_count',
    ];

    protected $casts = [
        'is_staff' => 'boolean',
        'is_accepted' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(CommunityQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
