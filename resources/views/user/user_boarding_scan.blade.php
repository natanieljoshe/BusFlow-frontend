@extends('user.user_layout')

@section('title', 'Check In/Out - BusFlow')
@section('page_title', 'Tap & Go')
@section('page_description', 'Scan this barcode at the bus stop to Check-in and Check-out. Fares are automatically deducted from your wallet.')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr_350px]">
    
    <!-- Barcode & Status Section -->
    <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden p-[30px] flex flex-col items-center justify-center min-h-[400px]">
        
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-[#c084fc] mb-2">Scan to Travel</h3>
            <p class="text-sm text-[#7a8aaa]">Present this barcode to the scanner at the bus stop or inside the bus.</p>
        </div>

        <!-- Real QR Code -->
        <div class="bg-white p-4 rounded-xl shadow-[0_0_30px_rgba(34,211,238,0.2)] mb-8 flex flex-col items-center">
            @php
                $userToken = 'USER-' . (session('user')['id'] ?? 'GUEST');
            @endphp
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $userToken }}" alt="User QR Code" class="w-48 h-48">
            <p class="text-center text-black font-mono mt-4 font-bold tracking-[0.2em]">{{ $userToken }}</p>
        </div>

        <div id="trip-status" class="w-full max-w-sm bg-violet-500/10 border border-violet-500/30 rounded-xl p-4 text-center transition-all">
            <span class="block text-xs font-bold text-[#7a8aaa] uppercase tracking-widest mb-1">Current Status</span>
            @if(count($bookings) > 0)
                <strong class="text-lg text-[#86efac] flex items-center justify-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#86efac] animate-pulse"></span>
                    {{ $bookings[0]['status'] == 'active' ? 'Checked In' : 'Booked / Ready to Check-in' }}
                </strong>
            @else
                <strong class="text-lg text-[#eef4ff] flex items-center justify-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span>
                    No Active Trips
                </strong>
            @endif
        </div>

    </section>

    <!-- Wallet & Last Trip Section -->
    <div class="grid gap-6 content-start">
        <!-- E-Wallet -->
        <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl p-[20px]">
            <h3 class="text-xs font-bold text-[#7a8aaa] uppercase tracking-[0.1em] mb-4">BusFlow Wallet</h3>
            <div class="flex flex-col gap-1">
                <span class="text-[#b8c8e8] text-sm">Available Balance</span>
                <strong class="text-[32px] font-black text-[#22d3ee] flex items-baseline gap-1">
                    <span class="text-lg">Rp</span> {{ number_format($wallet['balance'] ?? 0, 0, ',', '.') }}
                </strong>
            </div>
            
            <p class="text-xs text-[#7a8aaa] mt-4 leading-relaxed">
                Fares will be automatically deducted upon <strong class="text-[#86efac]">Check-out</strong>.
            </p>
        </section>

        <!-- Last Trip Info -->
        <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl p-[20px]">
            <h3 class="text-xs font-bold text-[#7a8aaa] uppercase tracking-[0.1em] mb-4">Last Transaction</h3>
            
            @if(count($allBookings ?? []) > 0)
            <div class="grid gap-3">
                <div class="flex justify-between items-start">
                    <div>
                        <strong class="text-[#eef4ff] text-sm block">
                            @if($allBookings[0]['status'] == 'active')
                                Check in Route {{ $allBookings[0]['trip']['route']['code'] ?? '-' }} (On Progress)
                            @else
                                Trip Route {{ $allBookings[0]['trip']['route']['code'] ?? '-' }}
                            @endif
                        </strong>
                        <span class="text-[#7a8aaa] text-xs">{{ \Carbon\Carbon::parse($allBookings[0]['created_at'])->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</span>
                    </div>
                    <strong class="{{ $allBookings[0]['status'] == 'active' ? 'text-amber-400' : 'text-rose-400' }} text-sm">
                        {{ $allBookings[0]['status'] == 'active' ? 'IN PROGRESS' : '- Rp ' . number_format($allBookings[0]['fare'], 0, ',', '.') }}
                    </strong>
                </div>
            </div>
            @else
            <p class="text-sm text-[#7a8aaa]">No transactions yet.</p>
            @endif
        </section>
        <!-- Current Position Tracker -->
        @if(count($bookings) > 0 && $bookings[0]['status'] == 'active')
        <section id="position-section" class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl p-[20px]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-[#7a8aaa] uppercase tracking-[0.1em]">📍 Current Position</h3>
                <span class="flex items-center gap-1.5 text-[10px] text-[#86efac] font-bold animate-pulse">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#86efac]"></span> LIVE
                </span>
            </div>
            <div id="position-loading" class="text-sm text-[#7a8aaa]">Loading position...</div>
            <div id="position-list" class="grid gap-2 hidden"></div>
            <p class="text-[10px] text-[#7a8aaa] mt-3">Auto-updates every 5 seconds</p>
        </section>
        @endif
    </div>

</div>

<script>
(function () {
    const API_URL = '{{ config("services.api.url", "http://127.0.0.1:8010") }}';
    const userId = {{ session('user') ? session('user')['id'] : 'null' }};
    const isActive = {{ (count($bookings) > 0 && $bookings[0]['status'] == 'active') ? 'true' : 'false' }};

    if (!isActive || !userId) return;

    async function fetchPosition() {
        try {
            const res = await fetch(`${API_URL}/api/current-position?user_id=${userId}`);
            if (!res.ok) return;
            const data = await res.json();

            const loading = document.getElementById('position-loading');
            const list = document.getElementById('position-list');
            if (!list) return;

            const currentId = data.current_halte_id;
            const haltes = data.route_haltes || [];

            if (haltes.length === 0) {
                if (loading) loading.textContent = data.current_halte
                    ? `Bus di: ${data.current_halte.name}`
                    : 'Posisi belum tersedia.';
                return;
            }

            if (loading) loading.classList.add('hidden');
            list.classList.remove('hidden');
            list.innerHTML = haltes.map((h, i) => {
                const isCurrent = h.id == currentId;
                return `<div class="flex items-center gap-3 p-2.5 rounded-lg transition-all ${isCurrent ? 'bg-cyan-400/10 border border-cyan-400/40' : 'border border-transparent'}">
                    <div class="flex flex-col items-center gap-0.5 shrink-0">
                        <div class="w-3 h-3 rounded-full border-2 ${isCurrent ? 'bg-cyan-400 border-cyan-400 shadow-[0_0_8px_rgba(34,211,238,0.8)]' : 'bg-transparent border-violet-500/40'}"></div>
                        ${i < haltes.length - 1 ? '<div class="w-0.5 h-4 bg-violet-500/20"></div>' : ''}
                    </div>
                    <div class="flex-1 flex items-center justify-between gap-2">
                        <span class="text-sm ${isCurrent ? 'text-[#22d3ee] font-bold' : 'text-[#7a8aaa]'}">${h.name}</span>
                        ${isCurrent ? '<span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 bg-cyan-400/20 text-cyan-400 rounded animate-pulse">CURRENT</span>' : ''}
                    </div>
                </div>`;
            }).join('');
        } catch (e) {
            console.error('Position fetch error', e);
        }
    }

    fetchPosition();
    setInterval(fetchPosition, 5000);
})();
</script>
@endsection