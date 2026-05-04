@extends('layouts.app')

@section('title', 'Buat Soal')

@section('content')
<div class="max-w-8xl mx-auto space-y-6">
@if ($errors->any())
<div id="error-alert" class="flex items-start p-4 mb-6 text-red-800 rounded-2xl bg-red-50 border border-red-100 shadow-sm animate-fade-in-down" role="alert">
    <!-- Ikon Error -->
    <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-white rounded-xl shadow-sm">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>

    <!-- Konten Pesan -->
    <div class="ml-4 text-sm font-medium flex-1">
        <span class="font-bold text-red-900 block mb-1">Terjadi Kesalahan!</span>
        <ul class="list-disc list-inside space-y-1 text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Tombol Tutup -->
    <button type="button" onclick="document.getElementById('error-alert').remove()" class="ml-auto -mx-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-100 inline-flex h-8 w-8 transition-colors">
        <span class="sr-only">Close</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
@endif


@if (session('success'))
<div id="success-alert" class="flex items-center p-4 mb-6 text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-100 shadow-sm animate-fade-in-down" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-emerald-500 bg-white rounded-xl shadow-sm">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    <div class="ml-4 text-sm font-medium">
        <span class="font-bold text-emerald-900">Berhasil!</span> {{ session('success') }}
    </div>

    <button type="button" onclick="document.getElementById('success-alert').remove()" class="ml-auto -mx-1.5 bg-emerald-50 text-emerald-500 rounded-lg focus:ring-2 focus:ring-emerald-400 p-1.5 hover:bg-emerald-100 inline-flex h-8 w-8 transition-colors">
        <span class="sr-only">Close</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
@endif


    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm table-auto border-collapse">
            <thead class="bg-emerald-400 text-white">
                <tr>
                    <th class="p-3 text-center w-[4%]">No</th>
                    <th class="p-3 text-left w-[26%]">Soal</th>
                    <th class="p-3 text-left w-[22%]">Jawaban</th>
                    <th class="p-3 text-left w-[34%]">Gambar Jawaban</th>
                    <th class="p-3 text-center w-[6%]">Kunci</th>
                </tr>
            </thead>


            <tbody>
            @foreach ($questions as $q)
                @foreach ($q->answers as $i => $a)
                {{-- @dd($a) --}}
                <tr class="align-top
                    {{ $i === 0 ? 'border-t-2 border-gray-300' : '' }}
                    {{ $i === $q->answers->count() - 1 ? 'border-b-2 border-gray-300' : '' }}
                ">

                    {{-- NO --}}
                    @if ($i === 0)
                    <td rowspan="{{ $q->answers->count() }}"
                        class="p-3 text-center font-semibold align-top">
                        {{ $loop->parent->iteration }}
                    </td>
                    @endif

                    {{-- SOAL --}}
                    @if ($i === 0)
                    <td rowspan="{{ $q->answers->count() }}"
                        class="p-3 align-top space-y-3 font-medium border">
                        <div>{{ $q->question_text }}</div>
                            
                        @if ($q->gambar)
                            <img
                                src="{{ asset('storage/'.$q->gambar) }}"
                                class="max-w-full max-h-48 rounded border"
                            >
                        @endif
                    </td>
                    @endif


                    {{-- JAWABAN --}}
                    <td class="p-3 align-top border ">
                        {{ $a->text }}
                    </td>

                    {{-- GAMBAR JAWABAN --}}
                    <td class="p-3 align-top border">
                        @if ($a->gambar)
                            <img
                                src="{{ asset('storage/'.$a->gambar) }}"
                                class="max-w-full max-h-40 rounded border"
                            >
                        @else
                            <span class="text-gray-400 italic">Tidak ada</span>
                        @endif
                    </td>

                    {{-- KUNCI --}}
                    <td class="p-3 text-center align-top border">
                        @if ($a->true)
                            <span class="text-green-600 text-lg font-bold">✔</span>
                        @endif
                    </td>





                </tr>
                @endforeach
            @endforeach
            </tbody>

        </table>
    </div>


</div>

@endsection