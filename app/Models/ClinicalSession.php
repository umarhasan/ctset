<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalSession extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'consultant_fees' => 'array', // JSON cast
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
    public function hospital() {
        return $this->belongsTo(Hospital::class);
    }
    public function rotation() {
        return $this->belongsTo(Rotation::class);
    }
    public function consultant() {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    public function getStatusTextAttribute()
    {
        return match ($this->involvement) {
            'A' => 'Active',
            'P' => 'Passive',
            default => 'Waiting'
        };
    }
}
