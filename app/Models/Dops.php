<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dops extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function competencies()
    {
        return $this->hasMany(DopsCompetencyDefinition::class, 'dopsid');
    }

    public function levels()
    {
        return $this->hasMany(DopsLevel::class, 'dopsid');
    }

    public function rotations()
    {
        return $this->hasMany(DopsRotation::class, 'dopsid');
    }

    public function requests()
    {
        return $this->hasMany(DopsRequest::class, 'dopsid');
    }
}
