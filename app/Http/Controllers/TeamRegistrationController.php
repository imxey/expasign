<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Validation\Rule; 

class TeamRegistrationController extends Controller
{
    /**
     * Menampilkan halaman form registrasi.
     */
    public function index()
    {
        return Inertia::render('Regist/index'); // Pastikan nama view-nya sesuai
    }

    /**
     * Fungsi untuk menyimpan file dan mengembalikan URL-nya.
     * Biar rapi dan ngga berulang-ulang.
     */
    private function storeFile(Request $request, string $key, string $folder): ?string
    {
        if ($request->hasFile($key)) {
            $file = $request->file($key);
            $fileName = $folder . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            try {
                Storage::disk('s3')->put($fileName, file_get_contents($file));
                return Storage::disk('s3')->url($fileName);
            } catch (\Exception $e) {
                // Jika gagal upload, kita throw exception biar transaksinya di-rollback
                throw new \Exception("Gagal mengupload file: " . $key);
            }
        }
        return null;
    }

    /**
     * Menangani proses registrasi tim.
     */
    public function handleRegistration(Request $request)
    {
        // 1. Cek periode pendaftaran
        if (Carbon::now()->lt(Carbon::parse('2025-08-18 00:00:01'))) {
            return response()->json(['message' => 'Pendaftaran belum dibuka'], 422);
        }

        if (Carbon::now()->gt(Carbon::parse('2025-08-28 23:59:59'))) {
            return response()->json(['message' => 'Pendaftaran sudah ditutup'], 422);
        }

        // 2. Validasi data yang masuk
        try {
            $validatedData = $request->validate([
                'team_name' => 'required|string|max:255|unique:teams,team_name',
                'category' => 'required|in:lkti,business_plan,poster_design',
                'payment_method' => 'required|in:auto,transfer',
                'isEdu' => 'required|boolean',
                'receipt' => 'required_if:payment_method,transfer|file|mimes:jpg,jpeg,png,pdf|max:2048',

                'members' => 'required|array|min:1|max:3',
                'members.*.name' => 'required|string|max:255',
                'members.*.nim' => 'required|numeric|unique:participants,nim',
                'members.*.email' => 'required|string|email|max:255|unique:participants,email',
                'members.*.phone' => 'required|string|max:20',
                'members.*.school' => 'required|string|max:255',
                'members.*.igLink' => 'required|url',
                'members.*.followExpa' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'members.*.followEdu' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'members.*.followMp' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'members.*.repostSg' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->errors()], 422);
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // 3. Logika Nominal dan Kode Unik
            $baseNominal = match ($validatedData['category']) {
                'lkti' => 1000,
                'business_plan' => 2000,
                'poster_design' => 3000,
            };
            
            $finalNominal = $baseNominal;
            $uniqueCode = null;

            if ($request->payment_method === 'auto') {
                do {
                    $uniqueCode = rand(100, 999);
                    $finalNominal = $baseNominal + $uniqueCode;
                } while (Team::where('nominal', $finalNominal)->exists());
            }
            
            // 4. Upload bukti bayar jika metode transfer
            $receiptPath = null;
            if ($request->payment_method === 'transfer') {
                $receiptPath = $this->storeFile($request, 'receipt', 'receipts');
            }

            // 5. Buat data Tim
            $team = Team::create([
                'id' => Str::uuid(),
                'team_name' => $validatedData['team_name'],
                'category' => $validatedData['category'],
                'nominal' => $finalNominal,
                'code' => $uniqueCode,
                'receipt_path' => $receiptPath,
                'isEdu' => $validatedData['isEdu'],
                'status' => $request->payment_method === 'auto' ? 'pending' : 'pending', // Status awal
            ]);

            // 6. Buat data tiap Participant
            foreach ($request->members as $index => $memberData) {
                // Upload semua file bukti untuk tiap anggota
                $followExpaPath = $this->storeFile($request, "members.{$index}.followExpa", 'proofs');
                $followEduPath = $this->storeFile($request, "members.{$index}.followEdu", 'proofs');
                $followMpPath = $this->storeFile($request, "members.{$index}.followMp", 'proofs');
                $repostSgPath = $this->storeFile($request, "members.{$index}.repostSg", 'proofs');

                Participant::create([
                    'id' => Str::uuid(),
                    'team_id' => $team->id,
                    'name' => $memberData['name'],
                    'nim' => $memberData['nim'],
                    'email' => $memberData['email'],
                    'phone' => $memberData['phone'],
                    'school' => $memberData['school'],
                    'igLink' => $memberData['igLink'],
                    'followExpa' => $followExpaPath,
                    'followEdu' => $followEduPath,
                    'followMp' => $followMpPath,
                    'repostSg' => $repostSgPath,
                    'role' => $index === 0 ? 'leader' : 'member', // Anggota pertama jadi leader
                ]);
            }

            // Jika semua berhasil, commit transaksi
            DB::commit();

            // 7. Beri response sesuai metode pembayaran
            if ($request->payment_method === 'auto') {
                return response()->json([
                    'success' => 'Pendaftaran tim berhasil!',
                    'redirect' => '/payment?team_id=' . $team->id . '&nominal=' . $team->nominal,
                ]);
            } else {
                // Kirim notif WA untuk verifikasi manual
                Http::withHeaders(['api_key' => env('API_KEY')])->post('https://api.expasign-edutime.site/send', [
                    'chatId' => env('WA_NUMBER'),
                    'message' => "*PENDAFTARAN TIM BARU (TRANSFER)*\n\nNama Tim: {$team->team_name}\nKategori: {$team->category}\nNominal: {$team->nominal}\n\nSilahkan buka admin panel untuk verifikasi pembayaran.",
                ]);
                return response()->json(['success' => 'Pendaftaran tim berhasil! Mohon tunggu verifikasi dari admin yaa.']);
            }

        } catch (\Throwable $e) {
            // Jika ada error, batalkan semua
            DB::rollBack();
            Log::error("Team Registration Failed: " . $e->getMessage() . " on line " . $e->getLine());
            return response()->json(['message' => 'Oops, terjadi kesalahan di server. Coba lagi nanti ya. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menangani callback dari payment gateway.
     */
    public function handleCallback(Request $request)
    {
        // Logika callback disesuaikan dengan data dari payment gateway
        $nominal = (int) $request->input('etc.amount_to_display');
        
        $team = Team::where('nominal', $nominal)->where('status', 'pending')->first();

        if (!$team) {
            return response()->json(['message' => 'Tim dengan nominal tersebut tidak ditemukan atau sudah diverifikasi.'], 404);
        }

        // Update status tim menjadi verified
        $team->update(['status' => 'verified']);

        // Kirim notif WA
        Http::withHeaders(['api_key' => env('API_KEY')])->post('https://api.expasign-edutime.site/send', [
            'chatId' => env('WA_NUMBER'),
            'message' => "*PEMBAYARAN TIM TERVERIFIKASI (OTOMATIS)*\n\nNama Tim: {$team->team_name}\nKategori: {$team->category}\nNominal: {$team->nominal}\nStatus: Terverifikasi\n\nData tim sudah lunas.",
        ]);

        return response()->json(['message' => 'Callback berhasil diproses, status tim telah diupdate.']);
    }
}
