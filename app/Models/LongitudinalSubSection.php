<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongitudinalSubSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'sub_section_title',
        'sub_section_type',
        'table_columns',
        'order'
    ];

    protected $casts = [
        'table_columns' => 'array'
    ];

    public function section()
    {
        return $this->belongsTo(LongitudinalSection::class, 'section_id');
    }

    public function items()
    {
        return $this->hasMany(LongitudinalItem::class, 'sub_section_id')->orderBy('order');
    }
}
