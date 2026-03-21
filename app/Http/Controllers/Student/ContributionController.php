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
            abort(404, 'Ooops, Document not found.');
        }

        if (!Storage::disk('public')->exists($contribution->word_document_path)) {
            abort(404, 'Ooops, File does not exist.');
        }

        return Storage::disk('public')->download($contribution->word_document_path);
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
            'word_document' => 'required|mimes:doc,docx|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'alt_texts' => 'required_with:images|array',
            'alt_texts.*' => 'required|string',
            'agreed_terms' => 'required|accepted',
        ]);

        $documentPath = $request->file('word_document')
            ->store('contributions/documents', 'public');

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

        // SAVE IMAGES
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {

                $path = $image->store('contributions/images', 'public');

                Image::create([
                    'contribution_id' => $contribution->id,
                    'image_path' => $path,
                    'alt_text' => $request->alt_texts[$index] ?? null,
                    'order' => $index
                ]);
            }
        }

        // SEND EMAIL TO FACULTY COORDINATOR
        try {
            $coordinator = User::role('Marketing Coordinator')
                ->where('faculty_id', Auth::user()->faculty_id)
                ->first();

            if ($coordinator && $coordinator->email) {
                Mail::to($coordinator->email)
                    ->send(new ContributionSubmitted($contribution));

                Log::info('Contribution email sent to coordinator', [
                    'coordinator_id' => $coordinator->id,
                    'email' => $coordinator->email,
                    'contribution_id' => $contribution->id
                ]);
            } else {
                Log::warning('No coordinator found for faculty', [
                    'faculty_id' => Auth::user()->faculty_id
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send contribution email', [
                'error' => $e->getMessage()
            ]);
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
            'word_document' => 'nullable|mimes:doc,docx|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'alt_texts' => 'required_with:images|array',
            'alt_texts.*' => 'required|string',
        ]);

        if ($request->hasFile('word_document')) {
            if ($contribution->word_document_path) {
                Storage::disk('public')->delete($contribution->word_document_path);
            }

            $contribution->word_document_path = $request->file('word_document')
                ->store('contributions/documents', 'public');
        }

        $contribution->update([
            'title' => $request->title,
            'content_summary' => $request->content_summary,
        ]);

        if ($request->delete_images) {
            foreach ($request->delete_images as $id) {
                $image = Image::find($id);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('replace_images')) {
            foreach ($request->replace_images as $id => $file) {
                if ($file) {
                    $image = Image::find($id);
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path);

                        $path = $file->store('contributions/images', 'public');

                        $image->update([
                            'image_path' => $path,
                            'alt_text' => $request->replace_alt_text[$id] ?? $image->alt_text,
                        ]);
                    }
                }
            }
        }

        if ($request->image_order) {
            $orderArray = explode(',', $request->image_order);
            foreach ($orderArray as $order => $id) {
                Image::where('id', $id)->update(['order' => $order]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {

                $path = $image->store('contributions/images', 'public');

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
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
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