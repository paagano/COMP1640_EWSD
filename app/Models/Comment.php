<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'contribution_id',
        'coordinator_id',
        'comment_text',
        'commented_at'
    ];

    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }
}