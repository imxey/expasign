@extends('layouts.app')

@section('content')
<body class="p-8">
    <h2 class="text-2xl font-bold mb-4">Upload Bukti Pembayaran</h2>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="text-green-600 mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error message --}}
    @if($errors->any())
        <div class="text-red-500 mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('regist.handle') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
        @csrf

        <input type="text" name="name" placeholder="Enter name" value="{{ old('name') }}" required class="border px-3 py-2 rounded">
        
        <input type="email" name="email" placeholder="Enter email" value="{{ old('email') }}" required class="border px-3 py-2 rounded">
        
        <input type="number" name="phone" placeholder="Enter phone" value="{{ old('phone') }}" required class="border px-3 py-2 rounded">
        
        <input type="text" name="school" placeholder="Enter school" value="{{ old('school') }}" required class="border px-3 py-2 rounded">
        
        <input type="number" name="nim" placeholder="Enter NIM" value="{{ old('nim') }}" required class="border px-3 py-2 rounded">
        
        <select name="category" required class="border px-3 py-2 rounded">
            <option value="">Pilih kategori</option>
            <option value="category1" {{ old('category') === 'category1' ? 'selected' : '' }}>Option 1</option>
            <option value="category2" {{ old('category') === 'category2' ? 'selected' : '' }}>Option 2</option>
            <option value="category3" {{ old('category') === 'category3' ? 'selected' : '' }}>Option 3</option>
        </select>

        <div class="flex flex-col">
            <label for="isEdu" class="mb-1">Apakah kamu bersedia hadir pada edutime tanggal 32 Agustus 2069?</label>
            <input type="hidden" name="isEdu" value="0">
            <input type="checkbox" name="isEdu" value="1" {{ old('isEdu') ? 'checked' : '' }} class="w-5 h-5">
        </div>

        <input type="file" name="receipt" required class="border p-2 rounded">

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Daftar
        </button>
    </form>
</body>
@endsection
