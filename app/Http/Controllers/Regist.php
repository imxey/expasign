<?php

namespace App\Http\Controllers;

use App\Models\Registrant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class Regist extends Controller
{
    //
    public function index()
    {
        return view('regist');
    }
    public function handleRegist(Request $request)
{
    if($request['auto'] == 1 ) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:registrants',
            'phone' => 'required|numeric',
            'nim' => 'required|numeric',
            'school' => 'required|string|max:255',
            'category' => 'required|string|in:category1,category2,category3',
            'isEdu' => 'required|boolean',
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('receipt');
        $fileName = 'images/' . Str::uuid() . '.' . $file->getClientOriginalExtension();

        try {
            Storage::disk('s3')->put($fileName, file_get_contents($file));
            $url = Storage::disk('s3')->url($fileName);
            echo('File berhasil di-upload');
        } catch (\Exception $e) {
            echo('Failed to upload file: ' . $e->getMessage());
        }

        if (request('category') === 'category1') {
            $validatedData['nominal'] = 1000;
        } elseif (request('category') === 'category2') {
            $validatedData['nominal'] = 2000;
        } elseif (request('category') === 'category3') {
            $validatedData['nominal'] = 3000;
        }

        do {
            $kodeUnik = rand(100, 999);
            $nominalFinal = $validatedData['nominal'] + $kodeUnik;
            $sudahAda = Registrant::where('nominal', $nominalFinal)->exists();
        } while ($sudahAda);

        $validatedData['nominal'] = $nominalFinal;


        $registrant = Registrant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'nim' => $validatedData['nim'],
            'school' => $validatedData['school'],
            'code' => $kodeUnik,
            'category' => $validatedData['category'],
            'nominal' => $validatedData['nominal'],
            'receipt' => $url,
            'isEdu' => $validatedData['isEdu'],
        ]);

        $id = $registrant->id;

        return view('saweria', [
            'id' => $id,
            'name'=> $validatedData['name'],
            'nominal' => $validatedData['nominal'],
        ]);
    }
    else if($request['transfer'] == 1 ) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:registrants',
            'phone' => 'required|numeric',
            'nim' => 'required|numeric',
            'school' => 'required|string|max:255',
            'category' => 'required|string|in:category1,category2,category3',
            'isEdu' => 'required|boolean',
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('receipt');
        $fileName = 'images/' . Str::uuid() . '.' . $file->getClientOriginalExtension();

        try {
            Storage::disk('s3')->put($fileName, file_get_contents($file));
            $url = Storage::disk('s3')->url($fileName);
            echo('File berhasil di-upload');
        } catch (\Exception $e) {
            echo('Failed to upload file: ' . $e->getMessage());
        }

        if (request('category') === 'category1') {
            $validatedData['nominal'] = 1000;
        } elseif (request('category') === 'category2') {
            $validatedData['nominal'] = 2000;
        } elseif (request('category') === 'category3') {
            $validatedData['nominal'] = 3000;
        }

        $registrant = Registrant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'nim' => $validatedData['nim'],
            'school' => $validatedData['school'],
            'category' => $validatedData['category'],
            'nominal' => $validatedData['nominal'],
            'receipt' => $url,
            'isEdu' => $validatedData['isEdu'],
        ]);

        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => env('FONNTE_NUMBER'),
            'message' => "*PENDAFTAR BARU*\n\nNama: {$registrant->name}\nPhone: {$registrant->phone}\nEmail: {$registrant->email}\nNIM: {$registrant->nim}\nSekolah/Universitas: {$registrant->school}\nKategori: {$registrant->category}\nNominal: {$registrant->nominal}\n\nSilahkan buka https://expasign-edutime.site/admin dan verifikasi pembayaran",
            'countryCode' => '62',
        ]);

        return redirect()->back()->with('success', 'Regist successfully!');
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
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => env('FONNTE_NUMBER'),
            'message' => "*PENDAFTAR BARU (OTOMATIS)*\n\nNama: {$db->name}\nPhone: {$db->phone}\nEmail: {$db->email}\nNIM: {$db->nim}\nSekolah/Universitas: {$db->school}\nKategori: {$db->category}\nNominal: {$db->nominal}\nstatus: {$db->status}\n",
            'countryCode' => '62',
        ]);

        return response()->json(['message' => 'Callback berhasil diproses']);
    }

}
