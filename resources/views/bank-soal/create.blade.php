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
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
    <thead class="bg-gray-100">
        <tr>
            <th rowspan="2" class="border p-2 text-left w-1/4">
                Soal
            </th>
            <th colspan="3" class="border p-2 text-center">
                Jawaban
            </th>
        </tr>
        <tr>
            <th class="border p-2 text-left w-1/8">
                Opsi
            </th>
            <th class="border p-2 text-left w-1/4">
                Isi Jawaban
            </th>
            <th class="border p-2 text-center w-1/8">
                Kunci
            </th>
        </tr>
    </thead>


            <tbody>
                @forelse ($questions as $index => $question)
                <tr class="border-t">
                    <td class="px-4 py-2">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-4 py-2">
                        <div class="font-medium">
                            {{ Str::limit($question->question_text, 80) }}
                        </div>

                        @if ($question->question_image)
                            <img
                                src="{{ asset('storage/'.$question->question_image) }}"
                                class="mt-2 w-32 rounded"
                            >
                        @endif
                    </td>

                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded
                            {{ $question->source === 'import' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($question->source) }}
                        </span>
                    </td>

                    <td class="px-4 py-2 text-center">
                        <div class="flex justify-center gap-2">
                            {{-- Edit --}}
                            <a
                                href="{{ route('teacher.questions.edit', $question) }}"
                                class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">
                                Edit
                            </a>

                            {{-- Delete --}}
                            <form
                                method="POST"
                                action="{{ route('teacher.questions.destroy', $question) }}"
                                onsubmit="return confirm('Hapus soal ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">
                        Belum ada soal
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
