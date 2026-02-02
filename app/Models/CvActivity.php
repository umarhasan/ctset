<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'cv_id',
        'activity'
    ];

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}
