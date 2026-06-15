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

    <section class="surface">
        <div class="surface-header">
            <div>
                <h2>Active Trip</h2>
                <p>Fleet and driver data will follow the user's active ticket/trip from the database.</p>
            </div>
            <span class="pill">{{ data_get($currentTrip, 'route_code', 'No active route') }}</span>
        </div>
        <div class="surface-body stack">
            @if ($currentTrip)
                <div class="active-route">
                    <div class="bus-asset" data-bus-code="{{ $busCode }}" aria-label="Fleet {{ $busCode }}"></div>
                    <div>
                        <div class="driver-info">
                            <div class="stat-box">
                                <span>Bus Number</span>
                                <strong>{{ data_get($bus, 'plate_number', data_get($currentTrip, 'bus_number', '-')) }}</strong>
                            </div>
                            <div class="stat-box">
                                <span>Driver</span>
                                <strong>{{ data_get($driver, 'name', data_get($currentTrip, 'driver_name', '-')) }}</strong>
                            </div>
                            <div class="stat-box">
                                <span>ETA Destination</span>
                                <strong>{{ data_get($currentTrip, 'eta_minutes', '-') }} min</strong>
                            </div>
                            <div class="stat-box">
                                <span>Rating</span>
                                <strong>{{ data_get($driver, 'rating', '-') }}</strong>
                            </div>
                        </div>

                        <div class="route-vector">
                            <div class="vector-step">
                                <div>
                                    <strong>Boarded</strong>
                                    <div class="meta">{{ data_get($currentTrip, 'origin_name', '-') }} - {{ data_get($currentTrip, 'boarded_at', '-') }}</div>
                                </div>
                            </div>
                            <div class="vector-step">
                                <div>
                                    <strong>Approaching</strong>
                                    <div class="meta">{{ data_get($currentTrip, 'next_stop_name', '-') }} - {{ data_get($currentTrip, 'next_stop_eta', '-') }}</div>
                                </div>
                            </div>
                            <div class="vector-step">
                                <div>
                                    <strong>Destination</strong>
                                    <div class="meta">{{ data_get($currentTrip, 'destination_name', '-') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <strong>No active trips yet.</strong>
                    <span>When the user taps in, the controller can send active trip data to the <code>$currentTrip</code> variable.</span>
                </div>
            @endif

            <form class="stack" action="{{ $ratingAction ?? '#' }}" method="post" data-rating-form>
                @csrf
                <input type="hidden" name="trip_id" value="{{ data_get($currentTrip, 'id') }}">
                <input type="hidden" name="rating" value="{{ $ratingValue }}" data-rating-value>
                <div>
                    <span class="mini-label">Driver Rating & Review</span>
                    <div class="stars" role="radiogroup" aria-label="Driver rating" style="margin-top: 8px;">
                        @for ($star = 1; $star <= 5; $star++)
                            <button
                                class="star-btn {{ $star <= (int) $ratingValue ? 'is-selected' : '' }}"
                                type="button"
                                data-rating-button="{{ $star }}"
                                aria-label="Give rating {{ $star }}"
                                aria-pressed="{{ $star <= (int) $ratingValue ? 'true' : 'false' }}"
                            ><svg width="18" height="18" viewBox="0 0 18 18" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M9 1.5l2.06 4.18 4.61.67-3.34 3.25.79 4.59L9 11.77l-4.12 2.42.79-4.59L2.33 6.35l4.61-.67L9 1.5z"/></svg></button>
                        @endfor
                    </div>
                </div>
                <div class="field">
                    <label for="feedback">Feedback</label>
                    <textarea class="textarea-control" id="feedback" name="feedback" placeholder="Write a review for the driver and fleet performance">{{ old('feedback') }}</textarea>
                </div>
                <div class="button-row">
                    <button class="ghost-btn" type="reset">Skip</button>
                    <button class="primary-btn" type="submit">Submit Rating</button>
                </div>
            </form>
        </div>
    </section>
@endsection