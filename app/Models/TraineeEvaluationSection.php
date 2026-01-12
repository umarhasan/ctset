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
}
