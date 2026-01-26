@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- exam_id wajib --}}
    <input type="hidden" name="exam_id" value="{{ $exam->id ?? 1 }}">

    {{-- file ZIP --}}
    <input type="file" name="file" required>
    
    <button type="submit">Upload & Preview</button>


<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Kelola Soal</h1>
            <p class="text-sm text-gray-500">
                Mata Pelajaran: {{ $exam->name }}
            </p>
        </div>

        <div class="flex gap-2">
            {{-- Upload ZIP --}}
            <button
                onclick="document.getElementById('zipModal').showModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded">
                Import ZIP
            </button>

            {{-- Tambah Soal --}}
            <button
                onclick="document.getElementById('manualModal').showModal()"
                class="px-4 py-2 bg-green-600 text-white rounded">
                + Tambah Soal
            </button>
        </div>
    </div>

    {{-- TABLE LIST SOAL --}}
    <h1 class="text-xl font-bold mb-4">
    Preview Soal Import ({{ $importedQuestions->count() }})
    </h1>

<div class="overflow-x-auto border rounded">
    <table class="min-w-full text-sm border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2 text-left w-1/4">Soal</th>
                <th class="border p-2 text-center w-1/12">Opsi</th>
                <th class="border p-2 text-left w-1/4">Jawaban</th>
                <th class="border p-2 text-left w-1/4">Gambar Jawaban</th>
                <th class="border p-2 text-center w-1/12">Kunci</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($importedQuestions as $q)

            @foreach ($q->answers as $index => $a)
                <tr class="align-top">

                    {{-- SOAL (rowspan) --}}
                    @if ($index === 0)
                        <td class="border p-2 whitespace-pre-line"
                            rowspan="{{ $q->answers->count() }}">
                            <div class="font-semibold">
                                {{ $q->question_text }}
                            </div>

                            @if ($q->question_image)
                                <img
                                    src="{{ asset('storage/'.$q->question_image) }}"
                                    class="mt-2 max-w-xs rounded border"
                                >
                            @endif
                        </td>
                    @endif

                    {{-- OPSI --}}
                    <td class="border p-2 text-center font-bold">
                        {{ $a->option_key }}
                    </td>

                    {{-- JAWABAN --}}
                    <td class="border p-2">
                        {{ $a->answer_text }}
                    </td>

                    {{-- GAMBAR JAWABAN --}}
                    <td class="border p-2">
                        @if ($a->answer_image)
                            <img
                                src="{{ asset('storage/'.$a->answer_image) }}"
                                class="max-w-xs rounded border"
                            >
                        @else
                            <span class="text-gray-400 italic">Tidak ada</span>
                        @endif
                    </td>

                    {{-- KUNCI --}}
                    <td class="border p-2 text-center">
                        @if ($a->is_true)
                            ✅
                        @endif
                    </td>

                </tr>
            @endforeach

        @empty
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">
                    Belum ada soal hasil import
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>


    {{-- SUBMIT --}}
    <div class="flex justify-end mt-6">
        <form method="POST" action="">
            {{-- {{ route('teacher.exams.submitQuestions', $exam) }} --}}
            @csrf
            <button class="px-6 py-2 bg-purple-600 text-white rounded">
                Submit Soal
            </button>
        </form>
    </div>

</div>

{{-- MODAL UPLOAD ZIP --}}
<dialog id="zipModal" class="rounded p-6 w-96">
    <form method="POST" action="" enctype="multipart/form-data">
        {{-- {{ route('teacher.questions.import', $exam) }} --}}
        @csrf
        <h2 class="text-lg font-bold mb-4">Import Soal via ZIP</h2>

        <input type="file" name="zip" required class="mb-4">

        <div class="flex justify-end gap-2">
            <button type="button" onclick="zipModal.close()">Batal</button>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">
                Import
            </button>
        </div>
    </form>
</dialog>

{{-- MODAL TAMBAH SOAL MANUAL --}}
<dialog id="manualModal" class="rounded p-6 w-[500px]">
    <form method="POST" action="" enctype="multipart/form-data">
        {{-- {{ route('teacher.questions.store', $exam) }} --}}
        @csrf

        <h2 class="text-lg font-bold mb-4">Tambah Soal Manual</h2>

        <div class="mb-3">
            <label class="block text-sm mb-1">Soal</label>
            <textarea name="question_text" class="w-full border rounded p-2" required></textarea>
        </div>

        <div class="mb-3">
            <label class="block text-sm mb-1">Gambar Soal (opsional)</label>
            <input type="file" name="question_image">
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" onclick="manualModal.close()">Batal</button>
            <button class="px-4 py-2 bg-green-600 text-white rounded">
                Simpan
            </button>
        </div>
    </form>
</dialog>


</form>


@endsection
