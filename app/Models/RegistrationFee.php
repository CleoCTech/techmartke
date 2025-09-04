<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Support\Str;
class RegistrationFee extends Model
{
    use HasFactory;
    use SearchTrait;
    use ColumnsTrait;
    protected $guarded = [];
    protected $keyType = 'int';
    public $incrementing = true;



    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'date:d-M-Y',
        'updated_at' => 'date:d-M-Y',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? (string) \Illuminate\Support\Str::uuid();
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
        if($column == 'is_active'){
            $options = [
                ['id' => true,'caption' => 'Active', 'color' => 'bg-green-500'],
                ['id' => false,'caption' => 'Inactive', 'color' => 'bg-gray-400'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
