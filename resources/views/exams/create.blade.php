@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<form action="{{ route('store-exams') }}" method="POST">
    @csrf

    <div class="mb-4">
        <label for="mapel" class="block text-gray-700 font-bold mb-2">mapel</label>
        <input type="text" name="mapel" id="mapel" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>

    <div class="mb-4">
        <label for="soal" class="block text-gray-700 font-bold mb-2">Soal</label>
        <input type="text" name="soal" id="soal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="time" class="block text-gray-700 font-bold mb-2">time</label>
        <input type="text" name="time" id="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="opened_time" class="block text-gray-700 font-bold mb-2">Opened Time</label>
        <input type="datetime-local" name="opened_time" id="opened_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="closed_time" class="block text-gray-700 font-bold mb-2">Closed Time</label>
        <input type="datetime-local" name="closed_time" id="closed_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Create User Exam
    </button>
@endsection
