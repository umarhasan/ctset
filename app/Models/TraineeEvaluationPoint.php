<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraineeEvaluationPoint extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'point_text', 'order'];

    public function section()
    {
        return $this->belongsTo(TraineeEvaluationSection::class, 'section_id');
    }
}