<aside class="w-64 bg-gray-900 text-white flex flex-col fixed h-full">
    <!-- Logo Area (Fixed di atas) -->
    <div class="p-6 border-b border-gray-800 flex flex-col justify-center shrink-0">
        <div class="flex flex-col justify-center items-center">
            <img src="{{ asset('gaboard-logo.png') }}" alt="GaBoard Logo" width="100">
        </div>
        <p class="text-xs text-gray-400 text-center">Sistem Penilaian Kinerja</p>
    </div>

    <!-- Navigation (Scrollable) -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto scrollbar-hide">
        @if(auth()->user()->role->slug === 'hr_manager')
            <!-- HR/Manager Menu -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">HR Manager</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Evaluations -->
            <a href="{{ route('evaluations.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('evaluations.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>Penilaian</span>
            </a>

            <!-- Employees -->
            <a href="{{ route('employees.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('employees.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Karyawan</span>
            </a>

            <!-- Departments -->
            <a href="{{ route('departments.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('departments.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Departemen</span>
            </a>

            <!-- Reports -->
            <a href="{{ route('exports.summary-report') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('exports.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Laporan</span>
            </a>

            <!-- KPI Targets -->
            <a href="{{ route('kpi-targets.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('kpi-targets.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Target KPI</span>
            </a>

            <!-- Attendance Schedules -->
            <a href="{{ route('attendance-schedules.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('attendance-schedules.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Jadwal Absensi</span>
            </a>

            <!-- Manage Attendance Entries -->
            <a href="{{ route('attendance-entries.manage') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('attendance-entries.manage') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Kelola Absensi</span>
            </a>

            <!-- Customer Satisfaction -->
            <a href="{{ route('customer-satisfaction.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('customer-satisfaction.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Kepuasan Pelanggan</span>
            </a>

        @elseif(auth()->user()->role->slug === 'employee')
            <!-- Employee Menu -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Karyawan</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- My Evaluations -->
            <a href="{{ route('evaluations.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('evaluations.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Hasil Penilaian Saya</span>
            </a>

            <!-- My KPI Reports -->
            <a href="{{ route('kpi-reports.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('kpi-reports.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <span>Laporan KPI Saya</span>
            </a>

            <!-- My Attendance -->
            <a href="{{ route('attendance-entries.my-attendance') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('attendance-entries.my-attendance') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Absensi Saya</span>
            </a>

            <!-- My Customer Satisfaction -->
            <a href="{{ route('customer-satisfaction.my-scores') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('customer-satisfaction.my-scores') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <span>Nilai Kepuasan Saya</span>
            </a>
        @endif
    </nav>
</aside>
