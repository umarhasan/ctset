<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DopsCompetencyDefinition extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function dops()
    {
        return $this->belongsTo(Dops::class,'dopsid');
    }

    public function definitions()
    {
        return $this->hasMany(DopsCompetencyDefinitionDetail::class, 'dcdid');
    }
}
