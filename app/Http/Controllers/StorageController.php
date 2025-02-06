<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class StorageController extends Controller
{
    public function fetchFile($filename)
    {
        if (Storage::disk('public')->exists($filename)) {
            return Response::make(Storage::disk('public')->get($filename), 200, [
                'Content-Type' => Storage::disk('public')->mimeType($filename),
                'Content-Disposition' => 'inline; filename="'.basename($filename).'"',
            ]);
        }

        if (Storage::disk('r2')->exists($filename)) {
            $fileContents = Storage::disk('r2')->get($filename);
            $mimeType = Storage::disk('r2')->mimeType($filename);

            return Response::make($fileContents, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="'.basename($filename).'"',
            ]);
        }

        return abort(404);
    }
}
