<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DopsAttempt extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function trainee(){
        return $this->belongsTo(User::class,'trainee_id');
    }

    public function rotation(){
        return $this->belongsTo(Rotation::class);
    }

    public function diagnosis(){
        return $this->belongsTo(Diagnosis::class);
    }

    public function procedure(){
        return $this->belongsTo(Procedure::class);
    }

    public function dops(){
        return $this->belongsTo(Dops::class);
    }

}
