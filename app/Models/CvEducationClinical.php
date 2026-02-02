<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvEducationClinical extends Model
{
    use HasFactory;
    protected $fillable = [
        'cv_id',
        'type',
        'title',
        'institute',
        'year_from',
        'year_to',
        'details'
    ];

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}

