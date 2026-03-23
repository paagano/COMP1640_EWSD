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

            // Upload to Supabase
            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
            ])->withBody(
                file_get_contents($file),
                $file->getMimeType()
            )->put(
                env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $filename
            );

            // Success
            if ($response->successful()) {
                return env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $filename;
            }

            // Debug response (VERY IMPORTANT for now)
            dd([
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            // Catch unexpected errors
            dd([
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }
}
