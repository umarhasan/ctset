<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationEvaluationSubitem extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'subitem_text', 'input_type', 'order'];

    public function section()
    {
        return $this->belongsTo(RotationEvaluationSection::class, 'section_id');
    }
}
