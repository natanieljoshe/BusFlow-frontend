@extends('user.user_layout')

@section('title', 'Trip History & Favourites - BusFlow')
@section('page_title', 'Trip History & Favourites')
@section('page_description', 'Access trip history, costs, frequently used routes, and user\'s favorite stops.')


@section('content')
    @php
        $tripHistory = $tripHistory ?? [];
        $favorites = $favorites ?? [];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.25fr)_minmax(340px,0.75fr)] gap-[18px] items-start">
        <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden transition-shadow duration-300 hover:shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_28px_rgba(168,85,247,0.14)]">
            <div class="flex items-start justify-between gap-4 p-[18px] border-b border-violet-500/[0.12]">
                <div>
                    <h2 class="text-xl font-bold">Trip History</h2>
                    <p class="mt-1.5 text-[#7a8aaa] text-[13px] leading-relaxed">List of past trips, routes, fleets, status, and ticket costs.</p>
                </div>
                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-violet-500/5 border border-violet-500/[0.28] text-[#b8c8e8] hover:bg-violet-500/[0.12] hover:border-violet-500/[0.65] hover:text-white hover:shadow-[0_0_14px_rgba(168,85,247,0.25)]" href="{{ $historyExportUrl ?? '#' }}">Export</a>
            </div>
            <div class="p-6">
                <ul class="grid gap-[10px] list-none">
                    @forelse ($tripHistory as $history)
                        @php
                            $fare = data_get($history, 'fare');
                        @endphp
                        <li class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] hover:border-violet-500/40 hover:shadow-[0_0_18px_rgba(168,85,247,0.1)] grid gap-[10px] p-[13px]">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <strong class="text-[15px] mb-1 block">{{ data_get($history, 'route_name', data_get($history, 'route', 'Route name unavailable')) }}</strong>
                                    <div class="text-[#7a8aaa] text-xs">
                                        {{ data_get($history, 'date', data_get($history, 'created_at', '-')) }}
                                        - Bus {{ data_get($history, 'bus_code', data_get($history, 'bus', '-')) }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <strong class="text-[15px] mb-1 block">{{ is_numeric($fare) ? 'Rp ' . number_format($fare, 0, ',', '.') : '-' }}</strong>
                                    <div><span class="inline-flex items-center justify-center min-h-[25px] px-2 rounded-[7px] text-[#86efac] bg-[rgba(134,239,172,0.1)] text-[11px] font-black">{{ data_get($history, 'status', '-') }}</span></div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="grid gap-2 place-items-start p-[18px] text-[#7a8aaa] min-h-[120px] border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms]">
                            <strong class="text-[#eef4ff]">Trip history is empty.</strong>
                            <span>Later, data from the transaction/trip table can be sent to the <code class="px-1.5 py-0.5 bg-violet-500/10 rounded text-xs">$tripHistory</code> variable.</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden transition-shadow duration-300 hover:shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_28px_rgba(168,85,247,0.14)]">
            <div class="flex items-start justify-between gap-4 p-[18px] border-b border-violet-500/[0.12]">
                <div>
                    <h2 class="text-xl font-bold">Favorite Routes & Stops</h2>
                    <p class="mt-1.5 text-[#7a8aaa] text-[13px] leading-relaxed">Save frequently used routes or stops for quick access.</p>
                </div>
                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-cyan-400/[0.08] border border-cyan-400/50 text-[#22d3ee] hover:bg-cyan-400/[0.16] hover:border-cyan-400 hover:text-white hover:shadow-[0_0_18px_rgba(34,211,238,0.4),inset_0_0_12px_rgba(34,211,238,0.08)]" href="{{ $addFavoriteUrl ?? '#' }}">Add Favorite</a>
            </div>
            <div class="p-[18px]">
                <ul class="grid gap-[10px] list-none">
                    @forelse ($favorites as $favorite)
                        <li class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] hover:border-violet-500/40 hover:shadow-[0_0_18px_rgba(168,85,247,0.1)] grid gap-[10px] p-[13px]">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <strong class="text-[15px] mb-1 block">{{ data_get($favorite, 'name', data_get($favorite, 'title', 'Favorite')) }}</strong>
                                    <div class="text-[#7a8aaa] text-xs">
                                        {{ data_get($favorite, 'type', 'Saved item') }}
                                        - {{ data_get($favorite, 'note', data_get($favorite, 'description', '-')) }}
                                    </div>
                                </div>
                                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-violet-500/5 border border-violet-500/[0.28] text-[#b8c8e8] hover:bg-violet-500/[0.12] hover:border-violet-500/[0.65] hover:text-white hover:shadow-[0_0_14px_rgba(168,85,247,0.25)]" href="{{ data_get($favorite, 'url', '#') }}">Open</a>
                            </div>
                        </li>
                    @empty
                        <li class="grid gap-2 place-items-start p-[18px] text-[#7a8aaa] min-h-[120px] border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms]">
                            <strong class="text-[#eef4ff]">No favorites yet.</strong>
                            <span>Favorite routes or stops will appear from the user's favorites table.</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>
@endsection
