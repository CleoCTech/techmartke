<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkPriceUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploaded_by',
        'raw_text',
        'parsed_data',
        'status',
        'products_created',
        'variants_created',
        'errors',
    ];

    protected $casts = [
        'parsed_data' => 'array',
        'errors' => 'array',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
