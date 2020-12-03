<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;

class AttachmentController
{
    public function showFile($path)
    {
        return response(Storage::disk('s3')->get($path))
                ->header('Content-Type', Storage::disk('s3')->mimeType($path));
    }
}
