<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public const YES = 1;
    public const NO = 0;

    protected $fillable = ['clinician_id', 'name', 'email', 'status'];

    public function clinicians()
    {
        return $this->belongsToMany(User::class, 'clinician_location', 'location_id', 'clinician_id');
    }
}
