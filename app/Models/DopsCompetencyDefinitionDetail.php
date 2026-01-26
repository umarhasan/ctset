<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DopsCompetencyDefinitionDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function competency()
    {
        return $this->belongsTo(
            DopsCompetencyDefinition::class,
            'dcdid'
        );
    }
}
