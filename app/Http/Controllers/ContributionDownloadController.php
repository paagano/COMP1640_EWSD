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

        $doc = $contribution->word_document_path;

        // If it's already a URL (Supabase OR local URL)
        if ($doc && strpos($doc, 'http') === 0) {
            return redirect()->away($doc);
        }

        // Otherwise treat as local file path
        if (!$doc || !Storage::disk('public')->exists($doc)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($doc);
    }
}