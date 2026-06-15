@extends('sopir.sopir_layout')

@section('title', 'BusFlow - Trip Details')
@section('page_title', 'Trip Details')
@section('page_description', 'View the full route, stop list, and departure time for your trip.')

@section('content')
<style>
    .single-panel {
        display: grid;
        gap: 16px;
        padding: 18px;
        border: 1px solid rgba(168,85,247,.22);
        border-radius: 12px;
        background: rgba(4,5,15,.96);
        box-shadow: 0 8px 40px rgba(0,0,0,.7), 0 0 0 1px rgba(168,85,247,.08);
        backdrop-filter: blur(24px);
    }
    .panel-section {
        padding: 14px;
        border: 1px solid rgba(168,85,247,.14);
        border-radius: 10px;
        background: rgba(4,5,15,.72);
    }
</style>

    <div class="single-panel">
        <div class="panel-section">
            <span class="pill">Trip Details</span>
            <h2 style="margin-top:12px;">Main Terminal → Cileungsi</h2>
            <p>Departure time: <strong>06:30</strong> • Estimated arrival: <strong>07:45</strong></p>
        </div>

        <div class="panel-section">
            <h3>Route Details</h3>
            <div class="list">
                <div class="list-item">
                    <div>
                        <strong>Origin</strong>
                        <div><span>Main Terminal</span></div>
                    </div>
                    <span class="badge">Start</span>
                </div>
                <div class="list-item">
                    <div>
                        <strong>Destination</strong>
                        <div><span>Cileungsi</span></div>
                    </div>
                    <span class="badge">Finish</span>
                </div>
                <div class="list-item">
                    <div>
                        <strong>Stops</strong>
                        <div><span>Pasar Baru • Simpang Lima • Taman Indah • Cileungsi</span></div>
                    </div>
                    <span class="badge warn">Stops</span>
                </div>
            </div>
        </div>
    </div>
@endsection