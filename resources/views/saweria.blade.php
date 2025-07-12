@extends('layouts.app')
@section('content')
<body class="bg-purple-50 flex items-center justify-center min-h-screen px-4">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
        <h1 class="text-2xl font-bold text-purple-700">Hai, {{ $name }}! 💖</h1>

        <p class="mt-4 text-gray-700">
            Untuk menyelesaikan pendaftaranmu, silakan lakukan pembayaran melalui Saweria dengan klik tombol di bawah ini:
        </p>

        <p class="mt-4 text-lg text-gray-800 font-semibold">
            Nominal yang harus dibayar:
        </p>

        <div class="text-3xl font-bold text-pink-600 mt-2 tracking-wide">
            Rp {{ number_format($nominal, 0, ',', '.') }}
        </div>

        <div class="mt-3 bg-yellow-100 border border-yellow-400 text-yellow-800 text-sm rounded-md px-4 py-2 font-medium">
            ⚠️ <strong>Perhatian:</strong> <br>
            <span class="text-red-600 font-bold uppercase">
                3 angka terakhir <u>WAJIB SAMA</u> persis!
            </span><br>
            Sistem hanya memverifikasi pembayaran jika nominal <u>sesuai 100%</u>!
        </div>

        <a href="https://saweria.co/Xeyla" target="_blank"
           class="mt-6 inline-block bg-purple-700 text-white py-3 px-6 rounded-full hover:bg-purple-800 transition-all duration-200 font-semibold">
            🔗 Bayar Sekarang via Saweria
        </a>

        <p class="mt-4 text-gray-500 text-xs">
            Setelah kamu bayar, sistem akan otomatis mencocokkan dan memverifikasi transaksimu 💫
        </p>
    </div>
</body>
@endsection