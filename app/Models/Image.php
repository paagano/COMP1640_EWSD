<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// This model represents an image associated with a contribution. 
// Each image belongs to a specific contribution and has a file path that points to where the image is stored. 
// The model includes a relationship to the Contribution model, allowing for easy retrieval of the contribution that an image is associated with. This structure enables the system to manage and display images related to contributions effectively, enhancing the visual appeal of the magazine when contributions are published. Additionally, it allows for efficient storage and organization of images within the system, ensuring that they are properly linked to their respective contributions for easy access and
class Image extends Model
{
    protected $fillable = [
        'contribution_id',
        'image_path',
        'alt_text',
    ];

    // Each image belongs to a specific contribution, which allows the system to easily retrieve the contribution that an image is associated with.
    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }
}