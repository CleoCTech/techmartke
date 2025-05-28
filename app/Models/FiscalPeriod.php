<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FiscalPeriod extends Model
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
        'end_date'  => 'date:d-M-Y',
        'start_date'  => 'date:d-M-Y',
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
     * Get the FiscalYear that owns the FiscalPeriod
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function FiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    } 
    public static function options($column){
        if($column == 'status'){
            $options = [
                ['id' => 'active','caption' => 'Active', 'color' => 'bg-green-500'],
                ['id' => 'inactive','caption' => 'Inactive', 'color' => 'bg-gray-400'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }
    public function churchBranchLeaderships()
    {
        return $this->hasMany(ChurchBranchLeadership::class);
    }
    public function departmentGoals()
    {
        return $this->hasMany(DepartmentGoal::class);
    }

    public function departmentActivities()
    {
        return $this->hasMany(DepartmentActivity::class);
    }

}
