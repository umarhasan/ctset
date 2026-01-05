<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected static function booted()
    {
        static::creating(function ($exam) {
            if (!$exam->exam_id) {
                $lastId = Exam::max('exam_id') ?? 0; // agar table khali ho to 0
                $exam->exam_id = $lastId + 1;
            }
        });
    }
    
    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_type', 'id');
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'question_type', 'id');
    }

    public function marketingType()
    {
        return $this->belongsTo(MarketingType::class, 'marketing', 'id');
    }

    public function examDuration()
    {
        return $this->belongsTo(ExamDurationType::class, 'exam_duration_type', 'id');
    }

    // Optional: Yes/No options for shuffling or previous button could be handled as boolean, no relation needed
}
