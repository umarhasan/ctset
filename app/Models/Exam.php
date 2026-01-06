<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

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

    public function questionTypes()
    {
        return $this->belongsToMany(
            QuestionType::class,
            'exam_question_type',
            'exam_id',
            'question_type_id'
        );
    }

    public function marketingType()
    {
        return $this->belongsTo(MarketingType::class, 'marketing', 'id');
    }


    public function examDuration()
    {
        return $this->belongsTo(ExamDurationType::class, 'exam_duration_type', 'id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get invited trainees for this exam
    public function invitedTrainees()
    {
        return $this->belongsToMany(User::class, 'invitations', 'exam_id', 'user_id')
                    ->withPivot('status', 'sent_at', 'completed_at')
                    ->withTimestamps()
                    ->whereHas('roles', function($q) {
                        $q->where('name', 'trainee');
                    });
    }



    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d-m-Y h:i:s a');
    }

    // Get status counts for this exam
    public function getStatusCountsAttribute()
    {
        return [
            'Unregistered' => $this->invitations()->where('status', 'Unregistered')->count(),
            'Incompleted' => $this->invitations()->where('status', 'Incompleted')->count(),
            'Completed' => $this->invitations()->where('status', 'Completed')->count(),
            'Absent' => $this->invitations()->where('status', 'Absent')->count(),
            'All' => $this->invitations()->count()
        ];
    }

   public function attempts(){
        return $this->hasMany(ExamAttempt::class);
    }

    public function result(){
        return $this->hasOne(ExamResult::class);
    }
}
