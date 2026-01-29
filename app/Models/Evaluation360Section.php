<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation360Section extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'evaluation_360_sections';

    public function evaluationForm()
    {
        return $this->belongsTo(Evaluation360Form::class, 'evaluation_360_form_id');
    }

     public function responses()
    {
        return $this->hasMany(Evaluation360Response::class,'section_id');
    }
}
