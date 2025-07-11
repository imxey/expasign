<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class File extends Controller
{
    
    public function handleUpload(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Validasi gagal, periksa file yang diupload',
                'errors' => $validator->errors()
                ], 422
            ); 
        }

        
        $file = $request->file('image');
        $fileName = 'images/' . Str::uuid() . '.' . $file->getClientOriginalExtension();

        try {
            
            Storage::disk('s3')->put($fileName, file_get_contents($file));
            $url = Storage::disk('s3')->url($fileName);

            
            return response()->json(
                [
                'success' => true,
                'message' => 'File berhasil di-upload',
                'data' => [
                    'url' => $url,
                    'file_name' => $fileName
                ]
                ], 201
            ); 

        } catch (\Exception $e) {
            
            return response()->json(
                [
                'success' => false,
                'message' => 'Failed',
                'error' => $e->getMessage()
                ], 500
            ); 
        }
    }
}
