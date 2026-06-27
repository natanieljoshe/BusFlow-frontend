@extends('user.user_layout')

@section('title', 'My Trip History - BusFlow')
@section('page_title', 'My Trip History')
@section('page_description', 'View your past bus stop visits and travel history.')

@section('content')
<section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden p-[20px]">
    <div class="flex items-center justify-between border-b border-violet-500/20 pb-4 mb-6">
        <h3 class="text-xl font-bold">Recent Trips</h3>
        <span class="text-xs text-[#7a8aaa] bg-[rgba(4,5,15,0.8)] border border-violet-500/30 px-3 py-1.5 rounded-md">Last 30 days</span>
    </div>

    <div class="grid gap-6">
        @forelse($bookings as $index => $booking)
        <div class="relative pl-6 {{ $index < count($bookings) - 1 ? 'border-l-2 border-violet-500/30' : 'border-l-2 border-transparent' }}">
            <span class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $index == 0 ? 'bg-cyan-400 border-[3px] border-[#04050f] shadow-[0_0_8px_rgba(34,211,238,0.6)]' : 'bg-[#7a8aaa] border-[3px] border-[#04050f]' }}"></span>
            <div class="mb-1">
                <span class="text-xs font-bold {{ $index == 0 ? 'text-[#c084fc]' : 'text-[#7a8aaa]' }} uppercase tracking-wider">{{ \Carbon\Carbon::parse($booking['booked_at'])->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</span>
            </div>
            <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] p-4 transition-all hover:bg-violet-500/5 {{ $index > 0 ? 'opacity-75' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <strong class="text-[#eef4ff] text-[16px] block mb-1">Route {{ $booking['trip']['route']['code'] ?? '-' }}</strong>
                        <div class="text-sm text-[#7a8aaa] flex flex-col gap-1.5 mt-2">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#86efac]"></span>
                                Start: <span class="text-[#b8c8e8]">Halte {{ $booking['boarding_stop']['name'] ?? '?' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                End: <span class="text-[#b8c8e8]">Halte {{ $booking['arrive_stop']['name'] ?? '?' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[18px] font-bold text-[#22d3ee]">$ {{ number_format($booking['fare'], 2, '.', ',') }}</span>
                        <span class="block text-[10px] {{ $index == 0 ? 'text-[#86efac] bg-[#86efac]/10' : 'text-[#7a8aaa] bg-[#7a8aaa]/10' }} mt-1 px-2 py-0.5 rounded uppercase tracking-wider">{{ ucfirst($booking['status']) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-[#7a8aaa] py-8 border border-violet-500/20 rounded-xl bg-violet-500/5">
            Belum ada riwayat perjalanan.
        </div>
        @endforelse
    </div>
</section>
@endsection
