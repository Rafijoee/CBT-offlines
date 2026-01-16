@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- exam_id wajib --}}
    <input type="hidden" name="exam_id" value="{{ $exam->id ?? 1 }}">

    {{-- file ZIP --}}
    <input type="file" name="file" required>
    
    <button type="submit">Upload & Preview</button>
</form>


@endsection
