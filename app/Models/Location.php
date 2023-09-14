<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public const YES = 1;
    public const NO = 0;

    protected $fillable = ['clinician_id', 'name', 'email', 'status'];

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', Location::YES);
    }

    public function clinicians()
    {
        return $this->belongsToMany(User::class, 'clinician_location', 'location_id', 'clinician_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'location_id', 'id');
    }
}
