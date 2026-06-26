@extends('admin.layouts.admin')

@section('title', 'Route Detail')
@section('header_title', 'Route Configuration')
@section('header_subtitle', 'Manage haltes, buses, and fare for this route')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.routes') }}" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 id="route-name-display" class="text-2xl font-bold text-white tracking-tight">Loading Route...</h2>
            <p class="text-slate-400 text-sm">Configure bus stops and buses assigned to this route.</p>
        </div>
    </div>

    <!-- Fare Edit Card -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-slate-900 via-amber-950 to-slate-900 p-6 border border-amber-500/10">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-amber-500 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
        <div class="flex items-center justify-between z-10 relative">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-amber-400"></i> Route Info
                </h3>
                <p class="text-sm text-amber-300/80 mb-1">Base Fare: <span class="text-xl font-bold text-amber-300" id="route-fare-display">Loading...</span></p>
            </div>
            <button onclick="openEditInfoModal()" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                <i class="fa-solid fa-pen"></i> Edit Info
            </button>
        </div>
    </div>

    <!-- Edit Info Modal -->
    <div id="edit-info-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-sm p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="edit-info-modal-content">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-white">Edit Route Info</h3>
                <button onclick="closeEditInfoModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="edit-info-form" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Route Name</label>
                    <input type="text" id="route-name-input" required
                        class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-amber-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Base Fare (USD)</label>
                    <input type="number" id="fare-input" min="3" step="1"
                        class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-amber-500 text-sm">
                    <p class="text-[10px] text-slate-500 mt-1">Flat ticket price per trip (in USD)</p>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeEditInfoModal()" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="save-info-btn" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Roster Banner (Haltes) -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 border border-indigo-500/10">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
        <div class="flex items-center justify-between z-10 relative">
            <div>
                <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-map-pin text-indigo-400"></i> Assigned Haltes
                </h3>
            </div>
            <button onclick="openAttachModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add Halte
            </button>
        </div>
    </div>

    <!-- Attach Halte Modal -->
    <div id="attach-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="attach-modal-content">
            <h3 class="text-xl font-bold text-white mb-4">Add Halte to Route</h3>
            <form id="attach-form" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Select Halte</label>
                    <select id="halte-select" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="">-- Choose Halte --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Sequence / Order</label>
                    <input type="number" id="halte-sequence" required min="1" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAttachModal()" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="save-attach-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        Attach Halte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assigned Bus Section -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 p-6 border border-purple-500/10">
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-purple-500 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
        <div class="flex items-center justify-between z-10 relative mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <i class="fa-solid fa-bus text-purple-400"></i> Assigned Buses
            </h3>
            <button onclick="openAssignBusModal()" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                <i class="fa-solid fa-link"></i> Assign Bus
            </button>
        </div>
        <div id="assigned-buses-list" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="text-purple-200/60 text-sm">Loading...</div>
        </div>
    </div>

    <!-- Assign Bus Modal -->
    <div id="assign-bus-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="assign-bus-modal-content">
            <h3 class="text-xl font-bold text-white mb-1">Assign Bus to Route</h3>
            <p class="text-xs text-slate-400 mb-4">Pilih bus yang belum punya rute (atau sudah di rute ini).</p>
            <form id="assign-bus-form" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Select Bus</label>
                    <select id="assign-bus-select" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-500 text-sm">
                        <option value="">-- Choose Bus --</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAssignBusModal()" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="save-assign-bus-btn" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        Assign Bus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Route Haltes List -->
    <div id="route-haltes-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-full text-center py-10 text-slate-500">
            <i class="fa-solid fa-spinner fa-spin text-indigo-500 text-3xl mb-4"></i>
            <p>Loading haltes...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    const routeId = '{{ $id }}';
    const token = localStorage.getItem('token');
    
    if (!token) {
        document.getElementById('route-haltes-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Silakan login terlebih dahulu.</div>`;
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    let currentRoute = null;

    // ─── Load Route Detail + Haltes ────────────────────────────────────────
    window.loadRouteDetail = async function() {
        try {
            const response = await fetch(`${API_URL}/api/admin/routes/${routeId}`, { headers });
            if (!response.ok) throw new Error('Failed to load route');
            const data = await response.json();
            currentRoute = data.data;
            
            document.getElementById('route-name-display').innerText = currentRoute.name;
            document.getElementById('route-name-input').value = currentRoute.name;

            // Update fare display
            const rawFare = parseFloat(currentRoute.fare_per_km || 0);
            const fare = Math.max(rawFare, 3);
            document.getElementById('route-fare-display').textContent = '$' + fare.toLocaleString('en-US');
            document.getElementById('fare-input').value = fare;
            
            const haltes = currentRoute.haltes || [];
            haltes.sort((a, b) => {
                const seqA = a.pivot ? a.pivot.sequence : 0;
                const seqB = b.pivot ? b.pivot.sequence : 0;
                return seqA - seqB;
            });

            const grid = document.getElementById('route-haltes-grid');
            grid.innerHTML = '';
            
            if (haltes.length === 0) {
                grid.innerHTML = `<div class="col-span-full text-center py-10 text-slate-500">No haltes assigned to this route.</div>`;
                return;
            }

            haltes.forEach(h => {
                const sequence = h.pivot ? h.pivot.sequence : '-';
                grid.innerHTML += `
                <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-xl overflow-hidden hover:border-indigo-500/30 transition-all duration-300 shadow-lg group">
                    <div class="p-5 border-b border-slate-800/50 bg-slate-800/20">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center font-bold text-emerald-400">
                                    ${sequence}
                                </div>
                                <div>
                                    <h4 class="text-white font-bold tracking-wide">${h.name}</h4>
                                    <span class="text-[10px] text-slate-400 font-mono">Code: ${h.code}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-900/50 flex justify-end">
                        <button onclick="detachHalte(${h.id})" class="px-3 py-1.5 text-[11px] font-medium rounded border border-red-700/50 text-red-300 hover:bg-red-600 hover:text-white transition-colors"><i class="fa-solid fa-trash"></i> Remove</button>
                    </div>
                </div>`;
            });
        } catch (e) {
            console.error(e);
            document.getElementById('route-haltes-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Error loading route details.</div>`;
        }
    };

    // ─── Load Assigned Buses (multiple) ────────────────────────────────────
    window.loadAssignedBuses = async function() {
        try {
            const res = await fetch(`${API_URL}/api/admin/buses`, { headers });
            if (!res.ok) throw new Error();
            const data = await res.json();
            const buses = Array.isArray(data) ? data : (data.data || []);
            const assignedBuses = buses.filter(b => b.route_id == routeId);

            const listEl = document.getElementById('assigned-buses-list');

            if (assignedBuses.length === 0) {
                listEl.innerHTML = `<p class="text-purple-200/60 text-sm italic">Belum ada bus yang di-assign ke rute ini.</p>`;
                return;
            }

            listEl.innerHTML = assignedBuses.map(b => {
                const isActive = b.status == 1 || b.status === true;
                return `
                <div class="flex items-center justify-between bg-slate-800/50 rounded-lg p-3 border border-purple-500/20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/10 border border-purple-500/30 flex items-center justify-center">
                            <i class="fa-solid fa-bus text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold text-sm">${b.plate_number}</p>
                            <p class="text-[11px] text-slate-400">${b.capacity} seats · ${isActive ? '<span class="text-emerald-400">Active</span>' : '<span class="text-amber-400">Maintenance</span>'}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="/admin/fleet/${b.id}" class="text-xs px-2 py-1 border border-slate-600 text-slate-300 hover:text-white rounded transition-colors">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                        <button onclick="unassignBus(${b.id})" class="text-xs px-2 py-1 border border-red-700/50 text-red-300 hover:bg-red-600 hover:text-white rounded transition-colors">
                            <i class="fa-solid fa-unlink"></i>
                        </button>
                    </div>
                </div>`;
            }).join('');
        } catch(e) {
            document.getElementById('assigned-buses-list').innerHTML = `<p class="text-red-400 text-sm">Gagal memuat data bus.</p>`;
        }
    };

    // ─── Load available buses for dropdown ──────────────────────────────────
    window.loadAvailableBuses = async function() {
        try {
            const res = await fetch(`${API_URL}/api/admin/buses`, { headers });
            if (!res.ok) return;
            const data = await res.json();
            const buses = Array.isArray(data) ? data : (data.data || []);
            const select = document.getElementById('assign-bus-select');
            select.innerHTML = '<option value="">-- Choose Bus --</option>';
            // Show unassigned buses only (buses already on this route already shown in list)
            buses.forEach(b => {
                if (!b.route_id || b.route_id == routeId) {
                    const alreadyOnRoute = b.route_id == routeId;
                    const label = `${b.plate_number} (Cap: ${b.capacity})${alreadyOnRoute ? ' — Already assigned' : ''}`;
                    if (!alreadyOnRoute) {
                        select.innerHTML += `<option value="${b.id}">${label}</option>`;
                    }
                }
            });
        } catch(e) {}
    };

    // ─── Edit Info Modal ────────────────────────────────────────────────────
    window.openEditInfoModal = function() {
        const modal = document.getElementById('edit-info-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('edit-info-modal-content').classList.remove('scale-95'), 10);
    };

    window.closeEditInfoModal = function() {
        document.getElementById('edit-info-modal-content').classList.add('scale-95');
        setTimeout(() => {
            const modal = document.getElementById('edit-info-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    };

    document.getElementById('edit-info-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('route-name-input').value;
        const fare = document.getElementById('fare-input').value;
        const btn = document.getElementById('save-info-btn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
        btn.disabled = true;

        try {
            const res = await fetch(`${API_URL}/api/admin/routes/${routeId}`, {
                method: 'PUT',
                headers: { ...headers, 'Content-Type': 'application/json' },
                body: JSON.stringify({ name: name, fare_per_km: parseFloat(fare) })
            });
            if (res.ok) {
                closeEditInfoModal();
                loadRouteDetail();
            } else {
                const data = await res.json();
                alert('Gagal simpan: ' + (data.message || 'Error'));
            }
        } catch(e) {
            alert('Connection error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save';
            btn.disabled = false;
        }
    });

    // ─── Modal helpers ─────────────────────────────────────────────────────
    window.openAssignBusModal = function() {
        loadAvailableBuses();
        const modal = document.getElementById('assign-bus-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('assign-bus-modal-content').classList.remove('scale-95'), 10);
    };

    window.closeAssignBusModal = function() {
        document.getElementById('assign-bus-modal-content').classList.add('scale-95');
        setTimeout(() => {
            const modal = document.getElementById('assign-bus-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    };

    // ─── Assign Bus Submit ─────────────────────────────────────────────────
    document.getElementById('assign-bus-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const busId = document.getElementById('assign-bus-select').value;
        if (!busId) return;
        const btn = document.getElementById('save-assign-bus-btn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Assigning...';
        btn.disabled = true;

        try {
            const res = await fetch(`${API_URL}/api/admin/buses/${busId}`, {
                method: 'PUT',
                headers: { ...headers, 'Content-Type': 'application/json' },
                body: JSON.stringify({ route_id: parseInt(routeId) })
            });
            if (res.ok) {
                closeAssignBusModal();
                loadAssignedBuses();
            } else {
                const data = await res.json();
                alert('Gagal assign bus: ' + (data.message || 'Error'));
            }
        } catch(e) {
            alert('Connection error');
        } finally {
            btn.innerHTML = 'Assign Bus';
            btn.disabled = false;
        }
    });

    // ─── Unassign Bus ─────────────────────────────────────────────────────
    window.unassignBus = async function(busId) {
        if (!confirm('Lepas bus dari rute ini?')) return;
        try {
            const res = await fetch(`${API_URL}/api/admin/buses/${busId}`, {
                method: 'PUT',
                headers: { ...headers, 'Content-Type': 'application/json' },
                body: JSON.stringify({ route_id: null })
            });
            if (res.ok) {
                loadAssignedBuses();
            } else {
                alert('Gagal melepas bus');
            }
        } catch(e) {
            alert('Connection error');
        }
    };

    // ─── Halte attach/detach ───────────────────────────────────────────────
    window.loadAllHaltes = async function() {
        try {
            const response = await fetch(`${API_URL}/api/admin/haltes`, { headers });
            if (response.ok) {
                const data = await response.json();
                const haltes = Array.isArray(data) ? data : (data.data || []);
                const select = document.getElementById('halte-select');
                haltes.forEach(h => {
                    select.innerHTML += `<option value="${h.id}">${h.name} (${h.code})</option>`;
                });
            }
        } catch(e) {}
    };

    window.openAttachModal = function() {
        document.getElementById('halte-select').value = '';
        document.getElementById('halte-sequence').value = '1';
        const modal = document.getElementById('attach-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('attach-modal-content').classList.remove('scale-95'), 10);
    };

    window.closeAttachModal = function() {
        document.getElementById('attach-modal-content').classList.add('scale-95');
        setTimeout(() => {
            const modal = document.getElementById('attach-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    };

    window.detachHalte = async function(halteId) {
        if(!confirm('Remove this halte from route?')) return;
        try {
            const response = await fetch(`${API_URL}/api/admin/routes/${routeId}/haltes/${halteId}`, {
                method: 'DELETE',
                headers: headers
            });
            if(response.ok) {
                loadRouteDetail();
            } else {
                alert('Failed to detach');
            }
        } catch(e) {
            alert('Error detaching halte');
        }
    };

    document.getElementById('attach-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const halteId = document.getElementById('halte-select').value;
        const sequence = document.getElementById('halte-sequence').value;
        const btn = document.getElementById('save-attach-btn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
        btn.disabled = true;

        try {
            const response = await fetch(`${API_URL}/api/admin/routes/${routeId}/haltes`, {
                method: 'POST',
                headers: { ...headers, 'Content-Type': 'application/json' },
                body: JSON.stringify({ halte_id: halteId, sequence: sequence })
            });
            if(response.ok) {
                closeAttachModal();
                loadRouteDetail();
            } else {
                const data = await response.json();
                alert('Failed to attach: ' + (data.message || 'Error'));
            }
        } catch(e) {
            alert('Error attaching halte');
        } finally {
            btn.innerHTML = 'Attach Halte';
            btn.disabled = false;
        }
    });

    // ─── Init ──────────────────────────────────────────────────────────────
    loadRouteDetail();
    loadAllHaltes();
    loadAssignedBuses();
});
</script>
@endpush
@endsection
