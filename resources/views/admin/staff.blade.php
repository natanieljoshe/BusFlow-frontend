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
            <input type="text" class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 placeholder-slate-500" placeholder="Search ID, Name, or Role...">
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <select class="bg-slate-800/50 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 w-full md:w-auto outline-none">
                <option value="all">All Roles</option>
                <option value="driver">Drivers</option>
                <option value="conductor">Conductors</option>
                <option value="maintenance">Maintenance</option>
            </select>
            <select class="bg-slate-800/50 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 w-full md:w-auto outline-none">
                <option value="all">All Status</option>
                <option value="available">Available</option>
                <option value="on_duty">On Duty</option>
                <option value="off_duty">Off Duty</option>
            </select>
            <button class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg px-4 py-2.5 text-sm font-medium transition-colors shadow-[0_0_15px_rgba(79,70,229,0.3)] flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add Staff
            </button>
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
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = window.API_URL || 'http://localhost:8001';
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

    // Fetch both drivers and conductors
    const [drivers, conductors] = await Promise.all([
        fetchWithAuth('/api/admin/drivers'),
        fetchWithAuth('/api/admin/conductors')
    ]);

    const staffList = [
        ...drivers.map(d => ({ ...d, role_type: 'Driver' })),
        ...conductors.map(c => ({ ...c, role_type: 'Conductor' }))
    ];

    const grid = document.getElementById('staff-grid');
    grid.innerHTML = '';
    
    if (staffList.length === 0) {
        grid.innerHTML = `<div class="col-span-full text-center py-10 text-slate-500">No staff records found.</div>`;
        return;
    }

    staffList.forEach(staff => {
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
                <button class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded-lg py-2 text-xs font-medium transition-colors">Profile</button>
            </div>
        </div>`;
    });
});
</script>
@endpush

</div>
@endsection
