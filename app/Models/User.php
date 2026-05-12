<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI PINDAH KE SINI (DI LUAR CASTS) ---

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'leader_id');
    }

    public function joinedProjects() 
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    public function tasks() 
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }
}
