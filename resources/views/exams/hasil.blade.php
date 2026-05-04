@extends('layouts.exam')

@section('content')

<div>

    {{-- HEADER --}}
<div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-3xl p-6 text-white shadow-xl flex justify-between items-center">
    
    <div>
        <h1 class="text-3xl font-bold">Hasil Ujian</h1>
        <p class="opacity-90">
            {{ $userExam->exam->mapel }}
        </p>
    </div>

    <div class="flex gap-3">
        
        <div>
            @if (Auth::user()->role == 'guru')
                <a href="{{ route('dashboard-guru') }}" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm font-semibold inline-block">
                    Dashboard
                </a>
            @else 
                <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm font-semibold inline-block">
                    Dashboard
                </a>
            @endif
        </div>

        <div>
            @hasSection('navbar-logout')
                @yield('navbar-logout')
            @else
                <button class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold">
                    Keluar
                </button>
            @endif
        </div>

    </div>
</div>

    {{-- INFO --}}
    <div class="grid md:grid-cols-3 gap-6 mt-8">
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500 text-sm">Tanggal Ujian</p>
            <p class="font-semibold">
                {{ $userExam->created_at->format('d F Y') }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500 text-sm">Jumlah Soal</p>
            <p class="font-semibold">{{ $result['total'] }} Soal</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500 text-sm">Nilai</p>
            <p class="font-semibold text-blue-600">
                {{ $result['nilai'] }}
            </p>
        </div>
    </div>

    {{-- NILAI DETAIL --}}
    <div class="grid md:grid-cols-3 gap-6 mt-8">

        <div class="md:col-span-2 bg-white rounded-3xl shadow p-8 space-y-6">

            <div class="flex gap-10 items-center">

                <div class="relative w-40 h-40">
                    <div class="absolute inset-0 rounded-full border-[12px] border-blue-500"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <p class="text-4xl font-bold text-blue-600">
                            {{ $result['nilai'] }}
                        </p>
                        <p class="text-gray-500 text-sm">Dari 100</p>
                    </div>
                </div>

                <div class="flex-1 space-y-4">
                    <h2 class="text-xl font-bold">Ringkasan Performa</h2>

                    <div class="bg-green-100 p-3 rounded-xl flex justify-between">
                        <span>✔ Jawaban Benar</span>
                        <span class="text-green-600 font-semibold">
                            {{ $result['benar'] }}
                        </span>
                    </div>

                    <div class="bg-red-100 p-3 rounded-xl flex justify-between">
                        <span>✖ Jawaban Salah</span>
                        <span class="text-red-600 font-semibold">
                            {{ $result['salah'] }}
                        </span>
                    </div>

                    <div class="bg-gray-100 p-3 rounded-xl flex justify-between">
                        <span>– Tidak Dijawab</span>
                        <span class="text-gray-600 font-semibold">
                            {{ $result['kosong'] }}
                        </span>
                    </div>
                </div>

            </div>

            <hr>

            <div class="flex justify-between items-center">
                <div>
                    <p class="font-semibold">Status Kelulusan</p>
                    <p class="text-gray-500 text-sm">
                        Nilai minimum kelulusan: 70
                    </p>
                </div>

                @if($hasil === 'lulus')
                    <span class="bg-green-500 text-white px-4 py-2 rounded-xl font-semibold">
                        🏆 Lulus
                    </span>
                @else
                    <span class="bg-red-500 text-white px-4 py-2 rounded-xl font-semibold">
                        ❌ Tidak Lulus
                    </span>
                @endif
            </div>

        </div>

        {{-- FEEDBACK --}}
        <div class="bg-white rounded-3xl shadow p-6 space-y-4">
            <h2 class="text-xl font-bold">Feedback</h2>

            @if($hasil === 'lulus')
                <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded-xl">
                    <p class="font-semibold">Kerja Bagus!</p>
                    <p class="text-sm text-gray-600">
                        Kamu sudah menguasai materi dengan baik.
                    </p>
                </div>
            @else
                <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded-xl">
                    <p class="font-semibold">Perlu Perbaikan</p>
                    <p class="text-sm text-gray-600">
                        Pelajari kembali materi dan coba lagi.
                    </p>
                </div>
            @endif
        </div>

    </div>

</div>

@endsection