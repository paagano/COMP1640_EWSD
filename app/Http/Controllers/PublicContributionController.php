<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicContributionController extends Controller
{
    /**
     * Display the specified contribution details (for PUBLISHED contributions ONLY).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fetch the contribution by ID and ensure it's published
        $contribution = \App\Models\Contribution::where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();

        // Return a view to display the contribution details
        return view('public.contributions.show', compact('contribution'));
    }
}
