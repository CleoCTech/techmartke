<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\SearchTrait;
use App\Traits\Admin\ColumnsTrait;
use Illuminate\Support\Str;

class Event extends Model
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
        'publish_time'  => 'datetime:d-M-Y h:m:s',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
        static::creating(function ($event) {
            $event->slug = Str::slug($event->title);
        });

        static::updating(function ($event) {
            $event->slug = Str::slug($event->title);
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
                ['id' => 1,'caption' => 'Save Only', 'color' => 'bg-gray-400'],
                ['id' => 2,'caption' => 'Save & Publish', 'color' => 'bg-green-500'],
                ['id' => 3,'caption' => 'Save & Publish Later', 'color' => 'bg-yellow-500'],
            ];
        }
        if($column == 'event_type'){
            $options = [
                ['id' => 1,'caption' => 'Event'],
                ['id' => 2,'caption' => 'Conference'],
                ['id' => 3,'caption' => 'Seminar'],
                ['id' => 4,'caption' => 'Workshop'],
                ['id' => 5,'caption' => 'Bootcamp'],
                ['id' => 6,'caption' => 'Networking'],
                ['id' => 7,'caption' => 'Training'],
                ['id' => 8,'caption' => 'Others'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }
    public static function eventType($column){
        if($column == 'event_type'){
            $options = [
                ['id' => 1,'caption' => 'Event'],
                ['id' => 2,'caption' => 'Branch'],
                ['id' => 3,'caption' => 'Departmental'],
                ['id' => 4,'caption' => 'Group'],
                ['id' => 5,'caption' => 'Evangelism'],
                ['id' => 6,'caption' => 'Service'],
                ['id' => 7,'caption' => 'Prayer Gathering'],
                ['id' => 8,'caption' => 'Seminar'],
                ['id' => 10,'caption' => 'Others'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
