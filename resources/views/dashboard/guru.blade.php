@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div x-data="{ openModal: false }" class="mx-auto space-y-10">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <img src="{{ asset('storage/images/list.png') }}" class="w-6 h-6">
            Daftar Ujian
        </h2>

        <div class="flex gap-3">
            <button
                @click="openModal = true"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                ➕ Buat Ujian
            </button>

            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                🔄 Sinkronisasi
            </button>

            <button class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm">
                📊 Lihat Nilai
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl p-4">
        <div class="rounded-2xl shadow overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-[#06D6A0] text-white">
                    <tr>
                        <th class="p-4 text-left">No</th>
                        <th class="p-4 text-left">Mata Pelajaran</th>
                        <th class="p-4 text-left">Kelas</th>
                        <th class="p-4 text-left">Waktu</th>
                        <th class="p-4 text-left">Jumlah Soal</th>
                        <th class="p-4 text-left">Token</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach ($exams as $index => $exam)
                    <tr>
                        <td class="p-4">{{ $index + 1 }}</td>

                        <td class="p-4 font-semibold">
                            {{ $exam->mapel }}
                        </td>

                        <td class="p-4">
                            Kelas {{ $exam->kelas }}
                        </td>

                        <td class="p-4">
                            <div class="font-semibold">
                                {{ $exam->time }} Menit
                            </div>

                            @if ($exam->opened_time && $exam->closed_time)
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($exam->opened_time)
                                    ->locale('id')
                                    ->translatedFormat('l, d F Y') }},
                                {{ \Carbon\Carbon::parse($exam->opened_time)->format('H.i') }}
                                -
                                {{ \Carbon\Carbon::parse($exam->closed_time)->format('H.i') }} WIB
                            </div>
                            @endif
                        </td>

                        <td class="p-4">
                            {{ $exam->soal }} Soal
                        </td>

                        <td class="p-4 font-mono">
                            {{ $exam->token }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-4">
                            @if (!$exam->opened_time || !$exam->closed_time)
                                <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-500">
                                    ⏳ Belum Dijadwalkan
                                </span>

                            @elseif ($now->lt($exam->opened_time))
                                <span class="px-3 py-1 rounded-full text-xs bg-orange-100 text-orange-600">
                                    ⏰ Akan Datang
                                </span>

                            @elseif ($now->between($exam->opened_time, $exam->closed_time))
                                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-600">
                                    ▶ Berlangsung
                                </span>

                            @else
                                <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-500">
                                    ✔ Selesai
                                </span>
                            @endif
                        </td>

                        <!-- AKSI -->
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-3">
                                <form action="{{ route('generate-token', $exam) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-blue-500 hover:text-blue-700" type="submit">🔄</button>
                                </form>

                                <button class="text-yellow-500 hover:text-yellow-600">✏️</button>
                                <button class="text-red-500 hover:text-red-600">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <!-- MODAL -->
    <div
        x-show="openModal"
        x-transition
        @click.self="openModal = false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl p-6">

            <div class="flex justify-center mb-4">
                <div class="w-14 h-14 rounded-full bg-green-500 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">+</span>
                </div>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl font-bold">Buat Ujian Baru</h2>
                <p class="text-sm text-gray-500">Isi data ujian berikut</p>
            </div>

            <form class="grid grid-cols-2 gap-4" action="{{ route('store-exams') }}" method="POST">
                @csrf

                <!-- MAPEL -->
                <div>
                    <label for="mapel" class="block text-sm font-semibold text-gray-700 mb-1">
                        Mata Pelajaran
                    </label>
                    <input
                        type="text"
                        name="mapel"
                        id="mapel"
                        placeholder="Contoh: Matematika"
                        class="border rounded-lg p-2 w-full"
                    >
                </div>

                <!-- KELAS -->
                <div>
                    <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-1">
                        Kelas
                    </label>
                    <input
                        type="text"
                        name="kelas"
                        id="kelas"
                        placeholder="Contoh: X IPA 1"
                        class="border rounded-lg p-2 w-full"
                        z
                    >
                </div>

                <!-- DURASI -->
                <div>
                    <label for="time" class="block text-sm font-semibold text-gray-700 mb-1">
                        Durasi (Menit)
                    </label>
                    <input
                        type="number"
                        name="time"
                        id="time"
                        placeholder="Contoh: 90"
                        class="border rounded-lg p-2 w-full"
                        
                    >
                </div>

                <!-- JUMLAH SOAL -->
                <div>
                    <label for="soal" class="block text-sm font-semibold text-gray-700 mb-1">
                        Jumlah Soal
                    </label>
                    <input
                        type="number"
                        name="soal"
                        id="soal"
                        placeholder="Contoh: 40"
                        class="border rounded-lg p-2 w-full"
                        
                    >
                </div>

                <!-- OPENED TIME -->
                <div>
                    <label for="opened_time" class="block text-sm font-semibold text-gray-700 mb-1">
                        Waktu Mulai
                    </label>
                    <input
                        type="datetime-local"
                        name="opened_time"
                        id="opened_time"
                        class="border rounded-lg p-2 w-full"
                        
                    >
                </div>

                <!-- CLOSED TIME -->
                <div>
                    <label for="closed_time" class="block text-sm font-semibold text-gray-700 mb-1">
                        Waktu Selesai
                    </label>
                    <input
                        type="datetime-local"
                        name="closed_time"
                        id="closed_time"
                        class="border rounded-lg p-2 w-full"
                        
                    >
                </div>

                <!-- ACTION -->
                <div class="col-span-2 flex justify-between mt-6">
                    <button
                        type="button"
                        @click="openModal = false"
                        class="w-1/2 mr-2 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="w-1/2 ml-2 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg">
                        Buat Ujian
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
