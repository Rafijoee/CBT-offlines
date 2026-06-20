@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-2xl shadow p-6">

        <h1 class="text-2xl font-bold mb-2">
            ✏️ Edit Siswa
        </h1>

        <p class="text-gray-500 mb-6">
            Ubah data siswa.
        </p>

        <form action="{{ route('siswa.update', $siswa) }}"
              method="POST"
              class="space-y-5">

            @csrf
            @method('PUT')

            <div>
                <label class="block mb-2 font-medium">
                    Nama Siswa
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $siswa->name) }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    email
                </label>

                <input
                    type="text"
                    name="email"
                    value="{{ old('email', $siswa->email) }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div class="flex gap-3">

                <a href="{{ route('siswa.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">
                    Kembali
                </a>

                <button
                    type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-lg">
                    Update
                </button>

            </div>

        </form>

    </div>

</div>

@endsection