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
            <div class="grid gap-[12px]">
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Main Terminal → Cileungsi</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]">06:30 • 12 June 2026</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider">Completed</span>
                </div>
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Main Terminal → Bekasi</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]">05:00 • 11 June 2026</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider">Completed</span>
                </div>
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Main Terminal → Bogor</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]">06:10 • 10 June 2026</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(244,191,85,0.16)] text-[#f4bf55] text-[11px] font-bold uppercase tracking-wider">Completed</span>
                </div>
            </div>
        </div>
    </div>
@endsection
