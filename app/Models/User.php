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

    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'id');
    }

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'photo_url',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
