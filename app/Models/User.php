<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ADMIN = 1;
    public const CLINICIAN = 0;
    public const ACTIVE = 1;
    public const DISABLED = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeClinicians(Builder $builder)
    {
        $builder->where('role', User::CLINICIAN);
    }

    public function scopeActive(Builder $builder)
    {
        $builder->whereNotNull('email_verified_at');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'clinician_location', 'location_id', 'clinician_id');
    }

    public function isAdmin()
    {
        $isAdmin = false;

        if (auth()->check()) {
            if (auth()->user()->role === User::ADMIN) {
                return $isAdmin = true;
            }
        }
    }

}
