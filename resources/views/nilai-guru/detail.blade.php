@extends('layouts.app')

@section('title', 'Detail Jawaban')

@section('content')
<div class=" mx-auto">
        <div class="max-w-8xl mx-auto">

    <div class="bg-white rounded-2xl shadow-lg p-6 my-4">
@php
    $benar = 0;
    $salah = 0;
    $kosong = 0;

    foreach($userExam->exam->bankSoals as $soal){
        $correct = $soal->answers->where('true',1)->first();
        $student = $userExam->userAnswers->where('bank_soal_id',$soal->id)->first();

        if(!$student){
            $kosong++;
        } elseif($student->answer_id == $correct?->id){
            $benar++;
        } else {
            $salah++;
        }
    }

    $total = $userExam->exam->bankSoals->count();
    $nilai = $total > 0 ? round(($benar/$total)*100,2) : 0;
@endphp


{{-- ================= HEADER COMPACT ================= --}}
<div class="bg-gray-100 rounded-xl shadow p-4 mb-2 border">

    {{-- NAMA + MAPEL --}}
    <div class="flex justify-between items-center mb-2">
        <div>
            <h2 class="text-lg font-bold">
                {{ $userExam->user->name }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ $userExam->exam->mapel }}
            </p>
        </div>

        <div class="text-right">
            <p class="text-xs text-gray-500">Nilai</p>
            <p class="text-xl font-bold text-emerald-600">
                {{ $nilai }}
            </p>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-3 gap-4 text-center">

        <div class="bg-green-100 rounded-lg py-2">
            <p class="text-xs text-green-600">Benar</p>
            <p class="font-semibold text-green-700">
                {{ $benar }}
            </p>
        </div>

        <div class="bg-red-100 rounded-lg py-2">
            <p class="text-xs text-red-600">Salah</p>
            <p class="font-semibold text-red-700">
                {{ $salah }}
            </p>
        </div>

        <div class="bg-gray-200 rounded-lg py-2">
            <p class="text-xs text-gray-600">Kosong</p>
            <p class="font-semibold text-gray-700">
                {{ $kosong }}
            </p>
        </div>

    </div>

</div>
    </div>

    <div class="overflow-x-auto border-2 border-emerald-500 rounded-lg">

        <table class="min-w-full text-sm">

            {{-- HEADER --}}
            <thead class="bg-emerald-500 text-white">
                <tr>
                    <th class="px-4 py-3 text-left w-16">No</th>
                    <th class="px-4 py-3 text-left">Soal</th>
                    <th class="px-4 py-3 text-left w-64">Opsi</th>
                    <th class="px-4 py-3 text-center w-24">Jawaban</th>
                    <th class="px-4 py-3 text-center w-24">Kunci</th>
                    <th class="px-4 py-3 text-center w-24">Status</th>
                </tr>
            </thead>

            <tbody class="bg-gray-100">

                @foreach($userExam->exam->bankSoals as $index => $soal)

                    @php
                        $correctAnswer = $soal->answers->where('true', 1)->first();

                        $studentAnswer = $userExam->userAnswers
                            ->where('bank_soal_id', $soal->id)
                            ->first();

                        $status = $studentAnswer 
                            && $correctAnswer
                            && $studentAnswer->answer_id == $correctAnswer->id;
                    @endphp

                    <tr class="border-b border-gray-300 align-top">

                        {{-- NO --}}
                        <td class="px-4 py-4 font-semibold">
                            {{ $index + 1 }}
                        </td>

                        {{-- SOAL --}}
                        <td class="px-4 py-4">
                            {!! $soal->question_text !!}
                        </td>

                        {{-- OPSI --}}
                        <td class="px-4 py-4 space-y-1">
                            @foreach($soal->answers as $answer)
                                <div class="
                                    {{ $answer->true ? 'text-green-600 font-semibold' : '' }}
                                ">
                                    {{ chr(65 + $loop->index) }}. 
                                    {{ $answer->text }}
                                </div>
                            @endforeach
                        </td>

                        {{-- JAWABAN SISWA --}}
                        <td class="px-4 py-4 text-center font-semibold">
                            @if($studentAnswer)
                                {{ chr(65 + $soal->answers->search(fn($a) => $a->id == $studentAnswer->answer_id)) }}
                            @else
                                -
                            @endif
                        </td>

                        {{-- KUNCI --}}
                        <td class="px-4 py-4 text-center font-semibold text-green-600">
                            @if($correctAnswer)
                                {{ chr(65 + $soal->answers->search(fn($a) => $a->id == $correctAnswer->id)) }}
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-4 text-center font-semibold">
                            @if(!$studentAnswer)
                                <span class="text-gray-500">Kosong</span>
                            @elseif($status)
                                <span class="text-green-600">Benar</span>
                            @else
                                <span class="text-red-500">Salah</span>
                            @endif
                        </td>

                    </tr>

                @endforeach

            </tbody>
        </table>

    </div>
</div>
@endsection