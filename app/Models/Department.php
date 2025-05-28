<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Support\Str;

class Department extends Model
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
    public static function options($column){
        if($column == 'status'){
            $options = [
                ['id' => 1,'caption' => 'Inactive', 'color' => 'bg-gray-400'],
                ['id' => 2,'caption' => 'Active', 'color' => 'bg-green-500'],
               // ['id' => 3,'caption' => 'Save & Publish Later', 'color' => 'bg-yellow-500'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }
    public function departmentGoals()
    {
        return $this->hasMany(DepartmentGoal::class);
    }

    public function departmentActivities()
    {
        return $this->hasMany(DepartmentActivity::class);
    }

    // Parent department relationship
    // Department heads relationship
    public function heads()
    {
        return $this->hasMany(DepartmentHead::class);
    }

    // Department members relationship
    // public function members()
    // {
    //     return $this->hasMany(DepartmentMember::class);
    // }

    // Department activities relationship
    public function activities()
    {
        return $this->hasMany(DepartmentActivity::class);
    }

    // Department goals relationship
    public function goals()
    {
        return $this->hasMany(DepartmentGoal::class);
    }

    // Incoming department requests
    public function incomingRequests()
    {
        return $this->hasMany(DepartmentRequest::class, 'to_department_id');
    }

    // Outgoing department requests
    public function outgoingRequests()
    {
        return $this->hasMany(DepartmentRequest::class, 'from_department_id');
    }

    // Get active members count
    public function getActiveMembersCountAttribute()
    {
        return $this->members()->where('status', 'active')->count();
    }

    // Get pending requests count
    public function getPendingRequestsCountAttribute()
    {
        return $this->incomingRequests()->where('status', 'pending')->count();
    }

    // Get active goals count
    public function getActiveGoalsCountAttribute()
    {
        return $this->goals()->where('status', 'active')->count();
    }

    // Get goals progress
    public function getGoalsProgressAttribute()
    {
        $goals = $this->goals()->where('status', 'active')->get();
        if ($goals->isEmpty()) return 0;

        $totalProgress = $goals->sum('progress');
        return round($totalProgress / $goals->count());
    }
}
