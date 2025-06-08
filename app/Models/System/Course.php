<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Traits\Admin\UuidTrait;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory; // use UuidTrait;

    protected $table = 'courses';

    protected $guarded = [];

    protected $casts = [
        'technologies' => 'json',
        'created_at'  => 'date:d-M-Y',
        'updated_at'  => 'date:d-M-Y',
    ];

    // public $incrementing = false;
    // protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
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

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('uuid', $value)->firstOrFail();
    }

    public static function options($column){
        if($column == 'status'){
            $options = [
                ['id' => 1,'caption' => 'Save Only', 'color' => 'bg-gray-400'],
                ['id' => 2,'caption' => 'Save & Publish', 'color' => 'bg-green-500'],
                ['id' => 3,'caption' => 'Save & Publish Later', 'color' => 'bg-yellow-500'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
} 