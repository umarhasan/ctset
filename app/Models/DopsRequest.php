<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DopsRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function dops()
    {
        return $this->belongsTo(Dops::class, 'dopsid');
    }

    public function competencies()
    {
        return $this->hasMany(
            DopsRequestCompetency::class,
            'requestid'
        );
    }

    public function levels()
    {
        return $this->hasMany(
            DopsRequestLevel::class,
            'requestid'
        );
    }
}
