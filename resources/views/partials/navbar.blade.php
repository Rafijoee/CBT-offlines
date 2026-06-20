<nav class="px-4 sm:py-2 sm:px-10">
    <div class="flex items-center justify-between rounded-2xl p-6 text-white
                bg-gradient-to-r @yield('navbar-gradient', 'from-blue-500 to-purple-500') shadow-lg">

        <div>
            <h1 class="text-2xl font-bold">
                @yield('navbar-title', 'Selamat Datang')
            </h1>

            <p class="text-sm opacity-90">
                @hasSection('navbar-subtitle')
                    @yield('navbar-subtitle')
                @else
                    @if (Auth::user()->role == 'guru')
                    Halo Guru, Selamat Datang !
                    @elseif (Auth::user()->role == 'user')
                    Halo, {{ Auth::user()->name }}! Selamat Datang?
                    @endif
                @endif
            </p>
        </div>

        <div class="flex items-center gap-4">

            {{-- Custom Actions --}}
            @hasSection('navbar-actions')
                @yield('navbar-actions')
            @endif

            {{-- Dashboard --}}
            @if (Auth::user()->role == 'guru')
            <a href="{{ route('dashboard-guru') }}" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm font-semibold">
                Dashboard
            </a>
            @else 
            <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm font-semibold">
                Dashboard
            </a>
            @endif


            {{-- Logout --}}
            @hasSection('navbar-logout')
                @yield('navbar-logout')
            @else
                <a href="{{ route('logout') }}" 
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold text-white">
                    Keluar
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endif

        </div>

    </div>
</nav>