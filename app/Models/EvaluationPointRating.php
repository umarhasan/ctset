<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationPointRating extends Model
{
    protected $fillable = [
        'evaluation_id',
        'section_id',
        'point_id',
        'rating'
    ];
}