<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SuccessStory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'title',
        'student_name',
        'course',
        'graduation_year',
        'description',
        'achievement',
        'cover_image',
        'sequence',
        'status',
        'publish_time',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'sequence' => 'integer',
        'status' => 'integer',
        'publish_time' => 'datetime',
        'created_at'  => 'date:d-M-Y',
        'updated_at'  => 'date:d-M-Y',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    // Accessor for cover image URL
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return config('app.storagePaths.success_stories.cover_images.readPath') . $this->cover_image;
        }
        return null;
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
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
