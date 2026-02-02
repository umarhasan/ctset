<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvMilestone extends Model
{
    use HasFactory;
    protected $fillable = [
        'cv_id',
        'title',
        'month_year',
        'description'
    ];
    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}

