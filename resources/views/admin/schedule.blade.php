@extends('admin.layouts.admin')

@section('title', 'GA Scheduler')
@section('header_title', 'Genetic Algorithm Control Center')
@section('header_subtitle', 'Automated schedule optimization engine')

@section('content')
<div class="flex flex-col gap-6">

    <!-- Top Panel: Control & Current Status -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Parameter Tuning Panel -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-indigo-400"></i> GA Parameters
            </h3>
            
            <form class="space-y-5">
                <div>
                    <div class="flex justify-between mb-1.5">
                        <label class="text-xs font-medium text-slate-400">Population Size</label>
                        <span class="text-xs text-indigo-400 font-mono">100</span>
                    </div>
                    <input type="range" min="10" max="200" value="100" class="w-full h-1.5 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                </div>
                
                <div>
                    <div class="flex justify-between mb-1.5">
                        <label class="text-xs font-medium text-slate-400">Max Generations</label>
                        <span class="text-xs text-indigo-400 font-mono">500</span>
                    </div>
                    <input type="range" min="100" max="1000" value="500" class="w-full h-1.5 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Mutation Rate</label>
                        <div class="relative">
                            <input type="text" value="0.05" class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Crossover Rate</label>
                        <div class="relative">
                            <input type="text" value="0.8" class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 font-mono">
                        </div>
                    </div>
                </div>

                <button type="button" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg px-4 py-2.5 text-sm font-medium transition-colors shadow-[0_0_15px_rgba(79,70,229,0.3)] flex justify-center items-center gap-2">
                    <i class="fa-solid fa-play"></i> Run Optimization
                </button>
            </form>
        </div>

        <!-- Generation Progress -->
        <div class="xl:col-span-2 bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 flex flex-col justify-center items-center relative overflow-hidden shadow-lg">
            <div class="absolute -top-32 -left-32 w-64 h-64 bg-indigo-500 rounded-full mix-blend-screen filter blur-[100px] opacity-20"></div>
            <div class="absolute -bottom-32 -right-32 w-64 h-64 bg-purple-500 rounded-full mix-blend-screen filter blur-[100px] opacity-20"></div>
            
            <div class="z-10 w-full max-w-lg">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-white tracking-wide">Optimization In Progress</h3>
                        <p class="text-xs text-indigo-400 mt-1 uppercase tracking-widest font-mono">Generasi 124 / 500</p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-white font-mono">24%</span>
                    </div>
                </div>
                
                <div class="w-full bg-slate-800 rounded-full h-3 mb-6 p-0.5 border border-slate-700">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full relative overflow-hidden shadow-[0_0_10px_rgba(99,102,241,0.5)]" style="width: 24%">
                        <div class="absolute inset-0 bg-white/20 w-full h-full animate-[shimmer_1s_infinite] -translate-x-full" style="background-image: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);"></div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 text-center border-t border-slate-800/50 pt-4">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Best Fitness</p>
                        <p class="text-lg font-bold text-emerald-400">98.4%</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Time Elapsed</p>
                        <p class="text-lg font-bold text-slate-300 font-mono">1m 42s</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Stagnation</p>
                        <p class="text-lg font-bold text-slate-300 font-mono">0 gen</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Optimal Schedule Results -->
    <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl overflow-hidden shadow-lg">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between bg-slate-800/20">
            <div>
                <h3 class="text-lg font-semibold text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-table-list text-indigo-400"></i> Best Schedule Result
                </h3>
                <p class="text-xs text-slate-400 mt-1">Jadwal dari kromosom terbaik di generasi terakhir</p>
            </div>
            <button class="text-xs font-medium px-4 py-2 rounded bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors border border-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-download"></i> Export
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <th class="px-6 py-4 font-medium">Trip ID</th>
                        <th class="px-6 py-4 font-medium">Route / Time</th>
                        <th class="px-6 py-4 font-medium">Bus Assignment</th>
                        <th class="px-6 py-4 font-medium">Driver Assignment</th>
                        <th class="px-6 py-4 font-medium text-right">Fitness</th>
                    </tr>
                </thead>
                <tbody id="schedule-tbody" class="text-sm divide-y divide-slate-800/50">
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading schedules...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-800 bg-slate-900 text-center">
            <button class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">Load More Rows</button>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = window.API_URL || 'http://localhost:8001';
    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('schedule-tbody').innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-red-400">Silakan login terlebih dahulu. Token tidak ditemukan.</td></tr>`;
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    async function fetchWithAuth(endpoint) {
        try {
            const response = await fetch(`${API_URL}${endpoint}`, { headers });
            if (response.status === 401) {
                localStorage.removeItem('token');
                document.getElementById('schedule-tbody').innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-red-400">Sesi telah berakhir. Silakan login kembali.</td></tr>`;
                return [];
            }
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            return Array.isArray(data) ? data : (data.data || []);
        } catch (error) {
            console.error(`Error fetching ${endpoint}:`, error);
            return [];
        }
    }

    const trips = await fetchWithAuth('/api/admin/trips');
    const tbody = document.getElementById('schedule-tbody');
    tbody.innerHTML = '';
    
    if (trips.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No schedules generated yet.</td></tr>`;
        return;
    }

    trips.forEach(trip => {
        const tripId = `TRP-${String(trip.id).padStart(4, '0')}`;
        const routeName = trip.route?.name || `Route ${trip.route_id}`;
        const time = `${trip.departure_time || '--:--'} - ${trip.estimated_arrival || '--:--'}`;
        const bus = trip.bus?.plate_number || 'Unassigned';
        const driver = trip.driver?.user?.name || 'Unassigned';
        const fitness = trip.schedule?.fitness_score || 0;
        
        const hasConflict = !trip.bus_id || !trip.driver_id || fitness < 0;
        const rowClass = hasConflict ? 'bg-red-500/5 hover:bg-slate-800/40' : 'hover:bg-slate-800/40';

        tbody.innerHTML += `
        <tr class="${rowClass} transition-colors">
            <td class="px-6 py-4 font-medium text-indigo-300 font-mono">
                ${tripId}
            </td>
            <td class="px-6 py-4">
                <div class="font-medium text-slate-200 mb-0.5">${routeName}</div>
                <div class="text-xs text-slate-400"><i class="fa-regular fa-clock mr-1"></i> ${time}</div>
            </td>
            <td class="px-6 py-4">
                ${hasConflict && !sched.bus_id ? 
                `<span class="inline-flex items-center gap-2 text-red-400 bg-red-500/10 px-2 py-1 rounded border border-red-500/20 text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i> Unassigned
                </span>` : 
                `<span class="inline-flex items-center gap-2 text-slate-300 bg-slate-800/50 px-2 py-1 rounded border border-slate-700/50 text-xs">
                    <i class="fa-solid fa-bus text-indigo-400"></i> ${bus}
                </span>`}
            </td>
            <td class="px-6 py-4">
                ${hasConflict && !sched.driver_id ? 
                `<span class="text-xs text-slate-500 italic">Menunggu Alokasi</span>` : 
                `<div class="flex items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(driver)}&background=312e81&color=a5b4fc" class="w-6 h-6 rounded-full">
                    <div class="text-xs">
                        <p class="text-slate-200">${driver}</p>
                    </div>
                </div>`}
            </td>
            <td class="px-6 py-4 text-right">
                <span class="${fitness < 0 ? 'text-red-400' : 'text-emerald-400'} font-mono text-xs">${fitness}</span>
            </td>
        </tr>`;
    });
});
</script>
@endpush
@endsection
