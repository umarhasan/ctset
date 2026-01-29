<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Evaluation360Response extends Model
{
    use HasFactory;
    protected $guarded = [];

     public function share()
    {
        return $this->belongsTo(Evaluation360FormShare::class,'share_id');
    }

    public function section()
    {
        return $this->belongsTo(Evaluation360Section::class,'section_id');
    }
}

