<nav class="bg-white shadow px-6 py-4">
    <div class="flex justify-between items-center">
        <h1 class="font-bold text-lg">CBT System</h1>

        <div>
            <span class="mr-4">{{ auth()->user()->name ?? 'Guest' }}</span>
            <a href="{{ route('logout') }}" class="text-red-500">Logout</a>
        </div>
    </div>
</nav>
