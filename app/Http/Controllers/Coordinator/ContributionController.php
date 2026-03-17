<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContributionStatusUpdated;

class ContributionController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LIST CONTRIBUTIONS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $facultyId = Auth::user()->faculty_id;

        $query = Contribution::with('student')
            ->where('faculty_id', $facultyId);

        /*
        |--------------------------------------------------------------------------
        | SEARCH (Title OR Student Name)
        |--------------------------------------------------------------------------
        */

        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('student', function ($s) use ($request) {
                        $s->where('name', 'like', '%' . $request->search . '%');
                  });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | TITLE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | STUDENT FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->student) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->student . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->status) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $contributions = $query
            ->latest()
            ->paginate(8);

        return view('coordinator.contributions.index', compact('contributions'));
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW CONTRIBUTION
    |--------------------------------------------------------------------------
    */

    public function show(Contribution $contribution)
    {
        $contribution->load(['student','images']);

        return view('coordinator.contributions.show', compact('contribution'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS + SAVE COMMENT + SEND EMAIL
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, Contribution $contribution)
    {

        /*
        |--------------------------------------------------------------------------
        | Back-End Validation: Prevent editing/review if already published
        |--------------------------------------------------------------------------
        */

        if ($contribution->status === 'published') {
            return redirect()
                ->route('coordinator.contributions.index')
                ->with('error', 'Published contributions cannot be modified.');
        }


        $request->validate([
            'comment_text' => 'required|string',
            'status' => 'required|in:commented,selected,rejected'
        ]);


        /*
        |--------------------------------------------------------------------------
        | Save Comment
        |--------------------------------------------------------------------------
        */

        Comment::create([

            'contribution_id' => $contribution->id,

            'coordinator_id' => Auth::id(),

            'comment_text' => $request->comment_text

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Contribution Status
        |--------------------------------------------------------------------------
        */

        $contribution->update([
            'status' => $request->status
        ]);


        /*
        |--------------------------------------------------------------------------
        | Send Email to Student
        |--------------------------------------------------------------------------
        */

        Mail::to($contribution->student->email)
            ->send(new ContributionStatusUpdated(
                $contribution,
                $request->comment_text
            ));


        return redirect()
            ->route('coordinator.contributions.index')
            ->with('success','Review submitted successfully and student notified.');

    }

}