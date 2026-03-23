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
use App\Services\SupabaseStorage;
use Carbon\Carbon;

class ContributionController extends Controller
{
    public function index()
    {
        $contributions = Auth::user()
            ->contributions()
            ->latest()
            ->get();

        return view('student.contributions.index', compact('contributions'));
    }

    public function show(Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);
        return view('student.contributions.show', compact('contribution'));
    }

    public function download(Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);

        if (!$contribution->word_document_path) {
            abort(404, 'Document not found.');
        }

        $doc = $contribution->word_document_path;

        if (strpos($doc, 'http') === 0) {
            return redirect()->away($doc);
        }

        if (!Storage::disk('public')->exists($doc)) {
            abort(404, 'File does not exist.');
        }

        return Storage::disk('public')->download($doc);
    }

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

    public function store(Request $request)
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        if (!$academicYear || Carbon::today()->gt($academicYear->submission_closure_date)) {
            return redirect()
                ->route('student.contributions.index')
                ->with('error', 'Submission deadline has passed.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content_summary' => 'required|string',
            'word_document' => 'required|mimes:doc,docx,pdf|max:10240',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'alt_texts' => 'required_with:images|array',
            'alt_texts.*' => 'required|string',
            'agreed_terms' => 'required|accepted',
        ]);

        $documentPath = SupabaseStorage::upload(
            $request->file('word_document'),
            'documents'
        );

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

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {

                $path = SupabaseStorage::upload($image, 'contributions');

                Image::create([
                    'contribution_id' => $contribution->id,
                    'image_path' => $path,
                    'alt_text' => $request->alt_texts[$index] ?? null,
                    'order' => $index
                ]);
            }
        }

        return redirect()
            ->route('student.contributions.index')
            ->with('success', 'Contribution submitted successfully.');
    }

    public function edit(Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);
        $this->authorizeEditable($contribution);

        return view('student.contributions.edit', compact('contribution'));
    }

    public function update(Request $request, Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);
        $this->authorizeEditable($contribution);

        $request->validate([
            'title' => 'required|string|max:255',
            'content_summary' => 'required|string',
            'word_document' => 'nullable|mimes:doc,docx,pdf|max:10240',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'alt_texts' => 'required_with:images|array',
            'alt_texts.*' => 'required|string',
        ]);

        // UPDATE DOCUMENT
        if ($request->hasFile('word_document')) {

            if ($contribution->word_document_path) {
                SupabaseStorage::delete($contribution->word_document_path);
            }

            $contribution->word_document_path = SupabaseStorage::upload(
                $request->file('word_document'),
                'documents'
            );
        }

        $contribution->update([
            'title' => $request->title,
            'content_summary' => $request->content_summary,
        ]);

        // DELETE IMAGES
        if ($request->delete_images) {
            foreach ($request->delete_images as $id) {
                $image = Image::find($id);
                if ($image) {

                    if ($image->image_path) {
                        SupabaseStorage::delete($image->image_path);
                    }

                    $image->delete();
                }
            }
        }

        // REPLACE IMAGES
        if ($request->hasFile('replace_images')) {
            foreach ($request->replace_images as $id => $file) {
                if ($file) {
                    $image = Image::find($id);
                    if ($image) {

                        if ($image->image_path) {
                            SupabaseStorage::delete($image->image_path);
                        }

                        $image->update([
                            'image_path' => SupabaseStorage::upload($file, 'contributions'),
                            'alt_text' => $request->replace_alt_text[$id] ?? $image->alt_text,
                        ]);
                    }
                }
            }
        }

        // NEW IMAGES
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {

                $path = SupabaseStorage::upload($image, 'contributions');

                Image::create([
                    'contribution_id' => $contribution->id,
                    'image_path' => $path,
                    'alt_text' => $request->alt_texts[$index] ?? null,
                    'order' => $index
                ]);
            }
        }

        return redirect()
            ->route('student.contributions.show', $contribution)
            ->with('success', 'Contribution updated successfully.');
    }

    public function destroy(Contribution $contribution)
    {
        $this->authorizeOwnership($contribution);
        $this->authorizeEditable($contribution);

        foreach ($contribution->images as $image) {

            if ($image->image_path) {
                SupabaseStorage::delete($image->image_path);
            }

            $image->delete();
        }

        if ($contribution->word_document_path) {
            SupabaseStorage::delete($contribution->word_document_path);
        }

        $contribution->delete();

        return redirect()
            ->route('student.contributions.index')
            ->with('success', 'Contribution deleted successfully.');
    }

    private function authorizeOwnership(Contribution $contribution)
    {
        if ($contribution->student_id !== Auth::id()) {
            abort(403);
        }
    }

    private function authorizeEditable(Contribution $contribution)
    {
        if (!in_array($contribution->status, ['submitted', 'commented'])) {
            return redirect()->route('student.contributions.index')->send();
        }
    }
}