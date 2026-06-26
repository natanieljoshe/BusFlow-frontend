@extends('user.user_layout')

@section('title', 'Routes & Search - BusFlow')
@section('page_title', 'Route Finder')
@section('page_description', 'Search routes, view stops, and monitor schedules from the dedicated routes page.')

@section('content')
    <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden transition-shadow duration-300">
        <div class="flex items-start justify-between gap-4 p-[18px] border-b border-violet-500/[0.12]">
            <div>
                <h2 class="text-xl font-bold">Route Search</h2>
                <p class="mt-1.5 text-[#7a8aaa] text-[13px] leading-relaxed">Input origin and destination locations to see bus recommendations from the BusFlow database.</p>
            </div>
        </div>
        
        <div class="p-[18px]">
            <form class="grid gap-[18px] md:grid-cols-[1fr_1fr_auto] items-end mb-8" action="{{ route('user.routes') }}" method="get">
                <div class="grid gap-2">
                    <label for="origin" class="text-[#b8c8e8] text-xs font-extrabold uppercase">Origin Location</label>
                    <input class="w-full border border-violet-500/25 rounded-lg text-[#eef4ff] bg-[rgba(4,5,15,0.98)] outline-none transition-all duration-200 h-11 px-[13px] focus:border-violet-500/70 focus:shadow-[0_0_0_3px_rgba(168,85,247,0.14)]" id="origin" name="origin" type="text" value="{{ request('origin') }}" placeholder="Enter origin stop/location">
                </div>
                <div class="grid gap-2">
                    <label for="destination" class="text-[#b8c8e8] text-xs font-extrabold uppercase">Destination</label>
                    <input class="w-full border border-violet-500/25 rounded-lg text-[#eef4ff] bg-[rgba(4,5,15,0.98)] outline-none transition-all duration-200 h-11 px-[13px] focus:border-violet-500/70 focus:shadow-[0_0_0_3px_rgba(168,85,247,0.14)]" id="destination" name="destination" type="text" value="{{ request('destination') }}" placeholder="Enter destination stop/location">
                </div>
                <button class="inline-flex items-center justify-center gap-[7px] h-11 px-6 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-cyan-400/[0.08] border border-cyan-400/50 text-[#22d3ee] hover:bg-cyan-400/[0.16] hover:border-cyan-400 hover:text-white hover:shadow-[0_0_18px_rgba(34,211,238,0.4),inset_0_0_12px_rgba(34,211,238,0.08)]" type="submit">Search</button>
            </form>

            <div>
                <div class="text-[#7a8aaa] text-xs mb-4 font-extrabold tracking-widest uppercase">Available Routes</div>
                
                <div class="grid gap-6 lg:grid-cols-2">
                    @forelse($routes as $route)
                    <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] p-5 transition-all duration-[220ms] hover:border-violet-500/40 hover:shadow-[0_0_18px_rgba(168,85,247,0.1)]">
                        <a href="{{ route('user.routes.details', ['id' => $route['id']]) }}" class="block mb-5 group">
                            <h4 class="text-[20px] font-bold text-[#c084fc] group-hover:text-[#22d3ee] transition-colors flex items-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0zm12 0a2 2 0 1 0 4 0a2 2 0 0 0-4 0z"/><path d="M4 17V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10M9 17h6"/></svg>
                                Route {{ $route['code'] ?? $route['name'] }}
                            </h4>
                            <p class="text-sm text-[#7a8aaa] mt-1">{{ $route['name'] ?? 'Rute Bus' }}</p>
                        </a>
                        
                        <div class="text-[10px] font-extrabold text-[#7a8aaa] uppercase tracking-[0.1em] mb-3">Bus Stops</div>
                        <ul class="ml-2.5 border-l-2 border-violet-500/20 pl-5 space-y-4 relative">
                            @if(isset($route['haltes']) && count($route['haltes']) > 0)
                                @foreach($route['haltes'] as $index => $halte)
                                <li class="relative">
                                    <span class="absolute -left-[27px] top-1.5 w-2.5 h-2.5 rounded-full {{ $index % 2 == 0 ? 'bg-cyan-400' : 'bg-violet-400' }} border-[2px] border-[#04050f] shadow-[0_0_8px_rgba(34,211,238,0.6)]"></span>
                                    <a href="{{ route('user.busstop', ['uid' => $halte['id']]) }}" class="text-[#eef4ff] hover:text-[#c084fc] transition-colors font-medium text-sm block">Halte {{ $halte['name'] }}</a>
                                </li>
                                @endforeach
                            @else
                                <li class="text-[#7a8aaa] text-sm">Tidak ada halte.</li>
                            @endif
                        </ul>
                    </div>
                    @empty
                    <div class="col-span-2 text-center text-[#7a8aaa] py-8 border border-violet-500/20 rounded-xl bg-violet-500/5">
                        Belum ada rute tersedia.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
