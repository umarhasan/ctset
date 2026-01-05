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

    protected $fillable = ['name','email','password'];
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
}
