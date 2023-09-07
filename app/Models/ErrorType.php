<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorType extends Model
{
    use HasFactory;

    public const ACTIVE = 1;
    public const DISABLED = 0;

    protected $fillable = ['name', 'status'];

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', ErrorType::ACTIVE);
    }
}
