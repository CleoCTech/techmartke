<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Support\Str;

class WebTraffic extends Model
{
    use HasFactory;
    use SearchTrait;
    use ColumnsTrait;

    protected $fillable = [
        'uuid',
        'date',
        'visitors',
        'page_views',
        'unique_visitors',
        'bounce_rate',
        'avg_session_duration',
        'top_pages',
        'referrers'
    ];

    protected $casts = [
        'date' => 'date',
        'top_pages' => 'array',
        'referrers' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->date)) {
                $model->date = now()->toDateString();
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

    // Scopes
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeForRange($query, $range)
    {
        $endDate = now()->toDateString();
        
        switch ($range) {
            case 'week':
                $startDate = now()->subDays(7)->toDateString();
                break;
            case 'month':
                $startDate = now()->subDays(30)->toDateString();
                break;
            case 'year':
                $startDate = now()->subYear()->toDateString();
                break;
            default:
                $startDate = now()->subDays(30)->toDateString();
        }
        
        return $query->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc');
    }

    // Helper method to record a visit
    public static function recordVisit($page = '/', $referrer = null)
    {
        $today = now()->toDateString();
        
        $traffic = static::firstOrCreate(
            ['date' => $today],
            [
                'uuid' => Str::uuid(),
                'visitors' => 0,
                'page_views' => 0,
                'unique_visitors' => 0
            ]
        );
        
        $traffic->increment('page_views');
        
        // Update top pages
        $topPages = $traffic->top_pages ?? [];
        if (!isset($topPages[$page])) {
            $topPages[$page] = 0;
        }
        $topPages[$page]++;
        arsort($topPages);
        $traffic->top_pages = array_slice($topPages, 0, 10, true); // Keep top 10
        
        // Update referrers
        if ($referrer) {
            $referrers = $traffic->referrers ?? [];
            if (!isset($referrers[$referrer])) {
                $referrers[$referrer] = 0;
            }
            $referrers[$referrer]++;
            arsort($referrers);
            $traffic->referrers = array_slice($referrers, 0, 10, true); // Keep top 10
        }
        
        $traffic->save();
        
        return $traffic;
    }
}
