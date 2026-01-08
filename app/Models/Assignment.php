<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $guarded = [];
     protected $casts = [
        'users' => 'array',
    ];
    public function fromType()
    {
        return $this->belongsTo(FromType::class,'from_type_id');
    }

    public function rotation()
    {
        return $this->belongsTo(Rotation::class,'rotation_id');
    }
}
