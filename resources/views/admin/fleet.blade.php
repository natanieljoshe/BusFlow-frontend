@extends('admin.layouts.admin')

@section('title', 'Fleet Management')
@section('header_title', 'Fleet Overview')
@section('header_subtitle', 'Real-time status monitoring and maintenance scheduling')

@section('content')
<div class="flex flex-col gap-6">

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-slate-400 text-sm font-medium tracking-wide uppercase">System Health</h3>
                <span class="px-2 py-1 bg-emerald-500/10 text-emerald-400 text-xs rounded border border-emerald-500/20"><i class="fa-solid fa-arrow-up"></i> 2.4%</span>
            </div>
            <div class="flex items-end gap-3">
                <span class="text-4xl font-bold text-white tracking-tight">88.7%</span>
                <span class="text-indigo-400 text-sm font-medium mb-1">Operational Readiness</span>
            </div>
            <div class="mt-4 flex flex-col gap-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Scheduled Maintenance</span>
                    <span class="text-slate-200 font-medium">14 Units</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Critical Failures</span>
                    <span class="text-red-400 font-medium">2 Units</span>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 relative overflow-hidden">
             <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3 sm:gap-0">
                <h3 class="text-slate-400 text-sm font-medium tracking-wide uppercase">Fleet Status Distribution</h3>
                <div class="flex flex-wrap items-center gap-3 text-xs font-medium">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_5px_rgba(16,185,129,0.8)]"></span> <span class="text-slate-300">Active (142)</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-[0_0_5px_rgba(245,158,11,0.8)]"></span> <span class="text-slate-300">Maintenance (18)</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_5px_rgba(239,68,68,0.8)]"></span> <span class="text-slate-300">Alert (2)</span></div>
                </div>
            </div>
            <div class="h-32 w-full flex items-end justify-between gap-1 sm:gap-2 px-1 sm:px-2 mt-2">
                <!-- Bar chart skeleton -->
                <div class="w-full bg-slate-800/50 rounded-t-sm h-[80%] relative group hover:bg-slate-700/50 transition-colors"><div class="absolute bottom-full mb-1 w-full text-center text-[10px] text-slate-500 opacity-0 group-hover:opacity-100">Sen</div></div>
                <div class="w-full bg-emerald-500/20 rounded-t-sm h-[95%] relative group hover:bg-emerald-500/30 transition-colors border-t border-emerald-500/30"><div class="absolute bottom-full mb-1 w-full text-center text-[10px] text-emerald-400 opacity-0 group-hover:opacity-100">Sel</div></div>
                <div class="w-full bg-slate-800/50 rounded-t-sm h-[70%] relative group hover:bg-slate-700/50 transition-colors"><div class="absolute bottom-full mb-1 w-full text-center text-[10px] text-slate-500 opacity-0 group-hover:opacity-100">Rab</div></div>
                <div class="w-full bg-amber-500/20 rounded-t-sm h-[60%] relative group hover:bg-amber-500/30 transition-colors border-t border-amber-500/30"><div class="absolute bottom-full mb-1 w-full text-center text-[10px] text-amber-400 opacity-0 group-hover:opacity-100">Kam</div></div>
                <div class="w-full bg-slate-800/50 rounded-t-sm h-[85%] relative group hover:bg-slate-700/50 transition-colors"><div class="absolute bottom-full mb-1 w-full text-center text-[10px] text-slate-500 opacity-0 group-hover:opacity-100">Jum</div></div>
                <div class="w-full bg-red-500/20 rounded-t-sm h-[40%] relative group hover:bg-red-500/30 transition-colors border-t border-red-500/30"><div class="absolute bottom-full mb-1 w-full text-center text-[10px] text-red-400 opacity-0 group-hover:opacity-100">Sab</div></div>
                <div class="w-full bg-slate-800/50 rounded-t-sm h-[75%] relative group hover:bg-slate-700/50 transition-colors"><div class="absolute bottom-full mb-1 w-full text-center text-[10px] text-slate-500 opacity-0 group-hover:opacity-100">Min</div></div>
            </div>
        </div>
    </div>

    <!-- Active Roster Banner -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 border border-indigo-500/10">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
        <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
            <i class="fa-solid fa-layer-group text-indigo-400"></i> Active Roster
        </h3>
        <p class="text-sm text-indigo-200/70 max-w-2xl">
            Monitoring the next generation of urban transit. Our high-tech fleet is equipped with real-time diagnostics and autonomous navigation capabilities for maximum efficiency.
        </p>
    </div>

    <!-- Fleet Grid -->
    <div id="fleet-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-full text-center py-10 text-slate-500">
            <i class="fa-solid fa-spinner fa-spin text-indigo-500 text-3xl mb-4"></i>
            <p>Loading fleet data...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = window.API_URL || 'http://localhost:8001';
    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('fleet-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Silakan login terlebih dahulu. Token tidak ditemukan.</div>`;
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    try {
        const response = await fetch(`${API_URL}/api/admin/buses`, { headers });
        if (response.status === 401) {
            localStorage.removeItem('token');
            document.getElementById('fleet-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Sesi telah berakhir. Silakan login kembali.</div>`;
            return;
        }
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.json();
        const buses = Array.isArray(data) ? data : (data.data || []);
        
        const grid = document.getElementById('fleet-grid');
        grid.innerHTML = '';
        
        if (buses.length === 0) {
            grid.innerHTML = `<div class="col-span-full text-center py-10 text-slate-500">No fleet data available.</div>`;
            return;
        }

        buses.forEach(bus => {
            const isActive = bus.status == 1 || bus.status === true;
            let statusColor = isActive ? 'emerald' : 'amber';
            let statusIcon = isActive ? 'fa-bus' : 'fa-wrench';
            let statusLabel = isActive ? 'ACTIVE' : 'MAINTENANCE';
            let statusBadge = isActive 
                ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                : 'bg-amber-500/10 text-amber-500 border-amber-500/20';

            const plate = bus.plate_number || bus.id || 'N/A';
            const capacity = bus.capacity || '?';
            
            grid.innerHTML += `
            <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-xl overflow-hidden hover:border-${statusColor}-500/30 transition-all duration-300 shadow-lg group">
                <div class="p-5 border-b border-slate-800/50 bg-slate-800/20">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-${statusColor}-500/10 border border-${statusColor}-500/30 flex items-center justify-center">
                                <i class="fa-solid ${statusIcon} text-${statusColor}-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold tracking-wide">${plate}</h4>
                                <span class="text-[10px] text-slate-400 font-mono">Capacity: ${capacity}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold ${statusBadge} border flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-${statusColor}-500 ${isActive ? 'animate-pulse' : ''}"></span> ${statusLabel}
                        </span>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex justify-between items-end border-b border-slate-800/50 pb-3">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Current Route</p>
                            <p class="text-sm text-slate-200 font-medium"><i class="fa-solid fa-route text-indigo-400 mr-1.5"></i> ${bus.route_id ? `Route ${bus.route_id}` : '--'}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button class="px-3 py-1.5 text-[11px] font-medium rounded border border-slate-700 text-slate-300 hover:bg-slate-800 transition-colors">Details</button>
                    </div>
                </div>
            </div>`;
        });

    } catch (error) {
        console.error("Error fetching buses:", error);
        document.getElementById('fleet-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Failed to load fleet data.</div>`;
    }
});
</script>
@endpush
@endsection
