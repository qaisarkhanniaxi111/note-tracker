<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    public const NOT_FIXED = 0;
    public const FIXED = 1;
    public const CONTACT_ME = 2;
    public const UNFOUNDED = 3;

    protected $fillable = ['location_id', 'clinician_id', 'patient_id', 'error_type_id', 'status', 'status_reason', 'date_of_service', 'comment'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function clinician()
    {
        return $this->belongsTo(User::class, 'clinician_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function errorType()
    {
        return $this->belongsTo(ErrorType::class);
    }

}
