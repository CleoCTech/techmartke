<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;

class AlbumCollection extends Model
{
    use HasFactory;
    use SearchTrait;
    use ColumnsTrait;

    protected $guarded = [];
    protected $keyType = 'int';
    public $incrementing = true;

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'metadata' => 'array',
        'sort_order' => 'integer',
        'created_at' => 'date:d-M-Y',
        'updated_at' => 'date:d-M-Y'
    ];

    protected $appends = [
        'cover_image_url',
        'image_count'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
                
                // Ensure slug is unique
                $originalSlug = $model->slug;
                $counter = 1;
                while (static::where('slug', $model->slug)->exists()) {
                    $model->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
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

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('uuid', $value)->firstOrFail();
    }
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function options($column)
    {
        if ($column == 'is_published') {
            $options = [
                ['id' => 1, 'caption' => 'Published', 'color' => 'bg-green-500'],
                ['id' => 0, 'caption' => 'Draft', 'color' => 'bg-gray-500'],
            ];
        }
        if ($column == 'is_featured') {
            $options = [
                ['id' => 1, 'caption' => 'Featured', 'color' => 'bg-yellow-500'],
                ['id' => 0, 'caption' => 'Regular', 'color' => 'bg-gray-400'],
            ];
        }
        if ($column == 'category') {
            $options = [
                ['id' => 'Academic', 'caption' => 'Academic', 'color' => 'bg-blue-500'],
                ['id' => 'Computer Lab', 'caption' => 'Computer Lab', 'color' => 'bg-purple-500'],
                ['id' => 'Programming', 'caption' => 'Programming', 'color' => 'bg-green-500'],
                ['id' => 'General', 'caption' => 'General', 'color' => 'bg-gray-500'],
                ['id' => 'Student Life', 'caption' => 'Student Life', 'color' => 'bg-pink-500'],
                ['id' => 'Graduation', 'caption' => 'Graduation', 'color' => 'bg-yellow-500'],
                ['id' => 'Workshops', 'caption' => 'Workshops', 'color' => 'bg-orange-500'],
                ['id' => 'Technology', 'caption' => 'Technology', 'color' => 'bg-indigo-500'],
                ['id' => 'Events', 'caption' => 'Events', 'color' => 'bg-red-500'],
                ['id' => 'Achievements', 'caption' => 'Achievements', 'color' => 'bg-emerald-500'],
            ];
        }
        if (isset($options)) {
            return $options;
        } else {
            return null;
        }
    }

    // Relationships
    public function images()
    {
        return $this->hasMany(AlbumImage::class)->orderBy('sort_order');
    }

    public function publishedImages()
    {
        return $this->hasMany(AlbumImage::class)->orderBy('sort_order');
    }

    // Accessors
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }
        return null;
    }

    public function getImageCountAttribute()
    {
        return $this->images()->count();
    }

    public function getFirstImageAttribute()
    {
        return $this->images()->first();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function getImageUrls()
    {
        return $this->images->map(function ($image) {
            return Storage::url($image->image_path);
        });
    }
}

