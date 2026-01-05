<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'status',
        'sent_at',
        'completed_at',
        'score',
        'percentage',
        'attempted_at',
        'submitted_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
        'attempted_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'decimal:2',
        'percentage' => 'decimal:2'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get readable status
    public function getStatusTextAttribute()
    {
        $statusMap = [
            'Unregistered' => 'Unregistered',
            'Incompleted' => 'Incomplete Exam',
            'Completed' => 'Completed The Exam',
            'Absent' => 'Absent'
        ];
        return $statusMap[$this->status] ?? $this->status;
    }

    // Get status badge class
    public function getStatusClassAttribute()
    {
        $classMap = [
            'Unregistered' => 'warning',
            'Incompleted' => 'info',
            'Completed' => 'success',
            'Absent' => 'danger'
        ];
        return $classMap[$this->status] ?? 'secondary';
    }

    // Get duration taken
    public function getDurationTakenAttribute()
    {
        if ($this->attempted_at && $this->submitted_at) {
            $minutes = $this->attempted_at->diffInMinutes($this->submitted_at);
            return $minutes . ' minutes';
        }
        return 'N/A';
    }
}
