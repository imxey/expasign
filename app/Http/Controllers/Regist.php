<?php

namespace App\Http\Controllers;

use App\Models\Registrant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;


class Regist extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Regist/index');
    }
    public function handleRegist(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:registrants',
            'phone' => 'required|numeric',
            'nim' => 'required|numeric',
            'school' => 'required|string|max:255',
            'category' => 'required|in:category1,category2,category3',
            'payment_method' => 'required|in:auto,transfer',
            'isEdu' => 'nullable|boolean', 
            'receipt' => 'required_if:payment_method,transfer|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $validatedData['isEdu'] = $request->boolean('isEdu'); 

        $url = null;

        if ($request->payment_method === 'transfer') {
            $file = $request->file('receipt');
            $fileName = 'images/' . Str::uuid() . '.' . $file->getClientOriginalExtension();

            try {
                Storage::disk('s3')->put($fileName, file_get_contents($file));
                $url = Storage::disk('s3')->url($fileName);
            } catch (\Exception $e) {
                return response()->json(['receipt' => 'Upload gagal: ' . $e->getMessage()]);
            }
        }

        $baseNominal = match ($validatedData['category']) {
            'category1' => 1000,
            'category2' => 2000,
            'category3' => 3000,
        };

        if ($validatedData['payment_method'] === 'auto') {
            do {
                $kodeUnik = rand(100, 999);
                $nominalFinal = $baseNominal + $kodeUnik;
                $sudahAda = Registrant::where('nominal', $nominalFinal)->exists();
            } while ($sudahAda);

            $validatedData['nominal'] = $nominalFinal;
            $validatedData['code'] = $kodeUnik;
        } else {
            $validatedData['nominal'] = $baseNominal;
            $validatedData['code'] = null;
        }

        $registrant = Registrant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'nim' => $validatedData['nim'],
            'school' => $validatedData['school'],
            'category' => $validatedData['category'],
            'nominal' => $validatedData['nominal'],
            'code' => $validatedData['code'],
            'receipt' => $url,
            'isEdu' => $validatedData['isEdu'], 
        ]);

        if ($validatedData['payment_method'] === 'auto') {
            return response()->json([
                'success' => 'Pendaftaran berhasil!',
                'redirect' => '/payment?id=' . $registrant->id . '&name=' . urlencode($registrant->name) . '&nominal=' . $registrant->nominal,
            ]);
        } else {
            Http::withHeaders([
                'api_key' => env('API_KEY'),
            ])->post(' https://api.expasign-edutime.site/send', [
                'chatId' => env('WA_NUMBER'),
                'message' => "*PENDAFTAR EXPASIGN BARU*\n\nNama: {$registrant->name}\nPhone: {$registrant->phone}\nEmail: {$registrant->email}\nNIM: {$registrant->nim}\nSekolah/Universitas: {$registrant->school}\nKategori: {$registrant->category}\nNominal: {$registrant->nominal}\n\nSilahkan buka https://expasign-edutime.site/admin dan verifikasi pembayaran",
            ]);
            return response()->json(['success' => 'Pendaftaran berhasil!']);
        }
    }

    public function handleCallback(Request $request)
    {
        $nominal = (int) $request->input('etc.amount_to_display');
        $category = '';
        $db = Registrant::where('nominal', $nominal)->first();
        $code = $db->code ?? null;
        if($nominal - $code  === 3000){
            $category = 'category3';
        } elseif($nominal - $code === 2000) {
            $category = 'category2';
        } elseif ($nominal - $code === 1000) {
            $category = 'category1';
        }else {
            return response()->json(['message' => 'Nominal tidak valid', 'dbNominal' => $db->nominal, 'dbCode' => $db->code, 'nominalReceived' => $nominal, 'category' => $category, 'dbCategory' => $db->category], 400);
        }

        if (!$db) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($db->nominal  === $nominal && $db->status !== 'Verified' && $db->category === $category) {
            $db->update(['status' => 'Verified']);
        }else{
            return response()->json(['message' => 'Data tidak sesuai', 'dbNominal' => $db->nominal, 'dbCode' => $db->code, 'nominalReceived' => $nominal, 'category' => $category, 'dbCategory' => $db->category, ], 400);
        }

        $response = Http::withHeaders([
            'api_key' => env('API_KEY'),
        ])->post(' https://api.expasign-edutime.site/send', [
            'chatId' => env('WA_NUMBER'),
            'message' => "*PENDAFTAR EXPASIGN BARU (OTOMATIS)*\n\nNama: {$db->name}\nPhone: {$db->phone}\nEmail: {$db->email}\nNIM: {$db->nim}\nSekolah/Universitas: {$db->school}\nKategori: {$db->category}\nNominal: {$db->nominal}\nstatus: {$db->status}\n",
        ]);

        return response()->json(['message' => 'Callback berhasil diproses']);
    }

}
