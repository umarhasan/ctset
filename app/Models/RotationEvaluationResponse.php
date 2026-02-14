<?php
// app/Models/RotationEvaluationResponse.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationEvaluationResponse extends Model
{
    use HasFactory;

    protected $table = 'rotation_evaluation_responses';

    protected $fillable = [
        'rotation_evaluation_id',
        'user_id',
        'data',
        'submitted_at',
    ];

    protected $casts = [
        'data' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function evaluation()
    {
        return $this->belongsTo(RotationEvaluation::class, 'rotation_evaluation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}