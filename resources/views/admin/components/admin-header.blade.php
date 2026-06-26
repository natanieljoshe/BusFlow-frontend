<header
    class="min-h-[4rem] py-2 bg-[#0f172a]/80 backdrop-blur-md border-b border-slate-800 flex items-center justify-between px-4 sm:px-6 z-10 sticky top-0">
    <div class="flex items-center gap-3 sm:gap-4 flex-1">
        <button id="mobile-menu-btn"
            class="md:hidden text-slate-400 hover:text-indigo-400 transition-colors focus:outline-none shrink-0">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <div class="flex flex-col justify-center">
            <h1 class="text-sm sm:text-lg font-semibold text-slate-100 tracking-wide leading-snug break-words">
                @yield('header_title', 'Global Intelligence Hub')</h1>
            <p class="text-[10px] sm:text-xs text-slate-400 leading-tight mt-0.5 break-words">@yield('header_subtitle', 'System Monitoring & Control')</p>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        <!-- System Status -->

        <div class="hidden md:block h-6 w-px bg-slate-700"></div>

        <!-- User Profile -->
        <div class="flex items-center gap-3 ml-2 cursor-pointer group">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-slate-200" id="header-admin-name">{{ session('user.name') ?? 'Admin User' }}</p>
                <p class="text-xs text-slate-500">{{ ucfirst(session('user.role') ?? 'Admin') }} &bull; {{ session('user.email') ?? 'admin@busflow.com' }}</p>
            </div>
            <div
                class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session('user.name') ?? 'Admin') }}&background=1e293b&color=818cf8" alt="Admin"
                    id="header-admin-avatar" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</header>
