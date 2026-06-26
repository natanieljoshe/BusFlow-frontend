@extends('admin.layouts.admin')

@section('title', 'Haltes Management')
@section('header_title', 'Haltes Management')
@section('header_subtitle', 'Manage bus stops (haltes) and their coordinates')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="flex flex-col gap-6">

    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 border border-indigo-500/10">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
        <div class="flex items-center justify-between z-10 relative">
            <div>
                <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-map-pin text-indigo-400"></i> Bus Stops Configurations
                </h3>
                <p class="text-sm text-indigo-200/70 max-w-2xl">
                    Define and manage physical bus stops and their geographical coordinates.
                </p>
            </div>
            <button onclick="openHalteModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Halte
            </button>
        </div>
    </div>

    <!-- Halte Modal -->
    <div id="halte-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="halte-modal-content">
            <h3 id="modal-title" class="text-xl font-bold text-white mb-4">Add New Halte</h3>
            <form id="halte-form" class="space-y-4">
                <input type="hidden" id="halte-id">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Halte Name</label>
                    <input type="text" id="halte-name" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Route ID</label>
                    <input type="number" id="halte-route" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Latitude</label>
                    <input type="number" step="0.0000001" id="halte-lat" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm" placeholder="-7.250445">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Longitude</label>
                    <input type="number" step="0.0000001" id="halte-lng" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm" placeholder="112.768845">
                </div>
                
                <!-- Map Container -->
                <div class="mt-4">
                    <label class="block text-xs font-medium text-slate-400 mb-1">Pick Location on Map</label>
                    <div id="halte-map" class="w-full h-48 rounded-lg border border-slate-700 z-10" style="z-index: 10;"></div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeHalteModal()" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="save-halte-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        Save Halte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Haltes Grid -->
    <div id="haltes-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-full text-center py-10 text-slate-500">
            <i class="fa-solid fa-spinner fa-spin text-indigo-500 text-3xl mb-4"></i>
            <p>Loading haltes data...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('haltes-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Silakan login terlebih dahulu. Token tidak ditemukan.</div>`;
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    window.loadHaltes = async function() {
        try {
            const response = await fetch(`${API_URL}/api/admin/haltes`, { headers });
            if (response.status === 401) {
                localStorage.removeItem('token');
                document.getElementById('haltes-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Sesi telah berakhir. Silakan login kembali.</div>`;
                return;
            }
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();
            const haltesList = Array.isArray(data) ? data : (data.data || []);
            
            const grid = document.getElementById('haltes-grid');
            grid.innerHTML = '';
            
            if (haltesList.length === 0) {
                grid.innerHTML = `<div class="col-span-full text-center py-10 text-slate-500">No haltes data available.</div>`;
                return;
            }

            haltesList.forEach(h => {
                const name = h.name || 'Unknown Halte';
                const route = h.route_id || '--';
                const id = h.id;
                const lat = h.latitude || '';
                const lng = h.longitude || '';
                
                grid.innerHTML += `
                <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-xl overflow-hidden hover:border-indigo-500/30 transition-all duration-300 shadow-lg group">
                    <div class="p-5 border-b border-slate-800/50 bg-slate-800/20">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center">
                                    <i class="fa-solid fa-map-pin text-indigo-400"></i>
                                </div>
                                <div>
                                    <h4 class="text-white font-bold tracking-wide">${name}</h4>
                                    <span class="text-[10px] text-slate-400 font-mono">Route: ${route}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-end border-b border-slate-800/50 pb-3">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Coordinates</p>
                                <p class="text-xs text-slate-300 font-mono">${lat}, ${lng}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end gap-2">
                            <button onclick="editHalte(${id}, '${name.replace(/'/g, "\\'")}', '${route}', '${lat}', '${lng}')" class="px-3 py-1.5 text-[11px] font-medium rounded border border-indigo-700/50 text-indigo-300 hover:bg-indigo-600 hover:text-white transition-colors"><i class="fa-solid fa-pen"></i> Edit</button>
                            <button onclick="deleteHalte(${id})" class="px-3 py-1.5 text-[11px] font-medium rounded border border-red-700/50 text-red-300 hover:bg-red-600 hover:text-white transition-colors"><i class="fa-solid fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>`;
            });

        } catch (error) {
            console.error("Error fetching haltes:", error);
            document.getElementById('haltes-grid').innerHTML = `<div class="col-span-full text-center py-10 text-red-400">Failed to load haltes data.</div>`;
        }
    };

    let map, marker;
    function initMap() {
        if (!map) {
            // Default center to Surabaya / Jakarta
            map = L.map('halte-map').setView([-7.250445, 112.768845], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            marker = L.marker([-7.250445, 112.768845], {draggable: true}).addTo(map);
            
            // On marker drag
            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                document.getElementById('halte-lat').value = pos.lat.toFixed(7);
                document.getElementById('halte-lng').value = pos.lng.toFixed(7);
            });

            // On map click
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('halte-lat').value = e.latlng.lat.toFixed(7);
                document.getElementById('halte-lng').value = e.latlng.lng.toFixed(7);
            });
        }
    }

    window.openHalteModal = function() {
        document.getElementById('modal-title').innerText = 'Add New Halte';
        document.getElementById('halte-id').value = '';
        document.getElementById('halte-name').value = '';
        document.getElementById('halte-route').value = '';
        document.getElementById('halte-lat').value = '';
        document.getElementById('halte-lng').value = '';
        const modal = document.getElementById('halte-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            document.getElementById('halte-modal-content').classList.remove('scale-95');
            initMap();
            map.invalidateSize();
            marker.setLatLng([-7.250445, 112.768845]);
            map.setView([-7.250445, 112.768845], 13);
        }, 150);
    };

    window.closeHalteModal = function() {
        document.getElementById('halte-modal-content').classList.add('scale-95');
        setTimeout(() => {
            const modal = document.getElementById('halte-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    };

    window.editHalte = function(id, name, route, lat, lng) {
        document.getElementById('modal-title').innerText = 'Edit Halte';
        document.getElementById('halte-id').value = id;
        document.getElementById('halte-name').value = name;
        document.getElementById('halte-route').value = route;
        document.getElementById('halte-lat').value = lat;
        document.getElementById('halte-lng').value = lng;
        const modal = document.getElementById('halte-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            document.getElementById('halte-modal-content').classList.remove('scale-95');
            initMap();
            map.invalidateSize();
            if(lat && lng) {
                const pos = [parseFloat(lat), parseFloat(lng)];
                marker.setLatLng(pos);
                map.setView(pos, 15);
            }
        }, 150);
    };

    window.deleteHalte = async function(id) {
        if(!confirm('Are you sure you want to delete this halte?')) return;
        try {
            const response = await fetch(`${API_URL}/api/admin/haltes/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}`, 'Accept': 'application/json' }
            });
            if(response.ok) {
                loadHaltes();
            } else {
                alert('Failed to delete halte');
            }
        } catch(e) {
            alert('Error deleting halte');
        }
    };

    document.getElementById('halte-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('halte-id').value;
        const name = document.getElementById('halte-name').value;
        const route = document.getElementById('halte-route').value;
        const lat = document.getElementById('halte-lat').value;
        const lng = document.getElementById('halte-lng').value;

        const method = id ? 'PUT' : 'POST';
        const url = id ? `/api/admin/haltes/${id}` : '/api/admin/haltes';
        const btn = document.getElementById('save-halte-btn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
        btn.disabled = true;

        const payload = { name: name };
        if (route) payload.route_id = route;
        if (lat) payload.latitude = lat;
        if (lng) payload.longitude = lng;

        try {
            const response = await fetch(`${API_URL}${url}`, {
                method: method,
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            
            if(response.ok) {
                closeHalteModal();
                loadHaltes();
            } else {
                const data = await response.json();
                alert('Failed to save: ' + (data.message || 'Unknown error'));
            }
        } catch(err) {
            alert('Error saving halte');
        } finally {
            btn.innerHTML = 'Save Halte';
            btn.disabled = false;
        }
    });

    loadHaltes();
});
</script>
@endpush
@endsection
