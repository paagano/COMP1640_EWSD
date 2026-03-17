<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'contribution_id',
        'image_path'
    ];

    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }
}