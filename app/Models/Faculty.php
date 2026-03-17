<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Contribution;

class Faculty extends Model
{
    protected $fillable = [
        'name',
        'guest_email',
        'guest_password'
    ];

    // Users
    public function users()
    {
        return $this->hasMany(User::class);
    }


    // Contributions
    // A faculty can have many contributions submitted by students belonging to that faculty. 
    // This relationship allows us to easily retrieve all contributions associated with a specific faculty, which can be useful for coordinators and managers when reviewing submissions and generating reports based on faculties.
    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }


    // Faculty Coordinator
    // Each faculty has one Marketing Coordinator.
    // We filter the faculty users by the role "Marketing Coordinator".
    public function coordinator()
    {
        return $this->hasOne(User::class, 'faculty_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Marketing Coordinator');
            });
    }
}