<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;
    use SearchTrait;
    use ColumnsTrait;
    
    protected $guarded = [];
    protected $keyType = 'int';
    public $incrementing = true;

    protected $casts = [
        'created_at'  => 'date:d-M-Y',
        'updated_at'  => 'date:d-M-Y',
        'publish_time'  => 'datetime:d-M-Y h:m:s',
    ];

    protected $appends = [
        'image_url'
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
        static::creating(function ($gallery) {
            $gallery->slug = Str::slug($gallery->title);
        });

        static::updating(function ($gallery) {
            $gallery->slug = Str::slug($gallery->title);
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
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
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
}
