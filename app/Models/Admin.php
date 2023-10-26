<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];


    protected $hidden = [
        'password',
        'c_password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];




    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
