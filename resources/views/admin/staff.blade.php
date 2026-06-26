@extends('admin.layouts.admin')

@section('title', 'Staff Directory')
@section('header_title', 'Staff Directory')
@section('header_subtitle', 'Operational personnel tracking and shift management')

@section('content')
<div class="flex flex-col gap-6">

    <!-- Filters & Search -->
    <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800 rounded-xl p-4 flex flex-col md:flex-row items-center gap-4 justify-between shadow-lg">
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-slate-500"></i>
            </div>
            <input type="text" id="staff-search" class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 placeholder-slate-500" placeholder="Search ID, Name, or Role...">
        </div>
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 w-full md:w-auto">
            <select id="staff-role-filter" class="bg-slate-800/50 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 w-full md:w-auto outline-none">
                <option value="all">All Roles</option>
                <option value="driver">Drivers</option>
                <option value="conductor">Conductors</option>
                <option value="maintenance">Maintenance</option>
            </select>
            <select id="staff-status-filter" class="bg-slate-800/50 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 w-full md:w-auto outline-none">
                <option value="all">All Status</option>
                <option value="available">Available</option>
                <option value="on_duty">On Duty</option>
                <option value="off_duty">Off Duty</option>
            </select>
            <button onclick="openAddStaffModal()" class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg px-4 py-2.5 text-sm font-medium transition-colors shadow-[0_0_15px_rgba(79,70,229,0.3)] flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add Staff
            </button>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div id="add-staff-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300 mx-4" id="add-staff-modal-content">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-white">Add New Staff</h3>
                <button onclick="closeAddStaffModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="add-staff-form" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Role Type</label>
                    <select id="staff-role-type" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="driver">Driver</option>
                        <option value="conductor">Conductor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">User Account</label>
                    <select id="staff-user-id" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="">-- Select User --</option>
                    </select>
                    <p class="text-[10px] text-slate-500 mt-1">Only shows users not yet registered as staff</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Employee ID</label>
                    <input type="text" id="staff-employee-id" placeholder="e.g. DRV-001" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Phone (optional)</label>
                    <input type="text" id="staff-phone" placeholder="e.g. 08123456789" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAddStaffModal()" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="save-staff-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save Staff
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Staff Grid -->
    <div id="staff-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="col-span-full text-center py-10 text-slate-500">
            <i class="fa-solid fa-spinner fa-spin text-indigo-500 text-3xl mb-4"></i>
            <p>Loading staff directory...</p>
        </div>
    </div>

</div>

@push('scripts')
<script>
// ---- Add Staff Modal ----
let allUsers = [];

window.openAddStaffModal = async function() {
    const modal = document.getElementById('add-staff-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => document.getElementById('add-staff-modal-content').classList.remove('scale-95'), 10);

    // Load available users (not yet staff)
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    const token = localStorage.getItem('token');
    const userSel = document.getElementById('staff-user-id');
    userSel.innerHTML = '<option value="">Loading...</option>';
    try {
        const res = await fetch(`${API_URL}/api/admin/users`, {
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });
        const data = await res.json();
        allUsers = Array.isArray(data) ? data : (data.data || []);
        userSel.innerHTML = '<option value="">-- Select User --</option>' +
            allUsers.map(u => `<option value="${u.id}">${u.name} (${u.email})</option>`).join('');
    } catch(e) {
        userSel.innerHTML = '<option value="">Failed to load users</option>';
    }
};

window.closeAddStaffModal = function() {
    document.getElementById('add-staff-modal-content').classList.add('scale-95');
    setTimeout(() => {
        const modal = document.getElementById('add-staff-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
};

document.getElementById('add-staff-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    const token = localStorage.getItem('token');
    const roleType = document.getElementById('staff-role-type').value;
    const userId = document.getElementById('staff-user-id').value;
    const employeeId = document.getElementById('staff-employee-id').value.trim();
    const phone = document.getElementById('staff-phone').value.trim();
    const btn = document.getElementById('save-staff-btn');

    if (!userId) { alert('Please select a user.'); return; }
    if (!employeeId) { alert('Please enter an Employee ID.'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

    const endpoint = roleType === 'driver' ? '/api/admin/drivers' : '/api/admin/conductors';
    const body = { user_id: parseInt(userId), employee_id: employeeId };
    if (phone) body.phone = phone;

    try {
        const res = await fetch(`${API_URL}${endpoint}`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
            body: JSON.stringify(body)
        });
        if (res.ok) {
            closeAddStaffModal();
            document.getElementById('add-staff-form').reset();
            // Reload staff list
            location.reload();
        } else {
            const err = await res.json();
            alert('Error: ' + (err.message || JSON.stringify(err.errors || err)));
        }
    } catch(err) {
        alert('Network error: ' + err.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Staff';
    }
});

document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('staff-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Silakan login terlebih dahulu. Token tidak ditemukan.</div>`;
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
                document.getElementById('staff-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Sesi telah berakhir. Silakan login kembali.</div>`;
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

    let globalStaffList = [];

    // Fetch both drivers and conductors
    const [drivers, conductors] = await Promise.all([
        fetchWithAuth('/api/admin/drivers'),
        fetchWithAuth('/api/admin/conductors')
    ]);

    globalStaffList = [
        ...drivers.map(d => ({ ...d, role_type: 'Driver' })),
        ...conductors.map(c => ({ ...c, role_type: 'Conductor' }))
    ];

    function renderStaff(list) {
        const grid = document.getElementById('staff-grid');
        grid.innerHTML = '';
        
        if (list.length === 0) {
            grid.innerHTML = `<div class="col-span-full text-center py-10 text-slate-500">No staff records found.</div>`;
            return;
        }

        list.forEach(staff => {
            const isDriver = staff.role_type === 'Driver';
            const roleColor = isDriver ? 'indigo' : 'emerald';
            const roleIcon = isDriver ? 'fa-steering-wheel' : 'fa-ticket';
            
            // Infer status from boolean is_available
            const isAvailable = staff.is_available == 1 || staff.is_available === true;
            let statusColor = isAvailable ? 'emerald' : 'slate';
            let statusLabel = isAvailable ? 'Available' : 'Off Duty';
            let statusBadge = isAvailable ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-400 border border-slate-700';
            let statusDot = isAvailable ? 'bg-emerald-500 animate-pulse shadow-[0_0_5px_rgba(16,185,129,0.8)]' : 'bg-slate-500';

            const name = staff.name || staff.user?.name || 'Unknown';
            const staffId = staff.employee_id || staff.id || 'N/A';
            const avatarUrl = staff.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=1e293b&color=94a3b8`;
            const assignment = staff.current_route || staff.bus_id || 'None';

            grid.innerHTML += `
            <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 relative overflow-hidden group hover:border-${roleColor}-500/40 transition-colors shadow-lg ${!isAvailable ? 'opacity-70' : ''}">
                <div class="absolute top-0 right-0 p-3">
                    <span class="w-2 h-2 rounded-full ${statusDot} block"></span>
                </div>
                <div class="flex flex-col items-center mb-4">
                    <div class="w-20 h-20 rounded-full border-2 border-${roleColor}-500/50 p-1 mb-3 relative ${!isAvailable ? 'grayscale' : ''}">
                        <img src="${avatarUrl}" alt="${name}" class="w-full h-full rounded-full object-cover">
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-slate-800 border border-slate-700 rounded-full flex items-center justify-center">
                            <i class="fa-solid ${roleIcon} text-${roleColor}-400 text-[10px]"></i>
                        </div>
                    </div>
                    <h4 class="text-white font-semibold text-lg tracking-wide">${name}</h4>
                    <p class="text-${roleColor}-400 text-xs font-medium tracking-widest uppercase mt-0.5">${staff.role_type}</p>
                </div>
                
                <div class="space-y-3 bg-slate-800/30 rounded-lg p-3 border border-slate-800/50">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">ID</span>
                        <span class="text-slate-300 font-mono">${staffId}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Status</span>
                        <span class="font-medium ${statusBadge} px-2 py-0.5 rounded">${statusLabel}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Current Assignment</span>
                        <span class="text-slate-300">${assignment}</span>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <a href="/admin/staff/${staff.role_type.toLowerCase()}/${staff.id}" class="flex-1 text-center bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded-lg py-2 text-xs font-medium transition-colors">
                        <i class="fa-solid fa-id-card mr-1"></i> Profile
                    </a>
                </div>
            </div>`;
        });
    }

    // Initial render
    renderStaff(globalStaffList);

    // Filter Logic
    const searchInput = document.getElementById('staff-search');
    const roleSelect = document.getElementById('staff-role-filter');
    const statusSelect = document.getElementById('staff-status-filter');

    function filterStaff() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleFilter = roleSelect.value.toLowerCase();
        const statusFilter = statusSelect.value.toLowerCase();

        const filtered = globalStaffList.filter(staff => {
            const name = (staff.name || staff.user?.name || '').toLowerCase();
            const staffId = String(staff.employee_id || staff.id || '').toLowerCase();
            const role = (staff.role_type || '').toLowerCase();
            
            const isAvailable = staff.is_available == 1 || staff.is_available === true;
            const statusStr = isAvailable ? 'available' : 'off_duty';

            const matchesSearch = name.includes(searchTerm) || staffId.includes(searchTerm) || role.includes(searchTerm);
            const matchesRole = roleFilter === 'all' || role === roleFilter;
            const matchesStatus = statusFilter === 'all' || statusStr === statusFilter;

            return matchesSearch && matchesRole && matchesStatus;
        });

        renderStaff(filtered);
    }

    searchInput.addEventListener('input', filterStaff);
    roleSelect.addEventListener('change', filterStaff);
    statusSelect.addEventListener('change', filterStaff);
});
</script>
@endpush
@endsection
