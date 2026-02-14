<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationEvaluation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'course_title', 'status'];

    public function sections()
    {
        return $this->hasMany(RotationEvaluationSection::class, 'rotation_evaluation_id')->orderBy('order');
    }

    
    public function responses()
    {
        return $this->hasMany(RotationEvaluationResponse::class, 'rotation_evaluation_id');
    }
}
