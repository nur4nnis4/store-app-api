<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    public $incrementing = false; //non-incrementing or non-numeric primary
    protected $keyType = 'string';

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'id');
    }

    protected $fillable = [
        'id',
        'name',
        'email',
        'address',
        'phone_number',
        'password',
        'photo_path',
        'email_verified_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
