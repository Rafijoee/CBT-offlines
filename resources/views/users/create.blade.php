@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-2xl shadow p-6">

        <h1 class="text-2xl font-bold mb-2">
            ➕ Tambah Siswa
        </h1>

        <p class="text-gray-500 mb-6">
            Tambahkan data siswa baru.
        </p>


        <form action="{{ route('siswa.store') }}"
              method="POST"
              class="space-y-5">

            @csrf

            <div>
                <label class="block mb-2 font-medium">
                    Nama Siswa
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border rounded-lg p-3">

                @error('name')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg p-3">

                <small class="text-gray-500">
                    Minimal 6 karakter
                </small>

                @error('password')
                    <small class="text-red-500 block">{{ $message }}</small>
                @enderror
            </div>

            <div class="flex gap-3">


                <button
                    type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-lg">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection