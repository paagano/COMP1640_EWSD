<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

// The User model represents a user in the University of Glasgow's Annual Magazine System. 
// It extends the Authenticatable class, which provides authentication features, and uses traits for API tokens, notifications, and role management. 
// The model includes fillable attributes for name, email, password, and faculty_id, which allows for mass assignment of these fields. 
// It also defines hidden attributes for password and remember_token to protect sensitive information when the model is serialized. 
// The casts property is used to specify how certain attributes should be cast when accessed, such as casting email_verified_at and last_login_at to datetime, and password to a hashed value. 
// The model defines relationships to the Faculty model (indicating that a user belongs to a faculty) and to the Contribution model (indicating that a user can have many contributions, specifically as a student). 
// This structure allows for efficient management of users, their roles, and their associations with faculties and contributions within the system, facilitating authentication, authorization, and organization of user-related data.
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