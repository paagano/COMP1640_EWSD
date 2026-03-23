<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupabaseStorage
{
    /**
     * MAIN ENTRY POINT
     * Automatically switches between local and Supabase
     */
    public static function upload($file, $folder = '')
    {
        // LOCAL ENV → use Laravel storage
        if (app()->environment('local')) {
            return self::uploadLocally($file, $folder);
        }

        // CLOUD → use Supabase
        return self::uploadToSupabase($file, $folder);
    }

    /**
     * LOCAL STORAGE (for development)
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
            // Generate filename with optional folder
            $filename = ($folder ? $folder . '/' : '') 
                . Str::random(20) . '.' 
                . $file->getClientOriginalExtension();

            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
                'x-upsert' => 'true',
            ])->withBody(
                file_get_contents($file),
                $file->getMimeType()
            )->put(
                env('SUPABASE_URL') . '/storage/v1/object/' 
                . env('SUPABASE_BUCKET') . '/' 
                . $filename
            );

            // SUCCESS → return public URL
            if ($response->successful()) {
                return env('SUPABASE_URL') . '/storage/v1/object/public/' 
                    . env('SUPABASE_BUCKET') . '/' 
                    . $filename;
            }

            // Log failure
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
}