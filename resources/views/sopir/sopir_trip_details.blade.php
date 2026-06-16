@extends('sopir.sopir_layout')

@section('title', 'BusFlow - Trip Details')
@section('page_title', 'Trip Details')
@section('page_description', 'View the full route, stop list, and departure time for your trip.')

@section('content')
    <div class="grid gap-4 p-[18px] border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl">
        <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-cyan-400/[0.12] text-[#22d3ee] text-xs font-bold uppercase tracking-wider">Trip Details</span>
            <h2 class="mt-3 text-lg font-bold">Main Terminal → Cileungsi</h2>
            <p class="text-[#b8c8e8] leading-relaxed">Departure time: <strong>06:30</strong> • Estimated arrival: <strong>07:45</strong></p>
        </div>

        <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
            <h3 class="text-lg font-bold mb-3">Route Details</h3>
            <div class="grid gap-[12px]">
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Origin</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]">Main Terminal</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider">Start</span>
                </div>
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Destination</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]">Cileungsi</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider">Finish</span>
                </div>
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Stops</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]">Pasar Baru • Simpang Lima • Taman Indah • Cileungsi</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(244,191,85,0.16)] text-[#f4bf55] text-[11px] font-bold uppercase tracking-wider">Stops</span>
                </div>
            </div>
        </div>
    </div>
@endsection
