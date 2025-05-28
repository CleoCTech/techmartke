<?php

namespace App\Models;

use App\Models\System\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Support\Str;

class SpecialNeed extends Model
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
                ['id' => 1,'caption' => 'Pending', 'color' => 'bg-gray-400'],
                ['id' => 2,'caption' => 'Approved', 'color' => 'bg-green-500'],
                ['id' => 3,'caption' => 'Resolved', 'color' => 'bg-blue-500'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

}
