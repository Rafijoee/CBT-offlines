<nav class="px-4 sm:py-2 sm:px-10">
    <div class="flex items-center justify-between rounded-2xl p-6 text-white
                bg-gradient-to-r from-blue-500 to-purple-500 shadow-lg">
        <div>
            <h1 class="text-2xl font-bold">Selamat Datang, {{Auth::user()->name }}</h1>
            <p class="text-sm opacity-90">Halo, {{Auth::user()->name }}! Siap ujian hari ini?</p>
        </div>

        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5z"/>
                </svg>
            </div>
            <button class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold">
                Keluar
            </button>
        </div>
    </div>
</nav>
