<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorage
{
    public static function upload($file)
    {
        try {
            // Generate unique filename
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

            // Upload to Supabase (IMPORTANT: no /public/ here)
            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
            ])->withBody(
                file_get_contents($file),
                $file->getMimeType()
            )->put(
                env('SUPABASE_URL') . '/storage/v1/object/' . env('SUPABASE_BUCKET') . '/' . $filename
            );

            // If upload successful → return PUBLIC URL
            if ($response->successful()) {
                return env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $filename;
            }

            // Log error instead of breaking app
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
