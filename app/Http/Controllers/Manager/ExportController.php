<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ExportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD ZIP
    |--------------------------------------------------------------------------
    */

    public function downloadZip()
    {
        // Get selected contributions
        // $contributions = Contribution::whereIn('status', ['selected', 'published'])
        $contributions = Contribution::whereIn('status', ['selected']) // Only selected contributions for ZIP export
            ->with(['student', 'faculty', 'images'])
            ->get();

        if ($contributions->isEmpty()) {
            return redirect()->back()->with('error', 'No contributions available for ZIP export.');
        }

        $zipFileName = 'selected_contributions_' . now()->format('Ymd_His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            foreach ($contributions as $contribution) {

                $folderName = str_replace(['/', '\\'], '-', $contribution->title);

                /*
                |--------------------------------------------------------------------------
                | ADD WORD DOCUMENT
                |--------------------------------------------------------------------------
                */
                if ($contribution->word_document_path) {

                    $docPath = storage_path('app/public/' . $contribution->word_document_path);

                    if (file_exists($docPath)) {
                        $zip->addFile(
                            $docPath,
                            $folderName . '/Document_' . basename($docPath)
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | ADD IMAGES
                |--------------------------------------------------------------------------
                */
                foreach ($contribution->images as $image) {

                    $imagePath = storage_path('app/public/' . $image->image_path);

                    if (file_exists($imagePath)) {
                        $zip->addFile(
                            $imagePath,
                            $folderName . '/Images/' . basename($imagePath)
                        );
                    }
                }
            }

            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Failed to create ZIP archive.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT CSV
    |--------------------------------------------------------------------------
    */

    public function exportCsv()
    {
        // $contributions = Contribution::whereIn('status', ['selected', 'published'])->get();
        $contributions = Contribution::whereIn('status', ['selected'])->get(); // Only selected contributions for CSV export

        $filename = "selected_contributions.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($contributions) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Title',
                'Content Summary',
                'Student',
                'Faculty',
                'Selected On',
                'Status'
            ]);

            foreach ($contributions as $contribution) {
                fputcsv($file, [
                    $contribution->title,
                    $contribution->content_summary,
                    $contribution->student->name ?? '',
                    $contribution->faculty->name ?? '',
                    $contribution->selected_at,
                    ucfirst($contribution->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PDF
    |--------------------------------------------------------------------------
    */

    public function exportPdf()
    {
        // $contributions = Contribution::whereIn('status', ['selected', 'published'])->get();
        $contributions = Contribution::whereIn('status', ['selected'])->get(); // Only selected contributions for PDF export

        $pdf = Pdf::loadView('manager.exports.pdf', compact('contributions'));

        return $pdf->download('selected_contributions.pdf');
    }
}