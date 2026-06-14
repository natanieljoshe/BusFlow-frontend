@extends('user.layout')

@section('title', 'Routes & Live Tracking - BusFlow')
@section('page_title', 'Route Finder & Live Tracking')
@section('page_description', 'Cari rute, cek jadwal, ETA halte, dan pantau posisi bus secara real-time dari halaman khusus routes.')


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

    <section class="surface">
        <div class="surface-header">
            <div>
                <h2>Pencarian Rute</h2>
                <p>Input lokasi asal dan tujuan untuk melihat rekomendasi bus dari database BusFlow.</p>
            </div>
            <span class="pill">{{ data_get($tracking, 'status', 'Waiting data') }}</span>
        </div>
        <div class="surface-body">
            <div class="route-layout">
                <div class="stack">
                    <form class="stack" action="{{ route('user.routes') }}" method="get">
                        <div class="field">
                            <label for="origin">Lokasi Asal</label>
                            <input class="input-control" id="origin" name="origin" type="text" value="{{ $origin }}" placeholder="Masukkan halte/lokasi asal">
                        </div>
                        <div class="field">
                            <label for="destination">Tujuan</label>
                            <input class="input-control" id="destination" name="destination" type="text" value="{{ $destination }}" placeholder="Masukkan halte/lokasi tujuan">
                        </div>
                        <button class="primary-btn" type="submit">Cari Rute</button>
                    </form>

                    <div>
                        <div class="mini-label" style="margin-bottom: 10px;">Recommended Routes</div>
                        <div class="route-cards">
                            @forelse ($recommendedRoutes as $route)
                                @php
                                    $fare = data_get($route, 'fare');
                                    $load = data_get($route, 'load_percent', data_get($route, 'load', 0));
                                @endphp
                                <article class="route-card">
                                    <div class="card-row">
                                        <div>
                                            <span class="route-code">{{ data_get($route, 'code', data_get($route, 'route_code', '-')) }}</span>
                                            <h3>{{ data_get($route, 'name', data_get($route, 'route_name', 'Nama rute belum tersedia')) }}</h3>
                                            <div class="meta">
                                                {{ data_get($route, 'origin_name', data_get($route, 'from', 'Asal')) }}
                                                ->
                                                {{ data_get($route, 'destination_name', data_get($route, 'to', 'Tujuan')) }}
                                            </div>
                                        </div>
                                        <div class="eta">
                                            {{ data_get($route, 'eta_minutes', data_get($route, 'eta', '-')) }}
                                            <span>min ETA</span>
                                        </div>
                                    </div>
                                    <div class="progress" aria-label="Kapasitas bus">
                                        <span style="--level: {{ (int) $load }}%;"></span>
                                    </div>
                                    <div class="card-row">
                                        <span class="meta">Depart {{ data_get($route, 'departure_time', data_get($route, 'depart', '-')) }}</span>
                                        <strong>{{ is_numeric($fare) ? 'Rp ' . number_format($fare, 0, ',', '.') : 'Tarif belum tersedia' }}</strong>
                                    </div>
                                </article>
                            @empty
                                <div class="empty-state">
                                    <strong>Belum ada rekomendasi rute.</strong>
                                    <span>Data akan muncul setelah controller mengambil rute dari database sesuai asal dan tujuan.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="map-wrap" aria-label="Peta live tracking BusFlow">
                    <div class="map-ribbon"></div>
                    <span class="map-node" style="left: 34%; top: 62%;">A</span>
                    <span class="map-node" style="left: 48%; top: 53%;">B</span>
                    <span class="map-node" style="left: 70%; top: 35%;">C</span>
                    @if ($tracking)
                        <span class="bus-pin" style="left: {{ (int) $trackingLeft }}%; top: {{ (int) $trackingTop }}%;">BUS</span>
                    @endif
                    <div class="map-status">
                        <div>
                            <span class="mini-label">Network Status</span>
                            <strong>{{ data_get($tracking, 'summary', 'Live tracking belum terhubung') }}</strong>
                        </div>
                        <div style="text-align: right;">
                            <strong>{{ data_get($tracking, 'synced_at', '-') }}</strong>
                            <span class="meta">Sync time</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-2" style="margin-top: 18px;">
                <div>
                    <div class="mini-label" style="margin-bottom: 10px;">Jadwal & ETA Halte</div>
                    <ul class="list">
                        @forelse ($scheduleRows as $row)
                            <li class="item-card">
                                <div class="card-row">
                                    <strong>{{ data_get($row, 'route_code', data_get($row, 'route', '-')) }} - {{ data_get($row, 'stop_name', data_get($row, 'stop', 'Halte')) }}</strong>
                                    <span class="status-badge">{{ data_get($row, 'status', '-') }}</span>
                                </div>
                                <div class="meta">
                                    Berangkat {{ data_get($row, 'departure_time', data_get($row, 'depart', '-')) }}
                                    - Estimasi tiba {{ data_get($row, 'arrival_time', data_get($row, 'arrive', '-')) }}
                                </div>
                            </li>
                        @empty
                            <li class="empty-state">
                                <strong>Jadwal belum tersedia.</strong>
                                <span>Nanti isi dari tabel jadwal/halte bisa dikirim lewat controller ke variabel <code>$scheduleRows</code>.</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="stat-grid">
                    <div class="stat-box">
                        <span>Active buses</span>
                        <strong>{{ $activeBusCount ?? '-' }}</strong>
                    </div>
                    <div class="stat-box">
                        <span>Avg ETA</span>
                        <strong>{{ $averageEta ?? '-' }}</strong>
                    </div>
                    <div class="stat-box">
                        <span>Saved stops</span>
                        <strong>{{ $savedStopCount ?? '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
