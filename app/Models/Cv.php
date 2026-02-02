<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'primary_speciality',
        'summary',
        'completeness',
        'template',
        'is_public',
        'share_token'
    ];

    public function profile()
    {
        return $this->hasOne(CvProfile::class);
    }

    public function educations()
    {
        return $this->hasMany(CvEducationClinical::class)
                    ->where('type','education');
    }

    public function clinicals()
    {
        return $this->hasMany(CvEducationClinical::class)
                    ->where('type','clinical');
    }

    public function researches()
    {
        return $this->hasMany(CvResearchAward::class)
                    ->where('type','research');
    }

    public function awards()
    {
        return $this->hasMany(CvResearchAward::class)
                    ->where('type','award');
    }

    public function milestones()
    {
        return $this->hasMany(CvMilestone::class);
    }

    public function documents()
    {
        return $this->hasMany(CvDocument::class);
    }

    public function activities()
    {
        return $this->hasMany(CvActivity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

        public function milestone()
    {
        return $this->hasMany(CvMilestone::class);
    }
    
}
