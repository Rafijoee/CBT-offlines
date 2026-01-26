@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class=" mx-auto space-y-10 ">

    <!-- UJIAN YANG BISA DIIKUTI -->
    @foreach ($exams as $exam)
    <section>
        <h2 class="text-xl font-bold mb-4">▶️ Ujian yang Bisa Diikuti</h2>

            
        <div class="grid md:grid-cols-3 gap-6 ">
            <!-- CARD -->
            <div class="flex flex-col justify-between bg-white rounded-xl shadow">
                <div class="p-5 space-y-3">
                    <div class="flex gap-2">
                        <div class="flex items-center justify-center w-[50px]  bg-[#C1E2FF] rounded-lg">
                            <img src="{{ asset('storage/images/icon_exam.png') }}" alt="Icon Exam" loading="lazy" class="w-9 object-contain">
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="font-semibold text-lg leading-none pb-1">{{ $exam->mapel }}</h3>
                            
                            <!-- Baris untuk label menit dan soal -->
                            <div class="flex gap-2 text-sm">
                                <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded">{{ $exam->time }} Menit</span>
                                <span class="px-2 py-1 bg-green-100 text-green-600 rounded">{{ $exam->soal }} Soal</span>
                            </div>
                        </div>
                    </div>
    
                    
                    <p class="text-sm text-[#6E7781]">
                        Ujian Bab Aljabar dan Geometri.
                    </p>
                    <div class="flex flex-col">
                        <span class="text-[#6E7781] text-sm">
                            Dibuka : 
                        </span>
                        <div class="flex justify-between items-center text-sm">
                            
                            <span class="text-[#131927]">
                                {{ \Carbon\Carbon::parse($exam->created_at)->locale('id')->translatedFormat('l, d F Y') }}, 
                                {{ \Carbon\Carbon::parse($exam->created_at)->format('H.i') }} - 
                                {{ \Carbon\Carbon::parse($exam->closed_time)->addMinutes($exam->time)->format('H.i') }} WIB
                            </span>
                            <div class="bg-blue-500 hover:bg-[#0063CC] rounded-lg px-4 py-1  text-white hover:text-gray-100">
                                <button class="text-sm">
                                    ▶ Mulai
                                </button>

                            </div>
                        </div>   
                    </div>

                    
                    <div class="text-xs text-[#057CFF]  bg-[#C1E2FF]/50 rounded p-2 ">
                    ℹ️ Tips: Baca soal dengan teliti!
                </div>
            </div>
            
            
            </div>
        </section>
        @endforeach
        
        <!-- LANJUTKAN UJIAN -->
        <section>
            <h2 class="text-xl font-bold mb-4">🔄 Lanjutkan Ujian</h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow p-5 space-y-4">
                    <div class="flex justify-between">
                        <h3 class="font-semibold">Ilmu Pengetahuan Sosial</h3>
                    <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded">
                        Sedang Berlangsung
                    </span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-orange-400 h-2 rounded-full" style="width:30%"></div>
                </div>

                <button class="w-full bg-orange-400 hover:bg-orange-500 text-white py-2 rounded-lg">
                    ▶ Mulai
                </button>
            </div>

            <div class="bg-white rounded-xl shadow p-5 space-y-4">
                <div class="flex justify-between">
                    <h3 class="font-semibold">Penjaskes</h3>
                    <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded">
                        Sedang Berlangsung
                    </span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-orange-400 h-2 rounded-full" style="width:90%"></div>
                </div>

                <button class="w-full bg-orange-400 hover:bg-orange-500 text-white py-2 rounded-lg">
                    ▶ Mulai
                </button>
            </div>
        </div>
    </section>

</div>

@endsection
