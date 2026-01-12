<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongitudinalSection extends Model
{
    use HasFactory;

    protected $fillable = ['longitudinal_requirement_id', 'section_letter', 'section_title', 'order'];

    public function requirement()
    {
        return $this->belongsTo(LongitudinalRequirement::class);
    }

    public function subSections()
    {
        return $this->hasMany(LongitudinalSubSection::class, 'section_id')->orderBy('order');
    }
}
