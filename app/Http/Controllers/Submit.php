<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Registrant;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class Submit extends Controller
{
    //
    public function index()
    {
        return Inertia::render('submission');
    }
    public function handleSubmission(Request $request)
    {
        try{
            $registrant = Registrant::where('email', $request->input('email'))->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Email tidak ditemukan.']);
        }
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255|exists:registrants,email',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:120000',
        ]);
        $file = $request->file('file');
        $fileName = 'images/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        try {
            Storage::disk('s3')->put($fileName, file_get_contents($file));
            $url = Storage::disk('s3')->url($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 'message' => 'Upload gagal: ' . $e->getMessage()
            ]);
        }
        try{
            $id = Registrant::where('email', $validatedData['email'])->first()->id ?? null;
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Email tidak ditemukan.']);
        }
        
        if (Registrant::where('id', $id)->first()->status === 'verified') {
            if (Registrant::where('id', $id)->first()->isSubmit) {
                return response()->json(['status' => 'error', 'message' => 'You have already submitted your file.']);
            }
            Submission::create([
                'registrant_id' => $id,
                'file' => $url,
            ]);
            Registrant::where('id', $id)->update(['isSubmit' => true]);
            return response()->json(['status' => 'success', 'message' => 'Submission berhasil dikirim!']);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'Akun Belum Diverifikasi']);
        }
    }
}
