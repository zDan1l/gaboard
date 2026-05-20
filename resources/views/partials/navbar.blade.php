<nav class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <h1 class="text-2xl font-bold text-orange-600">GaBoard</h1>
                <p class="text-xs text-gray-500">Sistem Penilaian Kinerja</p>
            </div>
        </div>

        <!-- Right Side -->
        <div class="flex items-center space-x-4">
            <!-- User Menu -->
            <div class="relative">
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->role->name }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                        <span class="text-orange-600 font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</nav>
