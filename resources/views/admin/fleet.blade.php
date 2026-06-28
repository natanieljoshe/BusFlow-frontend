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
                <span id="fleet-health" class="text-4xl font-bold text-white tracking-tight">--%</span>
                <span class="text-indigo-400 text-sm font-medium mb-1">Operational Readiness</span>
            </div>
            <div class="mt-4 flex flex-col gap-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Scheduled Maintenance</span>
                    <span id="fleet-maintenance-count" class="text-slate-200 font-medium">-- Units</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Critical Failures</span>
                    <span id="fleet-critical-count" class="text-red-400 font-medium">-- Units</span>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 relative overflow-hidden">
             <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3 sm:gap-0">
                <h3 class="text-slate-400 text-sm font-medium tracking-wide uppercase">Fleet Status Distribution</h3>
                <div class="flex flex-wrap items-center gap-3 text-xs font-medium">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_5px_rgba(16,185,129,0.8)]"></span> <span id="dist-active" class="text-slate-300">Active (--)</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-[0_0_5px_rgba(245,158,11,0.8)]"></span> <span id="dist-maintenance" class="text-slate-300">Maintenance (--)</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_5px_rgba(239,68,68,0.8)]"></span> <span id="dist-alert" class="text-slate-300">Alert (--)</span></div>
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
        <div class="flex items-center justify-between z-10 relative">
            <div>
                <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-indigo-400"></i> Active Roster
                </h3>
                <p class="text-sm text-indigo-200/70 max-w-2xl">
                    Monitoring the next generation of urban transit. Our high-tech fleet is equipped with real-time diagnostics and autonomous navigation capabilities.
                </p>
            </div>
            <button onclick="openBusModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Bus
            </button>
        </div>
    </div>

    <!-- Bus Modal -->
    <div id="bus-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="bus-modal-content">
            <h3 id="modal-title" class="text-xl font-bold text-white mb-4">Add New Bus</h3>
            <form id="bus-form" class="space-y-4">
                <input type="hidden" id="bus-id">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Plate Number</label>
                    <input type="text" id="bus-plate" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Capacity</label>
                    <input type="number" id="bus-capacity" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Status</label>
                    <select id="bus-status" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="1">Active</option>
                        <option value="0">Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Route</label>
                    <select id="bus-route" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="">-- Unassigned --</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeBusModal()" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="save-bus-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        Save Bus
                    </button>
                </div>
            </form>
        </div>
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
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('fleet-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Silakan login terlebih dahulu. Token tidak ditemukan.</div>`;
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    window.loadBuses = async function() {
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

        // Calculate dynamic stats — status from DB is boolean (true=active, false=maintenance)
        const totalBuses = buses.length;
        const activeBusesCount = buses.filter(b => b.status == 1 || b.status === true).length;
        const maintenanceBusesCount = buses.filter(b => b.status == 0 || b.status === false).length;
        const alertBusesCount = 0; // no alert status yet

        const healthPercent = totalBuses === 0 ? 0 : Math.round((activeBusesCount / totalBuses) * 100);
        
        document.getElementById('fleet-health').innerText = `${healthPercent}%`;
        document.getElementById('fleet-maintenance-count').innerText = `${maintenanceBusesCount} Units`;
        document.getElementById('fleet-critical-count').innerText = `${alertBusesCount} Units`;

        document.getElementById('dist-active').innerText = `Active (${activeBusesCount})`;
        document.getElementById('dist-maintenance').innerText = `Maintenance (${maintenanceBusesCount})`;
        document.getElementById('dist-alert').innerText = `Alert (${alertBusesCount})`;

        buses.forEach(bus => {
            const isActive = bus.status == 1 || bus.status === true || (typeof bus.status === 'string' && bus.status.toLowerCase() === 'active');
            let statusColor = isActive ? 'emerald' : 'amber';
            let statusIcon = isActive ? 'fa-bus' : 'fa-wrench';
            let statusLabel = isActive ? 'ACTIVE' : 'MAINTENANCE';
            let statusBadge = isActive 
                ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                : 'bg-amber-500/10 text-amber-500 border-amber-500/20';

            const plate = bus.plate_number || 'N/A';
            const capacity = bus.capacity || '?';
            const id = bus.id;
            
            grid.innerHTML += `
            <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-xl overflow-hidden hover:border-${statusColor}-500/30 transition-all duration-300 shadow-lg group">
                <a href="/admin/fleet/${id}" class="block p-5 border-b border-slate-800/50 bg-slate-800/20 hover:bg-slate-800/40 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-${statusColor}-500/10 border border-${statusColor}-500/30 flex items-center justify-center">
                                <i class="fa-solid ${statusIcon} text-${statusColor}-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold tracking-wide">${plate}</h4>
                                <span class="text-[10px] text-slate-400 font-mono">Capacity: ${capacity} | Route: ${bus.route ? bus.route.name : 'Unassigned'}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold ${statusBadge} border flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-${statusColor}-500 ${isActive ? 'animate-pulse' : ''}"></span> ${statusLabel}
                        </span>
                    </div>
                </a>
                <div class="p-5 space-y-4">
                    <div class="mt-1 flex justify-end gap-2">
                        <button onclick="window.location.href='/admin/fleet/' + ${id}" class="px-3 py-1.5 text-[11px] font-medium rounded border border-indigo-700/50 text-indigo-300 hover:bg-indigo-600 hover:text-white transition-colors"><i class="fa-solid fa-pen"></i> Edit</button>
                        <button onclick="deleteBus(${id})" class="px-3 py-1.5 text-[11px] font-medium rounded border border-red-700/50 text-red-300 hover:bg-red-600 hover:text-white transition-colors"><i class="fa-solid fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>`;
        });

    } catch (error) {
        console.error("Error fetching buses:", error);
        document.getElementById('fleet-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Failed to load fleet data.</div>`;
    }
};

window.openBusModal = function() {
    document.getElementById('modal-title').innerText = 'Add New Bus';
    document.getElementById('bus-id').value = '';
    document.getElementById('bus-plate').value = '';
    document.getElementById('bus-capacity').value = '40';
    document.getElementById('bus-status').value = '1';
    document.getElementById('bus-route').value = '';
    const modal = document.getElementById('bus-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => document.getElementById('bus-modal-content').classList.remove('scale-95'), 10);
};

window.closeBusModal = function() {
    document.getElementById('bus-modal-content').classList.add('scale-95');
    setTimeout(() => {
        const modal = document.getElementById('bus-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
};

window.editBus = function(id, plate, capacity, status, routeId) {
    document.getElementById('modal-title').innerText = 'Edit Bus';
    document.getElementById('bus-id').value = id;
    document.getElementById('bus-plate').value = plate;
    document.getElementById('bus-capacity').value = capacity;
    document.getElementById('bus-status').value = status;
    
    document.getElementById('bus-route').value = routeId || '';
    const modal = document.getElementById('bus-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => document.getElementById('bus-modal-content').classList.remove('scale-95'), 10);
};

window.deleteBus = async function(id) {
    if(!confirm('Are you sure you want to delete this bus?')) return;
    try {
        const response = await fetch(`${API_URL}/api/admin/buses/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}`, 'Accept': 'application/json' }
        });
        if(response.ok) {
            loadBuses();
        } else {
            alert('Failed to delete bus');
        }
    } catch(e) {
        alert('Error deleting bus');
    }
};

document.getElementById('bus-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('bus-id').value;
    const plate = document.getElementById('bus-plate').value;
    const capacity = document.getElementById('bus-capacity').value;
    const status = document.getElementById('bus-status').value;
    
    const routeId = document.getElementById('bus-route').value;

    const method = id ? 'PUT' : 'POST';
    const url = id ? `/api/admin/buses/${id}` : '/api/admin/buses';
    const btn = document.getElementById('save-bus-btn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    btn.disabled = true;

    try {
        const response = await fetch(`${API_URL}${url}`, {
            method: method,
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                plate_number: plate, 
                capacity: capacity, 
                status: status, 
                route_id: routeId || null 
            })
        });
        
        if(response.ok) {
            closeBusModal();
            loadBuses();
        } else {
            const data = await response.json();
            alert('Failed to save: ' + (data.message || 'Unknown error'));
        }
    } catch(err) {
        alert('Error saving bus');
    } finally {
        btn.innerHTML = 'Save Bus';
        btn.disabled = false;
    }
});

window.loadSelectOptions = async function() {
    try {
        const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
        const routesRes = await fetch(`${API_URL}/api/admin/routes`, { headers });
        if(routesRes.ok) {
            const routesData = await routesRes.json();
            const routes = Array.isArray(routesData) ? routesData : (routesData.data || []);
            
            const routeSelect = document.getElementById('bus-route');
            
            routes.forEach(r => {
                routeSelect.innerHTML += `<option value="${r.id}">${r.name}</option>`;
            });
        }
    } catch(e) {
        console.error('Failed to load options', e);
    }
};

loadSelectOptions();
loadBuses();
});
</script>
@endpush
@endsection
