<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'email',
        'phone_no',
        'title',
        'cover_image',
        'facebook_link',
        'x_link',
        'linkedin_link',
        'youtube_link',
        'whatsapp_link',
        'sequence',
        'status',
        'publish_time',
        'expertise',
        'experience',
        'education',
        'achievements',
        'quote',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'publish_time' => 'datetime',
        'expertise' => 'array',
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

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getRouteKey()
    {
        return $this->uuid;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function options($column)
    {
        if ($column == 'status') {
            return [
                ['id' => 1, 'caption' => 'Draft', 'color' => 'bg-gray-400'],
                ['id' => 2, 'caption' => 'Published', 'color' => 'bg-green-500'],
                ['id' => 3, 'caption' => 'Scheduled', 'color' => 'bg-yellow-500'],
            ];
        }
        return null;
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}

