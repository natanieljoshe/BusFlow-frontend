@extends('admin.layouts.admin')

@section('title', 'AI Schedule Optimizer')
@section('header_title', 'AI Schedule Optimizer')
@section('header_subtitle', 'Konfigurasi Parameter GA')

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
    .mode-btn {
        transition: all 0.3s ease;
    }
    .mode-btn.active {
        background-color: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }
</style>
@endpush

@section('content')
<div class="flex flex-col items-center justify-center w-full max-w-4xl mx-auto py-8">
    <div class="text-center mb-6 w-full">
        <h2 class="text-3xl font-bold mb-4 text-white">
            Konfigurasi Parameter Optimasi
        </h2>
        <p class="text-slate-400 text-sm max-w-2xl mx-auto">
            Atur parameter untuk model AI guna memprediksi dan menghasilkan jadwal keberangkatan bus yang optimal.
        </p>
    </div>

    <!-- Mode Selection -->
    <div class="flex justify-center mb-8 w-full">
        <div class="inline-flex rounded-md shadow-sm" role="group">
            <button type="button" id="btn-mode-json" onclick="switchMode('json')" class="mode-btn active px-6 py-2.5 text-sm font-medium border border-slate-700 rounded-l-lg bg-indigo-600 text-white hover:bg-slate-700">
                JSON Data (Demo)
            </button>
            <button type="button" id="btn-mode-db" onclick="switchMode('db')" class="mode-btn px-6 py-2.5 text-sm font-medium border-t border-b border-r border-slate-700 rounded-r-lg bg-slate-800 text-slate-300 hover:bg-slate-700">
                Database Data (Live)
            </button>
        </div>
    </div>

    <div class="glass-panel w-full rounded-2xl p-8 shadow-2xl">
        
        <!-- JSON MODE FORM -->
        <form id="form-json" class="space-y-6">
            <div class="mb-4 text-center">
                <span class="inline-block bg-indigo-500/20 text-indigo-300 px-3 py-1 rounded-full text-xs font-semibold border border-indigo-500/30">Mode: Mockup / JSON Static Payload</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 relative">
                    <label for="route" class="text-sm font-medium text-slate-300">Pilih Rute Bus</label>
                    <select id="route" name="route" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-400 outline-none input-glow transition-all duration-300 appearance-none cursor-not-allowed" disabled>
                        <option value="Q114" selected>Q114 (Queens Local) - Demo Fixed</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400" style="margin-top: 28px;">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="date" class="text-sm font-medium text-slate-300">Pilih Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="start_time" class="text-sm font-medium text-slate-300">Jam Operasional Mulai</label>
                    <input type="time" id="start_time" name="start_time" value="05:00" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300">
                </div>
                
                <div class="space-y-2">
                    <label for="end_time" class="text-sm font-medium text-slate-300">Jam Operasional Selesai</label>
                    <input type="time" id="end_time" name="end_time" value="23:00" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="fleet_count" class="text-sm font-medium text-slate-300">Jumlah Armada Tersedia (Demo)</label>
                    <div class="relative">
                        <input type="number" id="fleet_count" name="fleet_count" value="10" min="1" max="50" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-400 outline-none input-glow transition-all duration-300 cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-bus-simple text-slate-400"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">Nilai diatur otomatis dari file dummy JSON.</p>
                </div>
                
                <div class="space-y-2">
                    <label for="bus_capacity" class="text-sm font-medium text-slate-300">Kapasitas Bus (Penumpang/Bus)</label>
                    <div class="relative">
                        <input type="number" id="bus_capacity" name="bus_capacity" value="80" min="10" max="150" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-400 outline-none input-glow transition-all duration-300 cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-users text-slate-400"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">Nilai diatur otomatis dari file dummy JSON.</p>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-6 py-4 transition-all duration-300 flex items-center justify-center gap-2 group">
                    <span>Jalankan Optimasi AI (Demo)</span>
                    <i class="fa-solid fa-wand-magic-sparkles group-hover:rotate-12 transition-transform duration-300"></i>
                </button>
            </div>
        </form>

        <!-- DATABASE MODE FORM -->
        <form id="form-db" class="space-y-6 hidden">
            <div class="mb-4 text-center">
                <span class="inline-block bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-500/30">Mode: Live Database Backend Integration</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 relative">
                    <label for="route_db" class="text-sm font-medium text-slate-300">Pilih Rute Bus</label>
                    <select id="route_db" name="route" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300 appearance-none" required>
                        <option value="">-- Memuat rute... --</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400" style="margin-top: 28px;">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="date_db" class="text-sm font-medium text-slate-300">Pilih Tanggal</label>
                    <input type="date" id="date_db" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="start_time_db" class="text-sm font-medium text-slate-300">Jam Operasional Mulai</label>
                    <input type="time" id="start_time_db" name="start_time" value="05:00" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300" required>
                </div>
                
                <div class="space-y-2">
                    <label for="end_time_db" class="text-sm font-medium text-slate-300">Jam Operasional Selesai</label>
                    <input type="time" id="end_time_db" name="end_time" value="23:00" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="fleet_count_db" class="text-sm font-medium text-slate-300">Batas Maksimal Armada</label>
                    <div class="relative">
                        <input type="number" id="fleet_count_db" name="fleet_count" min="1" max="50" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-200 outline-none input-glow transition-all duration-300" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-bus-simple text-slate-400"></i>
                        </div>
                    </div>
                    <p id="fleet_helper" class="text-[11px] text-slate-400 mt-1">Total Armada: -, Tersedia: -</p>
                </div>
                
                <div class="space-y-2">
                    <label for="bus_capacity_db" class="text-sm font-medium text-slate-300">Kapasitas Rata-rata Bus (Otomatis)</label>
                    <div class="relative">
                        <input type="number" id="bus_capacity_db" name="bus_capacity" value="80" min="10" max="150" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-400 outline-none input-glow transition-all duration-300 cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-users text-slate-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="driver_count_db" class="text-sm font-medium text-slate-300">Batas Maksimal Supir</label>
                    <div class="relative">
                        <input type="number" id="driver_count_db" name="driver_count" min="1" max="100" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-200 outline-none input-glow transition-all duration-300" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-id-card text-slate-400"></i>
                        </div>
                    </div>
                    <p id="driver_helper" class="text-[11px] text-slate-400 mt-1">Total Supir: -, Tersedia: -</p>
                </div>
                
                <div class="space-y-2">
                    <label for="conductor_count_db" class="text-sm font-medium text-slate-300">Batas Maksimal Kondektur</label>
                    <div class="relative">
                        <input type="number" id="conductor_count_db" name="conductor_count" min="1" max="100" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-200 outline-none input-glow transition-all duration-300" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-ticket text-slate-400"></i>
                        </div>
                    </div>
                    <p id="conductor_helper" class="text-[11px] text-slate-400 mt-1">Total Kondektur: -, Tersedia: -</p>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" id="btn-submit-db" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl px-6 py-4 transition-all duration-300 flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>Jalankan Optimasi AI (Live)</span>
                    <i class="fa-solid fa-wand-magic-sparkles group-hover:rotate-12 transition-transform duration-300"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Progress Modal Overlay -->
    <div id="progress-modal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="glass-panel max-w-md w-full p-8 rounded-2xl shadow-2xl mx-4 text-center border border-slate-700">
            <h3 class="text-xl font-bold text-white mb-2">Memproses Optimasi AI</h3>
            <p id="progress-message" class="text-sm text-slate-400 mb-6">Memuat model AI...</p>
            
            <div class="w-full bg-slate-800 rounded-full h-4 mb-4 overflow-hidden border border-slate-700">
                <div id="progress-bar" class="bg-indigo-500 h-4 rounded-full transition-all duration-300 relative" style="width: 0%">
                    <div class="absolute inset-0 bg-white/20 w-full animate-pulse"></div>
                </div>
            </div>
            
            <div class="flex justify-between items-center mb-6">
                {{-- <span class="text-xs text-slate-500 font-medium">Proses ini berjalan di background</span> --}}
                <span id="progress-text" class="text-sm font-bold text-indigo-400">0%</span>
            </div>
            
            <button type="button" id="btn-cancel-job" class="text-sm text-red-400 hover:text-red-300 border border-red-500/30 hover:bg-red-500/10 px-4 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
                <i class="fa-solid fa-times"></i> Batalkan Proses
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchMode(mode) {
        const formJson = document.getElementById('form-json');
        const formDb = document.getElementById('form-db');
        const btnJson = document.getElementById('btn-mode-json');
        const btnDb = document.getElementById('btn-mode-db');

        if (mode === 'json') {
            formJson.classList.remove('hidden');
            formDb.classList.add('hidden');
            
            btnJson.classList.add('bg-indigo-600', 'text-white');
            btnJson.classList.remove('bg-slate-800', 'text-slate-300');
            
            btnDb.classList.remove('bg-indigo-600', 'text-white');
            btnDb.classList.add('bg-slate-800', 'text-slate-300');
        } else {
            formDb.classList.remove('hidden');
            formJson.classList.add('hidden');
            
            btnDb.classList.add('bg-indigo-600', 'text-white');
            btnDb.classList.remove('bg-slate-800', 'text-slate-300');
            
            btnJson.classList.remove('bg-indigo-600', 'text-white');
            btnJson.classList.add('bg-slate-800', 'text-slate-300');
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
        const token = localStorage.getItem('token');
        
        if (!token) return;

        const headers = {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        let allRoutes = [];
        let allBuses = [];
        let allDrivers = [];
        let allConductors = [];

        try {
            const [routesRes, busesRes, driversRes, conductorsRes] = await Promise.all([
                fetch(`${API_URL}/api/admin/routes`, { headers }),
                fetch(`${API_URL}/api/admin/buses`, { headers }),
                fetch(`${API_URL}/api/admin/drivers`, { headers }),
                fetch(`${API_URL}/api/admin/conductors`, { headers })
            ]);

            if (routesRes.ok) {
                const data = await routesRes.json();
                allRoutes = Array.isArray(data) ? data : (data.data || []);
            }
            if (busesRes.ok) {
                const data = await busesRes.json();
                allBuses = Array.isArray(data) ? data : (data.data || []);
            }
            if (driversRes.ok) {
                const data = await driversRes.json();
                allDrivers = Array.isArray(data) ? data : (data.data || []);
            }
            if (conductorsRes.ok) {
                const data = await conductorsRes.json();
                allConductors = Array.isArray(data) ? data : (data.data || []);
            }
            
            if(!Array.isArray(allRoutes)) allRoutes = Object.values(allRoutes);
            if(!Array.isArray(allBuses)) allBuses = Object.values(allBuses);
            if(!Array.isArray(allDrivers)) allDrivers = Object.values(allDrivers);
            if(!Array.isArray(allConductors)) allConductors = Object.values(allConductors);

            const routeSelect = document.getElementById('route_db');
            routeSelect.innerHTML = '<option value="">-- Pilih Rute --</option>';
            
            allRoutes.forEach(r => {
                routeSelect.innerHTML += `<option value="${r.id}">${r.name} (${r.code})</option>`;
            });
        } catch(e) {
            console.error("Error fetching data:", e);
        }

        document.getElementById('route_db').addEventListener('change', (e) => {
            const routeId = e.target.value;
            const btn = document.getElementById('btn-submit-db');
            
            if (!routeId) {
                document.getElementById('fleet_count_db').value = 0;
                document.getElementById('driver_count_db').value = 0;
                document.getElementById('conductor_count_db').value = 0;
                document.getElementById('fleet_helper').innerText = 'Total Armada: -, Tersedia: -';
                document.getElementById('driver_helper').innerText = 'Total Supir: -, Tersedia: -';
                document.getElementById('conductor_helper').innerText = 'Total Kondektur: -, Tersedia: -';
                btn.disabled = true;
                return;
            }

            // Calculate availabilities
            const getAvailable = (items) => items.filter(i => !i.route_id || String(i.route_id) === String(routeId)).length;
            
            const availBuses = getAvailable(allBuses);
            const availDrivers = getAvailable(allDrivers);
            const availConductors = getAvailable(allConductors);

            document.getElementById('fleet_count_db').value = availBuses;
            document.getElementById('driver_count_db').value = availDrivers;
            document.getElementById('conductor_count_db').value = availConductors;
            
            document.getElementById('fleet_helper').innerText = `Total Armada: ${allBuses.length} | Tersedia: ${availBuses} (Belum ditugaskan / milik rute ini)`;
            document.getElementById('driver_helper').innerText = `Total Supir: ${allDrivers.length} | Tersedia: ${availDrivers} (Belum ditugaskan / milik rute ini)`;
            document.getElementById('conductor_helper').innerText = `Total Kondektur: ${allConductors.length} | Tersedia: ${availConductors} (Belum ditugaskan / milik rute ini)`;

            const routeBuses = allBuses.filter(b => String(b.route_id) === String(routeId));
            if (routeBuses.length > 0) {
                let totalCap = 0;
                routeBuses.forEach(b => totalCap += (b.capacity || 0));
                document.getElementById('bus_capacity_db').value = Math.round(totalCap / routeBuses.length) || 80;
            } else {
                document.getElementById('bus_capacity_db').value = 80; // default
            }
            
            btn.disabled = false;
        });
        
        let pollingInterval = null;
        let currentJobId = null;

        const modal = document.getElementById('progress-modal');
        const progressMessage = document.getElementById('progress-message');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const btnCancel = document.getElementById('btn-cancel-job');

        function showModal(message = 'Memulai proses...') {
            modal.classList.remove('hidden');
            progressMessage.innerText = message;
            progressBar.style.width = '0%';
            progressText.innerText = '0%';
        }

        function hideModal() {
            modal.classList.add('hidden');
            if (pollingInterval) clearInterval(pollingInterval);
        }

        function startPolling(jobId) {
            currentJobId = jobId;
            localStorage.setItem('running_job_id', jobId);
            showModal();

            pollingInterval = setInterval(async () => {
                try {
                    const res = await fetch(`${API_URL}/api/admin/schedules/status/${jobId}`, { headers });
                    if (!res.ok) return;
                    
                    const data = await res.json();
                    
                    if (data.status === 'not_found') {
                        hideModal();
                        localStorage.removeItem('running_job_id');
                        alert('Job tidak ditemukan atau sudah kadaluarsa.');
                        return;
                    }

                    progressMessage.innerText = data.message || 'Memproses...';
                    progressBar.style.width = (data.progress || 0) + '%';
                    progressText.innerText = (data.progress || 0) + '%';

                    if (data.status === 'completed') {
                        hideModal();
                        localStorage.removeItem('running_job_id');
                        alert('Sukses: ' + data.message + '\n\nMengarahkan ke halaman hasil optimasi...');
                        window.location.href = '/admin/ga-results?job_id=' + jobId;
                    } else if (data.status === 'error') {
                        hideModal();
                        localStorage.removeItem('running_job_id');
                        alert('Gagal menjalankan optimasi: \n\n' + (data.error || 'Unknown error'));
                    } else if (data.status === 'cancelled') {
                        hideModal();
                        localStorage.removeItem('running_job_id');
                        alert('Proses optimasi dibatalkan.');
                    }
                } catch (e) {
                    console.error('Polling error', e);
                }
            }, 1000); // poll every 1 second
        }

        // Check for existing running job when page loads
        const savedJobId = localStorage.getItem('running_job_id');
        if (savedJobId) {
            // Check status once to ensure it's still running before resuming poll
            fetch(`${API_URL}/api/admin/schedules/status/${savedJobId}`, { headers })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'running') {
                        startPolling(savedJobId);
                    } else {
                        localStorage.removeItem('running_job_id');
                    }
                }).catch(e => localStorage.removeItem('running_job_id'));
        }

        btnCancel.addEventListener('click', async () => {
            if (!currentJobId) return;
            const confirmCancel = confirm("Yakin ingin membatalkan proses optimasi yang sedang berjalan?");
            if (!confirmCancel) return;
            
            btnCancel.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Membatalkan...';
            btnCancel.disabled = true;

            try {
                await fetch(`${API_URL}/api/admin/schedules/cancel/${currentJobId}`, { 
                    method: 'POST', 
                    headers 
                });
            } catch(e) {}
            
            btnCancel.innerHTML = '<i class="fa-solid fa-times"></i> Batalkan Proses';
            btnCancel.disabled = false;
        });
        
        document.getElementById('form-db').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-db');
            
            try {
                const response = await fetch(`${API_URL}/api/admin/schedules/generate`, {
                    method: 'POST',
                    headers: { ...headers, 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        route: document.getElementById('route_db').value,
                        date: document.getElementById('date_db').value,
                        start_time: document.getElementById('start_time_db').value,
                        end_time: document.getElementById('end_time_db').value,
                        fleet_count: document.getElementById('fleet_count_db').value,
                        driver_count: document.getElementById('driver_count_db').value,
                        conductor_count: document.getElementById('conductor_count_db').value,
                        bus_capacity: document.getElementById('bus_capacity_db').value,
                        mode: 'live'
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    startPolling(data.job_id);
                } else {
                    const err = await response.json();
                    alert('Gagal memulai optimasi: ' + (err.message || 'Error') + '\n\n' + (err.error || ''));
                }
            } catch (error) {
                alert('Gagal terhubung ke server Backend.');
            }
        });

        document.getElementById('form-json').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                const response = await fetch(`${API_URL}/api/admin/schedules/generate`, {
                    method: 'POST',
                    headers: { ...headers, 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        route: 'Q114',
                        date: document.getElementById('date').value,
                        start_time: document.getElementById('start_time').value,
                        end_time: document.getElementById('end_time').value,
                        fleet_count: document.getElementById('fleet_count').value,
                        driver_count: document.getElementById('fleet_count').value, // dummy
                        conductor_count: document.getElementById('fleet_count').value, // dummy
                        bus_capacity: document.getElementById('bus_capacity').value,
                        mode: 'demo'
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    startPolling(data.job_id);
                } else {
                    const err = await response.json();
                    alert('Gagal memulai optimasi Demo: ' + (err.message || 'Error') + '\n\n' + (err.error || ''));
                }
            } catch (error) {
                alert('Gagal terhubung ke server Backend.');
            }
        });

        document.getElementById('btn-submit-db').disabled = true;
    });
</script>
@endpush
@endsection
