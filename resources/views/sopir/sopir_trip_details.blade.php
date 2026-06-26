@extends('sopir.sopir_layout')

@section('title', 'BusFlow - Trip Details')
@section('page_title', 'Trip Details')
@section('page_description', 'View the full route, stop list, and departure time for your trip.')

@section('content')
    <div class="grid gap-4 p-[18px] border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl">
        <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-cyan-400/[0.12] text-[#22d3ee] text-xs font-bold uppercase tracking-wider">My Trips</span>
            <h2 class="mt-3 text-lg font-bold" id="route-title">Loading Route...</h2>
            <p class="text-[#b8c8e8] leading-relaxed">Departure time: <strong>06:30</strong> • Estimated arrival: <strong>07:45</strong></p>
        </div>

        <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
            <h3 class="text-lg font-bold mb-3">Route Stops</h3>
            <div class="grid gap-[12px]" id="haltes-container">
                <div class="text-center text-slate-400 py-4"><i class="fa-solid fa-spinner fa-spin"></i> Loading stops...</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const API_URL = window.API_URL || 'http://127.0.0.1:8010';
            const container = document.getElementById('haltes-container');
            const title = document.getElementById('route-title');
            
            try {
                // Fetch first route as a mock assigned trip
                const res = await fetch(`${API_URL}/api/routes`);
                if (!res.ok) throw new Error('Failed to load routes');
                
                const data = await res.json();
                const routes = Array.isArray(data) ? data : (data.data || []);
                if (routes.length === 0) {
                    container.innerHTML = '<p class="text-slate-400">No routes found.</p>';
                    title.innerText = 'No Route Assigned';
                    return;
                }
                
                const route = routes[0];
                title.innerText = `Route ${route.code || ''} - ${route.name}`;
                
                // Fetch details to get haltes
                const detailRes = await fetch(`${API_URL}/api/routes/${route.id}`);
                const detailData = await detailRes.json();
                const routeDetail = detailData.data || detailData;
                
                const haltes = routeDetail.haltes || [];
                if (haltes.length === 0) {
                    container.innerHTML = '<p class="text-slate-400">No stops for this route.</p>';
                    return;
                }
                
                container.innerHTML = '';
                
                // Sort haltes by sequence
                haltes.sort((a, b) => (a.pivot?.sequence || 0) - (b.pivot?.sequence || 0));
                
                haltes.forEach((halte, index) => {
                    const isStart = index === 0;
                    const isFinish = index === haltes.length - 1;
                    
                    let badge = '<span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(244,191,85,0.16)] text-[#f4bf55] text-[11px] font-bold uppercase tracking-wider">Stop</span>';
                    if (isStart) badge = '<span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider">Start</span>';
                    if (isFinish) badge = '<span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider">Finish</span>';

                    container.innerHTML += `
                        <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                            <div>
                                <strong class="block">${halte.name}</strong>
                                <div><span class="text-[#7a8aaa] text-[13px]">${halte.location || 'Terminal'}</span></div>
                            </div>
                            <div class="flex items-center gap-3">
                                ${badge}
                                <button onclick="imHere(${halte.id}, this)" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded shadow transition-colors im-here-btn">I'm Here</button>
                            </div>
                        </div>
                    `;
                });

            } catch (err) {
                container.innerHTML = '<p class="text-red-400">Error loading data.</p>';
                title.innerText = 'Error';
            }
        });

        window.imHere = async function(halteId, btn) {
            const originalText = btn.innerText;
            btn.innerText = 'Updating...';
            btn.disabled = true;

            try {
                const API_URL = window.API_URL || 'http://127.0.0.1:8010';
                const res = await fetch(`${API_URL}/api/driver/update-location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ halte_id: halteId })
                });

                if (res.ok) {
                    // Reset other buttons
                    document.querySelectorAll('.im-here-btn').forEach(b => {
                        b.innerText = "I'm Here";
                        b.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
                        b.classList.add('bg-indigo-600', 'hover:bg-indigo-500');
                        b.disabled = false;
                    });
                    
                    // Set current button to success state
                    btn.innerText = "Bus is Here";
                    btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-500');
                    btn.classList.add('bg-emerald-600', 'hover:bg-emerald-500');
                    btn.disabled = true;
                } else {
                    alert('Failed to update location');
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (err) {
                alert('Connection error');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        };
    </script>
@endsection
