<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use HasFactory;
    use SearchTrait;
    use ColumnsTrait;
    use SoftDeletes;

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
        static::creating(function ($designation) {
            if (empty($designation->slug)) {
                $designation->slug = Str::slug($designation->name, '_'); // Converts "Senior Pastor" -> "senior_pastor"
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

    /**
     * Get the church leadership roles for this designation.
     */
    public function churchLeadership(): HasMany
    {
        return $this->hasMany(ChurchLeadership::class);
    }
    public function churchBranchLeaderships()
    {
        return $this->hasMany(ChurchBranchLeadership::class);
    }
    public function departmentHeads()
    {
        return $this->hasMany(DepartmentHead::class);
    }

}
