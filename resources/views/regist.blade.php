@extends('layouts.app')

@section('content')

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center py-12" style="font-family: 'Orbitron', monospace; font-weight: 400;">
    <div class="relative z-10 p-8 md:p-12 bg-gray-800 rounded-lg shadow-2xl max-w-2xl w-11/12 border border-gray-700">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center text-blue-400">Daftar Expasign x Edutime 2025</h2>

        {{-- Flash message --}}
        @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-3 rounded-lg mb-6 text-center">
            {{ session('success') }}
        </div>
        @endif

        {{-- Error message --}}
        @if($errors->any())
        <div class="bg-red-500 text-white px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('regist.handle') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-300 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" placeholder="Input Nama Lengkap" value="{{ old('name') }}" required class="form-input w-full bg-gray-700 text-sm text-sm border border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-white px-4 py-2 rounded-md">
                </div>
                <div>
                    <label for="email" class="block text-gray-300 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" placeholder="Input Email" value="{{ old('email') }}" required class="form-input w-full bg-gray-700 text-sm border border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-white px-4 py-2 rounded-md">
                </div>
                <div>
                    <label for="phone" class="block text-gray-300 text-sm font-bold mb-2">Nomor Telepon</label>
                    <input type="number" name="phone" id="phone" placeholder="Input Nomor Telepon" value="{{ old('phone') }}" required class="form-input w-full bg-gray-700 text-sm border border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-white px-4 py-2 rounded-md">
                </div>
                <div>
                    <label for="school" class="block text-gray-300 text-sm font-bold mb-2">Asal Sekolah/Universitas</label>
                    <input type="text" name="school" id="school" placeholder="Input Asal Sekolah/Universitas" value="{{ old('school') }}" required class="form-input w-full bg-gray-700 text-sm border border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-white px-4 py-2 rounded-md">
                </div>
            </div>

            <div>
                <label for="nim" class="block text-gray-300 text-sm font-bold mb-2">NIM (Nomor Induk Mahasiswa)</label>
                <input type="number" name="nim" id="nim" placeholder="Input NIM Anda" value="{{ old('nim') }}" required class="form-input w-full bg-gray-700 text-sm border border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-white px-4 py-2 rounded-md">
            </div>

            <div>
                <label for="category" class="block text-gray-300 text-sm font-bold mb-2">Kategori Lomba</label>
                <select name="category" id="category" required class="form-select w-full bg-gray-700 text-sm border border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-white px-4 py-2 rounded-md">
                    <option value="">Pilih kategori lomba</option>
                    <option value="category1" {{ old('category') === 'category1' ? 'selected' : '' }}>LKTI</option>
                    <option value="category2" {{ old('category') === 'category2' ? 'selected' : '' }}>Esai</option>
                    <option value="category3" {{ old('category') === 'category3' ? 'selected' : '' }}>Desain Poster</option>
                </select>
            </div>

            <div class="">
                <p class="block text-gray-300 text-sm font-bold mb-3">METODE PEMBAYARAN</p>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex items-center gap-2">
                        <input type="radio" name="payment_method" id="auto" value="auto" {{ old('payment_method') === 'auto' ? 'checked' : '' }} class="form-radio h-5 w-5 text-blue-500 focus:ring-blue-500">
                        <label for="auto" class="text-gray-300 text-sm cursor-pointer">Auto Payment</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="radio" name="payment_method" id="transfer" value="transfer" {{ old('payment_method') === 'transfer' ? 'checked' : '' }} class="form-radio h-5 w-5 text-blue-500 focus:ring-blue-500">
                        <label for="transfer" class="text-gray-300 text-sm cursor-pointer">Transfer Bank</label>
                    </div>

                </div>
            </div>

            <div class="hidden" id="paymentDetails">
                <label for="receipt" class="block text-gray-300 text-sm font-bold mb-2">Upload Bukti Pembayaran</label>
                <input type="file" name="receipt" id="receipt" required class="form-input w-full bg-gray-700 text-sm border border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-white p-2 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                <p class="text-gray-400 text-xs mt-1">Ukuran maksimal file: 2MB. Format: JPG, PNG, PDF.</p>
            </div>


            <input type="hidden" name="isEdu" value="0">
            <div class="flex gap-2 text-sm">
                <input type="checkbox" name="isEdu" id="isEdu" value="1" {{ old('isEdu') ? 'checked' : '' }} class="form-checkbox h-5 w-5 p-2 text-blue-500 rounded focus:ring-blue-500">
                <label for="isEdu" class="text-gray-300 text-sm font-bold">Bersedia hadir pada edutime tanggal 32 Agustus 2069?</label>
            </div>

            <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-full text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl mt-4">
                Daftar Sekarang
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            const paymentDetailsDiv = document.getElementById('paymentDetails');
            const receiptInput = document.getElementById('receipt');

            function togglePaymentDetails() {
                // Cek apakah radio button 'transfer' yang terpilih
                if (document.getElementById('transfer').checked) {
                    paymentDetailsDiv.classList.remove('hidden');
                    receiptInput.setAttribute('required', 'required'); // Wajibkan field 'receipt'
                } else {
                    paymentDetailsDiv.classList.add('hidden');
                    receiptInput.removeAttribute('required'); // Hilangkan kewajiban field 'receipt'
                    receiptInput.value = ''; // Opsional: Bersihkan nilai input file jika disembunyikan
                }
            }

            // Tambahkan event listener ke setiap radio button
            paymentMethodRadios.forEach(radio => {
                radio.addEventListener('change', togglePaymentDetails);
            });

            // Panggil fungsi saat halaman pertama kali dimuat
            // Ini penting untuk menangani kasus old('payment_method') yang mungkin sudah terpilih
            togglePaymentDetails();
        });
    </script>
</body>
@endsection