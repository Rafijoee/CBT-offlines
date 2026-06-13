@extends('layouts.app')

@section('title', 'Kelola Siswa')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow p-6">

        <h1 class="text-2xl font-bold">
            👨‍🎓 Kelola Siswa
        </h1>

        <p class="text-gray-500 mt-1">
            Kelola data siswa yang dapat mengikuti ujian.
        </p>

    </div>

    {{-- Action --}}
    <div class="flex justify-between items-center">

        <div class="flex gap-3">

            <a href="{{ route('siswa.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                ➕ Tambah Siswa
            </a>


        </div>

        <form method="GET"
            action="{{ route('siswa.index') }}">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari siswa..."
                class="border rounded-lg px-4 py-2">

        </form>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-[#06D6A0] text-white">

                <tr>
                    <th class="p-4 text-left">No</th>
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Kelas</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($students as $index => $student)

                <tr class="border-b">

                    <td class="p-4">
                        {{ $index + 1 }}
                    </td>

                    <td class="p-4">
                        {{ $student->name }}
                    </td>

                    <td class="p-4">
                        {{ $student->email }}
                    </td>

                    <td class="p-4">
                        {{ $student->kelas }}
                    </td>

                    <td class="p-4">

                        <div class="flex justify-center gap-2">

                            {{-- Edit --}}
                            <a href="{{ route('siswa.edit', $student) }}"
                                class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                                ✏️ Edit
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('siswa.destroy', $student) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Hapus siswa ini?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                    🗑️ Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5"
                        class="text-center py-8 text-gray-500">

                        Belum ada data siswa

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection