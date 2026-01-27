<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DopsLevel extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function dops()
    {
        return $this->belongsTo(Dops::class, 'dopsid');
    }

     public function level()
    {
        return $this->belongsTo(Level::class, 'levelid');
    }

}
