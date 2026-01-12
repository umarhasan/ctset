<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongitudinalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_section_id',
        'item_number',
        'item_title',
        'description',
        'table_values',
        'is_checked',
        'alternative_text',
        'order'
    ];

    protected $casts = [
        'table_values' => 'array',
        'is_checked' => 'boolean'
    ];

    public function subSection()
    {
        return $this->belongsTo(LongitudinalSubSection::class, 'sub_section_id');
    }
}
