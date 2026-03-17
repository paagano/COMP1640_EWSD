<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

  
    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'email',
        'password',
        'faculty_id',
    ];

    // The attributes that should be hidden for serialization.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // The attributes that should be cast.
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];

   
    // Relationships
    // A user belongs to a faculty, which allows us to associate users with their respective faculties. 
    // This relationship is important for managing user access and permissions based on their faculty affiliation, as well as for organizing contributions and other activities within the system according to faculties.
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    // A user can have many contributions, which allows us to track all the contributions made by a specific user. 
    // This relationship is essential for students who submit contributions, as it enables them to view and manage their submissions, and for coordinators and managers to review and evaluate contributions based on the users who submitted them.
    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'student_id');
    }
}