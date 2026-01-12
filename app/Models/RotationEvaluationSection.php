<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationEvaluationSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'rotation_evaluation_id',
        'section_title',
        'section_type',
        'description',
        'options',
        'order'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function evaluation()
    {
        return $this->belongsTo(RotationEvaluation::class);
    }

    public function subitems()
    {
        return $this->hasMany(RotationEvaluationSubitem::class, 'section_id')->orderBy('order');
    }
}
