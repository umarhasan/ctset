<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DopsRating extends Model
{
    use HasFactory;
    protected $guarded = [];

     public function dops()
    {
        return $this->belongsTo(Dops::class, 'dropsid');
    }

     public function rating()
    {
        return $this->belongsTo(Rating::class, 'ratingid');
    }
}
