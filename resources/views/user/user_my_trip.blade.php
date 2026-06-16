@extends('user.user_layout')

@section('title', 'Active Trip & Driver Rating - BusFlow')
@section('page_title', 'Active Trip & Driver')
@section('page_description', 'View active trips, fleet profiles, driver names, bus fleet numbers, and submit driver performance ratings.')


@section('content')
    @php
        $currentTrip = $currentTrip ?? null;
        $driver = data_get($currentTrip, 'driver');
        $bus = data_get($currentTrip, 'bus');
        $busCode = data_get($bus, 'code', data_get($currentTrip, 'bus_code', 'BUS'));
        $ratingValue = old('rating', data_get($currentTrip, 'user_rating', 0));
    @endphp

    <style>
        .bus-asset::before {
            content: attr(data-bus-code);
            position: absolute;
            left:50%;top:50%;
            transform: translate(-50%,-50%);
            display: grid;place-items:center;
            width:94px;height:56px;
            border:2px solid rgba(168,85,247,.72);
            border-radius:9px;
            color:#c084fc;
            font-weight:900;
            box-shadow:0 0 32px rgba(168,85,247,.22);
        }
        .route-vector::before { content:""; position:absolute; left:7px; top:0; bottom:0; width:1px; border-left:1px dashed rgba(168,85,247,.3); }
        .vector-step::before {
            content:"";
            flex:0 0 12px;width:12px;height:12px;
            margin-left:-13px;margin-top:4px;
            border:2px solid #a855f7;
            border-radius:99px;
            background:#090d1a;
        }
        .star-btn.is-selected { color:#f4bf55; background:rgba(244,191,85,.18); box-shadow:0 0 10px rgba(244,191,85,.45); }
    </style>

    <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden transition-shadow duration-300 hover:shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_28px_rgba(168,85,247,0.14)]">
        <div class="flex items-start justify-between gap-4 p-[18px] border-b border-violet-500/[0.12]">
            <div>
                <h2 class="text-xl font-bold">Active Trip</h2>
                <p class="mt-1.5 text-[#7a8aaa] text-[13px] leading-relaxed">Fleet and driver data will follow the user's active ticket/trip from the database.</p>
            </div>
            <span class="inline-flex items-center gap-2 min-h-[34px] px-3 border border-violet-500/30 rounded-lg text-[#c084fc] bg-violet-500/10 text-xs font-extrabold">{{ data_get($currentTrip, 'route_code', 'No active route') }}</span>
        </div>
        <div class="p-[18px] grid gap-[18px]">
            @if ($currentTrip)
                <div class="grid grid-cols-1 lg:grid-cols-[180px_minmax(0,1fr)] gap-4 items-center">
                    <div class="bus-asset relative h-[180px] overflow-hidden border border-violet-500/[0.28] rounded-[9px] bg-[rgba(4,5,15,0.94)]" data-bus-code="{{ $busCode }}" aria-label="Fleet {{ $busCode }}"></div>
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[14px]">
                            <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] p-[14px]">
                                <span class="block text-[#7a8aaa] text-[11px] font-extrabold uppercase">Bus Number</span>
                                <strong class="block mt-1.5 text-[22px]">{{ data_get($bus, 'plate_number', data_get($currentTrip, 'bus_number', '-')) }}</strong>
                            </div>
                            <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] p-[14px]">
                                <span class="block text-[#7a8aaa] text-[11px] font-extrabold uppercase">Driver</span>
                                <strong class="block mt-1.5 text-[22px]">{{ data_get($driver, 'name', data_get($currentTrip, 'driver_name', '-')) }}</strong>
                            </div>
                            <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] p-[14px]">
                                <span class="block text-[#7a8aaa] text-[11px] font-extrabold uppercase">ETA Destination</span>
                                <strong class="block mt-1.5 text-[22px]">{{ data_get($currentTrip, 'eta_minutes', '-') }} min</strong>
                            </div>
                            <div class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] p-[14px]">
                                <span class="block text-[#7a8aaa] text-[11px] font-extrabold uppercase">Rating</span>
                                <strong class="block mt-1.5 text-[22px]">{{ data_get($driver, 'rating', '-') }}</strong>
                            </div>
                        </div>

                        <div class="route-vector grid gap-[14px] mt-3 pl-[7px] relative border-l border-dashed border-violet-500/30">
                            <div class="vector-step flex gap-[10px] items-start">
                                <div>
                                    <strong class="text-[15px] block">Boarded</strong>
                                    <div class="text-[#7a8aaa] text-xs">{{ data_get($currentTrip, 'origin_name', '-') }} - {{ data_get($currentTrip, 'boarded_at', '-') }}</div>
                                </div>
                            </div>
                            <div class="vector-step flex gap-[10px] items-start">
                                <div>
                                    <strong class="text-[15px] block">Approaching</strong>
                                    <div class="text-[#7a8aaa] text-xs">{{ data_get($currentTrip, 'next_stop_name', '-') }} - {{ data_get($currentTrip, 'next_stop_eta', '-') }}</div>
                                </div>
                            </div>
                            <div class="vector-step flex gap-[10px] items-start">
                                <div>
                                    <strong class="text-[15px] block">Destination</strong>
                                    <div class="text-[#7a8aaa] text-xs">{{ data_get($currentTrip, 'destination_name', '-') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid gap-2 place-items-start p-[18px] text-[#7a8aaa] min-h-[120px] border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms]">
                    <strong class="text-[#eef4ff]">No active trips yet.</strong>
                    <span>When the user taps in, the controller can send active trip data to the <code class="px-1.5 py-0.5 bg-violet-500/10 rounded text-xs">$currentTrip</code> variable.</span>
                </div>
            @endif

            <form class="grid gap-[18px]" action="{{ $ratingAction ?? '#' }}" method="post" data-rating-form>
                @csrf
                <input type="hidden" name="trip_id" value="{{ data_get($currentTrip, 'id') }}">
                <input type="hidden" name="rating" value="{{ $ratingValue }}" data-rating-value>
                <div>
                    <span class="text-[#7a8aaa] text-xs block">Driver Rating & Review</span>
                    <div class="flex gap-1.5 mt-2" role="radiogroup" aria-label="Driver rating">
                        @for ($star = 1; $star <= 5; $star++)
                            <button
                                class="star-btn grid w-[34px] h-[34px] place-items-center border border-[rgba(244,191,85,0.26)] rounded-lg text-[rgba(244,191,85,0.45)] bg-[rgba(244,191,85,0.06)] text-xl leading-none transition-all duration-[180ms] hover:border-[rgba(244,191,85,0.6)] hover:shadow-[0_0_12px_rgba(244,191,85,0.4)] {{ $star <= (int) $ratingValue ? 'is-selected' : '' }}"
                                type="button"
                                data-rating-button="{{ $star }}"
                                aria-label="Give rating {{ $star }}"
                                aria-pressed="{{ $star <= (int) $ratingValue ? 'true' : 'false' }}"
                            ><svg width="18" height="18" viewBox="0 0 18 18" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M9 1.5l2.06 4.18 4.61.67-3.34 3.25.79 4.59L9 11.77l-4.12 2.42.79-4.59L2.33 6.35l4.61-.67L9 1.5z"/></svg></button>
                        @endfor
                    </div>
                </div>
                <div class="grid gap-2">
                    <label for="feedback" class="text-[#b8c8e8] text-xs font-extrabold uppercase">Feedback</label>
                    <textarea class="w-full border border-violet-500/25 rounded-lg text-[#eef4ff] bg-[rgba(4,5,15,0.98)] outline-none transition-all duration-200 min-h-[92px] resize-y p-[12px_13px] focus:border-violet-500/70 focus:shadow-[0_0_0_3px_rgba(168,85,247,0.14)]" id="feedback" name="feedback" placeholder="Write a review for the driver and fleet performance">{{ old('feedback') }}</textarea>
                </div>
                <div class="flex items-center gap-[10px] flex-wrap">
                    <button class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-violet-500/5 border border-violet-500/[0.28] text-[#b8c8e8] hover:bg-violet-500/[0.12] hover:border-violet-500/[0.65] hover:text-white hover:shadow-[0_0_14px_rgba(168,85,247,0.25)]" type="reset">Skip</button>
                    <button class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-cyan-400/[0.08] border border-cyan-400/50 text-[#22d3ee] hover:bg-cyan-400/[0.16] hover:border-cyan-400 hover:text-white hover:shadow-[0_0_18px_rgba(34,211,238,0.4),inset_0_0_12px_rgba(34,211,238,0.08)]" type="submit">Submit Rating</button>
                </div>
            </form>
        </div>
    </section>
@endsection
