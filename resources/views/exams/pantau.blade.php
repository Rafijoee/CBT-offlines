@extends('layouts.app')

@section('title', 'Detail Jawaban')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl shadow p-5 text-center">
        <h2 class="text-3xl font-bold text-blue-600">
            {{ $totalStudents }}
        </h2>
        <p class="text-gray-500">Total Siswa</p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 text-center">
        <h2 class="text-3xl font-bold text-red-500">
            {{ $belum }}
        </h2>
        <p class="text-gray-500">Belum Mengerjakan</p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 text-center">
        <h2 class="text-3xl font-bold text-yellow-500">
            {{ $sedang }}
        </h2>
        <p class="text-gray-500">Sedang Mengerjakan</p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 text-center">
        <h2 class="text-3xl font-bold text-green-500">
            {{ $selesai }}
        </h2>
        <p class="text-gray-500">Sudah Selesai</p>
    </div>

</div>

@php
$progress = $totalStudents > 0
    ? round(($selesai / $totalStudents) * 100)
    : 0;
@endphp

<div class="bg-white rounded-xl shadow p-5 mb-6">

    <h2 class="font-semibold text-lg mb-3">
        Progress Ujian
    </h2>

    <div class="w-full bg-gray-200 rounded-full h-6">
        <div
            class="bg-blue-600 h-6 rounded-full text-white text-sm flex items-center justify-center"
            style="width: {{ $progress }}%"
        >
            {{ $progress }}%
        </div>
    </div>

    <p class="text-sm text-gray-500 mt-2">
        {{ $selesai }} dari {{ $totalStudents }} siswa telah menyelesaikan ujian
    </p>

</div>
<div class="bg-white rounded-xl shadow">

    <div class="p-5 border-b">
        <h2 class="font-semibold text-lg">
            Monitoring Peserta Ujian
        </h2>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm ">

            <thead class="bg-[#06D6A0] text-white">

                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama Siswa</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Mulai</th>
                    <th class="p-3 text-left">Selesai</th>
                </tr>

            </thead>

            <tbody>

                @forelse($monitoring as $index => $item)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $index + 1 }}
                    </td>

                    <td class="p-3">
                        {{ $item['student']->name }}
                    </td>

                    <td class="p-3">

                        @if($item['status'] == 'belum')
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
                                Belum Mengerjakan
                            </span>

                        @elseif($item['status'] == 'sedang')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">
                                Sedang Mengerjakan
                            </span>

                        @else
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                Selesai
                            </span>
                        @endif

                    </td>

                    <td class="p-3">
                        {{ $item['started_at']
                            ? \Carbon\Carbon::parse($item['started_at'])->format('H:i:s')
                            : '-' }}
                    </td>

                    <td class="p-3">
                        {{ $item['submitted_at']
                            ? \Carbon\Carbon::parse($item['submitted_at'])->format('H:i:s')
                            : '-' }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">
                        Tidak ada data siswa
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<meta http-equiv="refresh" content="10">
@endsection