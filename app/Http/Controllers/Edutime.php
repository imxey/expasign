<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Registrant;
use Inertia\Inertia;

class Edutime extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Edutime');
    }
    public function handleEdutime(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric',
            'nim' => 'required|numeric',
            'address' => 'required|string|max:255',
            'school' => 'required|string|max:255',
        ]);
        $registrant = Registrant::where('email', $validatedData['email'])->first();
        if ($registrant) {
            return response()->json(['status' => 'error', 'message' => 'Email sudah terdaftar.']);
        }
        try{
            Registrant::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'nim' => $validatedData['nim'],
                'school' => $validatedData['school'],
                'category' => 'edutime', 
                'nominal' => 0, 
                'status' => 'verified',
                'isExpa' => 0,
                'isEdu' => 1,
            ]);
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => env('FONNTE_NUMBER'),
                'message' => "*PENDAFTAR EDUTIME BARU*\n\nNama: {$validatedData['name']}\nPhone: {$validatedData['phone']}\nEmail: {$validatedData['email']}\nNIM: {$validatedData['nim']}\nAsal Universitas: {$validatedData['school']}",
                'countryCode' => '62',
            ]);
            return response()->json(['status' => 'success', 'message' => 'Pendaftaran berhasil!']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage()]);
        }
    }
}
