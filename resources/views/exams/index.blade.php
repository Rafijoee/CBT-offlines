@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2 class="text-2xl font-bold mb-4">Exams</h2>
<a href="{{ route('create-exams') }}" class="block p-2 hover:bg-gray-100">
    Create Exams
</a>
@foreach ($exams as $exam)
<form action="{{ route('check-exams', ['exams' => $exam->id]) }}" method="POST">
    @csrf

    
    <body class="bg-[#050b18] flex items-center justify-center min-h-screen">
        
        <div class="group relative p-[1px] rounded-2xl overflow-hidden transition-all duration-500 hover:scale-105 active:scale-95 cursor-pointer">
            
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-600 opacity-30 group-hover:opacity-100 animate-pulse transition-opacity duration-500"></div>
            
            <div class="relative bg-slate-900/80 backdrop-blur-xl p-8 rounded-2xl w-80 border border-white/10">
                
                <div class="absolute -top-10 -left-10 w-24 h-24 bg-cyan-500/20 blur-[40px] rounded-full group-hover:bg-cyan-500/40 transition-colors"></div>
                
                <div class="relative mb-6">
                    <div class="w-14 h-14 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-lg flex items-center justify-center shadow-[0_0_15px_rgba(34,211,238,0.4)] group-hover:shadow-cyan-400/60 transition-all duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-2 tracking-wider">{{ $exam->mapel }}</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Jumlah Soal : {{ $exam->soal }}
                </p>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Waktu Pengerjaan : {{ $exam->time }}
                </p>
                
    <button type="submit"
        class="w-full py-2 px-4 rounded-lg bg-white/5 border border-cyan-500/30
               text-cyan-400 font-semibold text-xs tracking-[2px] uppercase
               hover:bg-cyan-500 hover:text-black transition-all duration-300">
        kerjakan
    </button>
    <a href="{{ route('create-question', ['id' => $exam->id]) }}"class="w-full py-2 px-4 rounded-lg bg-white/5 border border-cyan-500/30
               text-cyan-400 font-semibold text-xs tracking-[2px] uppercase
               hover:bg-cyan-500 hover:text-black transition-all duration-300" >Masukkan Bank Soal</a>
                
                
</div>
</div>
</form>
@endforeach




@endsection
