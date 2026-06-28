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
        <div class="grid grid-cols-2 gap-4 mt-5">
            <div class="bg-slate-800/50 rounded-lg p-3">
                <p class="text-xs text-slate-500 mb-1">Capacity</p>
                <p id="bus-capacity" class="text-white font-semibold">--</p>
            </div>
            <div class="bg-slate-800/50 rounded-lg p-3 flex justify-between items-center">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Route</p>
                    <p id="bus-route" class="text-white font-semibold">--</p>
                </div>
                <button onclick="openEditRouteModal()" class="text-slate-400 hover:text-indigo-400 text-[10px] uppercase font-bold tracking-wider px-2 py-1 border border-slate-700 hover:border-indigo-500 rounded transition-all">
                    <i class="fa-solid fa-pen mr-1"></i> Change
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Route Modal -->
    <div id="edit-route-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-sm p-6 shadow-2xl transform scale-95 transition-transform duration-300 mx-4" id="edit-route-modal-content">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-white">Change Bus Route</h3>
                <button onclick="closeEditRouteModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Select Route</label>
                    <select id="modal-route-id" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="">-- Unassigned --</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditRouteModal()" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="button" onclick="saveBusRoute()" id="save-route-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    const token = localStorage.getItem('token');
    const busId = {{ $id }};

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

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

        // Load routes for modal if not loaded
        if (document.getElementById('modal-route-id').options.length <= 1) {
            try {
                const rRes = await fetch(`${API_URL}/api/admin/routes`, { headers });
                if (rRes.ok) {
                    const rData = await rRes.json();
                    const routes = Array.isArray(rData) ? rData : (rData.data || []);
                    document.getElementById('modal-route-id').innerHTML = '<option value="">-- Unassigned --</option>' + 
                        routes.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
                }
            } catch(e) {}
        }
        document.getElementById('modal-route-id').value = bus.route_id || '';
    }

    // ---- Edit Route Modal ----
    window.openEditRouteModal = function() {
        const modal = document.getElementById('edit-route-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('edit-route-modal-content').classList.remove('scale-95'), 10);
    };

    window.closeEditRouteModal = function() {
        document.getElementById('edit-route-modal-content').classList.add('scale-95');
        setTimeout(() => {
            const modal = document.getElementById('edit-route-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    };

    window.saveBusRoute = async function() {
        const btn = document.getElementById('save-route-btn');
        const routeId = document.getElementById('modal-route-id').value;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        try {
            const res = await fetch(`${API_URL}/api/admin/buses/${busId}`, {
                method: 'PUT',
                headers: { ...headers, 'Content-Type': 'application/json' },
                body: JSON.stringify({ route_id: routeId ? parseInt(routeId) : null })
            });

            if (res.ok) {
                location.reload();
            } else {
                const err = await res.json();
                alert('Error: ' + (err.message || 'Failed to update route'));
            }
        } catch(e) {
            alert('Network error.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save';
        }
    };

    // Init
    await loadBus();
});
</script>
@endpush
@endsection
