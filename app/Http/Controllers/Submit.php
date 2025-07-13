<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Registrant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Submit extends Controller
{
    //
    public function index()
    {
        return view('submission')->with('title', 'Submission Form');
    }
    public function handleSubmission(Request $request)
    {
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
            return redirect()->back()->withErrors([
                'file' => 'Upload gagal: ' . $e->getMessage()
            ]);
        }

        $id = Registrant::where('email', $validatedData['email'])->first()->id ?? null;
        if(Registrant::where('id', $id)->first()->status === 'verified'){
            Submission::create([
                'registrant_id' => $id,
                'file' => $url,
            ]);
            return redirect()->route('submit.index')->with('success', 'Submission berhasil dikirim!');
        }else {
            return redirect()->back()->withErrors(['email' => 'Akun Belum Diverifikasi']);
        }
    }
}
