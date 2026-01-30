<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation360FormShare extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'evaluation_360_form_shares';

    protected $dates = ['locked_at'];

    public function form()
    {
        return $this->belongsTo(Evaluation360Form::class,'evaluation_360_form_id');
    }

    public function responses()
    {
        return $this->hasMany(Evaluation360Response::class,'share_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class,'shared_by');
    }



    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isLocked()
    {
        return $this->status === 'I';
    }
}
