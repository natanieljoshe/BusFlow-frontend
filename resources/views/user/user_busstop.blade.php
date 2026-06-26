@extends('user.user_layout')

@section('title', 'Bus Stop Info - BusFlow')
@section('page_title', 'Halte ' . ($uid ?? 'Info'))
@section('page_description', 'Information and arriving buses for this bus stop.')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr_2fr]">
    <!-- Stop Info -->
    <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden p-[20px] self-start">
        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-violet-500/20">
            <a href="{{ route('user.routes') }}" class="text-[#7a8aaa] hover:text-[#eef4ff] transition-colors">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <h3 class="text-lg font-bold text-[#c084fc]">Stop Details</h3>
        </div>
        
        <div class="grid gap-4">
            <div>
                <p class="text-xs text-[#7a8aaa] uppercase tracking-wider font-bold mb-1">UID</p>
                <p class="text-sm text-[#eef4ff] font-mono bg-violet-500/10 px-2 py-1 rounded inline-block">{{ $uid ?? 'HT-XXX' }}</p>
            </div>
            <div>
                <p class="text-xs text-[#7a8aaa] uppercase tracking-wider font-bold mb-1">Facilities</p>
                <div class="flex gap-2 flex-wrap mt-1.5">
                    <span class="text-[11px] bg-[rgba(4,5,15,0.8)] border border-violet-500/30 px-2 py-1 rounded-md text-[#b8c8e8]">Seating</span>
                    <span class="text-[11px] bg-[rgba(4,5,15,0.8)] border border-violet-500/30 px-2 py-1 rounded-md text-[#b8c8e8]">Canopy</span>
                    <span class="text-[11px] bg-[rgba(4,5,15,0.8)] border border-violet-500/30 px-2 py-1 rounded-md text-[#b8c8e8]">Info Board</span>
                </div>
            </div>
            <div>
                <p class="text-xs text-[#7a8aaa] uppercase tracking-wider font-bold mb-1">Status</p>
                <span class="inline-flex items-center gap-1.5 text-sm text-[#86efac] font-medium mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-[#86efac] shadow-[0_0_8px_#86efac] animate-pulse"></span>
                    Active / Open
                </span>
            </div>
        </div>
    </section>

    <!-- Passing Buses -->
    <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden p-[20px]">
        <h3 class="text-lg font-bold mb-5 pb-3 border-b border-violet-500/20">Passing Buses Schedule</h3>
        
        <div class="grid gap-[14px]">
            <article class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] p-[16px] flex justify-between items-center transition-all hover:bg-violet-500/5 hover:border-violet-500/40">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-400/20 to-violet-500/20 border border-cyan-400/30 flex items-center justify-center text-cyan-400 font-black text-sm">
                        M15
                    </div>
                    <div>
                        <strong class="text-[16px] text-[#eef4ff] block mb-0.5">Towards: Kota</strong>
                        <p class="text-xs text-[#7a8aaa]">Bus B-101 (Reguler)</p>
                    </div>
                </div>
                <div class="text-right">
                    <strong class="text-[18px] text-[#22d3ee] block">08:15</strong>
                    <p class="text-[11px] text-[#86efac] font-bold mt-1 uppercase tracking-wider animate-pulse">Arriving soon</p>
                </div>
            </article>

            <article class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] p-[16px] flex justify-between items-center transition-all hover:bg-violet-500/5 hover:border-violet-500/40">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-400/10 to-violet-500/10 border border-violet-500/30 flex items-center justify-center text-[#c084fc] font-black text-sm">
                        S21
                    </div>
                    <div>
                        <strong class="text-[16px] text-[#eef4ff] block mb-0.5">Towards: Ciputat</strong>
                        <p class="text-xs text-[#7a8aaa]">Bus B-205 (Ekspres)</p>
                    </div>
                </div>
                <div class="text-right">
                    <strong class="text-[18px] text-[#eef4ff] block">08:45</strong>
                    <p class="text-[11px] text-[#7a8aaa] font-bold mt-1 uppercase tracking-wider">Scheduled</p>
                </div>
            </article>
        </div>
    </section>
</div>
@endsection
