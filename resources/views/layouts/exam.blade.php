<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Exam Mode')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#EAF3FF] bg-[radial-gradient(#bcd9ff_1px,transparent_1px)] [background-size:20px_20px] p-4">
    <div 
        class="min-h-screen bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('storage/images/background.png') }}');"
    >

    <main class="py-6 sm:px-6 lg:px-10 px-4">
        @yield('content')
    </main>

</body>
</html>