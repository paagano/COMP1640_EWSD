<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\SupabaseStorage;

class MagazineController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'magazine' => 'required|mimes:pdf|max:10240'
        ]);

        $file = $request->file('magazine');
        $disk = config('filesystems.default');

        $filename = 'latest-magazine.pdf';

        // 🌐 CLOUD (Supabase)
        if ($disk === 'supabase') {

            // Delete old file
            SupabaseStorage::delete('magazine/' . $filename);

            // Upload new file
            $url = SupabaseStorage::uploadAs($file, 'magazine/' . $filename);

            return back()->with('success', 'Magazine uploaded successfully');

        }

        // 💻 LOCAL
        Storage::disk('public')->delete('magazine/' . $filename);

        $file->storeAs('magazine', $filename, 'public');

        return back()->with('success', 'Magazine uploaded successfully');
    }
}