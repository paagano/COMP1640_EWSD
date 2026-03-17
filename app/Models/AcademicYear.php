<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// This model represents an academic year in the system. 
// It includes fields for the year name, submission closure date, final closure date, and whether it is the active year. 
// The model also contains logic to ensure that only one academic year can be active at a time. 
// When an academic year is set to active, all other years are automatically set to inactive. 
// This ensures that there is always a clear distinction between the current active academic year and any past or future years. 
// The model also defines relationships to contributions made during that academic year, allowing for easy retrieval of related data.
class AcademicYear extends Model
{
    protected $fillable = [
        'year_name',
        'submission_closure_date',
        'final_closure_date',
        'is_active'
    ];

    protected $casts = [
        'submission_closure_date' => 'datetime',
        'final_closure_date'      => 'datetime',
        'is_active'               => 'boolean',
    ];
    
    // Boot Method – Ensure Only One Active Year at a Time
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($academicYear) {

            if ($academicYear->is_active) {
                self::where('id', '!=', $academicYear->id)
                    ->update(['is_active' => false]);
            }

        });
    }

   
    // Relationships  
    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    
    // Query Scopes
    // Get Active Academic Year
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    // Latest by Start Date (optional future use)
    public function scopeLatestFirst(Builder $query)
    {
        return $query->orderByDesc('submission_closure_date');
    }
}