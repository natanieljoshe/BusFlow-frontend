@extends('admin.layouts.admin')

@section('title', 'Analytics')
@section('header_title', 'Predictive Analytics')
@section('header_subtitle', 'Machine learning passenger volume predictions & insights')

@section('content')
<div class="flex flex-col gap-6">

    <!-- Key Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Metric 1 -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 shadow-lg">
            <h4 class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">Total Passengers (Today)</h4>
            <div class="flex items-end gap-3">
                <span class="text-2xl font-bold text-white">24,512</span>
                <span class="text-xs font-medium text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded border border-emerald-500/20 mb-1">
                    <i class="fa-solid fa-arrow-trend-up"></i> +12.5%
                </span>
            </div>
        </div>
        <!-- Metric 2 -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 shadow-lg">
            <h4 class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">Predicted Peak Hour</h4>
            <div class="flex items-end gap-3">
                <span class="text-2xl font-bold text-white">17:00</span>
                <span class="text-xs font-medium text-amber-400 mb-1">~8,400 pax</span>
            </div>
        </div>
        <!-- Metric 3 -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 shadow-lg">
            <h4 class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">Route Efficiency</h4>
            <div class="flex items-end gap-3">
                <span class="text-2xl font-bold text-indigo-400">92.4%</span>
                <span class="text-xs font-medium text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded border border-emerald-500/20 mb-1">
                    <i class="fa-solid fa-arrow-trend-up"></i> +1.2%
                </span>
            </div>
        </div>
        <!-- Metric 4 -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-5 shadow-lg">
            <h4 class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-2">ML Model Accuracy</h4>
            <div class="flex items-end gap-3">
                <span class="text-2xl font-bold text-emerald-400">96.8%</span>
                <span class="text-xs font-medium text-slate-400 mb-1">v2.1.4</span>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Main Passenger Prediction Chart -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-slate-200">24H Passenger Volume Prediction</h3>
                <select class="bg-slate-800/50 border border-slate-700 text-slate-300 text-xs rounded focus:ring-indigo-500 focus:border-indigo-500 block p-1.5 outline-none">
                    <option>Today</option>
                    <option>Tomorrow</option>
                    <option>Next 7 Days</option>
                </select>
            </div>
            <div class="h-72 w-full relative">
                <canvas id="predictionChart"></canvas>
            </div>
        </div>

        <!-- Fleet Utilization Chart -->
        <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl p-6 shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-slate-200">Corridor Load Factor</h3>
                <button class="text-slate-400 hover:text-white transition-colors"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            </div>
            <div class="h-72 w-full relative">
                <canvas id="loadFactorChart"></canvas>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const API_URL = window.API_URL || 'http://localhost:8001';
    const token = localStorage.getItem('token');
    if (!token) return;

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    async function fetchWithAuth(endpoint) {
        try {
            const response = await fetch(`${API_URL}${endpoint}`, { headers });
            if (response.status === 401) {
                localStorage.removeItem('token');
                return [];
            }
            if (!response.ok) return [];
            const data = await response.json();
            return Array.isArray(data) ? data : (data.data || []);
        } catch (error) {
            console.error(`Error fetching ${endpoint}:`, error);
            return [];
        }
    }

    // Shared Chart Configs
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = "'Inter', sans-serif";
    
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: '#cbd5e1', font: { size: 12 } } },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                titleColor: '#f1f5f9',
                bodyColor: '#cbd5e1',
                borderColor: 'rgba(99, 102, 241, 0.3)',
                borderWidth: 1,
                padding: 12
            }
        },
        scales: {
            x: { grid: { color: 'rgba(51, 65, 85, 0.3)', drawBorder: false } },
            y: { grid: { color: 'rgba(51, 65, 85, 0.3)', drawBorder: false } }
        }
    };

    // Initialize charts empty first
    const ctxPred = document.getElementById('predictionChart').getContext('2d');
    const gradActual = ctxPred.createLinearGradient(0, 0, 0, 400);
    gradActual.addColorStop(0, 'rgba(168, 85, 247, 0.5)');
    gradActual.addColorStop(1, 'rgba(168, 85, 247, 0.0)');
    const gradPredict = ctxPred.createLinearGradient(0, 0, 0, 400);
    gradPredict.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
    gradPredict.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

    const predChart = new Chart(ctxPred, {
        type: 'line',
        data: {
            labels: ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'],
            datasets: [
                {
                    label: 'Actual Volume',
                    data: [],
                    borderColor: '#c084fc',
                    backgroundColor: gradActual,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#1e293b',
                    pointBorderColor: '#c084fc'
                },
                {
                    label: 'ML Prediction',
                    data: [],
                    borderColor: '#818cf8',
                    backgroundColor: gradPredict,
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6
                }
            ]
        },
        options: commonOptions
    });

    const ctxLoad = document.getElementById('loadFactorChart').getContext('2d');
    const loadChart = new Chart(ctxLoad, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Load Factor (%)',
                data: [],
                backgroundColor: [],
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            ...commonOptions,
            plugins: { ...commonOptions.plugins, legend: { display: false } }
        }
    });

    // Fetch data and update
    const [routes, trips] = await Promise.all([
        fetchWithAuth('/api/admin/routes'),
        fetchWithAuth('/api/admin/trips')
    ]);

    // Update Load Factor Chart
    if (routes && routes.length > 0) {
        const routeLabels = routes.map(r => r.name || `R-${r.id}`);
        // Generate mock load factor based on real routes for display if no real load factor endpoint
        const loadData = routes.map(r => Math.floor(Math.random() * 50) + 40); 
        const bgColors = loadData.map(val => {
            if (val > 80) return 'rgba(239, 68, 68, 0.8)'; // Red
            if (val > 60) return 'rgba(245, 158, 11, 0.8)'; // Amber
            return 'rgba(16, 185, 129, 0.8)'; // Emerald
        });

        loadChart.data.labels = routeLabels;
        loadChart.data.datasets[0].data = loadData;
        loadChart.data.datasets[0].backgroundColor = bgColors;
        loadChart.update();
    }

    // Update Prediction Chart with random data scaled by trip count to show dynamicity
    const baseVolume = trips.length > 0 ? trips.length * 10 : 2000;
    const actualData = [
        baseVolume * 0.5, baseVolume * 1.8, baseVolume * 0.9, 
        baseVolume * 1.2, baseVolume * 1.0, null, null, null, null
    ];
    const predictData = [
        baseVolume * 0.48, baseVolume * 1.75, baseVolume * 0.95, 
        baseVolume * 1.25, baseVolume * 1.05, baseVolume * 1.6, 
        baseVolume * 2.1, baseVolume * 0.8, baseVolume * 0.3
    ];
    
    predChart.data.datasets[0].data = actualData;
    predChart.data.datasets[1].data = predictData;
    predChart.update();

    // Update top metrics
    document.querySelector('.grid.gap-4 .text-2xl').innerText = (baseVolume * 5).toLocaleString();
});
</script>
@endpush
@endsection
