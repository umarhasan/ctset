<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_code',
        'department_id',
        'table_name',
        'description',
        'is_active'
    ];

    protected $appends = ['table_label'];

    // Relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Active modal types
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Table label for display
    public function getTableLabelAttribute()
    {
        $tables = [
            'cicu_ward_rounds' => 'CICU Ward Rounds',
            'grand_ward_rounds' => 'Grand Ward Rounds',
            'daily_ward_rounds' => 'Daily Ward Rounds',
            'dops_trainees' => 'DOPS Trainees',
            'scientific_meetings' => 'Scientific Meetings',
            'matcvs_meetings' => 'MATCVS Meetings',
            'department_meetings' => 'Department Meetings',
            'evaluations' => 'Evaluations'
        ];

        return $tables[$this->table_name] ?? $this->table_name;
    }

    // Available tables
    public static function getAvailableTables()
    {
        return [
            'cicu_ward_rounds' => 'CICU Ward Rounds',
            'grand_ward_rounds' => 'Grand Ward Rounds',
            'daily_ward_rounds' => 'Daily Ward Rounds',
            'dops_trainees' => 'DOPS Trainees',
            'scientific_meetings' => 'Scientific Meetings',
            'matcvs_meetings' => 'MATCVS Meetings',
            'department_meetings' => 'Department Meetings',
            'evaluations' => 'Evaluations'
        ];
    }
}