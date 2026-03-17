<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    /*
    |--------------------------------------------------------------------------
    | Boot Method – Ensure Only One Active Year
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

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