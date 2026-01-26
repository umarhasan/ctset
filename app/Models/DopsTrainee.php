<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DopsTrainee extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function dops()
    {
        return $this->belongsTo(Dops::class, 'did');
    }
}
