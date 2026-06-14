@extends('user.layout')

@section('title', 'Trip History & Favourites - BusFlow')
@section('page_title', 'Trip History & Favourites')
@section('page_description', 'Akses riwayat perjalanan, biaya, rute yang sering dipakai, dan halte favorit user.')


@section('content')
    @php
        $tripHistory = $tripHistory ?? [];
        $favorites = $favorites ?? [];
    @endphp

    <div class="content-grid">
        <section class="surface">
            <div class="surface-header">
                <div>
                    <h2>Riwayat Perjalanan</h2>
                    <p>Daftar perjalanan terdahulu, rute, armada, status, dan biaya tiket.</p>
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
                                    <strong>{{ data_get($history, 'route_name', data_get($history, 'route', 'Nama rute belum tersedia')) }}</strong>
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
                            <strong>Riwayat perjalanan kosong.</strong>
                            <span>Nanti data dari tabel transaksi/perjalanan bisa dikirim ke variabel <code>$tripHistory</code>.</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section class="surface">
            <div class="surface-header">
                <div>
                    <h2>Rute & Halte Favorit</h2>
                    <p>Simpan rute atau halte yang sering digunakan untuk akses cepat.</p>
                </div>
                <a class="primary-btn" href="{{ $addFavoriteUrl ?? '#' }}">Add Favorite</a>
            </div>
            <div class="surface-body">
                <ul class="list">
                    @forelse ($favorites as $favorite)
                        <li class="item-card">
                            <div class="card-row">
                                <div>
                                    <strong>{{ data_get($favorite, 'name', data_get($favorite, 'title', 'Favorit')) }}</strong>
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
                            <strong>Belum ada favorit.</strong>
                            <span>Rute atau halte favorit akan muncul dari tabel favorit user.</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>
@endsection
