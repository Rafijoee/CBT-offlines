@extends('layouts.app')

@section('title', 'Akses Diblokir')

@section('content')
<div class="min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md text-center space-y-6">

        {{-- ICON --}}
        <div class="text-red-500 text-6xl">
            🚫
        </div>

        {{-- TITLE --}}
        <h1 class="text-2xl font-bold text-gray-800">
            Kamu Keluar dari Ujian
        </h1>

        {{-- DESC --}}
        <p class="text-gray-600">
            Sistem mendeteksi kamu keluar dari halaman ujian.<br>
            Untuk melanjutkan, silakan masukkan kode dari guru.
        </p>

        {{-- ERROR --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif


        {{-- FORM --}}
        <form action="{{ route('exam.resetStatus', ['id' => $id]) }}" method="POST" class="space-y-4">
            @csrf
            <input type="text"
                name="code"
                placeholder="Masukkan kode guru"
                required
                class="w-full px-4 py-3 border rounded-xl text-center text-lg tracking-widest focus:ring-2 focus:ring-blue-400 focus:outline-none">

            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-xl transition">
                🔓 Masuk Kembali ke Ujian
            </button>
        </form>

        {{-- OPTIONAL INFO --}}
        <p class="text-xs text-gray-400">
            Jika terjadi kesalahan, hubungi pengawas ujian.
        </p>

    </div>

</div>
@endsection