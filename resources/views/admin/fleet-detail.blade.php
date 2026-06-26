@extends('admin.layouts.admin')

@section('title', 'Bus Detail - Fleet Management')
@section('header_title', 'Bus Detail')
@section('header_subtitle', 'Manage bus information and assigned staff')

@section('content')
<div class="flex flex-col gap-6">

    <!-- Back Button -->
    <div>
        <a href="/admin/fleet" class="inline-flex items-center gap-2 text-slate-400 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Fleet
        </a>
    </div>

    <!-- Bus Info Card -->
    <div id="bus-info-card" class="bg-slate-900/50 border border-slate-800 rounded-xl p-6">
        <div class="flex items-center gap-4 mb-1">
            <div id="bus-icon" class="w-14 h-14 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center">
                <i class="fa-solid fa-bus text-emerald-400 text-2xl"></i>
            </div>
            <div>
                <h2 id="bus-plate" class="text-2xl font-bold text-white">Loading...</h2>
                <span id="bus-status-badge" class="mt-1 inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-bold border">
                    <span class="w-1.5 h-1.5 rounded-full"></span> --
                </span>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-5">
            <div class="bg-slate-800/50 rounded-lg p-3">
                <p class="text-xs text-slate-500 mb-1">Capacity</p>
                <p id="bus-capacity" class="text-white font-semibold">--</p>
            </div>
            <div class="bg-slate-800/50 rounded-lg p-3">
                <p class="text-xs text-slate-500 mb-1">Route</p>
                <p id="bus-route" class="text-white font-semibold">--</p>
            </div>
            <div class="bg-slate-800/50 rounded-lg p-3">
                <p class="text-xs text-slate-500 mb-1">Drivers</p>
                <p id="bus-driver-count" class="text-white font-semibold">--</p>
            </div>
            <div class="bg-slate-800/50 rounded-lg p-3">
                <p class="text-xs text-slate-500 mb-1">Conductors</p>
                <p id="bus-conductor-count" class="text-white font-semibold">--</p>
            </div>
        </div>
    </div>

    <!-- Assigned Staff -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Assigned Drivers -->
        <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-300 uppercase tracking-widest">Assigned Drivers</h3>
                <button onclick="openAssignModal('driver')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Assign Driver
                </button>
            </div>
            <div id="assigned-drivers" class="grid gap-3">
                <div class="text-slate-500 text-sm">Loading...</div>
            </div>
        </div>

        <!-- Assigned Conductors -->
        <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-300 uppercase tracking-widest">Assigned Conductors</h3>
                <button onclick="openAssignModal('conductor')" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Assign Conductor
                </button>
            </div>
            <div id="assigned-conductors" class="grid gap-3">
                <div class="text-slate-500 text-sm">Loading...</div>
            </div>
        </div>
    </div>

</div>

<!-- Assign Modal -->
<div id="assign-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-lg p-6 shadow-2xl transform scale-95 transition-transform duration-300 mx-4" id="assign-modal-content">
        <div class="flex items-center justify-between mb-4">
            <h3 id="assign-modal-title" class="text-xl font-bold text-white">Assign Driver</h3>
            <button onclick="closeAssignModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <p class="text-xs text-slate-500 mb-4">Showing only unassigned staff. Status badge reflects their availability.</p>
        <div id="assign-staff-list" class="grid gap-3 max-h-80 overflow-y-auto pr-1">
            <div class="text-slate-500 text-sm">Loading...</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = window.API_URL || 'http://localhost:8001';
    const token = localStorage.getItem('token');
    const busId = {{ $id }};

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    let currentAssignType = 'driver'; // 'driver' or 'conductor'
    let allDrivers = [];
    let allConductors = [];

    // ---- Status helpers ----
    function getStatusMeta(staff) {
        const avail = staff.is_available == 1 || staff.is_available === true;
        if (avail) return { label: 'Available', color: 'emerald', dot: 'bg-emerald-500 animate-pulse' };
        return { label: 'Off Duty', color: 'slate', dot: 'bg-slate-500' };
    }

    // ---- Load Bus ----
    async function loadBus() {
        const res = await fetch(`${API_URL}/api/admin/buses/${busId}`, { headers });
        if (!res.ok) return;
        const { data: bus } = await res.json();

        // Update bus info
        const isActive = bus.status == 1 || bus.status === true;
        const statusColor = isActive ? 'emerald' : 'amber';
        const icon = document.getElementById('bus-icon');
        icon.className = `w-14 h-14 rounded-xl bg-${statusColor}-500/10 border border-${statusColor}-500/30 flex items-center justify-center`;
        icon.innerHTML = `<i class="fa-solid fa-bus text-${statusColor}-400 text-2xl"></i>`;

        document.getElementById('bus-plate').textContent = bus.plate_number || 'N/A';
        const badge = document.getElementById('bus-status-badge');
        badge.className = `mt-1 inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-bold border ${isActive ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20'}`;
        badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-${statusColor}-500 ${isActive ? 'animate-pulse' : ''}"></span> ${isActive ? 'ACTIVE' : 'MAINTENANCE'}`;

        document.getElementById('bus-capacity').textContent = (bus.capacity || '--') + ' seats';
        document.getElementById('bus-route').textContent = bus.route ? bus.route.name : 'Unassigned';
        document.getElementById('bus-driver-count').textContent = (bus.drivers?.length || 0) + ' assigned';
        document.getElementById('bus-conductor-count').textContent = (bus.conductors?.length || 0) + ' assigned';

        renderAssigned(bus.drivers || [], 'assigned-drivers', 'driver');
        renderAssigned(bus.conductors || [], 'assigned-conductors', 'conductor');
    }

    function renderAssigned(list, containerId, type) {
        const container = document.getElementById(containerId);
        if (list.length === 0) {
            container.innerHTML = `<p class="text-slate-500 text-sm italic">No ${type}s assigned yet.</p>`;
            return;
        }
        const color = type === 'driver' ? 'indigo' : 'emerald';
        container.innerHTML = list.map(staff => {
            const st = getStatusMeta(staff);
            const name = staff.user?.name || 'Unknown';
            return `<div class="flex items-center justify-between bg-slate-800/50 rounded-lg p-3 border border-slate-700/50">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=1e293b&color=94a3b8" class="w-9 h-9 rounded-full border border-slate-700">
                    <div>
                        <p class="text-sm font-semibold text-white">${name}</p>
                        <p class="text-[11px] text-slate-400">${staff.employee_id || 'ID N/A'}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1.5 text-[11px] font-bold text-${st.color}-400 bg-${st.color}-500/10 px-2 py-0.5 rounded border border-${st.color}-500/20">
                        <span class="w-1.5 h-1.5 rounded-full ${st.dot}"></span>${st.label}
                    </span>
                    <button onclick="unassignStaff(${staff.id}, '${type}')" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 border border-red-700/40 rounded hover:bg-red-900/30 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>`;
        }).join('');
    }

    // ---- Load all staff ----
    async function loadAllStaff() {
        const [dRes, cRes] = await Promise.all([
            fetch(`${API_URL}/api/admin/drivers`, { headers }),
            fetch(`${API_URL}/api/admin/conductors`, { headers })
        ]);
        const dData = await dRes.json();
        const cData = await cRes.json();
        allDrivers = Array.isArray(dData) ? dData : (dData.data || []);
        allConductors = Array.isArray(cData) ? cData : (cData.data || []);
    }

    // ---- Assign Modal ----
    window.openAssignModal = function(type) {
        currentAssignType = type;
        document.getElementById('assign-modal-title').textContent = type === 'driver' ? 'Assign Driver' : 'Assign Conductor';

        const list = type === 'driver' ? allDrivers : allConductors;
        const unassigned = list.filter(s => !s.bus_id || s.bus_id == busId);
        const color = type === 'driver' ? 'indigo' : 'emerald';
        const listEl = document.getElementById('assign-staff-list');

        if (unassigned.length === 0) {
            listEl.innerHTML = `<p class="text-slate-500 text-sm italic">No available ${type}s to assign.</p>`;
        } else {
            listEl.innerHTML = unassigned.map(staff => {
                const st = getStatusMeta(staff);
                const name = staff.user?.name || 'Unknown';
                const alreadyOnBus = staff.bus_id == busId;
                return `<div class="flex items-center justify-between bg-slate-800/50 rounded-lg p-3 border border-slate-700/50 ${alreadyOnBus ? 'border-'+color+'-500/30' : ''}">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=1e293b&color=94a3b8" class="w-9 h-9 rounded-full border border-slate-700">
                        <div>
                            <p class="text-sm font-semibold text-white">${name}</p>
                            <p class="text-[11px] text-slate-400">${staff.employee_id || 'ID N/A'}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex items-center gap-1.5 text-[11px] font-bold text-${st.color}-400 bg-${st.color}-500/10 px-2 py-0.5 rounded border border-${st.color}-500/20">
                            <span class="w-1.5 h-1.5 rounded-full ${st.dot}"></span>${st.label}
                        </span>
                        ${alreadyOnBus
                            ? `<span class="text-xs text-${color}-400 font-bold px-2">Assigned</span>`
                            : `<button onclick="assignStaff(${staff.id}, '${type}')" class="text-xs px-3 py-1 bg-${color}-600 hover:bg-${color}-500 text-white rounded transition-colors font-semibold">Assign</button>`
                        }
                    </div>
                </div>`;
            }).join('');
        }

        const modal = document.getElementById('assign-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('assign-modal-content').classList.remove('scale-95'), 10);
    };

    window.closeAssignModal = function() {
        document.getElementById('assign-modal-content').classList.add('scale-95');
        setTimeout(() => {
            const modal = document.getElementById('assign-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    };

    // ---- Assign staff to bus ----
    window.assignStaff = async function(staffId, type) {
        const endpoint = type === 'driver' ? 'drivers' : 'conductors';
        const body = type === 'driver'
            ? { driver_ids: [...(allDrivers.filter(d => d.bus_id == busId).map(d => d.id)), staffId] }
            : { conductor_ids: [...(allConductors.filter(c => c.bus_id == busId).map(c => c.id)), staffId] };

        const res = await fetch(`${API_URL}/api/admin/buses/${busId}`, {
            method: 'PUT',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });

        if (res.ok) {
            closeAssignModal();
            await loadAllStaff();
            loadBus();
        } else {
            const err = await res.json();
            alert('Failed: ' + (err.message || 'Unknown error'));
        }
    };

    // ---- Unassign staff ----
    window.unassignStaff = async function(staffId, type) {
        if (!confirm('Remove this staff from the bus?')) return;
        const currentIds = type === 'driver'
            ? allDrivers.filter(d => d.bus_id == busId && d.id != staffId).map(d => d.id)
            : allConductors.filter(c => c.bus_id == busId && c.id != staffId).map(c => c.id);

        const body = type === 'driver' ? { driver_ids: currentIds } : { conductor_ids: currentIds };

        const res = await fetch(`${API_URL}/api/admin/buses/${busId}`, {
            method: 'PUT',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });

        if (res.ok) {
            await loadAllStaff();
            loadBus();
        } else {
            alert('Failed to unassign staff.');
        }
    };

    // Init
    await loadAllStaff();
    await loadBus();
});
</script>
@endpush
@endsection
