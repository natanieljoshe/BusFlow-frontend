<header class="h-16 bg-[#0f172a]/80 backdrop-blur-md border-b border-slate-800 flex items-center justify-between px-6 z-10 sticky top-0">
    <div>
        <h1 class="text-lg font-semibold text-slate-100 tracking-wide">@yield('header_title', 'Global Intelligence Hub')</h1>
        <p class="text-xs text-slate-400">@yield('header_subtitle', 'System Monitoring & Control')</p>
    </div>

    <div class="flex items-center gap-5">
        <!-- System Status -->
        <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
            <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
            <span class="text-xs font-medium text-emerald-400">System Nominal</span>
        </div>

        <div class="h-6 w-px bg-slate-700"></div>

        <!-- Notifications -->
        <button class="text-slate-400 hover:text-indigo-400 transition-colors relative">
            <i class="fa-regular fa-bell text-lg"></i>
            <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_5px_rgba(99,102,241,0.8)]"></span>
        </button>

        <!-- Settings -->
        <button class="text-slate-400 hover:text-indigo-400 transition-colors">
            <i class="fa-solid fa-gear text-lg"></i>
        </button>

        <!-- User Profile -->
        <div class="flex items-center gap-3 ml-2 cursor-pointer group">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-medium text-slate-200 group-hover:text-indigo-300 transition-colors" id="header-admin-name">Loading...</p>
                <p class="text-xs text-slate-500" id="header-admin-role">Admin</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=Admin&background=1e293b&color=818cf8" alt="Admin" id="header-admin-avatar" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</header>
