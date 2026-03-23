<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupabaseStorage
{
    /**
     * MAIN ENTRY POINT
     */
    public static function upload($file, $folder = '')
    {
        if (env('USE_SUPABASE', false)) {
            return self::uploadToSupabase($file, $folder);
        }

        return self::uploadLocally($file, $folder);
    }

    /**
     * UPLOAD WITH FIXED NAME (for latest magazine)
     */
    public static function uploadAs($file, $path)
    {
        if (env('USE_SUPABASE', false)) {
            return self::uploadToSupabaseAs($file, $path);
        }

        return self::uploadLocallyAs($file, $path);
    }

    /**
     * LOCAL UPLOAD (random name)
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
     * LOCAL UPLOAD (fixed name)
     */
    protected static function uploadLocallyAs($file, $path)
    {
        try {
            // Delete old file
            Storage::disk('public')->delete($path);

            // Store new file
            Storage::disk('public')->put($path, file_get_contents($file));

            return asset('storage/' . $path);

        } catch (\Exception $e) {
            \Log::error('Local uploadAs failed', [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * SUPABASE UPLOAD (random name)
     */
    protected static function uploadToSupabase($file, $folder)
    {
        try {
            $filename = ($folder ? $folder . '/' : '')
                . Str::random(20) . '.'
                . $file->getClientOriginalExtension();

            return self::uploadToSupabaseAs($file, $filename);

        } catch (\Exception $e) {
            \Log::error('Supabase upload exception', [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * SUPABASE UPLOAD (fixed path) - used for latest magazine
     */
    protected static function uploadToSupabaseAs($file, $path)
    {
        try {
            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');
            $bucket = env('SUPABASE_BUCKET');

            $response = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
                'x-upsert' => 'true',
            ])->attach(
                'file',
                fopen($file->getRealPath(), 'r'),
                basename($path)
            )->post(
                $supabaseUrl . '/storage/v1/object/' . $bucket . '/' . $path
            );

            if ($response->successful()) {
                return $supabaseUrl . '/storage/v1/object/public/' . $bucket . '/' . $path;
            }

            // dd($response->status(), $response->body()); // DEBUG

            \Log::error('Supabase uploadAs failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            \Log::error('Supabase uploadAs exception', [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * DELETE FILE (works for both local + supabase)
     */
    public static function delete($pathOrUrl)
    {
        if (env('USE_SUPABASE', false)) {
            return self::deleteFromSupabase($pathOrUrl);
        }

        return Storage::disk('public')->delete($pathOrUrl);
    }

    /**
     * DELETE FROM SUPABASE
     */
    protected static function deleteFromSupabase($pathOrUrl)
    {
        try {
            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');
            $bucket = env('SUPABASE_BUCKET');

            // If full URL, extract path
            if (strpos($pathOrUrl, $supabaseUrl) === 0) {
                $pathOrUrl = str_replace(
                    $supabaseUrl . '/storage/v1/object/public/' . $bucket . '/',
                    '',
                    $pathOrUrl
                );
            }

            $response = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
            ])->delete(
                $supabaseUrl . '/storage/v1/object/' . $bucket . '/' . $pathOrUrl
            );

            if (!$response->successful()) {
                \Log::error('Supabase delete failed', [
                    'path' => $pathOrUrl,
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