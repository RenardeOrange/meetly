<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function publicFile(string $path)
    {
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path);
    }
}
