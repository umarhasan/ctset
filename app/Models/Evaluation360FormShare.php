<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation360FormShare extends Model
{
     use HasFactory;
    protected $fillable = [
        'evaluation_360_form_id',
        'shared_by',
        'student_id',
        'name',
        'email',
        'phone',
        'details',
        'pin',
        'status',
        'locked_at'
    ];

    protected $dates = ['locked_at'];

    // Which form is shared
    public function form()
    {
        return $this->belongsTo(Evaluation360Form::class,'evaluation_360_form_id');
    }

    // All answers by this evaluator
    public function responses()
    {
        return $this->hasMany(Evaluation360Response::class,'share_id');
    }

    // Student (trainee)
    public function student()
    {
        return $this->belongsTo(User::class,'student_id');
    }

    // Admin who shared
    public function sharedBy()
    {
        return $this->belongsTo(User::class,'shared_by');
    }

    // Helpers
    public function isLocked()
    {
        return $this->status === 'I';
    }
}
