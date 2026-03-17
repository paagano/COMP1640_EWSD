<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// This model represents a comment made by a coordinator on a contribution. 
// Each comment is associated with a specific contribution and a coordinator (user). 
// The model includes fields for the comment text, the timestamp of when the comment was made, and relationships to both the contribution and the coordinator. 
// This allows for easy retrieval of comments related to a contribution and the coordinator who made the comment, facilitating communication and feedback within the system.
class Comment extends Model
{
    protected $fillable = [
        'contribution_id',
        'coordinator_id',
        'comment_text',
        'commented_at'
    ];

    // Relationships
    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }

    // Each comment belongs to a coordinator, which allows the system to identify which user (coordinator) made the comment on the contribution.
    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }
}