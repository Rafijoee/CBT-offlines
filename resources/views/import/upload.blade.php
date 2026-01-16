@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <input type="hidden" name="exam_id" value="1">

  <input type="file" name="file" required>

  <button type="submit">Upload Excel</button>
</form>

@endsection
