<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = ['name','email','password','profile_image','signature_image','bio'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at'=>'datetime'];

    // Get all trainees (users with 'trainee' role)
    public function scopeTrainees($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', 'trainee');
        });
    }

    // Check if user is trainee
    public function isTrainee(): bool
    {
        return $this->hasRole('trainee');
    }

    public function examInvitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'user_id');
    }
    // Get all trainers (users with 'trainer' role)
    public function scopeTrainers($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', 'trainer');
        });
    }

    // Get all admins
    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', 'admin');
        });
    }

    public function examAttempts(){
        return $this->hasMany(ExamAttempt::class);
    }

    public function tabs(){
        return $this->hasMany(ProfileTab::class);
    }

    public function grandWardRounds(): HasMany
    {
        return $this->hasMany(GrandWardRound::class, 'user_id');
    }

    // Consultant ke ward rounds
    public function consultantRounds(): HasMany
    {
        return $this->hasMany(GrandWardRound::class, 'consultant_id');
    }

     // Sections of this form
    public function sections(): HasMany
    {
        return $this->hasMany(Evaluation360Section::class, 'evaluation_id');
    }

    public function shared360Forms()
    {
        return $this->hasMany(Evaluation360FormShare::class,'shared_by');
    }

    // Student → received results
    public function received360Results()
    {
        return $this->hasMany(Evaluation360FormShare::class,'assigned_to')
                    ->where('status','A');
    }

    public function assigned360Forms()
    {
        return $this->hasMany(Evaluation360FormShare::class, 'email', 'email');
    }
    // Student ko jo final approved results mile
    public function completed360Forms()
    {
        return $this->hasMany(Evaluation360FormShare::class, 'assigned_to')
                    ->where('status', 'A');
    }


}
