<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['project_id', 'user_id', 'title', 'description', 'status', 'due_date'];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relasi balik ke Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relasi ke User yang ngerjain task ini (Assignee)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Comment
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
