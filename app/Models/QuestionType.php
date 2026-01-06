<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function exams()
    {
        return $this->belongsToMany(
            Exam::class,
            'exam_question_type',
            'question_type_id',
            'exam_id'
        );
    }
}
