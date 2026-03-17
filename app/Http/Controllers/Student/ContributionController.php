<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Contribution;
use App\Models\AcademicYear;
use App\Models\Image;
use App\Models\User;
use App\Mail\ContributionSubmitted;
use Carbon\Carbon;

class ContributionController extends Controller
{
    // --------------------------------------------------------------------------------------------
    //  LIST CONTRIBUTIONS: Students can view a list of their contributions with status indicators.
    // --------------------------------------------------------------------------------------------
    public function index()
    {
        $contributions = Auth::user()
            ->contributions()
            ->latest()
            ->get();

        return view('student.contributions.index', compact('contributions'));
    }

  
    // --------------------------------------------------------------------------------------------------------------------------------
    //  SHOW: Students can view details of their contribution, including the content summary and any feedback from faculty coordinators.
    // --------------------------------------------------------------------------------------------------------------------------------
    
    public function show(Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);

        return view('student.contributions.show', compact('contribution'));
    }

    // ------------------------------------------------------------------------------------------------------
    //  DOWNLOAD WORD DOCUMENT: Students can download the Word document they uploaded for their contribution.
    // ------------------------------------------------------------------------------------------------------
    
    public function download(Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);

        if (!$contribution->word_document_path) {
            abort(404, 'Ooops, Document not found.');
        }

        if (!Storage::disk('public')->exists($contribution->word_document_path)) {
            abort(404, 'Ooops, File does not exist.');
        }

        return Storage::disk('public')->download($contribution->word_document_path);
    }

    // ---------------------------------------------------------------------------------------------------------------------
    //  CREATE: Students can access a form to submit a new contribution, but only IF the submission deadline has not passed.
    // ---------------------------------------------------------------------------------------------------------------------

    public function create()
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        if (!$academicYear || Carbon::today()->gt($academicYear->submission_closure_date)) {
            return redirect()
                ->route('student.contributions.index')
                ->with('error', 'Submissions are closed for this academic year.');
        }

        return view('student.contributions.create');
    }

    // -----------------------------------------------------------------------------------------------------
    //  STORE: Handles the submission of a new contribution, including file uploads and email notifications.
    // -----------------------------------------------------------------------------------------------------
    
    public function store(Request $request)
    {
        // Check if the submission deadline has passed for the active academic year. If it has, prevent submission and show an error message.
        $academicYear = AcademicYear::where('is_active', true)->first();

        // If no active academic year is found or if today's date is past the submission closure date, redirect back with an error message.
        if (!$academicYear || Carbon::today()->gt($academicYear->submission_closure_date)) {
            return redirect()
                ->route('student.contributions.index')
                ->with('error', 'Submission deadline has passed.');
        }

        // Validate input data and files. Ensure the student has agreed to the terms before allowing submission. File validation includes checking for Word document formats and image types/sizes.
        $request->validate([
            'title' => 'required|string|max:255', 
            'content_summary' => 'required|string',
            'word_document' => 'required|mimes:doc,docx|max:5120', // Max 5MB, only Word documents
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'agreed_terms' => 'required|accepted', // Ensures students agree to terms before submission
        ]);

        // Store the Word document in the "public/contributions/documents" directory and save the contribution record in the database with a "submitted" status.
        $documentPath = $request->file('word_document')
            ->store('contributions/documents', 'public');

        // Create the contribution record in the database
        $contribution = Contribution::create([
            'title' => $request->title,
            'content_summary' => $request->content_summary,
            'word_document_path' => $documentPath,
            'status' => 'submitted',
            'student_id' => Auth::id(),
            'faculty_id' => Auth::user()->faculty_id,
            'academic_year_id' => $academicYear->id,
            'agreed_terms' => true,
        ]);

        // --------------------------------------------------------------------------------------------------
        //  STORE IMAGES: If the student uploaded any images, store them and associate with the contribution.
        // --------------------------------------------------------------------------------------------------

        // Check if images were uploaded and store each image in the "public/contributions/images" directory, creating an Image record for each one linked to the contribution.
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $path = $image->store('contributions/images', 'public');

                Image::create([
                    'contribution_id' => $contribution->id,
                    'image_path' => $path,
                ]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //  EMAIL NOTIFICATION TO FACULTY COORDINATORS: After a contribution is submitted, send an email notification to all Marketing Coordinators of the student's faculty.
        // ------------------------------------------------------------------------------------------------------------------------------------------------------------------

        try {

            // Reload model with relationships used in the email template
            $contribution = Contribution::with([
                'student',
                'faculty',
                'academicYear'
            ])->find($contribution->id);

            // Find all Marketing Coordinators for the contribution's faculty
            $coordinators = User::role('Marketing Coordinator')
                ->where('faculty_id', $contribution->faculty_id)
                ->get();

            if ($coordinators->count() > 0) {

                foreach ($coordinators as $coordinator) {

                    Mail::to($coordinator->email)
                        ->send(new ContributionSubmitted($contribution));

                    Log::info('Contribution submission email sent.', [
                        'contribution_id' => $contribution->id,
                        'coordinator_email' => $coordinator->email,
                    ]);
                }

            } else {

                Log::warning('No Marketing Coordinator found for faculty.', [
                    'faculty_id' => $contribution->faculty_id,
                ]);
            }

        } catch (\Exception $e) {

            Log::error('Failed to send contribution submission email.', [
                'contribution_id' => $contribution->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('student.contributions.index')
            ->with('success', 'Contribution submitted successfully.');
    }

    // ---------------------------------------------------------------------------------------------------------------------------
    //  EDIT: Students can access a form to edit their contribution, but only if it is still in "submitted" or "commented" status.
    // ---------------------------------------------------------------------------------------------------------------------------
    public function edit(Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);
        $this->authorizeEditable($contribution);

        return view('student.contributions.edit', compact('contribution'));
    }

    // -------------------------------------------------------------------------------------------------
    //  UPDATE: Handles the update of a contribution, including file replacements and new image uploads.
    // -------------------------------------------------------------------------------------------------
    
    public function update(Request $request, Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);
        $this->authorizeEditable($contribution);

        $request->validate([
            'title' => 'required|string|max:255',
            'content_summary' => 'required|string',
            'word_document' => 'nullable|mimes:doc,docx|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($request->hasFile('word_document')) {

            if ($contribution->word_document_path) {
                Storage::disk('public')->delete($contribution->word_document_path);
            }

            $documentPath = $request->file('word_document')
                ->store('contributions/documents', 'public');

            $contribution->word_document_path = $documentPath;
        }

        $contribution->update([
            'title' => $request->title,
            'content_summary' => $request->content_summary,
        ]);

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $path = $image->store('contributions/images', 'public');

                Image::create([
                    'contribution_id' => $contribution->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('student.contributions.show', $contribution)
            ->with('success', 'Contribution updated successfully.');
    }

    // -------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //  DELETE: Allows students to delete their contribution, but only if it is still in "submitted" or "commented" status. This also deletes associated files and images.
    // -------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
    public function destroy(Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);
        $this->authorizeEditable($contribution);

        if ($contribution->word_document_path) {
            Storage::disk('public')->delete($contribution->word_document_path);
        }

        foreach ($contribution->images as $image) {

            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $contribution->delete();

        return redirect()
            ->route('student.contributions.index')
            ->with('success', 'Contribution deleted successfully.');
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------
    //  AUTHORIZATION HELPERS: Ensure students can only access and modify their own contributions, and only when they are in an editable state.
    // ----------------------------------------------------------------------------------------------------------------------------------------

    private function authorizeOwnership(Contribution $contribution)
    {
        if ($contribution->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function authorizeEditable(Contribution $contribution)
    {
        if (!in_array($contribution->status, ['submitted', 'commented'])) {
            return redirect()
                ->route('student.contributions.index')
                ->with('error', 'This contribution can no longer be modified.')
                ->send();
        }
    }
}