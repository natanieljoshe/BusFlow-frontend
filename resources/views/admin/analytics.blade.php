@extends('admin.layouts.admin')

@section('title', 'System Analytics - BusFlow')
@section('page_title', 'System Analytics')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Metric 1 -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 shadow-lg">
            <h4 class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">Total Buses</h4>
            <div class="flex items-end gap-3">
                <span class="text-3xl font-bold text-white" id="stat-buses">...</span>
                <i class="fa-solid fa-bus text-indigo-400 mb-1"></i>
            </div>
        </div>
        <!-- Metric 2 -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 shadow-lg">
            <h4 class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">Total Drivers</h4>
            <div class="flex items-end gap-3">
                <span class="text-3xl font-bold text-white" id="stat-drivers">...</span>
                <i class="fa-solid fa-id-card text-emerald-400 mb-1"></i>
            </div>
        </div>
        <!-- Metric 3 -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 shadow-lg">
            <h4 class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">Total Passengers</h4>
            <div class="flex items-end gap-3">
                <span class="text-3xl font-bold text-white" id="stat-passengers">...</span>
                <i class="fa-solid fa-users text-amber-400 mb-1"></i>
            </div>
        </div>
        <!-- Metric 4 -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 shadow-lg">
            <h4 class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">Total Routes</h4>
            <div class="flex items-end gap-3">
                <span class="text-3xl font-bold text-white" id="stat-routes">...</span>
                <i class="fa-solid fa-route text-rose-400 mb-1"></i>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bus Status Chart -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-4 sm:p-6 shadow-lg">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-base sm:text-lg font-semibold text-slate-200">Bus Status Overview</h3>
            </div>
            <div class="h-64 sm:h-72 w-full relative">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        
        <!-- Routes Chart -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-4 sm:p-6 shadow-lg">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-base sm:text-lg font-semibold text-slate-200">Routes Overview</h3>
            </div>
            <div class="h-64 sm:h-72 w-full relative">
                <canvas id="routeChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    const token = localStorage.getItem('token');
    if (!token) return;

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    async function fetchWithAuth(endpoint) {
        try {
            const response = await fetch(`${API_URL}${endpoint}`, { headers });
            if (!response.ok) return [];
            const data = await response.json();
            return Array.isArray(data) ? data : (data.data || []);
        } catch (error) {
            console.error(`Error fetching ${endpoint}:`, error);
            return [];
        }
    }

    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = "'Inter', sans-serif";
    
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: '#cbd5e1', font: { size: 12 } } }
        }
    };

    // Initialize Empty Charts
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [0, 0],
                backgroundColor: ['#34d399', '#f87171'],
                borderWidth: 0
            }]
        },
        options: { ...commonOptions, cutout: '70%' }
    });

    const ctxRoute = document.getElementById('routeChart').getContext('2d');
    const routeChart = new Chart(ctxRoute, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Routes (Count)',
                data: [],
                backgroundColor: '#818cf8',
                borderRadius: 4
            }]
        },
        options: {
            ...commonOptions,
            plugins: { ...commonOptions.plugins, legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(51, 65, 85, 0.3)', drawBorder: false } },
                y: { grid: { color: 'rgba(51, 65, 85, 0.3)', drawBorder: false } }
            }
        }
    });

    // Fetch Data
    const [buses, drivers, users, routes] = await Promise.all([
        fetchWithAuth('/api/admin/buses'),
        fetchWithAuth('/api/admin/drivers'),
        fetchWithAuth('/api/admin/users'),
        fetchWithAuth('/api/admin/routes')
    ]);

    // Update Stats
    const passengers = users.filter(u => u.role === 'passenger');
    document.getElementById('stat-buses').innerText = buses.length;
    document.getElementById('stat-drivers').innerText = drivers.length;
    document.getElementById('stat-passengers').innerText = passengers.length;
    document.getElementById('stat-routes').innerText = routes.length;

    // Update Status Chart
    const activeBuses = buses.filter(b => b.status).length;
    const inactiveBuses = buses.length - activeBuses;
    statusChart.data.datasets[0].data = [activeBuses, inactiveBuses];
    statusChart.update();

    // Update Route Chart
    routeChart.data.labels = routes.map(r => r.name || r.code);
    routeChart.data.datasets[0].data = routes.map(() => 1); // Just showing routes for now
    routeChart.update();
});
</script>
@endpush
@endsection

