<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorage
{
    public static function upload($file)
    {
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
            'Content-Type' => 'application/octet-stream',
        ])->put(
            env('SUPABASE_URL') . '/storage/v1/object/' . env('SUPABASE_BUCKET') . '/' . $filename,
            file_get_contents($file)
        );

        if ($response->successful()) {
            return env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $filename;
        }

        return null;
    }
}
