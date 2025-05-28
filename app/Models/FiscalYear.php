<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FiscalYear extends Model
{
    use HasFactory;
    use HasFactory;
    use SearchTrait;
    use ColumnsTrait;

    protected $guarded = [];
    protected $keyType = 'int';
    public $incrementing = true;

    protected $casts = [
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

     /**
     * Get the fiscal periods for this fiscal year.
     */
    public function fiscalPeriods(): HasMany
    {
        return $this->hasMany(FiscalPeriod::class);
    }

    public function getSummaryAttribute()
    {
        return [
            'total_periods' => $this->fiscalPeriods->count(),
            'total_days' => $this->fiscalPeriods->sum(function ($period) {
                return Carbon::parse($period->start_date)->diffInDays($period->end_date);
            }),
        ];
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
