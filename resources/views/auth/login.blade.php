<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login CBT</title>
    @vite('resources/css/app.css')
</head>
<body
    class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ asset('storage/images/background.png') }}')"
>

<div class="w-full max-w-6xl px-4">
    <!-- Pastikan menggunakan flex-row dan items-stretch -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row items-stretch min-h-[550px]">
        
        <!-- LEFT IMAGE -->
        <div class="hidden md:block md:w-1/2 relative"> 
            <img
                src="{{ asset('storage/images/login.png') }}"
                alt="School"
                class="absolute inset-0 w-full h-full object-cover"
            >
        </div>

        <!-- RIGHT FORM -->
        <!-- Tambahkan z-10 dan relative untuk memastikan form berada di lapisan atas dan bisa diklik -->
        <div class="w-full md:w-1/2 p-10 flex flex-col justify-center relative z-10 bg-white">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl p-6 text-center mb-8">
                <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center bg-white/20 rounded-full text-2xl">
                    🎓
                </div>
                <h1 class="text-xl font-semibold">Ujian Online Sekolah</h1>
                <p class="text-sm opacity-90">Sistem CBT Berbasis Web</p>
                @if (session()->has('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 text-center">
                        {{session()->get('error')}}
                    </div>
                @endif
            </div>

            <h2 class="text-xl font-semibold mb-1">Selamat Datang!</h2>
            <p class="text-sm text-gray-500 mb-6">Masuk untuk mengikuti ujian online</p>



            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="Masukkan Email"
                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm h-10 border-3 px-3"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            placeholder="********"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm h-10 border-3 px-3"
                        >
                    </div>
                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition"
                >
                    Masuk Sekarang
                </button>
            </form>
        </div>

    </div>
</div>


</body>
</html>
