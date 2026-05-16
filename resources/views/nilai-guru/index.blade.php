@extends('layouts.app')

@section('title', 'Detail Jawaban Siswa')

@section('content')

<div class="min-h-screen">

    <!-- FILTER CARD -->
    <div class="max-w-8xl mx-auto">

    <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
        <form method="GET" action="{{ route('nilai.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Mata Pelajaran -->
                <div class="bg-blue-50 rounded-xl p-5 flex gap-4 items-center">
                    <div class="bg-blue-500 text-white w-12 h-12 flex items-center justify-center rounded-lg text-xl">
                        📘
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-semibold mb-1">
                            Mata Pelajaran
                        </label>
                        <select name="mapel" class="w-full rounded-lg border-gray-300">
                            <option value="">Semua Mapel</option>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel }}"
                                    {{ request('mapel') == $mapel ? 'selected' : '' }}>
                                    {{ $mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Nama Siswa (Search) -->
                <div class="bg-green-50 rounded-xl p-5 flex gap-4 items-center">
                    <div class="bg-green-500 text-white w-12 h-12 flex items-center justify-center rounded-lg text-xl">
                        🎓
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-semibold mb-1">
                            Nama Siswa
                        </label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nama siswa..."
                               class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <!-- Kelas -->
                <div class="bg-yellow-50 rounded-xl p-5 flex gap-4 items-center">
                    <div class="bg-yellow-400 text-white w-12 h-12 flex items-center justify-center rounded-lg text-xl">
                        👥
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-semibold mb-1">
                            Kelas
                        </label>
                        <select name="kelas" class="w-full rounded-lg border-gray-300">
                            <option value="">Semua Kelas</option>
                            <option value="5" {{ request('kelas') == 5 ? 'selected' : '' }}>Kelas 5</option>
                            <option value="6" {{ request('kelas') == 6 ? 'selected' : '' }}>Kelas 6</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl shadow">
                    🔍 Tampilkan Hasil
                </button>
            </div>
        </form>
    </div>
</div>

        <!-- TABEL HASIL -->
<div class="bg-white rounded-2xl shadow-lg p-6 mt-8">

    <h2 class="text-xl font-bold mb-4">
        Hasil Ujian Siswa
    </h2>
<div class="flex items-center justify-between mb-4">

    {{-- BUTTON SYNC --}}
    <form action="{{ route('sync.server') }}" method="POST">
        @csrf

        <button
            type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl shadow"
        >
            🔄 Sinkronkan Nilai
        </button>
    </form>

    {{-- DOWNLOAD --}}
    <div class="relative">
        <button
            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl shadow"
            type="button"
            onclick="document.getElementById('downloadMenu').classList.toggle('hidden')"
        >
            ⬇️ Download
        </button>

        <div id="downloadMenu"
             class="hidden absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border z-50">

            <a href="{{ route('nilai.download-result', request()->all() + ['type' => 'pdf']) }}"
               class="block px-4 py-3 hover:bg-gray-100">
                📄 Download PDF
            </a>

            <a href="{{ route('nilai.download-result', request()->all() + ['type' => 'csv']) }}"
               class="block px-4 py-3 hover:bg-gray-100">
                📊 Download CSV
            </a>

        </div>
    </div>

</div>



    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
            @foreach($data as $index => $item)
            <thead>
                <tr class="bg-emerald-500 text-white">
                    <th class="p-3 text-left rounded-tl-xl w-12">No</th>
                    <th class="p-3 text-left">Nama Siswa</th>
                    <th class="p-3 text-left">Mata Pelajaran</th>
                    <th class="p-3 text-center ">Nilai</th>
                    <th class="p-3 text-center w-32 rounded-tr-xl">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                <tr class="hover:bg-gray-50">
                    <td class="p-3 font-semibold">{{ $index + 1 }}</td>
                    <td class="p-3">{{ $item->user->name }}</td>
                    <td class="p-3">{{ $item->exam->mapel }}</td>
                    <td class="p-3 text-center">
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold">
                            {{ $item->skor }}
                        </span>
                    </td>
                    <td class="p-3 text-center flex justify-center gap-3">
                        <a href="{{ route('exam.detail', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                            🔍 Detail
                        </a>
                        <button class="text-red-500 hover:text-red-700">
                            🗑️
                        </button>
                    </td>
                </tr>


            </tbody>
            @endforeach
        </table>
    </div>
</div>


    </div>
{{-- Tabel --}}

    
</div>

@endsection
