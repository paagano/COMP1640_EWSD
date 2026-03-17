<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// This model represents a log of export activities performed by managers in the system. 
// Each log entry includes the ID of the manager who performed the export, the type of export (e.g., contributions, reports), the number of records exported, and the timestamp of when the export occurred. 
// This model allows for tracking and auditing of export activities, providing insights into who is exporting data and how frequently exports are happening. It can be useful for monitoring system usage and ensuring that exports are being performed by authorized personnel. Additionally, it can help in identifying any potential issues or irregularities in export activities, contributing to the overall security and integrity of the system.
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