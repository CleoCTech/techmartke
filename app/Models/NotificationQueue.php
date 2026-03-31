<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationQueue extends Model
{
    use HasFactory;

    protected $table = 'notification_queue';

    protected $fillable = [
        'subscriber_id',
        'notification_id',
        'channel',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function subscriber()
    {
        return $this->belongsTo(VipSubscriber::class, 'subscriber_id');
    }

    public function notification()
    {
        return $this->belongsTo(StockNotification::class, 'notification_id');
    }
}
