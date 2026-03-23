<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupabaseStorage
{
    /**
     * MAIN ENTRY POINT
     * Uses explicit flag instead of environment detection
     */
    public static function upload($file, $folder = '')
    {
        // USE SUPABASE (controlled via .env)
        if (env('USE_SUPABASE', false)) {
            return self::uploadToSupabase($file, $folder);
        }

        // DEFAULT → LOCAL STORAGE
        return self::uploadLocally($file, $folder);
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

            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');
            $bucket = env('SUPABASE_BUCKET');

            $response = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
                'x-upsert' => 'true',
            ])->withBody(
                file_get_contents($file),
                $file->getMimeType()
            )->put(
                $supabaseUrl . '/storage/v1/object/' . $bucket . '/' . $filename
            );

            // SUCCESS → return public URL
            if ($response->successful()) {
                return $supabaseUrl . '/storage/v1/object/public/' . $bucket . '/' . $filename;
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

    // OPTIONAL DELETE METHOD
    // Can be used to clean up old files if needed. Supabase doesn't auto-delete old files on update.
        public static function delete($fileUrl)
    {
        try {
            // Only delete if Supabase is enabled
            if (!env('USE_SUPABASE', false)) {
                return false;
            }

            // Ignore if not a valid Supabase URL
            if (!$fileUrl || strpos($fileUrl, env('SUPABASE_URL')) !== 0) {
                return false;
            }

            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');
            $bucket = env('SUPABASE_BUCKET');

            // Extract file path after /public/{bucket}/
            $path = str_replace(
                $supabaseUrl . '/storage/v1/object/public/' . $bucket . '/',
                '',
                $fileUrl
            );

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
            ])->delete(
                $supabaseUrl . '/storage/v1/object/' . $bucket . '/' . $path
            );

            if (!$response->successful()) {
                \Log::error('Supabase delete failed', [
                    'file' => $fileUrl,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }

            return $response->successful();

        } catch (\Exception $e) {
            \Log::error('Supabase delete exception', [
                'error' => $e->getMessage()
            ]);
        }

        return false;
    }
}