<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AlbumImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_collection_id',
        'image_path',
        'original_filename',
        'alt_text',
        'caption',
        'sort_order',
        'image_metadata'
    ];

    protected $casts = [
        'image_metadata' => 'array',
        'sort_order' => 'integer'
    ];

    protected $appends = [
        'image_url',
        'thumbnail_url'
    ];

    // Relationships
    public function albumCollection()
    {
        return $this->belongsTo(AlbumCollection::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    public function getThumbnailUrlAttribute()
    {
        // You can implement thumbnail generation logic here
        // For now, return the same URL
        return $this->image_url;
    }

    // Helper methods
    public function getImageSize()
    {
        if ($this->image_metadata && isset($this->image_metadata['width'], $this->image_metadata['height'])) {
            return [
                'width' => $this->image_metadata['width'],
                'height' => $this->image_metadata['height']
            ];
        }
        return null;
    }

    public function getFileSize()
    {
        if ($this->image_metadata && isset($this->image_metadata['file_size'])) {
            return $this->image_metadata['file_size'];
        }
        return null;
    }
}
