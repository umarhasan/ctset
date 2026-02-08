<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_code',
        'module_type',
        'description',
        'is_active'
    ];

    protected $appends = ['module_label'];

    // Relationship with ModalTypes
    public function modalTypes()
    {
        return $this->hasMany(ModalType::class);
    }

    // Active departments
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Module label for display
    public function getModuleLabelAttribute()
    {
        $modules = [
            'C' => 'Clinical Activities',
            'S' => 'Scientific Meetings',
            'M' => 'MATCVS',
            'D' => 'Department/Unit',
            'E' => 'Evaluation Form'
        ];

        return $modules[$this->module_type] ?? $this->module_type;
    }
}