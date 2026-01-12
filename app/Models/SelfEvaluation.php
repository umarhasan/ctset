<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfEvaluation extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $casts = [
        'goal_plan_actions' => 'array',
        'question_actions' => 'array',
    ];

}
