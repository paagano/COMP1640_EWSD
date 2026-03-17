<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Contribution;

class ContributionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW SELECTED CONTRIBUTION
    |--------------------------------------------------------------------------
    */

    public function show(Contribution $contribution)
    {
        // Optional: Restrict viewing if needed
        // if (!in_array($contribution->status, ['selected','published'])) {
        //     abort(403, 'Only selected or published contributions can be viewed.');
        // }

        return view('manager.contributions.show', compact('contribution'));
    }


    /*
    |--------------------------------------------------------------------------
    | MARK CONTRIBUTION AS PUBLISHED
    |--------------------------------------------------------------------------
    */

    public function publish(Contribution $contribution)
    {
        // Ensure only selected contributions can be published
        if ($contribution->status !== 'selected') {
            return redirect()->back()
                ->with('error', 'Only selected contributions can be published.');
        }

        $contribution->update([
            'status' => 'published',
            'published_at' => now(), // Store publication date
        ]);

        return redirect()
            ->route('manager.dashboard')
            ->with('success', 'Contribution marked as Published successfully.');
    }
}