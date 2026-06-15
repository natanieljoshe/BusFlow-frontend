@extends('user.user_layout')

@section('title', 'Trip History & Favourites - BusFlow')
@section('page_title', 'Trip History & Favourites')
@section('page_description', 'Access trip history, costs, frequently used routes, and user\'s favorite stops.')


@section('content')
    @php
        $tripHistory = $tripHistory ?? [];
        $favorites = $favorites ?? [];
    @endphp

    <div class="content-grid">
        <section class="surface">
            <div class="surface-header">
                <div>
                    <h2>Trip History</h2>
                    <p>List of past trips, routes, fleets, status, and ticket costs.</p>
                </div>
                <a class="ghost-btn" href="{{ $historyExportUrl ?? '#' }}">Export</a>
            </div>
            <div class="surface-body" style="padding: 24px;">
                <ul class="list">
                    @forelse ($tripHistory as $history)
                        @php
                            $fare = data_get($history, 'fare');
                        @endphp
                        <li class="item-card">
                            <div class="card-row">
                                <div>
                                    <strong>{{ data_get($history, 'route_name', data_get($history, 'route', 'Route name unavailable')) }}</strong>
                                    <div class="meta">
                                        {{ data_get($history, 'date', data_get($history, 'created_at', '-')) }}
                                        - Bus {{ data_get($history, 'bus_code', data_get($history, 'bus', '-')) }}
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <strong>{{ is_numeric($fare) ? 'Rp ' . number_format($fare, 0, ',', '.') : '-' }}</strong>
                                    <div><span class="status-badge">{{ data_get($history, 'status', '-') }}</span></div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="empty-state">
                            <strong>Trip history is empty.</strong>
                            <span>Later, data from the transaction/trip table can be sent to the <code>$tripHistory</code> variable.</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section class="surface">
            <div class="surface-header">
                <div>
                    <h2>Favorite Routes & Stops</h2>
                    <p>Save frequently used routes or stops for quick access.</p>
                </div>
                <a class="primary-btn" href="{{ $addFavoriteUrl ?? '#' }}">Add Favorite</a>
            </div>
            <div class="surface-body">
                <ul class="list">
                    @forelse ($favorites as $favorite)
                        <li class="item-card">
                            <div class="card-row">
                                <div>
                                    <strong>{{ data_get($favorite, 'name', data_get($favorite, 'title', 'Favorite')) }}</strong>
                                    <div class="meta">
                                        {{ data_get($favorite, 'type', 'Saved item') }}
                                        - {{ data_get($favorite, 'note', data_get($favorite, 'description', '-')) }}
                                    </div>
                                </div>
                                <a class="ghost-btn" href="{{ data_get($favorite, 'url', '#') }}">Open</a>
                            </div>
                        </li>
                    @empty
                        <li class="empty-state">
                            <strong>No favorites yet.</strong>
                            <span>Favorite routes or stops will appear from the user's favorites table.</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>
@endsection