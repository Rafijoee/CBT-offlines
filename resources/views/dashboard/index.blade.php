@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

    <p>Selamat datang di sistem CBT.</p>



    <a href="{{ route('exams') }}" class="block p-2 hover:bg-gray-100">
        exams
    </a>
@endsection
