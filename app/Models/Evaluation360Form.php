<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation360Form extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'evaluation_360_forms';

    public function sections()
    {
        return $this->hasMany(Evaluation360Section::class, 'evaluation_360_form_id')->orderBy('order');
    }

    public function shares()
    {
        return $this->hasMany(Evaluation360FormShare::class,'evaluation_360_form_id');
    }


}
