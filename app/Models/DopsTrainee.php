<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasModalType;

class DopsTrainee extends Model
{
    use HasFactory , HasModalType;
    protected $guarded = [];

    public function dops()
    {
        return $this->belongsTo(Dops::class, 'did');
    }
}
