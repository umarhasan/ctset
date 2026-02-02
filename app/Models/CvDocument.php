<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'cv_id',
        'title',
        'file'
    ];

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}

