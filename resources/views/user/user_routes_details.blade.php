@extends('user.user_layout')

@section('title', 'Route Details - BusFlow')
@section('page_title', 'Route ' . ($id ?? 'Details'))
@section('page_description', 'Today\'s schedule for this specific route.')

@section('content')
<section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden p-[24px]">
    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-violet-500/20">
        <a href="{{ route('user.routes') }}" class="text-[#7a8aaa] hover:text-[#eef4ff] transition-colors">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
        <h3 class="text-xl font-bold">All Schedules</h3>
    </div>
    
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse($trips as $trip)
        <article class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] p-[16px] border-l-[4px] border-l-[#22d3ee] shadow-md transition-transform hover:-translate-y-1">
            <strong class="text-[18px] text-[#eef4ff] flex items-center justify-between">
                {{ \Carbon\Carbon::parse($trip['departure_time'])->format('H:i') }}
                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-1 bg-cyan-400/10 text-cyan-400 rounded">Trip</span>
            </strong>
            <div class="mt-3 text-[#7a8aaa] text-sm grid gap-1.5">
                <p>Days: <span class="text-[#f4bf55] font-bold">{{ ucfirst($trip['schedule']['day_of_week'] ?? 'Setiap Hari') }}</span></p>
                <p>Bus Code: <span class="text-[#c084fc] font-bold">{{ $trip['bus']['hull_number'] ?? $trip['bus']['plate_number'] ?? '-' }}</span></p>
                <p>Driver: <span class="text-[#eef4ff]">{{ $trip['driver']['user']['name'] ?? 'Assigned' }}</span></p>
                <p>Status: <span class="text-[#86efac] font-medium">{{ $trip['is_active'] ? 'Active' : 'Finished' }}</span></p>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center text-[#7a8aaa] py-8 border border-violet-500/20 rounded-xl bg-violet-500/5">
            Belum ada jadwal trip yang tersedia untuk rute ini.
        </div>
        @endforelse
    </div>
</section>
@endsection
