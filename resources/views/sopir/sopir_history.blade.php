@extends('sopir.sopir_layout')

@section('title', 'BusFlow - Trip History')
@section('page_title', 'Trip History')
@section('page_description', 'View the list of trips you have completed.')

@section('content')
    <div class="grid gap-4 p-[18px] border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl">
        <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-cyan-400/[0.12] text-[#22d3ee] text-xs font-bold uppercase tracking-wider">Trip History</span>
            <h2 class="mt-3 text-lg font-bold">A list of journeys you have completed</h2>
        </div>

        <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
            <div class="grid gap-[12px]" id="history-container">
                <div class="text-center text-[#7a8aaa] text-[13px] py-4"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading history...</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const API_URL = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
            const token = localStorage.getItem('token');
            const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };

            const container = document.getElementById('history-container');

            try {
                // Fetch driver's trips (assume history is trips with status completed or past)
                const tripRes = await fetch(`${API_URL}/api/trips`, { headers });
                if(tripRes.ok) {
                    const trips = await tripRes.json();
                    const tripList = trips.data || trips;
                    
                    if (tripList.length > 0) {
                        container.innerHTML = '';
                        tripList.forEach(trip => {
                            const routeName = trip.route ? trip.route.name : 'Unknown Route';
                            const time = trip.departure_time || 'N/A';
                            // Format date from created_at or use current for demo
                            const date = trip.created_at ? new Date(trip.created_at).toLocaleDateString() : 'N/A';
                            const status = trip.status || 'Completed';
                            
                            const div = document.createElement('div');
                            div.className = 'flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]';
                            div.innerHTML = `
                                <div>
                                    <strong class="block">${routeName}</strong>
                                    <div><span class="text-[#7a8aaa] text-[13px]">${time} &bull; ${date}</span></div>
                                </div>
                                <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider">${status}</span>
                            `;
                            container.appendChild(div);
                        });
                    } else {
                        container.innerHTML = '<div class="text-center text-[#7a8aaa] text-[13px] py-4">No trip history found.</div>';
                    }
                } else {
                    container.innerHTML = '<div class="text-center text-[#7a8aaa] text-[13px] py-4">Failed to load history.</div>';
                }
            } catch(e) {
                console.error("Error fetching trip history:", e);
                container.innerHTML = '<div class="text-center text-[#7a8aaa] text-[13px] py-4">An error occurred.</div>';
            }
        });
    </script>
@endsection
