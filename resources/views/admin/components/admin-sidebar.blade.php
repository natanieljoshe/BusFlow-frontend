<aside class="w-64 bg-[#0f172a] border-r border-slate-800 flex flex-col justify-between hidden md:flex z-20">
    <div>
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-[0_0_15px_rgba(99,102,241,0.5)]">
                    <i class="fa-solid fa-bus text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">BusFlow</span>
            </div>
        </div>

        <nav class="p-4 space-y-2 mt-2">
            <!-- Menu Items -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shadow-[inset_0_0_20px_rgba(99,102,241,0.1)]' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                <i class="fa-solid fa-desktop w-5 text-center"></i>
                <span class="font-medium text-sm">Command Center</span>
            </a>

            <a href="{{ route('admin.fleet') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.fleet') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                <i class="fa-solid fa-bus-simple w-5 text-center"></i>
                <span class="font-medium text-sm">Fleet Management</span>
            </a>

            <a href="{{ route('admin.staff') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.staff') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span class="font-medium text-sm">Staff Directory</span>
            </a>

            <a href="{{ route('admin.schedule') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.schedule') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                <i class="fa-solid fa-calendar-alt w-5 text-center"></i>
                <span class="font-medium text-sm">GA Scheduler</span>
            </a>

            <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.analytics') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                <i class="fa-solid fa-chart-line w-5 text-center"></i>
                <span class="font-medium text-sm">Analytics</span>
            </a>
        </nav>
    </div>

    <div class="p-4 space-y-3">
        <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-colors text-sm font-medium">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Emergency Override
        </button>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors text-sm font-medium">
            <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
            Exit System
        </a>
    </div>
</aside>
