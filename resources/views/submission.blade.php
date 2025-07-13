@extends('layouts.app')

@section('content')

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center py-12" style="font-family: 'Orbitron', monospace; font-weight: 400;">
    <div class="relative z-10 p-8 md:p-12 bg-gray-800 rounded-lg shadow-2xl max-w-2xl w-11/12 border border-gray-700">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center text-blue-400">Submission Expasign x Edutime 2025</h2>

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

        <form action="{{ route('submit.handle') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
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
            </div>
            <div class="" id="paymentDetails">
                <label for="receipt" class="block text-gray-300 text-sm font-bold mb-2">Upload File</label>
                <input type="file" name="file" id="receipt" required class="form-input w-full bg-gray-700 text-sm border border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-white p-2 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                <p class="text-gray-400 text-xs mt-1">Ukuran maksimal file: 100MB. Format: JPG, PNG, PDF.</p>
            </div>
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-full text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl mt-4">
                Submit
            </button>
        </form>
    </div>

    <script>
        
    </script>
</body>
@endsection