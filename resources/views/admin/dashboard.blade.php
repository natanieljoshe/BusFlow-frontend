@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Global Intelligence Hub')
@section('header_subtitle', 'System Monitoring & Control')

@section('content')
<div class="flex flex-col gap-6">

    <!-- 3. Hero banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900 p-5 sm:p-8 border border-indigo-500/20 shadow-lg">
        <!-- Abstract Background Effects -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-0">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2 tracking-tight">Global Intelligence Hub</h2>
                <p class="text-indigo-200/80 max-w-xl text-xs sm:text-sm leading-relaxed">Real-time status monitoring and maintenance scheduling. The genetic algorithm scheduler is actively optimizing fleet operations.</p>
            </div>
            <div class="hidden lg:block text-right bg-slate-900/40 p-4 rounded-xl border border-indigo-500/20 backdrop-blur-sm">
                <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wider mb-1"><i class="fa-regular fa-clock"></i> System Time</div>
                <div class="text-2xl font-mono text-white tracking-widest" id="system-time">--:--:--</div>
            </div>
        </div>
    </div>

    <!-- 4. System Overview — 3 stat cards -->
    <div>
        <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-indigo-400"></i> System Overview
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Total Bus Aktif -->
            <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 hover:border-indigo-500/30 transition-all duration-300 hover:shadow-[0_0_20px_rgba(99,102,241,0.1)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-colors"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2.5 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                        <i class="fa-solid fa-bus text-lg"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <i class="fa-solid fa-arrow-trend-up"></i> +12%
                    </span>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1" id="stat-buses">45</div>
                    <div class="text-sm font-medium text-slate-400">Total Bus Aktif</div>
                </div>
            </div>

            <!-- Card 2: Sopir Tersedia -->
            <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 hover:border-purple-500/30 transition-all duration-300 hover:shadow-[0_0_20px_rgba(168,85,247,0.1)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-colors"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2.5 rounded-lg bg-purple-500/10 text-purple-400 border border-purple-500/20">
                        <i class="fa-solid fa-id-card text-lg"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                        <i class="fa-solid fa-minus"></i> 0%
                    </span>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1" id="stat-drivers">32</div>
                    <div class="text-sm font-medium text-slate-400">Sopir Tersedia</div>
                </div>
            </div>

            <!-- Card 3: Trip Hari Ini -->
            <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 hover:border-sky-500/30 transition-all duration-300 hover:shadow-[0_0_20px_rgba(14,165,233,0.1)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-sky-500/10 rounded-full blur-xl group-hover:bg-sky-500/20 transition-colors"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2.5 rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20">
                        <i class="fa-solid fa-route text-lg"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <i class="fa-solid fa-arrow-trend-up"></i> +5%
                    </span>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1" id="stat-trips">128</div>
                    <div class="text-sm font-medium text-slate-400">Trip Hari Ini</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5 & 6. Chart & Schedule Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Trips -->
        <div class="lg:col-span-2 bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-200">Tren Operasional (Trips)</h3>
                    <p class="text-xs text-slate-400 mt-1">Monitoring jumlah trip seminggu terakhir</p>
                </div>
                <div class="flex bg-slate-800/80 rounded-lg p-1 border border-slate-700/50">
                    <div class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-slate-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 shadow-[0_0_5px_rgba(99,102,241,0.8)]"></span>
                        Jumlah Trips
                    </div>
                </div>
            </div>
            <div class="h-[280px] w-full relative">
                <canvas id="tripsChart"></canvas>
            </div>
        </div>

        <!-- Status Jadwal -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 flex flex-col relative overflow-hidden">
            <!-- decorative background -->
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-purple-500/5 rounded-full blur-2xl pointer-events-none"></div>
            
            <h3 class="text-lg font-semibold text-slate-200 mb-8 flex items-center gap-2">
                <i class="fa-solid fa-microchip text-indigo-400"></i> Status Jadwal Aktif
            </h3>
            
            <div class="flex-1 flex flex-col justify-center items-center text-center">
                <div class="relative mb-6">
                    <!-- Spinning ring animation effect -->
                    <div class="absolute inset-0 border-2 border-indigo-500/30 rounded-full animate-[spin_3s_linear_infinite]"></div>
                    <div class="absolute -inset-2 border-2 border-dashed border-purple-500/30 rounded-full animate-[spin_6s_linear_infinite_reverse]"></div>
                    <div class="absolute inset-2 border-2 border-sky-500/20 rounded-full"></div>
                    
                    <div class="w-32 h-32 rounded-full bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700 flex flex-col items-center justify-center p-4 relative z-10 shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
                        <span class="text-3xl font-bold text-white tracking-tight drop-shadow-md" id="schedule-gen">G-124</span>
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest mt-1">Generasi</span>
                    </div>
                </div>

                <div class="space-y-3 w-full">
                    <div class="inline-flex w-full justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium shadow-[inset_0_0_10px_rgba(16,185,129,0.1)]" id="schedule-status">
                        <i class="fa-solid fa-check-circle"></i> Computation Complete
                    </div>
                    <p class="text-xs text-slate-400 px-4 leading-relaxed">Jadwal operasional hari ini sudah berhasil di-generate secara optimal.</p>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('admin.schedule') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl transition-all duration-300 text-sm font-medium shadow-[0_0_20px_rgba(99,102,241,0.3)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5 group">
                    <span>Lihat Detail Jadwal</span> 
                    <i class="fa-solid fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 7. Alert Maintenance -->
    <div>
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl overflow-hidden shadow-lg">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between bg-slate-800/20 gap-3 sm:gap-0">
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Alert Maintenance
                    </h3>
                    <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Bus yang mendekati atau melewati jadwal servis berkala</p>
                </div>
                <a href="{{ route('admin.fleet') }}" class="text-[10px] sm:text-xs font-medium px-3 py-1.5 rounded bg-slate-800 text-indigo-400 hover:bg-slate-700 hover:text-indigo-300 transition-colors border border-slate-700">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/80 text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-800">
                            <th class="px-6 py-4 font-medium">Armada ID</th>
                            <th class="px-6 py-4 font-medium">Odometer</th>
                            <th class="px-6 py-4 font-medium">Status Prioritas</th>
                            <th class="px-6 py-4 font-medium text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-800/50" id="maintenance-table-body">
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat data maintenance...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Live Clock for Hero Banner
    function updateTime() {
        const now = new Date();
        document.getElementById('system-time').innerText = now.toLocaleTimeString('en-US', { hour12: false });
    }
    setInterval(updateTime, 1000);
    updateTime();
</script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    // Inject API URL dynamically or fallback
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    
    // Retrieve Bearer token from localStorage
    const token = localStorage.getItem('token');

    if (!token) {
        console.warn("No token found.");
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    // Helper function to fetch data and handle 401s
    async function fetchWithAuth(endpoint) {
        if (!token) return []; 
        try {
            const response = await fetch(`${API_URL}${endpoint}`, { headers });
            
            if (response.status === 401) {
                localStorage.removeItem('token');
                return [];
            }
            if (!response.ok) return [];
            
            const data = await response.json();
            return Array.isArray(data) ? data : (data.data || []);
        } catch (error) {
            console.error(`Error fetching ${endpoint}:`, error);
            return [];
        }
    }

    async function fetchUser() {
        if (!token) return null;
        try {
            const response = await fetch(`${API_URL}/api/me`, { headers });
            if (!response.ok) return null;
            return await response.json();
        } catch(e) { return null; }
    }

    // Chart.js Setup
    const ctx = document.getElementById('tripsChart').getContext('2d');
    const gradientIndigo = ctx.createLinearGradient(0, 0, 0, 400);
    gradientIndigo.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
    gradientIndigo.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

    const dashboardChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [
                {
                    label: 'Trips Harian',
                    data: [15, 22, 18, 30, 25, 35, 20],
                    backgroundColor: gradientIndigo,
                    borderColor: '#818cf8',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
            scales: { x: { grid: { color: 'rgba(51, 65, 85, 0.4)', drawBorder: false, borderDash: [5, 5] }, ticks: { color: '#94a3b8' } }, y: { grid: { color: 'rgba(51, 65, 85, 0.4)', drawBorder: false, borderDash: [5, 5] }, ticks: { color: '#94a3b8' } } },
            interaction: { mode: 'nearest', axis: 'x', intersect: false }
        }
    });

    // Run parallel API requests to real endpoints
    const [meData, buses, drivers, trips, schedules] = await Promise.all([
        fetchUser(),
        fetchWithAuth('/api/admin/buses'),
        fetchWithAuth('/api/admin/drivers'),
        fetchWithAuth('/api/admin/trips'),
        fetchWithAuth('/api/admin/schedules')
    ]);

    // 1. Update Header (Avatar & Name)
    if (meData) {
        const user = meData.data || meData;
        document.getElementById('header-admin-name').innerText = user.name || 'Admin';
        if (user.avatar) {
            document.getElementById('header-admin-avatar').src = user.avatar;
        }
    }

    // 2. Update System Overview 3 Stats Cards
    const activeBuses = buses.filter(b => (b.status || '').toLowerCase() === 'active').length || buses.length;
    const activeDrivers = drivers.filter(d => (d.status || '').toLowerCase() === 'available' || (d.status || '').toLowerCase() === 'on_duty').length || drivers.length;
    
    document.getElementById('stat-buses').innerText = activeBuses;
    document.getElementById('stat-drivers').innerText = activeDrivers;
    document.getElementById('stat-trips').innerText = trips.length;

    // 3. Update Chart: Performa Algoritma GA
    // Using mock data for trips instead of waiting for GA.
    if(trips && trips.length > 0) {
        dashboardChart.data.datasets[0].data[6] = trips.length;
        dashboardChart.update();
    }

    // 4. Update Status Jadwal Panel
    const statusEl = document.getElementById('schedule-status');
    if (schedules && schedules.length > 0) {
        document.getElementById('schedule-gen').innerText = `G-TBD`;
        statusEl.className = 'inline-flex w-full justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium shadow-[inset_0_0_10px_rgba(16,185,129,0.1)]';
        statusEl.innerHTML = '<i class="fa-solid fa-check-circle"></i> Selesai (Optimal)';
    } else {
        document.getElementById('schedule-gen').innerText = `G-0`;
        statusEl.className = 'inline-flex w-full justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-400 text-sm font-medium';
        statusEl.innerHTML = '<i class="fa-solid fa-clock"></i> Menunggu Jadwal...';
    }

    // 5. Update Alert Maintenance Table
    const maintenanceBuses = buses.filter(b => b.status == 0 || b.status === false || (typeof b.status === 'string' && (b.status.toLowerCase() === 'maintenance' || b.status.toLowerCase() === 'alert')));
    const tbody = document.getElementById('maintenance-table-body');
    
    tbody.innerHTML = '';
    
    if (maintenanceBuses.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm">Semua armada dalam kondisi prima. Tidak ada jadwal pemeliharaan.</td></tr>`;
    } else {
        maintenanceBuses.forEach(bus => {
            const busId = bus.plate_number || bus.id || 'Unknown';
            const odometer = bus.odometer || 0;
            const statusLabel = bus.status || 'Maintenance';
            const isCritical = statusLabel.toLowerCase() === 'alert';
            
            const badgeClass = isCritical ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20';
            const badgeIconClass = isCritical ? 'bg-red-500 animate-pulse shadow-[0_0_5px_rgba(239,68,68,0.8)]' : 'bg-amber-500 shadow-[0_0_5px_rgba(245,158,11,0.8)]';

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-800/40 transition-colors group';
            tr.innerHTML = `
                <td class="px-6 py-4 font-medium text-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center border border-slate-700 shadow-inner">
                            <i class="fa-solid fa-bus-simple text-slate-400 text-xs"></i>
                        </div>
                        ${busId}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-slate-300 font-mono text-xs bg-slate-800/50 px-2 py-1 rounded border border-slate-700/50">${odometer.toLocaleString()} km</span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium ${badgeClass} border">
                        <span class="w-1.5 h-1.5 rounded-full ${badgeIconClass}"></span> ${statusLabel}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="/admin/fleet" class="px-3 py-1.5 rounded-lg border border-slate-700 text-slate-400 hover:bg-indigo-500/10 hover:text-indigo-400 hover:border-indigo-500/30 transition-colors text-xs font-medium opacity-70 group-hover:opacity-100 inline-block">
                        <i class="fa-solid fa-arrow-right mr-1"></i> View in Fleet
                    </a>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
});
</script>
@endpush
