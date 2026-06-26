@extends('admin.layouts.admin')

@section('title', 'Routes Management')
@section('header_title', 'Routes Management')
@section('header_subtitle', 'Manage bus routes and their configurations')

@section('content')
    <div class="flex flex-col gap-6">

        <!-- Active Roster Banner -->
        <div
            class="relative overflow-hidden rounded-xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 border border-indigo-500/10">
            <div
                class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-20">
            </div>
            <div class="flex items-center justify-between z-10 relative">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-route text-indigo-400"></i> Route Configurations
                    </h3>
                    <p class="text-sm text-indigo-200/70 max-w-2xl">
                        Define and manage all operational routes across the city network.
                    </p>
                </div>
                <button onclick="openRouteModal()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Add New Route
                </button>
            </div>
        </div>

        <!-- Route Modal -->
        <div id="route-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300"
                id="route-modal-content">
                <h3 id="modal-title" class="text-xl font-bold text-white mb-4">Add New Route</h3>
                <form id="route-form" class="space-y-4">
                    <input type="hidden" id="route-id">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Route Name</label>
                        <input type="text" id="route-name" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Base Fare ($)</label>
                        <input type="number" id="route-fare" required min="3" step="0.01"
                            class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeRouteModal()"
                            class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                        <button type="submit" id="save-route-btn"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                            Save Route
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Routes Grid -->
        <div id="routes-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="col-span-full text-center py-10 text-slate-500">
                <i class="fa-solid fa-spinner fa-spin text-indigo-500 text-3xl mb-4"></i>
                <p>Loading routes data...</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
                const token = localStorage.getItem('token');
                if (!token) {
                    document.getElementById('routes-grid').innerHTML =
                        `<div class="col-span-full text-center py-10 text-red-400">Silakan login terlebih dahulu. Token tidak ditemukan.</div>`;
                    return;
                }

                const headers = {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                };

                window.loadRoutes = async function() {
                    try {
                        const response = await fetch(`${API_URL}/api/admin/routes`, {
                            headers
                        });
                        if (response.status === 401) {
                            localStorage.removeItem('token');
                            document.getElementById('routes-grid').innerHTML =
                                `<div class="col-span-full text-center py-10 text-red-400">Sesi telah berakhir. Silakan login kembali.</div>`;
                            return;
                        }
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                        const data = await response.json();
                        const routesList = Array.isArray(data) ? data : (data.data || []);

                        const grid = document.getElementById('routes-grid');
                        grid.innerHTML = '';

                        if (routesList.length === 0) {
                            grid.innerHTML =
                                `<div class="col-span-full text-center py-10 text-slate-500">No routes data available.</div>`;
                            return;
                        }

                        routesList.forEach(r => {
                            const name = r.name || 'Unknown Route';
                            const rawFare = parseFloat(r.fare_per_km || 0);
                            const fare = Math.max(rawFare, 3);
                            const fareDisplay = '$' + fare.toFixed(2);
                            const id = r.id;

                            grid.innerHTML += `
                <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-xl overflow-hidden hover:border-indigo-500/30 transition-all duration-300 shadow-lg group">
                    <div class="p-5 border-b border-slate-800/50 bg-slate-800/20">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center">
                                    <i class="fa-solid fa-route text-indigo-400"></i>
                                </div>
                                <div>
                                    <h4 class="text-white font-bold tracking-wide">${name}</h4>
                                    <span class="text-[10px] text-slate-400 font-mono">${fareDisplay}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="mt-4 flex justify-end gap-2">
                             <a href="/admin/routes/${id}" class="px-3 py-1.5 text-[11px] font-medium rounded border border-emerald-700/50 text-emerald-300 hover:bg-emerald-600 hover:text-white transition-colors"><i class="fa-solid fa-map-location-dot"></i> Details </a>
                            <button onclick="deleteRoute(${id})" class="px-3 py-1.5 text-[11px] font-medium rounded border border-red-700/50 text-red-300 hover:bg-red-600 hover:text-white transition-colors"><i class="fa-solid fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>`;
                        });

                    } catch (error) {
                        console.error("Error fetching routes:", error);
                        document.getElementById('routes-grid').innerHTML =
                            `<div class="col-span-full text-center py-10 text-red-400">Failed to load routes data.</div>`;
                    }
                };

                window.openRouteModal = function() {
                    document.getElementById('modal-title').innerText = 'Add New Route';
                    document.getElementById('route-id').value = '';
                    document.getElementById('route-name').value = '';
                    document.getElementById('route-fare').value = '3';
                    const modal = document.getElementById('route-modal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => document.getElementById('route-modal-content').classList.remove(
                        'scale-95'), 10);
                };

                window.closeRouteModal = function() {
                    document.getElementById('route-modal-content').classList.add('scale-95');
                    setTimeout(() => {
                        const modal = document.getElementById('route-modal');
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }, 200);
                };



                window.deleteRoute = async function(id) {
                    if (!confirm('Are you sure you want to delete this route?')) return;
                    try {
                        const response = await fetch(
                            `${API_URL}/api/admin/routes/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                    'Accept': 'application/json'
                                }
                            });
                        if (response.ok) {
                            loadRoutes();
                        } else {
                            alert('Failed to delete route');
                        }
                    } catch (e) {
                        alert('Error deleting route');
                    }
                };

                document.getElementById('route-form').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const id = document.getElementById('route-id').value;
                    const name = document.getElementById('route-name').value;
                    const fare = document.getElementById('route-fare').value;

                    const method = id ? 'PUT' : 'POST';
                    const url = id ? `/api/admin/routes/${id}` : '/api/admin/routes';
                    const btn = document.getElementById('save-route-btn');
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
                    btn.disabled = true;

                    try {
                        const response = await fetch(
                            `${API_URL}${url}`, {
                                method: method,
                                headers: {
                                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    name: name,
                                    fare_per_km: fare,
                                    code: name.substring(0, 3).toUpperCase()
                                })
                            });

                        if (response.ok) {
                            closeRouteModal();
                            loadRoutes();
                        } else {
                            const data = await response.json();
                            alert('Failed to save: ' + (data.message || 'Unknown error'));
                        }
                    } catch (err) {
                        alert('Error saving route');
                    } finally {
                        btn.innerHTML = 'Save Route';
                        btn.disabled = false;
                    }
                });

                loadRoutes();
            });
        </script>
    @endpush
@endsection
