@extends('layouts.app')

@section('title', 'Buat Soal')

@section('content')
<div class="max-w-8xl mx-auto space-y-6">
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

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div class="grid grid-cols-3 gap-4 w-full">

            <button
                onclick="document.getElementById('downloadTemplate').showModal()"
                class="w-full px-4 py-3 bg-green-600 text-white rounded-lg text-center">
                ➕ Download Template
            </button>

            <button
                onclick="document.getElementById('zipModal').showModal()"
                class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg text-center">
                📦 Import ZIP
            </button>

            <button
                onclick="document.getElementById('manualModal').showModal()"
                class="w-full px-4 py-3 bg-green-600 text-white rounded-lg text-center">
                ➕ Tambah Soal
            </button>

        </div>
    </div>


    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm table-auto border-collapse">
            <thead class="bg-emerald-400 text-white">
                <tr>
                    <th class="p-3 text-center w-[4%]">No</th>
                    <th class="p-3 text-left w-[26%]">Soal</th>
                    <th class="p-3 text-center w-[6%]">Opsi</th>
                    <th class="p-3 text-left w-[22%]">Jawaban</th>
                    <th class="p-3 text-left w-[34%]">Gambar Jawaban</th>
                    <th class="p-3 text-center w-[6%]">Kunci</th>
                    <th class="p-3 text-center w-[6%]">Aksi</th>
                </tr>
            </thead>


            <tbody>
            @foreach ($importedQuestions as $q)
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

                        @if ($q->question_image)
                            <img
                                src="{{ asset('storage/'.$q->question_image) }}"
                                class="max-w-full max-h-48 rounded border"
                            >
                        @endif
                    </td>
                    @endif

                    {{-- OPSI --}}
                    <td class="p-3 text-center font-bold align-top border">
                        {{ $a->option_key }}
                    </td>

                    {{-- JAWABAN --}}
                    <td class="p-3 align-top border ">
                        {{ $a->answer_text }}
                    </td>

                    {{-- GAMBAR JAWABAN --}}
                    <td class="p-3 align-top border">
                        @if ($a->answer_image)
                            <img
                                src="{{ asset('storage/'.$a->answer_image) }}"
                                class="max-w-full max-h-40 rounded border"
                            >
                        @else
                            <span class="text-gray-400 italic">Tidak ada</span>
                        @endif
                    </td>

                    {{-- KUNCI --}}
                    <td class="p-3 text-center align-top border">
                        @if ($a->is_true)
                            <span class="text-green-600 text-lg font-bold">✔</span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                @if ($i === 0)
                <td
                    rowspan="{{ $q->answers->count() }}"
                    class="p-3 text-center align-top border"
                >
                    <div class="flex  gap-2 items-center">

                        {{-- EDIT --}}
                        <a href=""
                        class="text-blue-600 hover:text-blue-800 text-lg"
                        title="Edit Soal">
                            ✏️
                        </a>

                        {{-- DELETE --}}
                        <form
                            action=""
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus soal ini?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="text-red-600 hover:text-red-800 text-lg"
                                title="Hapus Soal"
                            >
                                🗑️
                            </button>
                        </form>

                    </div>
                </td>
                @endif




                </tr>
                @endforeach
            @endforeach
            </tbody>

        </table>
    </div>


    {{-- SUBMIT --}}
    <div class="flex justify-end">
        <form method="POST" action="">
            {{-- route submit final --}}
            @csrf
            <button class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                ✅ Submit Soal
            </button>
        </form>
    </div>

</div>

{{-- MODAL ZIP --}}
<dialog id="zipModal" class="ml-[550px] rounded-2xl p-0 backdrop:bg-gray-900/50 backdrop:backdrop-blur-sm shadow-2xl overflow-hidden border-0">
    <div class="w-[450px] bg-white">
        {{-- Header Modal --}}
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <span class="text-blue-600">📦</span> Import Soal ZIP
            </h2>
            <button 
                onclick="zipModal.close()" 
                class="text-gray-400 hover:text-red-500 transition-colors text-2xl"
            >
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('import.upload') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            
            {{-- Area Upload Custom --}}
            <input type="hidden" name="exam_id" value="{{ $exam->id ?? '' }}"> 
    
            <div class="group relative ...">
                {{-- UBAH name="zip" MENJADI name="file" AGAR SESUAI CONTROLLER --}}
                <input 
                    type="file" 
                    name="file" 
                    required 
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    onchange="updateFileName(this)"
                >

                <div class="space-y-3">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span id="fileName" class="font-semibold text-blue-600">Klik untuk upload</span> atau drag file ZIP ke sini
                    </div>
                    <p class="text-xs text-gray-400 italic">Maksimal ukuran file: 10MB</p>
                </div>
            </div>

            {{-- Info Box --}}
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0 text-amber-400">⚠️</div>
                    <div class="ml-3">
                        <p class="text-xs text-amber-800">
                            Pastikan format file sesuai dengan template agar data terbaca sistem.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3">
                <button 
                    type="button" 
                    onclick="zipModal.close()" 
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                >
                    Batal
                </button>
                <button 
                    class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all font-medium flex items-center justify-center gap-2"
                >
                    <span>🚀</span> Import Sekarang
                </button>
            </div>
        </form>
    </div>
</dialog>

{{-- MODAL MANUAL --}}
<dialog id="manualModal" class="rounded-2xl p-0 ml-[510px] backdrop:bg-black/50 shadow-2xl">
    <form
        method="POST"
        action=""
        enctype="multipart/form-data"
        class="bg-white max-w-3xl w-full rounded-2xl p-6 relative"
    >
        @csrf
        <button
            type="button"
            onclick="manualModal.close()" {{-- Gunakan ID langsung lebih aman --}}
            class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-xl z-10"
        >
            ✖
        </button>

        <!-- TITLE -->
        <h2 class="text-xl font-bold mb-4">Tambah Soal Baru</h2>

        <!-- TEKS SOAL -->
        <div class="mb-6">
            <label for="question_text" class="block font-semibold mb-2">
                Teks Soal
            </label>
            <textarea
                id="question_text"
                name="question_text"
                rows="4"
                placeholder="Masukkan teks soal"
                class="w-full border rounded-lg p-3 focus:ring focus:ring-blue-200"
                required
            ></textarea>
        </div>

        <!-- OPSI JAWABAN -->
        <div class="mb-6">
            <p class="font-semibold mb-3">Opsi Jawaban</p>

            <!-- OPSI LOOP -->
            @foreach (['A','B','C','D'] as $opt)
            <div class="border rounded-xl p-4 mb-3">

                <div class="flex items-center gap-3">
                    <span class="font-bold">{{ $opt }}.</span>

                    <input
                        type="text"
                        name="answers[{{ $opt }}][text]"
                        placeholder="Opsi {{ $opt }}"
                        class="flex-1 border rounded-lg p-2"
                        required
                    >

                    <!-- UPLOAD GAMBAR OPSI -->
                    <label class="cursor-pointer flex items-center gap-2 text-blue-600 text-sm">
                        📷
                        <span class="text-gray-400">Belum ada gambar</span>
                        <input
                            type="file"
                            name="answers[{{ $opt }}][image]"
                            class="hidden"
                        >
                    </label>
                </div>

                <!-- RADIO KUNCI -->
                <label class="flex items-center gap-2 text-sm text-green-600 mt-2">
                    <input
                        type="radio"
                        name="correct_answer"
                        value="{{ $opt }}"
                        required
                    >
                    Jawaban Benar
                </label>

            </div>
            @endforeach
        </div>

        <!-- GAMBAR SOAL -->
        <div class="mb-6">
            <label class="block font-semibold mb-2">
                Gambar Soal
            </label>

            <label class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg cursor-pointer">
                ⬆ Upload Gambar
                <input
                    type="file"
                    name="question_image"
                    class="hidden"
                >
            </label>
        </div>

        <!-- BUTTON -->
        <div class="flex gap-4">
            <button
                type="button"
                onclick="manualModal.close()"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg"
            >
                Batal
            </button>

            <button
                type="submit"
                class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg"
            >
                Buat Ujian
            </button>
        </div>

    </form>
</dialog>



@endsection

<script>
document.querySelectorAll('dialog').forEach(dialog => {
    dialog.addEventListener('mousedown', (e) => {
        // Deteksi jika yang diklik adalah elemen <dialog> itu sendiri (background/backdrop)
        // bukan isi di dalamnya
        if (e.target === dialog) {
            dialog.close();
        }
    });
});
</script>