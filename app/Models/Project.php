<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'leader_id', 'status', 'due_date'];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relasi ke User (sebagai Leader)
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    // Relasi ke User (sebagai Anggota/Team Member) - Many to Many
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    // Relasi ke Task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
