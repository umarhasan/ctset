<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'cv_id',
        'full_name',
        'university',
        'class_year',
        'primary_interest',
        'email',
        'phone',
        'profile_image'
    ];

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}

