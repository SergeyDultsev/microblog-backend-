<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController
{
    public function getImage($fileName)
    {
        $filePath = storage_path('/app/public/images/' . $fileName);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response()->file($filePath);
    }
}
