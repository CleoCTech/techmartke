<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Support\Str;

class ScholarshipApplication extends Model
{
    use HasFactory;
    use SearchTrait;
    use ColumnsTrait;
    
    protected $guarded = [];
    protected $keyType = 'int';
    public $incrementing = true;

    protected $casts = [
        'created_at' => 'date:d-M-Y',
        'updated_at' => 'date:d-M-Y',
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
                ['id' => 1,'caption' => 'New', 'color' => 'bg-gray-400'],
                ['id' => 2,'caption' => 'Pending Approval', 'color' => 'bg-yellow-500'],
                ['id' => 3,'caption' => 'Approved', 'color' => 'bg-green-500'],
                ['id' => 4,'caption' => 'Rejected', 'color' => 'bg-red-500'],
            ];
        }
        if($column == 'type'){
            $options = [
                ['id' => 'student','caption' => 'Student Scholarship', 'color' => 'bg-blue-500'],
                ['id' => 'professional','caption' => 'Professional Scholarship', 'color' => 'bg-green-500'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }
}
