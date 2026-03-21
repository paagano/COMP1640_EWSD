<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ContributionDownloadController extends Controller
{
    public function download(Contribution $contribution)
    {
        // Increment download count
        $contribution->increment('download_count');

        // Return file download
        return response()->download(
            storage_path('app/public/' . $contribution->word_document_path)
        );
    }
}