<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvResearchAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'cv_id',
        'type',
        'title',
        'year',
        'details'
    ];

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}
