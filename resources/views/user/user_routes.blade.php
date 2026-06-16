@extends('user.user_layout')

@section('title', 'Routes & Live Tracking - BusFlow')
@section('page_title', 'Route Finder & Live Tracking')
@section('page_description', 'Search routes, check schedules, stop ETAs, and monitor real-time bus positions from the dedicated routes page.')


@section('content')
    @php
        $tracking = $tracking ?? null;
        $recommendedRoutes = $recommendedRoutes ?? [];
        $scheduleRows = $scheduleRows ?? [];
        $origin = old('origin', request('origin'));
        $destination = old('destination', request('destination'));
        $trackingLeft = data_get($tracking, 'left', 58);
        $trackingTop = data_get($tracking, 'top', 44);
    @endphp

    <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden transition-shadow duration-300 hover:shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_28px_rgba(168,85,247,0.14)]">
        <div class="flex items-start justify-between gap-4 p-[18px] border-b border-violet-500/[0.12]">
            <div>
                <h2 class="text-xl font-bold">Route Search</h2>
                <p class="mt-1.5 text-[#7a8aaa] text-[13px] leading-relaxed">Input origin and destination locations to see bus recommendations from the BusFlow database.</p>
            </div>
            <span class="inline-flex items-center gap-2 min-h-[34px] px-3 border border-violet-500/30 rounded-lg text-[#c084fc] bg-violet-500/10 text-xs font-extrabold">{{ data_get($tracking, 'status', 'Waiting data') }}</span>
        </div>
        <div class="p-[18px]">
            <div class="grid grid-cols-1 lg:grid-cols-[330px_minmax(0,1fr)] gap-[18px]">
                <div class="grid gap-[18px]">
                    <form class="grid gap-[18px]" action="{{ route('user.routes') }}" method="get">
                        <div class="grid gap-2">
                            <label for="origin" class="text-[#b8c8e8] text-xs font-extrabold uppercase">Origin Location</label>
                            <input class="w-full border border-violet-500/25 rounded-lg text-[#eef4ff] bg-[rgba(4,5,15,0.98)] outline-none transition-all duration-200 h-11 px-[13px] focus:border-violet-500/70 focus:shadow-[0_0_0_3px_rgba(168,85,247,0.14)]" id="origin" name="origin" type="text" value="{{ $origin }}" placeholder="Enter origin stop/location">
                        </div>
                        <div class="grid gap-2">
                            <label for="destination" class="text-[#b8c8e8] text-xs font-extrabold uppercase">Destination</label>
                            <input class="w-full border border-violet-500/25 rounded-lg text-[#eef4ff] bg-[rgba(4,5,15,0.98)] outline-none transition-all duration-200 h-11 px-[13px] focus:border-violet-500/70 focus:shadow-[0_0_0_3px_rgba(168,85,247,0.14)]" id="destination" name="destination" type="text" value="{{ $destination }}" placeholder="Enter destination stop/location">
                        </div>
                        <button class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-cyan-400/[0.08] border border-cyan-400/50 text-[#22d3ee] hover:bg-cyan-400/[0.16] hover:border-cyan-400 hover:text-white hover:shadow-[0_0_18px_rgba(34,211,238,0.4),inset_0_0_12px_rgba(34,211,238,0.08)]" type="submit">Search Route</button>
                    </form>

                    <div>
                        <div class="text-[#7a8aaa] text-xs mb-[10px]">Recommended Routes</div>
                        <div class="grid gap-[10px]">
                            @forelse ($recommendedRoutes as $route)
                                @php
                                    $fare = data_get($route, 'fare');
                                    $load = data_get($route, 'load_percent', data_get($route, 'load', 0));
                                @endphp
                                <article class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] hover:border-violet-500/40 hover:shadow-[0_0_18px_rgba(168,85,247,0.1)] grid gap-[10px] p-[13px] border-l-[3px] border-l-[#a855f7]">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <span class="inline-flex items-center min-h-[25px] px-2 rounded-[7px] text-[#04050f] bg-gradient-to-r from-[#a855f7] to-[#22d3ee] text-xs font-black">{{ data_get($route, 'code', data_get($route, 'route_code', '-')) }}</span>
                                            <h3 class="text-[15px] mt-1 mb-1">{{ data_get($route, 'name', data_get($route, 'route_name', 'Route name unavailable')) }}</h3>
                                            <div class="text-[#7a8aaa] text-xs">
                                                {{ data_get($route, 'origin_name', data_get($route, 'from', 'Origin')) }}
                                                ->
                                                {{ data_get($route, 'destination_name', data_get($route, 'to', 'Destination')) }}
                                            </div>
                                        </div>
                                        <div class="text-[#22d3ee] text-lg font-black text-right">
                                            {{ data_get($route, 'eta_minutes', data_get($route, 'eta', '-')) }}
                                            <span class="block text-[#7a8aaa] text-[11px] font-bold">min ETA</span>
                                        </div>
                                    </div>
                                    <div class="h-1.5 rounded-full overflow-hidden bg-violet-500/[0.12]" aria-label="Bus capacity">
                                        <span class="block h-full rounded-[inherit] bg-gradient-to-r from-[#a855f7] to-[#22d3ee]" style="width: {{ (int) $load }}%;"></span>
                                    </div>
                                    <div class="flex items-start justify-between gap-3">
                                        <span class="text-[#7a8aaa] text-xs">Depart {{ data_get($route, 'departure_time', data_get($route, 'depart', '-')) }}</span>
                                        <strong class="text-[15px]">{{ is_numeric($fare) ? 'Rp ' . number_format($fare, 0, ',', '.') : 'Fare unavailable' }}</strong>
                                    </div>
                                </article>
                            @empty
                                <div class="grid gap-2 place-items-start p-[18px] text-[#7a8aaa] min-h-[120px] border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms]">
                                    <strong class="text-[#eef4ff]">No recommended routes yet.</strong>
                                    <span>Data will appear after the controller fetches routes from the database based on origin and destination.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="relative min-h-[520px] border border-violet-500/[0.22] rounded-[10px] overflow-hidden bg-[linear-gradient(90deg,rgba(168,85,247,0.06)_1px,transparent_1px),linear-gradient(0deg,rgba(168,85,247,0.06)_1px,transparent_1px),#050818] bg-[length:42px_42px,42px_42px,auto]" aria-label="BusFlow live tracking map">
                    <div class="absolute left-[18%] right-[-8%] top-[58%] h-1 rounded-[999px] bg-gradient-to-r from-transparent via-[#a855f7] via-[#22d3ee] via-[#c084fc] to-transparent rotate-[-38deg] shadow-[0_0_18px_rgba(168,85,247,0.7),0_0_36px_rgba(34,211,238,0.4)] animate-pulse"></div>
                    <span class="absolute grid place-items-center rounded-lg transform -translate-x-1/2 -translate-y-1/2 w-7 h-7 border border-violet-500/60 bg-[rgba(5,8,24,0.92)] text-[#c084fc] text-[11px] font-black shadow-[0_0_10px_rgba(168,85,247,0.4)] animate-pulse" style="left: 34%; top: 62%;">A</span>
                    <span class="absolute grid place-items-center rounded-lg transform -translate-x-1/2 -translate-y-1/2 w-7 h-7 border border-violet-500/60 bg-[rgba(5,8,24,0.92)] text-[#c084fc] text-[11px] font-black shadow-[0_0_10px_rgba(168,85,247,0.4)] animate-pulse" style="left: 48%; top: 53%; animation-delay: 0.6s;">B</span>
                    <span class="absolute grid place-items-center rounded-lg transform -translate-x-1/2 -translate-y-1/2 w-7 h-7 border border-violet-500/60 bg-[rgba(5,8,24,0.92)] text-[#c084fc] text-[11px] font-black shadow-[0_0_10px_rgba(168,85,247,0.4)] animate-pulse" style="left: 70%; top: 35%; animation-delay: 1.2s;">C</span>
                    @if ($tracking)
                        <span class="absolute grid place-items-center rounded-lg transform -translate-x-1/2 -translate-y-1/2 w-[38px] h-[38px] text-[#04050f] bg-gradient-to-br from-[#22d3ee] to-[#a855f7] shadow-[0_0_28px_rgba(34,211,238,0.7),0_0_56px_rgba(168,85,247,0.4)] text-[11px] font-black animate-pulse" style="left: {{ (int) $trackingLeft }}%; top: {{ (int) $trackingTop }}%;">BUS</span>
                    @endif
                    <div class="absolute left-4 right-4 bottom-4 grid grid-cols-[minmax(0,1fr)_auto] gap-3 items-center p-[14px] border border-violet-500/25 rounded-[9px] bg-[rgba(4,5,15,0.88)] backdrop-blur-sm">
                        <div>
                            <span class="text-[#7a8aaa] text-xs block">Network Status</span>
                            <strong class="block text-sm">{{ data_get($tracking, 'summary', 'Live tracking not connected') }}</strong>
                        </div>
                        <div class="text-right">
                            <strong class="block text-sm">{{ data_get($tracking, 'synced_at', '-') }}</strong>
                            <span class="text-[#7a8aaa] text-xs">Sync time</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[14px] mt-[18px]">
                <div>
                    <div class="text-[#7a8aaa] text-xs mb-[10px]">Schedule & Stop ETA</div>
                    <ul class="grid gap-[10px] list-none">
                        @forelse ($scheduleRows as $row)
                            <li class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] hover:border-violet-500/40 hover:shadow-[0_0_18px_rgba(168,85,247,0.1)] grid gap-[10px] p-[13px]">
                                <div class="flex items-start justify-between gap-3">
                                    <strong class="text-[15px]">{{ data_get($row, 'route_code', data_get($row, 'route', '-')) }} - {{ data_get($row, 'stop_name', data_get($row, 'stop', 'Stop')) }}</strong>
                                    <span class="inline-flex items-center justify-center min-h-[25px] px-2 rounded-[7px] text-[#86efac] bg-[rgba(134,239,172,0.1)] text-[11px] font-black">{{ data_get($row, 'status', '-') }}</span>
                                </div>
                                <div class="text-[#7a8aaa] text-xs">
                                    Depart {{ data_get($row, 'departure_time', data_get($row, 'depart', '-')) }}
                                    - Estimated arrival {{ data_get($row, 'arrival_time', data_get($row, 'arrive', '-')) }}
                                </div>
                            </li>
                        @empty
                            <li class="grid gap-2 place-items-start p-[18px] text-[#7a8aaa] min-h-[120px] border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms]">
                                <strong class="text-[#eef4ff]">Schedule unavailable.</strong>
                                <span>The contents of the schedule/stop table can be sent via the controller to the <code class="px-1.5 py-0.5 bg-violet-500/10 rounded text-xs">$scheduleRows</code> variable later.</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="grid grid-cols-3 gap-[14px]">
                    <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] p-[14px]">
                        <span class="block text-[#7a8aaa] text-[11px] font-extrabold uppercase">Active buses</span>
                        <strong class="block mt-1.5 text-[22px]">{{ $activeBusCount ?? '-' }}</strong>
                    </div>
                    <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] p-[14px]">
                        <span class="block text-[#7a8aaa] text-[11px] font-extrabold uppercase">Avg ETA</span>
                        <strong class="block mt-1.5 text-[22px]">{{ $averageEta ?? '-' }}</strong>
                    </div>
                    <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] p-[14px]">
                        <span class="block text-[#7a8aaa] text-[11px] font-extrabold uppercase">Saved stops</span>
                        <strong class="block mt-1.5 text-[22px]">{{ $savedStopCount ?? '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
