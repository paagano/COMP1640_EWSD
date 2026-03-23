<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
<<<<<<< HEAD
use Illuminate\Support\Facades\Storage;
=======
>>>>>>> 8ee02d48b0ed6145be52f059aba7b7469bdcc4cb
use Illuminate\Support\Str;

class SupabaseStorage
{
<<<<<<< HEAD
    public static function upload($file, $folder = '')
    {
        // If LOCAL → use Laravel storage
        if (app()->environment('local')) {
            return self::uploadLocally($file, $folder);
        }

        // Else → use Supabase
        return self::uploadToSupabase($file, $folder);
    }

    /**
     * LOCAL STORAGE (for dev)
     */
    protected static function uploadLocally($file, $folder)
    {
        try {
            $path = $file->store($folder, 'public');

            return asset('storage/' . $path);

        } catch (\Exception $e) {
            \Log::error('Local upload failed', [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * SUPABASE STORAGE (for production)
     */
    protected static function uploadToSupabase($file, $folder)
    {
        try {
            $filename = ($folder ? $folder . '/' : '') . Str::random(20) . '.' . $file->getClientOriginalExtension();

=======
    public static function upload($file)
    {
        try {
            // Generate unique filename
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

            // Upload to Supabase (IMPORTANT: no /public/ here)
>>>>>>> 8ee02d48b0ed6145be52f059aba7b7469bdcc4cb
            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
                'x-upsert' => 'true',
            ])->withBody(
                file_get_contents($file),
                $file->getMimeType()
            )->put(
                env('SUPABASE_URL') . '/storage/v1/object/' . env('SUPABASE_BUCKET') . '/' . $filename
            );

<<<<<<< HEAD
=======
            // If upload successful → return PUBLIC URL
>>>>>>> 8ee02d48b0ed6145be52f059aba7b7469bdcc4cb
            if ($response->successful()) {
                return env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $filename;
            }

<<<<<<< HEAD
=======
            // Log error instead of breaking app
>>>>>>> 8ee02d48b0ed6145be52f059aba7b7469bdcc4cb
            \Log::error('Supabase upload failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            \Log::error('Supabase exception', [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 8ee02d48b0ed6145be52f059aba7b7469bdcc4cb
