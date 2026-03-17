<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'export_type',
        'record_count',
        'exported_at',
    ];

    protected $casts = [
        'exported_at' => 'datetime',
    ];
}