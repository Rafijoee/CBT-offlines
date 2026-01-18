@extends('layouts.app')

@section('title', 'Preview Soal')

@section('content')
<div class="max-w-7xl mx-auto">

    <h1 class="text-xl font-bold mb-4">
        Preview Soal ({{ $session->questions->count() }})
    </h1>

    <div class="overflow-x-auto border rounded">
        <table class="min-w-full text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2 text-left w-1/4">Soal</th>
                    <th class="border p-2 text-left w-1/4">Opsi</th>
                    <th class="border p-2 text-left w-1/4">Jawaban</th>
                    <th class="border p-2 text-center w-1/8">Kunci</th>
                </tr>
            </thead>

            <tbody>
            {{-- @dd($session->questions) --}}
            @foreach ($session->questions as $q)

                @foreach ($q->answers as $index => $a)
                    <tr class="align-top">

                        {{-- SOAL (rowspan) --}}
                        @if ($index === 0)
                            <td class="border p-2 whitespace-pre-line"
                                rowspan="{{ $q->answers->count() }}">
                                {{ $q->question_text }}
                                @if ($q->question_image)
                                {{-- @dd(asset('storage/'.$q->question_image)) --}}
                                    <img src="{{ asset('storage/'.$q->question_image) }}"
                                        class="mt-2 max-w-xs rounded">
                                @endif
                            </td>
                        @endif

                        {{-- OPSI --}}
                        <td class="border p-2 font-bold">
                            {{ $a->option_key }}
                        </td>

                        {{-- JAWABAN --}}
                        <td class="border p-2">
                            {{ $a->answer_text }}
                            @if ($a->answer_image)
                                    <img src="{{ asset('storage/'.$a->answer_image) }}"
                                        class="mt-2 max-w-xs rounded">
                                @endif
                            </td>
                        </td>

                        {{-- KUNCI --}}
                        <td class="border p-2 text-center">
                            @if ($a->is_true)
                                ✅
                            @endif
                        </td>

                    </tr>
                @endforeach

            @endforeach
            </tbody>

        </table>
    </div>

    {{-- ACTION --}}
    <form method="POST" action="{{ route('import.finalize', $session->id) }}" class="mt-4">
        @csrf
        <button class="bg-green-600 text-white px-4 py-2 rounded">
            Submit Soal ke Ujian
        </button>
    </form>

</div>
@endsection
