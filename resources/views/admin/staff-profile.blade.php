@extends('admin.layouts.admin')

@section('title', 'Staff Profile')
@section('header_title', 'Staff Profile')
@section('header_subtitle', 'Detailed information and performance overview')

@section('content')
<div class="flex flex-col gap-6">

    {{-- Back Button --}}
    <div>
        <a href="/admin/staff" class="inline-flex items-center gap-2 text-slate-400 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Staff
        </a>
    </div>

    {{-- Loading Skeleton --}}
    <div id="profile-loading" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-slate-900/50 border border-slate-800 rounded-xl p-6 animate-pulse">
            <div class="flex flex-col items-center gap-3">
                <div class="w-28 h-28 rounded-full bg-slate-800"></div>
                <div class="h-5 w-32 bg-slate-800 rounded"></div>
                <div class="h-3 w-20 bg-slate-800 rounded"></div>
            </div>
        </div>
        <div class="lg:col-span-2 bg-slate-900/50 border border-slate-800 rounded-xl p-6 animate-pulse space-y-4">
            <div class="h-4 w-48 bg-slate-800 rounded"></div>
            <div class="grid grid-cols-2 gap-4">
                <div class="h-16 bg-slate-800 rounded-lg"></div>
                <div class="h-16 bg-slate-800 rounded-lg"></div>
                <div class="h-16 bg-slate-800 rounded-lg"></div>
                <div class="h-16 bg-slate-800 rounded-lg"></div>
            </div>
        </div>
    </div>

    {{-- Actual Profile Content --}}
    <div id="profile-content" class="hidden grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Identity Card --}}
        <div class="lg:col-span-1 flex flex-col gap-6">

            {{-- Avatar Card --}}
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6 flex flex-col items-center text-center relative overflow-hidden">
                {{-- Glow BG --}}
                <div id="profile-glow" class="absolute -top-10 left-1/2 -translate-x-1/2 w-48 h-48 rounded-full blur-3xl opacity-20 pointer-events-none"></div>

                <div id="avatar-ring" class="relative w-28 h-28 rounded-full p-1 border-2 mb-4 z-10">
                    <img id="profile-avatar" src="" alt="Avatar" class="w-full h-full rounded-full object-cover">
                    <div id="avatar-badge" class="absolute bottom-1 right-1 w-8 h-8 rounded-full border-2 border-slate-900 flex items-center justify-center">
                        <i id="avatar-icon" class="text-sm"></i>
                    </div>
                </div>

                <h2 id="profile-name" class="text-2xl font-bold text-white mb-1">--</h2>
                <p id="profile-role" class="text-xs font-semibold tracking-widest uppercase mb-3"></p>

                <div id="profile-status-badge" class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border mb-5">
                    <span id="status-dot" class="w-2 h-2 rounded-full"></span>
                    <span id="status-text">--</span>
                </div>

                {{-- Toggle Availability --}}
                <button id="toggle-availability-btn" onclick="toggleAvailability()" class="w-full py-2 rounded-lg text-xs font-semibold border transition-all">
                    Toggle Availability
                </button>
            </div>

            {{-- Quick Info --}}
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-5 space-y-4">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Quick Info</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-id-badge text-indigo-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500">Employee ID</p>
                            <p id="info-employee-id" class="text-sm font-mono text-white font-semibold">--</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-envelope text-indigo-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500">Email</p>
                            <p id="info-email" class="text-sm text-white break-all">--</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone text-indigo-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500">Phone</p>
                            <p id="info-phone" class="text-sm text-white">--</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-calendar-plus text-indigo-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500">Joined</p>
                            <p id="info-joined" class="text-sm text-white">--</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Details --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Stats Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-route text-indigo-400"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500">Total Trips</p>
                        <p id="stat-trips" class="text-xl font-bold text-white">--</p>
                    </div>
                </div>
                <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bus text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500">Assigned Bus</p>
                        <p id="stat-bus" class="text-xl font-bold text-white">--</p>
                    </div>
                </div>
                <div id="stat-license-card" class="hidden bg-slate-900/50 border border-slate-800 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-id-card text-amber-400"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500">License Type</p>
                        <p id="stat-license" class="text-xl font-bold text-white">--</p>
                    </div>
                </div>
            </div>

            {{-- Assigned Bus Detail --}}
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Current Bus Assignment</h3>
                <div id="bus-assignment" class="text-slate-500 text-sm italic">Loading...</div>
            </div>

            {{-- Driver-only: License Info --}}
            <div id="license-section" class="hidden bg-slate-900/50 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">
                    <i class="fa-solid fa-id-card text-amber-400 mr-1"></i> License Details
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-800/50 rounded-lg p-3">
                        <p class="text-[10px] text-slate-500 mb-1">License Number</p>
                        <p id="license-number" class="text-sm font-mono text-white font-semibold">--</p>
                    </div>
                    <div class="bg-slate-800/50 rounded-lg p-3">
                        <p class="text-[10px] text-slate-500 mb-1">License Type</p>
                        <p id="license-type" class="text-sm text-white font-semibold">--</p>
                    </div>
                    <div class="bg-slate-800/50 rounded-lg p-3 col-span-2">
                        <p class="text-[10px] text-slate-500 mb-1">Expiry Date</p>
                        <p id="license-expiry" class="text-sm text-white font-semibold">--</p>
                    </div>
                </div>
            </div>

            {{-- Edit Staff Section --}}
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Edit Staff Info</h3>
                    <button onclick="toggleEdit()" id="edit-toggle-btn" class="text-xs px-3 py-1.5 border border-slate-700 text-slate-300 hover:text-white rounded-lg transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-pen"></i> Edit
                    </button>
                </div>
                <form id="edit-form" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Employee ID</label>
                            <input type="text" id="edit-employee-id" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Phone</label>
                            <input type="text" id="edit-phone" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="toggleEdit()" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-colors">Cancel</button>
                        <button type="submit" id="save-edit-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                        </button>
                    </div>
                </form>
                <div id="edit-empty-state" class="text-center py-3 text-slate-500 text-sm">
                    Click <span class="text-slate-300 font-medium">Edit</span> to modify staff information.
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-red-950/20 border border-red-900/40 rounded-xl p-5">
                <h3 class="text-xs font-bold text-red-500 uppercase tracking-widest mb-3">Danger Zone</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300 font-medium">Remove Staff</p>
                        <p class="text-xs text-slate-500">This will permanently delete this staff record.</p>
                    </div>
                    <button onclick="deleteStaff()" class="px-4 py-2 border border-red-700/60 text-red-400 hover:bg-red-700 hover:text-white text-xs font-semibold rounded-lg transition-all flex items-center gap-2">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Error State --}}
    <div id="profile-error" class="hidden text-center py-20 text-red-400">
        <i class="fa-solid fa-triangle-exclamation text-4xl mb-4"></i>
        <p id="error-msg">Failed to load staff profile.</p>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = window.API_URL || 'http://localhost:8001';
    const token = localStorage.getItem('token');
    const staffType = '{{ $type }}'; // 'driver' or 'conductor'
    const staffId   = {{ $id }};

    const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };
    let staffData = null;

    // ---- Load Profile ----
    try {
        const endpoint = staffType === 'driver'
            ? `/api/admin/drivers/${staffId}`
            : `/api/admin/conductors/${staffId}`;

        const res = await fetch(`${API_URL}${endpoint}`, { headers });
        if (!res.ok) throw new Error(res.status === 404 ? 'Staff not found.' : 'Failed to load.');

        const json = await res.json();
        staffData = json.data;
        renderProfile(staffData);
    } catch(err) {
        document.getElementById('profile-loading').classList.add('hidden');
        document.getElementById('profile-error').classList.remove('hidden');
        document.getElementById('error-msg').textContent = err.message;
        return;
    }

    // ---- Render ----
    function renderProfile(s) {
        const isDriver = staffType === 'driver';
        const color = isDriver ? 'indigo' : 'emerald';
        const icon  = isDriver ? 'fa-steering-wheel' : 'fa-ticket';
        const isAvail = s.is_available == 1 || s.is_available === true;
        const name  = s.user?.name || 'Unknown';

        // Avatar
        const avatarUrl = s.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=1e293b&color=94a3b8&size=128`;
        document.getElementById('profile-avatar').src = avatarUrl;
        document.getElementById('profile-glow').style.background = isDriver ? '#6366f1' : '#10b981';
        document.getElementById('avatar-ring').className = `relative w-28 h-28 rounded-full p-1 border-2 mb-4 z-10 border-${color}-500/60`;
        document.getElementById('avatar-badge').className = `absolute bottom-1 right-1 w-8 h-8 rounded-full border-2 border-slate-900 flex items-center justify-center bg-${color}-600`;
        document.getElementById('avatar-icon').className = `fa-solid ${icon} text-white text-xs`;

        document.getElementById('profile-name').textContent = name;
        document.getElementById('profile-role').className = `text-xs font-semibold tracking-widest uppercase mb-3 text-${color}-400`;
        document.getElementById('profile-role').textContent = isDriver ? 'Driver' : 'Conductor';

        // Status badge
        const statusBadge = document.getElementById('profile-status-badge');
        statusBadge.className = `inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border mb-5 ${isAvail ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-slate-800 text-slate-400 border-slate-700'}`;
        document.getElementById('status-dot').className = `w-2 h-2 rounded-full ${isAvail ? 'bg-emerald-500 animate-pulse' : 'bg-slate-500'}`;
        document.getElementById('status-text').textContent = isAvail ? 'Available' : 'Off Duty';

        // Toggle btn
        const toggleBtn = document.getElementById('toggle-availability-btn');
        if (isAvail) {
            toggleBtn.className = 'w-full py-2 rounded-lg text-xs font-semibold border transition-all border-amber-700/50 text-amber-400 hover:bg-amber-700/20';
            toggleBtn.innerHTML = '<i class="fa-solid fa-moon mr-1"></i> Set Off Duty';
        } else {
            toggleBtn.className = 'w-full py-2 rounded-lg text-xs font-semibold border transition-all border-emerald-700/50 text-emerald-400 hover:bg-emerald-700/20';
            toggleBtn.innerHTML = '<i class="fa-solid fa-sun mr-1"></i> Set Available';
        }

        // Quick Info
        document.getElementById('info-employee-id').textContent = s.employee_id || 'N/A';
        document.getElementById('info-email').textContent = s.user?.email || 'N/A';
        document.getElementById('info-phone').textContent = s.phone || 'N/A';
        const joined = s.joined_at ? new Date(s.joined_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A';
        document.getElementById('info-joined').textContent = joined;

        // Stats
        document.getElementById('stat-trips').textContent = s.trips?.length ?? (s.trips_count ?? 0);
        document.getElementById('stat-bus').textContent = s.bus?.plate_number || 'None';

        // Driver-only license
        if (isDriver && s.license_number) {
            document.getElementById('license-section').classList.remove('hidden');
            document.getElementById('stat-license-card').classList.remove('hidden');
            document.getElementById('stat-license-card').classList.add('flex');
            document.getElementById('stat-license').textContent = s.license_type || '--';
            document.getElementById('license-number').textContent = s.license_number;
            document.getElementById('license-type').textContent = s.license_type || '--';
            const expiry = s.license_expiry ? new Date(s.license_expiry).toLocaleDateString('id-ID', { year:'numeric', month:'long', day:'numeric' }) : 'N/A';
            document.getElementById('license-expiry').textContent = expiry;
        }

        // Bus Assignment
        const busDiv = document.getElementById('bus-assignment');
        if (s.bus) {
            const busColor = isDriver ? 'indigo' : 'emerald';
            busDiv.innerHTML = `
            <div class="flex items-center justify-between bg-slate-800/50 rounded-lg p-4 border border-slate-700/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-${busColor}-500/10 border border-${busColor}-500/30 flex items-center justify-center">
                        <i class="fa-solid fa-bus text-${busColor}-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-lg">${s.bus.plate_number || 'N/A'}</p>
                        <p class="text-xs text-slate-400">${s.bus.capacity ? s.bus.capacity + ' seats' : ''} ${s.bus.route ? '· Route: ' + s.bus.route.name : ''}</p>
                    </div>
                </div>
                <a href="/admin/fleet/${s.bus.id}" class="text-xs px-3 py-1.5 border border-${busColor}-700/50 text-${busColor}-400 hover:bg-${busColor}-700/20 rounded-lg transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> View Bus
                </a>
            </div>`;
        } else {
            busDiv.innerHTML = `<p class="text-slate-500 italic text-sm">No bus assigned yet.</p>`;
        }

        // Prefill edit form
        document.getElementById('edit-employee-id').value = s.employee_id || '';
        document.getElementById('edit-phone').value = s.phone || '';

        // Show content
        document.getElementById('profile-loading').classList.add('hidden');
        document.getElementById('profile-content').classList.remove('hidden');
        document.getElementById('profile-content').classList.add('grid');
    }

    // ---- Toggle Availability ----
    window.toggleAvailability = async function() {
        const newVal = !(staffData.is_available == 1 || staffData.is_available === true);
        const endpoint = staffType === 'driver'
            ? `/api/admin/drivers/${staffId}`
            : `/api/admin/conductors/${staffId}`;

        const res = await fetch(`${API_URL}${endpoint}`, {
            method: 'PUT',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify({ is_available: newVal })
        });
        if (res.ok) {
            const json = await res.json();
            staffData = { ...staffData, is_available: newVal };
            renderProfile(staffData);
        } else {
            alert('Failed to update availability.');
        }
    };

    // ---- Edit Toggle ----
    window.toggleEdit = function() {
        const form = document.getElementById('edit-form');
        const empty = document.getElementById('edit-empty-state');
        form.classList.toggle('hidden');
        empty.classList.toggle('hidden');
    };

    // ---- Save Edit ----
    document.getElementById('edit-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('save-edit-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        const body = {
            employee_id: document.getElementById('edit-employee-id').value.trim(),
            phone: document.getElementById('edit-phone').value.trim(),
        };

        const endpoint = staffType === 'driver'
            ? `/api/admin/drivers/${staffId}`
            : `/api/admin/conductors/${staffId}`;

        const res = await fetch(`${API_URL}${endpoint}`, {
            method: 'PUT',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });

        if (res.ok) {
            const json = await res.json();
            staffData = { ...staffData, ...body };
            renderProfile(staffData);
            toggleEdit();
        } else {
            const err = await res.json();
            alert('Error: ' + (err.message || JSON.stringify(err.errors || err)));
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Changes';
    });

    // ---- Delete Staff ----
    window.deleteStaff = async function() {
        if (!confirm(`Are you sure you want to permanently delete this staff record?\nThis action cannot be undone.`)) return;

        const endpoint = staffType === 'driver'
            ? `/api/admin/drivers/${staffId}`
            : `/api/admin/conductors/${staffId}`;

        const res = await fetch(`${API_URL}${endpoint}`, { method: 'DELETE', headers });
        if (res.ok) {
            window.location.href = '/admin/staff';
        } else {
            alert('Failed to delete staff.');
        }
    };
});
</script>
@endpush
@endsection
