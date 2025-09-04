<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'date_received',
        'awarding_body',
        'status',
        'publish_time',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date_received' => 'date',
        'publish_time' => 'datetime',
    ];

    public static function options($field)
    {
        $options = [
            'status' => [
                1 => 'Draft',
                2 => 'Published',
                3 => 'Scheduled'
            ]
        ];

        return $options[$field] ?? [];
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
} 