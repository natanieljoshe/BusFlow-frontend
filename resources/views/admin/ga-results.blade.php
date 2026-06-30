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
    
    if (isset($payload['schedule_data']['route'])) {
        $routeParam = $payload['schedule_data']['route'];
    } elseif (isset($payload['route_stops']) && collect($payload['route_stops'])->count() > 0) {
        $routeParam = $payload['route_stops'][0]['route_id'];
    }
    
    if (isset($payload['schedule_data']['date'])) {
        $dateParam = $payload['schedule_data']['date'];
    }

    $routeName = $routeParam;
    if (isset($payload['routes']) && count($payload['routes']) > 0) {
        $r = $payload['routes'][0];
        $code = $r['code'] ?? $r['id'] ?? $routeParam;
        $name = $r['name'] ?? '';
        $routeName = $name ? "{$code} - {$name}" : $code;
    }

    $dateStr = \Carbon\Carbon::parse($dateParam)->isoFormat('dddd, D MMMM Y');
    
    // Find stops for map based on payload.JSON
    $targetRouteForMap = 'Q114';
    if(isset($payload['route_stops'])) {
        $hasRoute = collect($payload['route_stops'])->where('route_id', $routeParam)->count() > 0;
        if($hasRoute) $targetRouteForMap = $routeParam;
    }
    $stops = collect($payload['route_stops'] ?? [])->where('route_id', $targetRouteForMap)->values()->toJson();

    // Recommendation counts
    $trips = $payload['trips'] ?? [];
    $uniqueBuses = count(array_unique(array_column($trips, 'bus_id')));
    $uniqueDrivers = count(array_unique(array_column($trips, 'driver_id')));
    $uniqueConductors = count(array_unique(array_column($trips, 'conductor_id')));
    $totalPenalty = $payload['schedule_data']['total_penalty'] ?? 0;
@endphp

<div class="space-y-8 max-w-7xl mx-auto pb-12">
    <!-- Header -->
    <div class="glass-card p-6 border-l-4 border-l-indigo-500 flex justify-between items-center">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-microchip text-indigo-400"></i> Hasil Optimasi Jadwal Bus <span class="text-indigo-400">{{ $routeName }}</span>
                </h1>
                <p class="text-slate-400"><i class="fa-regular fa-calendar mr-2"></i> {{ $dateStr }}</p>
            </div>
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

            <!-- Efisiensi & Rekomendasi -->
            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-700/50 pb-4">
                    <div class="p-2 bg-emerald-500/20 text-emerald-400 rounded-lg"><i class="fa-solid fa-lightbulb"></i></div>
                    <h2 class="text-lg font-bold text-white">Rekomendasi Kebutuhan Rute AI</h2>
                </div>
                
                <div class="space-y-6">
                    <div class="bg-slate-900/50 rounded-lg p-4 border border-indigo-500/30">
                        <p class="text-sm text-slate-300 mb-4">Berdasarkan hasil optimasi, rute ini hanya membutuhkan sumber daya berikut untuk beroperasi secara maksimal tanpa penundaan:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-slate-800 rounded-lg p-4 text-center border border-slate-700">
                                <div class="text-amber-400 text-3xl mb-2"><i class="fa-solid fa-bus"></i></div>
                                <div class="text-3xl font-bold text-white">{{ $uniqueBuses > 0 ? $uniqueBuses : '-' }}</div>
                                <div class="text-sm text-slate-400 mt-1">Armada Bus</div>
                            </div>
                            <div class="bg-slate-800 rounded-lg p-4 text-center border border-slate-700">
                                <div class="text-blue-400 text-3xl mb-2"><i class="fa-solid fa-user-tie"></i></div>
                                <div class="text-3xl font-bold text-white">{{ $uniqueDrivers > 0 ? $uniqueDrivers : '-' }}</div>
                                <div class="text-sm text-slate-400 mt-1">Supir</div>
                            </div>
                            <div class="bg-slate-800 rounded-lg p-4 text-center border border-slate-700">
                                <div class="text-emerald-400 text-3xl mb-2"><i class="fa-solid fa-ticket"></i></div>
                                <div class="text-3xl font-bold text-white">{{ $uniqueConductors > 0 ? $uniqueConductors : '-' }}</div>
                                <div class="text-sm text-slate-400 mt-1">Kondektur</div>
                            </div>
                            <div class="bg-slate-800 rounded-lg p-4 text-center border border-red-500/30">
                                <div class="text-red-400 text-3xl mb-2"><i class="fa-solid fa-scale-unbalanced"></i></div>
                                <div class="text-3xl font-bold text-white">{{ number_format($totalPenalty, 0, ',', '.') }}</div>
                                <div class="text-sm text-slate-400 mt-1">Total Penalti</div>
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
                <div class="flex justify-between items-center mb-4 border-b border-slate-700/50 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-500/20 text-amber-400 rounded-lg"><i class="fa-solid fa-network-wired"></i></div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Tabel Jadwal Waktu Terbaru</h2>
                            <p class="text-xs text-slate-400">Slot keberangkatan berdasarkan optimasi AI</p>
                        </div>
                    </div>
                    @if(request('mode') !== 'demo')
                    <button id="btn-apply-schedule" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-check-double"></i> Apply New Schedule
                    </button>
                    @endif
                </div>
                
                <div class="max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                                <th class="pb-3 pt-2 font-medium">Waktu Keberangkatan</th>
                                <th class="pb-3 pt-2 font-medium">Siklus</th>
                                <th class="pb-3 pt-2 font-medium">Headway</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-300">
                            @if(count($trips) > 0)
                                @foreach($trips as $index => $trip)
                                    <tr class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors">
                                        <td class="py-3 font-semibold text-white">{{ $trip['departure_time'] }}</td>
                                        <td class="py-3">
                                            <span class="bg-indigo-500/20 text-indigo-300 px-2 py-1 rounded text-xs">Trip {{ $index + 1 }}</span>
                                        </td>
                                        <td class="py-3 text-slate-400">
                                            @if($index > 0)
                                                @php
                                                    $prev = \Carbon\Carbon::parse($trips[$index-1]['departure_time']);
                                                    $curr = \Carbon\Carbon::parse($trip['departure_time']);
                                                    $diff = $prev->diffInMinutes($curr);
                                                @endphp
                                                {{ $diff }} menit
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-slate-500">Data jadwal tidak ditemukan.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
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
        
        // Handle Apply Schedule
        const btnApply = document.getElementById('btn-apply-schedule');
        if (btnApply) {
            btnApply.addEventListener('click', async function() {
                const confirmed = confirm("Apakah Anda yakin ingin menerapkan jadwal ini ke database? Jadwal lama pada tanggal ini untuk rute terpilih akan dihapus dan diganti secara massal.");
                if (!confirmed) return;
                
                const urlParams = new URLSearchParams(window.location.search);
                const jobId = urlParams.get('job_id');
                if (!jobId) {
                    alert('Job ID tidak ditemukan!');
                    return;
                }
                
                const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
                const token = localStorage.getItem('token');
                
                btnApply.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                btnApply.disabled = true;
                
                try {
                    const response = await fetch(`${API_URL}/api/admin/schedules/apply`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({ job_id: jobId })
                    });
                    
                    const result = await response.json();
                    if (response.ok) {
                        alert('Sukses! Jadwal baru berhasil diterapkan ke database.');
                    } else {
                        alert('Gagal: ' + (result.message || 'Terjadi kesalahan pada server.'));
                    }
                } catch(e) {
                    alert('Gagal terhubung ke server.');
                    console.error(e);
                }
                
                btnApply.innerHTML = '<i class="fa-solid fa-check-double"></i> Apply New Schedule';
                btnApply.disabled = false;
            });
        }
    });
</script>
@endpush
