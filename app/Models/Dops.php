<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dops extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean'
    ];



    // ===== RELATIONS =====
    public function rotations()
    {
        return $this->hasMany(DopsRotation::class,'dopsid');
    }



    public function levels()
    {
        return $this->hasMany(DopsLevel::class,'dopsid');
    }

    public function ratings()
    {
        return $this->hasMany(DopsRating::class,'dropsid');
    }

    public function competencies()
    {
        return $this->hasMany(DopsCompetencyDefinition::class,'dopsid');
    }

    public function Dopsattempt()
    {
        return $this->hasMany( DopsAttempt::class,'dops_id');
    }
    // ===== HELPER ACCESSORS =====
    public function getRotationIdsAttribute()
    {
        return $this->rotations->pluck('rotationid')->toArray();
    }

    public function getLevelIdsAttribute()
    {
        return $this->levels->pluck('levelid')->toArray();
    }


    public function getRatingIdsAttribute()
    {
        return $this->ratings->pluck('ratingid')->toArray();
    }

// In your Dops model
    protected $appends = ['competencies_array'];




    public function competenciesWithDefinitions()
    {
        return $this->hasMany(DopsCompetencyDefinition::class,'dopsid')
            ->with('definitions');
    }
    public function getCompetenciesArrayAttribute()
    {
        return $this->competenciesWithDefinitions->map(function($item){
            return [
                'id' => $item->id,
                'competency_id' => $item->cdid,   // column name in DopsCompetencyDefinition
                'order' => $item->dcdseq,
                'definitions' => $item->definitions->pluck('title')->toArray()
            ];
        })->toArray();
    }



}
