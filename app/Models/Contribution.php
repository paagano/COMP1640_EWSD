<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// This model represents a contribution made by a student for the annual magazine. 
// Each contribution includes a title, a summary of the content, a path to the uploaded Word document, and a status indicating whether the contribution is submitted, commented on, selected, or rejected. 
// The model also defines relationships to the student who made the contribution, the faculty it belongs to, the academic year during which it was submitted, any associated images, and comments made by coordinators. 
// This structure allows for easy management and retrieval of contributions, as well as facilitating communication between students and coordinators through comments. The model also includes a field to track whether the student has agreed to the terms and conditions, which is important for ensuring compliance with the submission guidelines. Additionally, a 'published_at' timestamp can be used to track when a contribution has been published in the magazine, providing a clear record of the contribution's lifecycle from submission to publication.
class Contribution extends Model
{
    protected $fillable = [
        'title',
        'content_summary',
        'word_document_path',
        'status',
        'student_id',
        'faculty_id',
        'academic_year_id',
        'agreed_terms',
        'published_at',
       
    ];

    // Relationships
    // Each contribution belongs to a student, a faculty, and an academic year.
    // A contribution can have many images and comments associated with it.
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Each contribution belongs to a faculty, which helps categorize the contribution based on the student's field of study.
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    // Each contribution belongs to an academic year, which allows the system to organize contributions based on the year they were submitted.
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // A contribution can have multiple images associated with it, which can be used to enhance the content of the contribution when published in the magazine.
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    // A contribution can have multiple comments from coordinators, which allows for feedback and communication between the student and the coordinators regarding the contribution.
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}