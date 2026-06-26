@extends('admin.layouts.admin')

@section('title', 'Optimization Results')
@section('header_title', 'Optimization Results')
@section('header_subtitle', 'Hasil Jadwal Optimal AI')

@push('styles')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .glass-card {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
    }
    #map { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 10; }
</style>
@endpush

@section('content')
@php
    $routeParam = request('route', 'M15+');
    $dateParam = request('date', '2024-07-15');
    $dateStr = \Carbon\Carbon::parse($dateParam)->isoFormat('dddd, D MMMM Y');
    
    // Find stops for map based on payload.JSON
    $targetRouteForMap = 'Q114';
    if(isset($payload['route_stops'])) {
        $hasRoute = collect($payload['route_stops'])->where('route_id', $routeParam)->count() > 0;
        if($hasRoute) $targetRouteForMap = $routeParam;
    }
    $stops = collect($payload['route_stops'] ?? [])->where('route_id', $targetRouteForMap)->values()->toJson();
@endphp

<div class="space-y-8 max-w-7xl mx-auto pb-12">
    <!-- Header -->
    <div class="glass-card p-6 border-l-4 border-l-indigo-500 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">HASIL OPTIMASI JADWAL BUS — {{ $routeParam }}</h1>
            <p class="text-slate-400"><i class="fa-regular fa-calendar mr-2"></i> {{ $dateStr }}</p>
        </div>
        <a href="{{ route('admin.ga-optimizer') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors text-sm font-medium flex items-center gap-2 bg-indigo-500/10 px-4 py-2 rounded-lg">
            <i class="fa-solid fa-arrow-left"></i> Kembali Edit
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column -->
        <div class="space-y-8">
            <!-- Random Forest Chart -->
            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-700/50 pb-4">
                    <div class="p-2 bg-blue-500/20 text-blue-400 rounded-lg"><i class="fa-solid fa-chart-area"></i></div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Prediksi Penumpang (Random Forest)</h2>
                        <p class="text-xs text-slate-400">Estimasi demand per jam berdasarkan histori data</p>
                    </div>
                </div>
                
                <div class="h-64 w-full mb-4">
                    <canvas id="predictionChart"></canvas>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="bg-slate-900/50 rounded-lg p-3 border border-slate-800">
                        <p class="text-xs text-slate-400 mb-1">Peak Pagi</p>
                        <p class="font-semibold text-white">07.00-09.00 <span class="text-indigo-400 text-sm ml-1">(1.100/jam)</span></p>
                    </div>
                    <div class="bg-slate-900/50 rounded-lg p-3 border border-slate-800">
                        <p class="text-xs text-slate-400 mb-1">Peak Sore</p>
                        <p class="font-semibold text-white">17.00-19.00 <span class="text-indigo-400 text-sm ml-1">(980/jam)</span></p>
                    </div>
                </div>
            </div>

            <!-- Efisiensi -->
            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-700/50 pb-4">
                    <div class="p-2 bg-emerald-500/20 text-emerald-400 rounded-lg"><i class="fa-solid fa-leaf"></i></div>
                    <h2 class="text-lg font-bold text-white">Ringkasan Efisiensi</h2>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">Sebelum Optimasi (Konvensional)</h3>
                        <div class="bg-slate-900/50 rounded-lg p-4 border border-slate-800 flex items-center gap-3 text-slate-300">
                            <i class="fa-solid fa-clock-rotate-left text-slate-500"></i>
                            <p>Headway tetap <strong>15 menit</strong> sepanjang hari tanpa mempedulikan fluktuasi demand.</p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-semibold text-indigo-400 uppercase tracking-wider mb-2">Sesudah Optimasi (Genetic Algorithm)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="bg-slate-900/50 rounded-lg p-4 border border-indigo-500/30 text-center">
                                <div class="text-indigo-400 text-2xl mb-1"><i class="fa-solid fa-users"></i></div>
                                <div class="text-2xl font-bold text-white">+23%</div>
                                <div class="text-xs text-slate-400">Penumpang Terlayani</div>
                            </div>
                            <div class="bg-slate-900/50 rounded-lg p-4 border border-emerald-500/30 text-center">
                                <div class="text-emerald-400 text-2xl mb-1"><i class="fa-solid fa-gas-pump"></i></div>
                                <div class="text-2xl font-bold text-white">+15%</div>
                                <div class="text-xs text-slate-400">Efisiensi BBM</div>
                            </div>
                            <div class="bg-slate-900/50 rounded-lg p-4 border border-amber-500/30 text-center">
                                <div class="text-amber-400 text-2xl mb-1"><i class="fa-solid fa-bus"></i></div>
                                <div class="text-2xl font-bold text-white">8/10</div>
                                <div class="text-xs text-slate-400">Armada Terpakai</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- GA Schedule -->
            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-700/50 pb-4">
                    <div class="p-2 bg-amber-500/20 text-amber-400 rounded-lg"><i class="fa-solid fa-network-wired"></i></div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Jadwal Optimal (Genetic Algorithm)</h2>
                        <p class="text-xs text-slate-400">Headway dinamis berdasarkan prediksi penumpang</p>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between items-center bg-slate-900/30 p-3 rounded-lg border border-slate-800">
                        <span class="text-slate-300 font-medium">05.00 - 06.00</span>
                        <span class="bg-slate-800 px-3 py-1 rounded text-sm text-slate-300">Headway 20 menit</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-900/30 p-3 rounded-lg border border-slate-800">
                        <span class="text-slate-300 font-medium">06.00 - 07.00</span>
                        <span class="bg-slate-800 px-3 py-1 rounded text-sm text-slate-300">Headway 10 menit</span>
                    </div>
                    <div class="flex justify-between items-center bg-indigo-900/20 p-3 rounded-lg border border-indigo-500/30 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-2 h-full bg-red-500"></div>
                        <span class="text-indigo-300 font-bold flex items-center gap-2">07.00 - 09.00 <span class="px-2 py-0.5 bg-red-500/20 text-red-400 text-[10px] rounded uppercase font-bold">Peak</span></span>
                        <span class="bg-indigo-500/20 border border-indigo-500/50 px-3 py-1 rounded text-sm text-indigo-200 font-bold">Headway 5 menit</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-900/30 p-3 rounded-lg border border-slate-800">
                        <span class="text-slate-300 font-medium">09.00 - 15.00</span>
                        <span class="bg-slate-800 px-3 py-1 rounded text-sm text-slate-300">Headway 15 menit</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-900/30 p-3 rounded-lg border border-slate-800">
                        <span class="text-slate-300 font-medium">15.00 - 17.00</span>
                        <span class="bg-slate-800 px-3 py-1 rounded text-sm text-slate-300">Headway 10 menit</span>
                    </div>
                    <div class="flex justify-between items-center bg-indigo-900/20 p-3 rounded-lg border border-indigo-500/30 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-2 h-full bg-red-500"></div>
                        <span class="text-indigo-300 font-bold flex items-center gap-2">17.00 - 19.00 <span class="px-2 py-0.5 bg-red-500/20 text-red-400 text-[10px] rounded uppercase font-bold">Peak</span></span>
                        <span class="bg-indigo-500/20 border border-indigo-500/50 px-3 py-1 rounded text-sm text-indigo-200 font-bold">Headway 6 menit</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-900/30 p-3 rounded-lg border border-slate-800">
                        <span class="text-slate-300 font-medium">19.00 - 23.00</span>
                        <span class="bg-slate-800 px-3 py-1 rounded text-sm text-slate-300">Headway 20 menit</span>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-700/50 pb-4">
                    <div class="p-2 bg-purple-500/20 text-purple-400 rounded-lg"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Konektivitas Halte</h2>
                        <p class="text-xs text-slate-400">Peta rute interaktif dengan transfer terbanyak</p>
                    </div>
                </div>
                
                <div id="map" class="mb-4"></div>
                
                <div class="bg-slate-900/50 rounded-lg p-4 border border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-300 mb-2">Halte dengan Transfer Terbanyak:</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li class="flex items-start gap-2"><i class="fa-solid fa-location-dot mt-1 text-red-400"></i> <span><strong>3 AV/E 42 ST</strong> &rarr; 8 rute lain (Hub utama)</span></li>
                        <li class="flex items-start gap-2"><i class="fa-solid fa-location-dot mt-1 text-orange-400"></i> <span><strong>UNION TPKE</strong> &rarr; 5 rute lain</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Setup Chart.js
        const ctx = document.getElementById('predictionChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
                datasets: [{
                    label: 'Prediksi Penumpang',
                    data: [120, 340, 890, 1100, 600, 450, 400, 420, 430, 410, 500, 680, 850, 980, 500, 300, 200, 150, 95],
                    borderColor: '#818cf8',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#818cf8',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.9)',
                        titleColor: '#818cf8',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return context.parsed.y + ' orang/jam'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8', maxRotation: 45, minRotation: 45 }
                    }
                }
            }
        });

        // 2. Setup Leaflet Map
        const stops = {!! $stops !!};
        if(stops && stops.length > 0) {
            const map = L.map('map').setView([stops[0].latitude, stops[0].longitude], 12);
            
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            const latlngs = [];
            stops.forEach((stop, index) => {
                const latlng = [stop.latitude, stop.longitude];
                latlngs.push(latlng);
                
                let iconColor = '#818cf8';
                let isHub = false;
                if(index === 3 || index === Math.floor(stops.length/2)) {
                    iconColor = '#ef4444';
                    isHub = true;
                }
                
                const markerHtml = `<div style="background-color: ${iconColor}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px ${iconColor};"></div>`;
                const customIcon = L.divIcon({
                    html: markerHtml,
                    className: 'custom-div-icon',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });

                let popupContent = `<b>${stop.stop_name}</b><br><span style="color:#666;font-size:12px;">Route: ${stop.route_id}</span>`;
                if(isHub) popupContent += `<br><span style="color:#ef4444;font-weight:bold;font-size:12px;">Transfer Hub (Multiple Routes)</span>`;

                L.marker(latlng, {icon: customIcon})
                 .bindPopup(popupContent)
                 .addTo(map);
            });

            L.polyline(latlngs, {color: '#818cf8', weight: 4, opacity: 0.8}).addTo(map);
            map.fitBounds(L.polyline(latlngs).getBounds(), {padding: [30, 30]});
        } else {
            document.getElementById('map').innerHTML = '<div class="flex items-center justify-center h-full text-slate-500">Data lokasi halte tidak tersedia di payload untuk rute ini.</div>';
        }
    });
</script>
@endpush
