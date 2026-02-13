<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraineeEvaluationSection extends Model
{
    
    use HasFactory;

    protected $guarded = [];
    public function evaluation()
    {
        return $this->belongsTo(TraineeEvaluation::class);
    }

    public function points()
    {
        return $this->hasMany(TraineeEvaluationPoint::class, 'section_id')->orderBy('order');
    }
}
