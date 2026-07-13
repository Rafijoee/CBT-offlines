@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
{{-- Tampilkan Alert jika ada error dari session --}}
@if (session('error'))
    <div id="alertError" class="mb-6 flex items-center p-4 text-red-800 border-t-4 border-red-300 bg-red-50 rounded-lg shadow-sm" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ms-3 text-sm font-medium">
            Gagal: <span class="font-normal">{{ session('error') }}</span>
        </div>
        <button type="button" onclick="document.getElementById('alertError').remove()" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
@endif

<div class="mx-auto space-y-10">

    <!-- ================= UJIAN YANG BISA DIIKUTI ================= -->
    <section>
        <h2 class="text-xl font-bold mb-4">▶️ Ujian yang Bisa Diikuti</h2>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($exams as $exam)
                <div class="flex flex-col justify-between bg-white rounded-xl shadow">
                    <div class="p-5 space-y-3">

                        <div class="flex gap-2">
                            <div class="flex items-center justify-center w-[50px] bg-[#C1E2FF] rounded-lg">
                                <img src="{{ asset('storage/images/icon_exam.png') }}" 
                                     alt="Icon Exam" 
                                     class="w-9 object-contain">
                            </div>

                            <div class="flex flex-col gap-1">
                                <h3 class="font-semibold text-lg leading-none pb-1">
                                    {{ $exam->mapel }}
                                </h3>

                                <div class="flex gap-2 text-sm">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded">
                                        {{ $exam->time }} Menit
                                    </span>
                                    <span class="px-2 py-1 bg-green-100 text-green-600 rounded">
                                        {{ $exam->soal }} Soal
                                    </span>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-[#6E7781]">
                            Ujian Bab Aljabar dan Geometri.
                        </p>

                        <div class="flex flex-col">
                            <span class="text-[#6E7781] text-sm">Dibuka :</span>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-[#131927]">
                                    {{ \Carbon\Carbon::parse($exam->created_at)->locale('id')->translatedFormat('l, d F Y') }},
                                    {{ \Carbon\Carbon::parse($exam->created_at)->format('H.i') }} -
                                    {{ \Carbon\Carbon::parse($exam->closed_time)->addMinutes($exam->time)->format('H.i') }} WIB
                                </span>
                                <button
                                    class="btnMulai bg-blue-500 text-white px-4 py-1 rounded-lg"
                                    data-id="{{ $exam->id }}"
                                    data-mapel="{{ $exam->mapel }}">
                                    ▶ Mulai >
                                </button>
                            </div>
                        </div>

                        <div class="text-xs text-[#057CFF] bg-[#C1E2FF]/50 rounded p-2">
                            ℹ️ Tips: Baca soal dengan teliti!
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    </section>

    <!-- ================= LANJUTKAN UJIAN ================= -->
    <section>
        <h2 class="text-xl font-bold mb-4">🔄 Lanjutkan Ujian</h2>
        
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($currents as $current)
                <div class="bg-white rounded-xl shadow p-5 space-y-4">
                    
                    <div class="flex justify-between">
                        <h3 class="font-semibold">
                            {{ $current?->mapel }}
                        </h3>

                        <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded">
                            Sedang Berlangsung
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-400 h-2 rounded-full" style="width:30%"></div>
                    </div>
                    <button 
                                    data-id="{{ $current->id }}"
                                    data-mapel="{{ $current->mapel }}"
                        class="btnMulai bg-blue-500 hover:bg-[#0063CC] rounded-lg px-4 py-1 text-white text-sm">
                        ▶ Lanjutkan>
                    </button>

                </div>
            @endforeach
        </div>
    </section>

    <!-- ================= UJIAN SELESAI ================= -->
    <section>
        <h2 class="text-xl font-bold mb-4">✅ Ujian Selesai</h2>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach($finishs as $finish)
                <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col justify-between border border-gray-100 relative">
                    
                    <div class="flex justify-between items-start">
                        <div class="flex gap-3">
                            <div class="flex items-center justify-center w-14 h-14 bg-[#C6F7D0] rounded-xl">
                                <img src="{{ asset('storage/images/icon_exam.png') }}" 
                                    alt="Icon" 
                                    class="w-8 object-contain">
                            </div>

                            <div class="flex flex-col gap-1">
                                <h3 class="font-bold text-[#1E293B] text-lg">
                                    {{ $finish?->mapel }}
                                </h3>
                                <div class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#C6F7D0] text-[#42AD62] rounded-full text-xs font-semibold w-fit">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></span>
                                    Selesai
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-[#42AD62] text-white rounded-full font-bold text-lg">
                            {{ $finish->userExams->first()->skor }}
                        </div>
                    </div>

                    <div class="flex justify-between items-end mt-8">
                        <div class="flex flex-col">
                            <span class="text-[#94A3B8] text-sm">Tanggal:</span>
                            <span class="text-[#1E293B] font-semibold">
                                {{ \Carbon\Carbon::parse($finish->updated_at)->locale('id')->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        <a href="{{ route('exam.finish', ['userExam' => $finish->id]) }}"
                        class="bg-[#C6F7D0] hover:bg-[#b0f2bd] text-[#42AD62] px-5 py-2 rounded-xl text-sm font-bold transition-all shadow-sm">
                            Lihat Hasil
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- ================= UJIAN AKAN DATANG ================= -->
<section class="my-8">
    <!-- Header Section -->
    <div class="flex items-center gap-2 mb-6">
        <div class="p-2 bg-slate-100 text-slate-500 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800">Ujian yang Akan Datang</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($comings as $exam)
            <!-- Card dengan aksen abu-abu (locked state) -->
            <div class="relative flex flex-col justify-between bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden opacity-90">

                <!-- Overlay label "Belum Dibuka" di pojok -->
                <div class="absolute top-4 right-4 flex items-center gap-1 bg-gray-800/90 text-white text-[10px] font-semibold px-2.5 py-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Terkunci
                </div>

                <div class="p-6 space-y-4">

                    <!-- Header Card: Info Mapel & Badge -->
                    <div class="flex gap-3 items-start">
                        <div class="flex items-center justify-center p-3 bg-gray-100 text-gray-400 rounded-xl shrink-0">
                            <img src="{{ asset('storage/images/icon_exam.png') }}"
                                 alt="Icon Exam"
                                 class="w-8 h-8 object-contain grayscale opacity-70">
                        </div>

                        <div class="flex flex-col gap-1.5 w-full pr-8">
                            <h3 class="font-bold text-gray-500 text-lg leading-tight line-clamp-1">
                                {{ $exam->mapel }}
                            </h3>

                            <div class="flex flex-wrap gap-2 text-xs font-medium">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-md">
                                    {{ $exam->time }} Menit
                                </span>
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-md">
                                    {{ $exam->soal }} Soal
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Ujian Bab Aljabar dan Geometri.
                    </p>

                    <hr class="border-gray-100" />

                    <!-- Informasi Waktu Buka -->
                    <div class="space-y-2">
                        <span class="text-gray-400 text-xs font-medium uppercase tracking-wider">Akan Dibuka:</span>

                        <div class="flex justify-between items-end gap-2">
                            <div class="text-sm text-gray-500 font-medium leading-normal">
                                <p class="text-gray-600">{{ \Carbon\Carbon::parse($exam->opened_time)->locale('id')->translatedFormat('l, d F Y') }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Pukul {{ \Carbon\Carbon::parse($exam->opened_time)->format('H.i') }} WIB
                                </p>
                            </div>

                            <!-- Tombol Disabled -->
                            <button
                                type="button"
                                disabled
                                class="flex items-center gap-1.5 bg-gray-200 text-gray-400 font-medium text-sm px-4 py-2 rounded-xl cursor-not-allowed pointer-events-none shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                <span>Terkunci</span>
                            </button>
                        </div>
                    </div>

                    <!-- Banner Info -->
                    <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 border border-slate-100 rounded-xl p-3">
                        <span class="text-base leading-none">⏳</span>
                        <p class="font-medium">Ujian belum bisa diakses, tunggu hingga waktu yang ditentukan.</p>
                    </div>

                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 col-span-full">Belum ada ujian yang akan datang.</p>
        @endforelse
    </div>
</section>

<!-- ================= UJIAN YANG TELAT ================= -->
<section class="my-8">
    <!-- Header Section dengan Icon yang Lebih Bagus -->
    <div class="flex items-center gap-2 mb-6">
        <div class="p-2 bg-red-100 text-red-600 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800">Ujian yang Telat</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($lates as $exam)
            <!-- Card dengan Aksen Border Merah Tipis -->
            <div class="flex flex-col justify-between bg-white rounded-2xl border border-red-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="p-6 space-y-4">

                    <!-- Header Card: Info Mapel & Badge -->
                    <div class="flex gap-3 items-start">
                        <!-- Icon Exam Box (Diubah ke aksen soft merah/rose agar senada) -->
                        <div class="flex items-center justify-center p-3 bg-rose-50 text-rose-500 rounded-xl shrink-0">
                            <img src="{{ asset('storage/images/icon_exam.png') }}" 
                                 alt="Icon Exam" 
                                 class="w-8 h-8 object-contain">
                        </div>

                        <div class="flex flex-col gap-1.5 w-full">
                            <h3 class="font-bold text-gray-800 text-lg leading-tight line-clamp-1">
                                {{ $exam->mapel }}
                            </h3>

                            <div class="flex flex-wrap gap-2 text-xs font-medium">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-md">
                                    {{ $exam->time }} Menit
                                </span>
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-md">
                                    {{ $exam->soal }} Soal
                                </span>
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-md font-semibold">
                                    Terlewat
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Ujian Bab Aljabar dan Geometri.
                    </p>

                    <!-- Garis Pembatas Halus -->
                    <hr class="border-gray-100" />

                    <!-- Informasi Waktu & Tombol Aksi -->
                    <div class="space-y-2">
                        <span class="text-gray-400 text-xs font-medium uppercase tracking-wider">Waktu Pelaksanaan:</span>

                        <div class="flex justify-between items-end gap-2">
                            <div class="text-sm text-gray-700 font-medium leading-normal">
                                <p class="text-gray-800">{{ \Carbon\Carbon::parse($exam->created_at)->locale('id')->translatedFormat('l, d F Y') }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($exam->created_at)->format('H.i') }} -
                                    {{ \Carbon\Carbon::parse($exam->closed_time)->addMinutes($exam->time)->format('H.i') }} WIB
                                </p>
                            </div>
                            
                            <!-- Tombol Mulai dengan Warna Merah/Crimson Indikasi Telat -->
                            <button
                                class="btnMulai flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white font-medium text-sm px-4 py-2 rounded-xl shadow-sm shadow-red-200 transition-colors duration-200 shrink-0"
                                data-id="{{ $exam->id }}"
                                data-mapel="{{ $exam->mapel }}">
                                <span>Mulai</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Banner Tips Box -->
                    <div class="flex items-center gap-2 text-xs text-amber-700 bg-amber-50 border border-amber-100 rounded-xl p-3">
                        <span class="text-base leading-none">💡</span>
                        <p class="font-medium">Tips: Baca soal dengan teliti dan perhatikan sisa waktu!</p>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</section>



</div>

<div id="modalToken" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg p-6 w-[400px] text-center">

        <div class="flex justify-center mb-3">
            <div class="bg-blue-500 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl">
                🔑
            </div>
        </div>

        <h2 class="text-lg font-semibold">Masukkan Token Ujian</h2>
        <p id="modalMapel" class="text-gray-500 text-sm mb-3">Nama Mapel</p>

        <div class="bg-blue-100 text-blue-700 text-sm rounded-lg p-2 mb-4">
            Token diberikan oleh guru sebelum ujian dimulai.
        </div>

        <form id="formToken" method="POST">
            @csrf
            <input
                id="inputToken"
                type="text"
                name="token" {{-- Pastikan name="token" agar terbaca di $request->token --}}
                maxlength="6"
                required
                class="border rounded-lg w-full text-center py-2 tracking-[10px] text-lg uppercase focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="XXXXXX"
            >

            <p class="text-xs text-gray-400 mt-2 mb-4">
                Masukkan 6 karakter token (huruf dan angka)
            </p>

            <div class="flex gap-3">
                <button id="btnClose" type="button" class="w-1/2 bg-red-500 text-white py-2 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="w-1/2 bg-blue-500 text-white py-2 rounded-lg text-center">
                    Mulai Ujian
                </button>
            </div>
        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalToken');
    const btnClose = document.getElementById('btnClose');
    const formToken = document.getElementById('formToken');
    const modalMapel = document.getElementById('modalMapel');
    const inputToken = document.getElementById('inputToken');
    const buttons = document.querySelectorAll('.btnMulai');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const mapel = this.dataset.mapel;

            modalMapel.innerText = mapel;
            inputToken.value = ''; 
            
            // Set ACTION form ke route POST examtoken/{id}
            formToken.action = `/examtoken/${id}`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    btnClose.addEventListener('click', function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });
});
</script>
@endpush