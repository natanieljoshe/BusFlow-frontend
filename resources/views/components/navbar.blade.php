<aside id="sidebar-nav"
    class="sidebar sticky top-0 h-screen flex flex-col gap-6 px-[18px] py-6 bg-[rgba(4,5,15,0.84)] border-r border-violet-500/[0.22] backdrop-blur-2xl shadow-[2px_0_40px_rgba(168,85,247,0.08)] max-[1180px]:sticky max-[1180px]:top-0 max-[1180px]:h-auto max-[1180px]:border-b max-[1180px]:border-r-0 z-[100] transition-all"
    aria-label="Navigasi BusFlow">

    <!-- Brand & Mobile Toggle -->
    <div class="flex items-center justify-between">
        <a class="flex items-center gap-3 px-2 no-underline text-[#eef4ff]" href="#">
            <div class="w-10 h-10 flex items-center justify-center shrink-0">
                <img src="{{ asset('assets/logo/logo_fix.png') }}" alt="BusFlow Logo" class="w-full h-full object-contain">
            </div>
            <span>
                <strong class="block text-lg">BusFlow</strong>
                <span class="text-[#7a8aaa] text-xs">Transit System</span>
            </span>
        </a>

        <!-- Hamburger button for tablet/mobile -->
        <button id="mobile-menu-btn"
            class="min-[1181px]:hidden text-[#c084fc] p-2 focus:outline-none bg-violet-500/10 rounded-lg border border-violet-500/20">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    <!-- Nav Content -->
    <div id="nav-content" class="flex flex-col flex-1 gap-6 max-[1180px]:hidden overflow-y-auto custom-scrollbar">

        <div class="p-[14px] border border-violet-500/20 rounded-[10px] bg-[rgba(4,5,15,0.95)] shrink-0">
            <span class="text-[#7a8aaa] text-xs">Current Session</span>
            <strong class="block mt-1 text-sm">{{ session('user.name') ?? 'Guest User' }}</strong>
            <p class="text-[10px] text-slate-400 mt-0.5">{{ session('user.email') ?? 'guest@busflow.com' }}</p>
            <p class="text-xs text-indigo-300 font-semibold mt-1">{{ ucfirst(session('user.role') ?? 'User') }} Mode</p>
            <span id="navbar-status-dot"
                class="status-dot inline-flex items-center gap-[7px] text-xs font-bold uppercase mt-2.5 text-[#86efac]">Active</span>
        </div>

        <nav class="flex-1 shrink-0 pb-4">
            @if(session('user.role') === 'passenger' || !session()->has('user'))
            <!-- User Section -->
            <div class="text-[10px] font-extrabold text-[#7a8aaa] uppercase tracking-[0.1em] mb-2.5 px-3">User Menu
            </div>
            <ul class="grid gap-1.5 list-none mb-6">
                <li>
                    <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                        {{ request()->routeIs('user.routes') ? 'is-active' : '' }}"
                        href="{{ route('user.routes') }}">
                        <span
                            class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                <circle cx="3" cy="12" r="1.8" stroke="currentColor"
                                    stroke-width="1.3" />
                                <circle cx="12" cy="3" r="1.8" stroke="currentColor"
                                    stroke-width="1.3" />
                                <path d="M3 10.2C3 7 6.5 5.5 7.5 5.5C8.5 5.5 12 4.5 12 4.8" stroke="currentColor"
                                    stroke-width="1.3" stroke-linecap="round" />
                                <path d="M4.5 7.5h6" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-dasharray="1.5 1.5" />
                            </svg>
                        </span>
                        <span>Routes</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                        {{ request()->routeIs('user.boarding-scan') ? 'is-active' : '' }}"
                        href="{{ route('user.boarding-scan') }}">
                        <span
                            class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                <rect x="1.5" y="3.5" width="12" height="8" rx="1.5" stroke="currentColor"
                                    stroke-width="1.3" />
                                <path d="M1.5 6.5h12" stroke="currentColor" stroke-width="1.3" />
                                <rect x="3" y="8.5" width="3" height="1.5" rx=".5" fill="currentColor" />
                            </svg>
                        </span>
                        <span>Boarding Scan</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                        {{ request()->routeIs('user.my-trip') ? 'is-active' : '' }}" href="{{ route('user.my-trip') }}">
                        <span class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M13.5 7.5L7.5 13.5m0 0L1.5 7.5m6 6V1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span>My Trip</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                        {{ request()->routeIs('user.my-wallet') ? 'is-active' : '' }}" href="{{ route('user.my-wallet') }}">
                        <span class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                        </span>
                        <span>My Wallet</span>
                    </a>
                </li>
            </ul>
            @endif

            @if(in_array(session('user.role'), ['driver', 'conductor']))
            <!-- Driver Section -->
            <div
                class="text-[10px] font-extrabold text-[#7a8aaa] uppercase tracking-[0.1em] mb-2.5 px-3 pt-2 border-t border-violet-500/10">
                Driver Menu</div>
            <ul class="grid gap-1.5 list-none">
                <li>
                    <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                        {{ request()->routeIs('sopir.home') ? 'is-active' : '' }}"
                        href="{{ route('sopir.home') }}">
                        <span
                            class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                <path d="M2 6.5L7.5 2l5.5 4.5v5a1 1 0 01-1 1h-3v-4H6v4H3a1 1 0 01-1-1z"
                                    stroke="currentColor" stroke-width="1.3" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                        {{ request()->routeIs('sopir.trip-details') ? 'is-active' : '' }}"
                        href="{{ route('sopir.trip-details') }}">
                        <span
                            class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                <rect x="2" y="3" width="11" height="9" rx="2" stroke="currentColor"
                                    stroke-width="1.3" />
                                <path d="M4 6h7M4 8.5h4" stroke="currentColor" stroke-width="1.2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span>Trip Details</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                        {{ request()->routeIs('sopir.history') ? 'is-active' : '' }}"
                        href="{{ route('sopir.history') }}">
                        <span
                            class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                <circle cx="7.5" cy="7.5" r="5.5" stroke="currentColor"
                                    stroke-width="1.3" />
                                <path d="M7.5 7.5v-3" stroke="currentColor" stroke-width="1.3"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span>History</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                        {{ request()->routeIs('sopir.boarding-scanner') ? 'is-active' : '' }}"
                        href="{{ route('sopir.boarding-scanner') }}">
                        <span
                            class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <path d="M7 7h.01"></path>
                                <path d="M17 7h.01"></path>
                                <path d="M7 17h.01"></path>
                                <path d="M17 17h.01"></path>
                            </svg>
                        </span>
                        <span>Boarding Scanner</span>
                    </a>
                </li>
            </ul>
            @endif
        </nav>

        <!-- Logout Section -->
        <div class="mt-auto pt-4 border-t border-violet-500/20 px-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 min-h-[42px] px-3 border border-rose-500/30 rounded-[10px] bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 text-sm font-bold transition-all duration-[220ms] shadow-[0_0_15px_rgba(244,63,94,0.1)] hover:shadow-[0_0_20px_rgba(244,63,94,0.3)]">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>
</aside>

<style>
    /* Custom scrollbar for nav */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(168, 85, 247, 0.2);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(168, 85, 247, 0.5);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dot = document.getElementById('navbar-status-dot');
        if (dot) {
            const statusColor = { aktif: '#86efac', istirahat: '#fde68a', cuti: '#fda4af' };
            const statusLabel = { aktif: 'Active', istirahat: 'On Break', cuti: 'Day Off' };
            const s = localStorage.getItem('bf_driver_status') || 'aktif';
            dot.style.color = statusColor[s] || statusColor.aktif;
            dot.textContent = statusLabel[s] || statusLabel.aktif;

            window.addEventListener('bf-status-change', (e) => {
                const ns = e.detail.status;
                dot.style.color = statusColor[ns] || statusColor.aktif;
                dot.textContent = statusLabel[ns] || statusLabel.aktif;
            });
        }

        const btn = document.getElementById('mobile-menu-btn');
        const content = document.getElementById('nav-content');
        const sidebar = document.getElementById('sidebar-nav');

        if (btn && content && sidebar) {
            btn.addEventListener('click', () => {
                content.classList.toggle('max-[1180px]:hidden');

                // Toggle full screen coverage on mobile
                sidebar.classList.toggle('max-[1180px]:fixed');
                sidebar.classList.toggle('inset-0');
                sidebar.classList.toggle('max-[1180px]:h-screen');
                sidebar.classList.toggle('max-[1180px]:bg-[rgba(4,5,15,0.98)]');

                // Disable body scroll
                document.body.classList.toggle('overflow-hidden');
            });
        }
    });
</script>
