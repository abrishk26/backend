<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'role'];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
            if (!$model->role) {
                $model->role = 'customer'; // Default role
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if the user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
