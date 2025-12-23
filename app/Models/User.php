<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Session;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Support\Str;

class User extends Authenticatable implements LaratrustUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    // use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uuid', 'name', 'email', 'password', 'user_category', 'status', 'created_by', 'updated_by',
    ];
    protected $keyType = 'int';
    public $incrementing = true;

    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'  => 'date:d-M-Y',
        'updated_at'  => 'date:d-M-Y',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
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
    
    public function getProfileAttribute()
    {
        return Session::get('profile', null);
    }

    public function setProfileAttribute($value)
    {
        Session::put('profile', $value);
    }
    // public function generateUUid()
    // {
    //     return \Uuid::generate();

    // }

 	public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function options($column)
    {
        if($column == 'status'){
            $options = [
                ['id' => 1,'caption' => 'Inactive', 'color' => 'bg-yellow-500'],
                ['id' => 2,'caption' => 'Active', 'color' => 'bg-green-500'],
            ];
        }
        if($column == 'user_category'){
            $options = [
                ['id' => 2,'caption' => 'Regular', 'color' => 'bg-yellow-200'],
                ['id' => 3,'caption' => 'Admin', 'color' => 'bg-yellow-500'],
                ['id' => 100,'caption' => 'Super Admin', 'color' => 'bg-green-500'],
            ];
        }
        if(isset($options)){
            return $options;
        }else{
            return null;
        }
    }


}