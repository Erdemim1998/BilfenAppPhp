<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FileUploadController
{
    public function UploadFile(Request $request): JsonResponse
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $directoryName = $request->input('directoryName');
            $filePath = $file->storeAs($directoryName, $file->getClientOriginalName(), 'public');
            $fileUrl = asset('storage/' . $filePath);

            return response()->json([
                'message' => 'File uploaded successfully',
                'filePath' => $fileUrl,
            ], 200);
        }

        return response()->json(['message' => 'File upload failed'], 400);
    }
}
