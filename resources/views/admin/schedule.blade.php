@extends('admin.layouts.admin')

@section('title', 'AI Schedule Optimizer')
@section('header_title', 'AI Schedule Optimizer')
@section('header_subtitle', 'Automated schedule optimization engine (DB Integrated)')

@push('styles')
<style>
    .glass-panel {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .input-glow:focus {
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
        border-color: #6366f1;
    }
</style>
@endpush

@section('content')
<div class="flex flex-col items-center justify-center w-full max-w-4xl mx-auto py-8">
    <div class="text-center mb-10 w-full">
        <h2 class="text-3xl font-bold mb-4 text-white">
            Konfigurasi Parameter Optimasi
        </h2>
        <p class="text-slate-400 text-sm max-w-2xl mx-auto">
            Atur parameter untuk model AI guna memprediksi dan menghasilkan jadwal keberangkatan bus yang optimal berdasarkan rute nyata di database.
        </p>
    </div>

    <div class="glass-panel w-full rounded-2xl p-8 shadow-2xl">
        <form id="ga-form" action="{{ route('admin.ga-results') }}" method="GET" class="space-y-6">
            
            <!-- Route & Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 relative">
                    <label for="route" class="text-sm font-medium text-slate-300">Pilih Rute Bus</label>
                    <select id="route" name="route" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300 appearance-none" required>
                        <option value="">-- Memuat rute... --</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400" style="margin-top: 28px;">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="date" class="text-sm font-medium text-slate-300">Pilih Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300" required>
                </div>
            </div>

            <!-- Operating Hours -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="start_time" class="text-sm font-medium text-slate-300">Jam Operasional Mulai</label>
                    <input type="time" id="start_time" name="start_time" value="05:00" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300" required>
                </div>
                
                <div class="space-y-2">
                    <label for="end_time" class="text-sm font-medium text-slate-300">Jam Operasional Selesai</label>
                    <input type="time" id="end_time" name="end_time" value="23:00" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300" required>
                </div>
            </div>

            <!-- Fleet & Capacity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="fleet_count" class="text-sm font-medium text-slate-300">Jumlah Armada (Otomatis dari DB)</label>
                    <div class="relative">
                        <input type="number" id="fleet_count" name="fleet_count" value="0" min="1" max="50" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-400 outline-none input-glow transition-all duration-300 cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-bus-simple text-slate-400"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">Dihitung otomatis berdasarkan rute yang dipilih.</p>
                </div>
                
                <div class="space-y-2">
                    <label for="bus_capacity" class="text-sm font-medium text-slate-300">Kapasitas Rata-rata Bus (Otomatis dari DB)</label>
                    <div class="relative">
                        <input type="number" id="bus_capacity" name="bus_capacity" value="80" min="10" max="150" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-400 outline-none input-glow transition-all duration-300 cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-users text-slate-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" id="btn-submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-6 py-4 transition-all duration-300 flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>Jalankan Optimasi AI (DB Integrated)</span>
                    <i class="fa-solid fa-wand-magic-sparkles group-hover:rotate-12 transition-transform duration-300"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = window.API_URL || 'http://localhost:8001';
    const token = localStorage.getItem('token');
    
    if (!token) {
        alert("Sesi tidak valid, mohon login terlebih dahulu.");
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    let allRoutes = [];
    let allBuses = [];

    // Fetch Routes and Buses
    try {
        const [routesRes, busesRes] = await Promise.all([
            fetch(`${API_URL}/api/admin/routes`, { headers }),
            fetch(`${API_URL}/api/admin/buses`, { headers })
        ]);

        if (routesRes.ok) {
            const data = await routesRes.json();
            allRoutes = Array.isArray(data) ? data : (data.data || []);
        }

        if (busesRes.ok) {
            const data = await busesRes.json();
            allBuses = Array.isArray(data) ? data : (data.data || []);
        }
        
        populateRoutes();
    } catch(e) {
        console.error("Error fetching data:", e);
        document.getElementById('route').innerHTML = '<option value="">Gagal memuat rute</option>';
    }

    function populateRoutes() {
        const routeSelect = document.getElementById('route');
        routeSelect.innerHTML = '<option value="">-- Pilih Rute --</option>';
        
        allRoutes.forEach(r => {
            routeSelect.innerHTML += `<option value="${r.id}">${r.name} (${r.code})</option>`;
        });
    }

    document.getElementById('route').addEventListener('change', (e) => {
        const routeId = e.target.value;
        const btn = document.getElementById('btn-submit');
        
        if (!routeId) {
            document.getElementById('fleet_count').value = 0;
            btn.disabled = true;
            return;
        }

        const routeBuses = allBuses.filter(b => b.route_id == routeId);
        document.getElementById('fleet_count').value = routeBuses.length;

        if (routeBuses.length === 0) {
            alert('Tidak ada armada bus yang di-assign ke rute ini. Silakan tambahkan bus ke rute terlebih dahulu.');
            btn.disabled = true;
        } else {
            // Calculate average capacity
            let totalCap = 0;
            routeBuses.forEach(b => totalCap += (b.capacity || 0));
            const avg = Math.round(totalCap / routeBuses.length) || 80;
            document.getElementById('bus_capacity').value = avg;
            btn.disabled = false;
        }
    });
    
    // Initial disable
    document.getElementById('btn-submit').disabled = true;
});
</script>
@endpush
@endsection
