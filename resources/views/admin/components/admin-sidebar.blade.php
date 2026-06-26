<!-- Mobile overlay -->
<div id="mobile-overlay"
    class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-20 hidden md:hidden transition-opacity opacity-0"></div>

<aside id="sidebar"
    class="fixed md:static inset-y-0 left-0 w-64 bg-[#0f172a] border-r border-slate-800 flex-col justify-between z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex">
    <div class="flex-1 overflow-y-auto">
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 flex items-center justify-center">
                    <img src="{{ asset('assets/logo/logo_fix.png') }}" alt="BusFlow Logo"
                        class="w-full h-full object-contain">
                </div>
                <span
                    class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">BusFlow</span>
            </div>
        </div>

        <nav class="p-4 space-y-2 mt-2">
            @php $userRole = session('user')['role'] ?? 'admin'; @endphp

            <!-- Menu Items -->
            @if ($userRole !== 'operator')
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shadow-[inset_0_0_20px_rgba(99,102,241,0.1)]' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <i class="fa-solid fa-desktop w-5 text-center"></i>
                    <span class="font-medium text-sm">Command Center</span>
                </a>

                <a href="{{ route('admin.fleet') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.fleet') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <i class="fa-solid fa-bus-simple w-5 text-center"></i>
                    <span class="font-medium text-sm">Fleet Management</span>
                </a>

                <a href="{{ route('admin.staff') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.staff') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span class="font-medium text-sm">Staff Directory</span>
                </a>
            @endif

            <a href="{{ route('admin.routes') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.routes') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                <i class="fa-solid fa-route w-5 text-center"></i>
                <span class="font-medium text-sm">Routes</span>
            </a>

            @if ($userRole !== 'operator')
                <a href="{{ route('admin.haltes') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.haltes') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <i class="fa-solid fa-map-pin w-5 text-center"></i>
                    <span class="font-medium text-sm">Haltes</span>
                </a>

                <a href="{{ route('admin.users') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.users') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <i class="fa-solid fa-user-shield w-5 text-center"></i>
                    <span class="font-medium text-sm">Users & RBAC</span>
                </a>

                <a href="{{ route('admin.analytics') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.analytics') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i>
                    <span class="font-medium text-sm">Analytics</span>
                </a>

                <a href="{{ route('admin.ga-optimizer') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.ga-optimizer') || request()->routeIs('admin.ga-results') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shadow-[inset_0_0_20px_rgba(99,102,241,0.1)]' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <i class="fa-solid fa-wand-magic-sparkles w-5 text-center"></i>
                    <span class="font-medium text-sm">AI Optimizer</span>
                </a>
            @endif

            <a href="{{ route('admin.boarding-scanner') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.boarding-scanner') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shadow-[inset_0_0_20px_rgba(99,102,241,0.1)]' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                <i class="fa-solid fa-qrcode w-5 text-center"></i>
                <span class="font-medium text-sm">Boarding Scanner</span>
            </a>
        </nav>
    </div>

    <div class="p-4 space-y-3">
        <a href="#" onclick="handleLogout(event)"
            class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors text-sm font-medium">
            <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
            Exit System
        </a>
    </div>
</aside>

<script>
    async function handleLogout(e) {
        e.preventDefault();
        const token = localStorage.getItem('token');

        // Optional: Ganti tulisan jadi logging out untuk UX
        const btn = e.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin w-5 text-center"></i> Exiting...';
        btn.style.pointerEvents = 'none';

        if (token) {
            try {
                await fetch(`${API_URL}/api/logout`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
            } catch (error) {
                console.error('Logout error:', error);
            }
        }

        // Hapus token dan arahkan ke login page
        localStorage.removeItem('token');
        window.location.href = "{{ route('login') }}";
    }
</script>
