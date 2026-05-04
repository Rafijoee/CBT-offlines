@extends('layouts.app')

@section('title', 'Ujian')

@section('content')

<div class="space-y-6">

    {{-- HEADER UJIAN --}}
    <div class="flex justify-between items-center rounded-2xl p-6 text-white
                bg-gradient-to-r from-blue-500 to-purple-500 shadow-lg">

        <div>
            <h1 class="text-3xl font-bold">Matematika</h1>
            <p class="text-sm opacity-90">
                ⏱ Sisa waktu: <span id="timer">89:59</span>
            </p>
        </div>

        <div class="flex gap-4">
            <button id="markBtn"
                class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-xl font-semibold">
                ⚑ Tandai Ragu
            </button>

            <button
                class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-xl font-semibold">
                ⏹ Selesai Ujian
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- SOAL --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6 space-y-6">

            <div class="flex items-center gap-4">
                <span class="bg-blue-500 text-white px-4 py-2 rounded-xl font-bold">1</span>
                <h2 class="text-xl font-semibold">Soal Nomor 1</h2>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl">
                Sebuah taman berbentuk persegi panjang dengan panjang 20 m dan lebar 15 m...
            </div>

            {{-- OPSI --}}
            <div class="space-y-4">

                @foreach(['A. 14 Pohon','B. 24 Pohon','C. 34 Pohon','D. 44 Pohon'] as $key => $opsi)
                    <button
                        class="option w-full text-left border rounded-xl p-4 hover:bg-blue-50 transition"
                        data-index="{{ $key }}"
                    >
                        {{ $opsi }}
                    </button>
                @endforeach

            </div>

            <div class="flex justify-between pt-6">
                <button class="px-4 py-2 bg-gray-200 rounded-xl">← Sebelumnya</button>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-xl">Selanjutnya →</button>
            </div>

        </div>

        {{-- DAFTAR SOAL --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex items-center gap-3 mb-4">
                <div class="bg-blue-500 text-white p-2 rounded-lg">☰</div>
                <h3 class="font-bold text-lg">Daftar Soal</h3>
            </div>

            <div class="grid grid-cols-5 gap-2 mb-6">
                @for($i=1;$i<=40;$i++)
                    <button class="number-btn bg-gray-200 rounded-lg py-2 text-sm"
                            data-number="{{ $i }}">
                        {{ $i }}
                    </button>
                @endfor
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-300 rounded"></div>
                    Belum dijawab
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    Sudah dijawab
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-yellow-400 rounded"></div>
                    Tandai ragu-ragu
                </div>
            </div>

        </div>
    </div>

</div>

@endsection
