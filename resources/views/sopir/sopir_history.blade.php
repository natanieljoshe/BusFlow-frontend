@extends('sopir.sopir_layout')

@section('title', 'BusFlow - Trip History')
@section('page_title', 'Trip History')
@section('page_description', 'View the list of trips you have completed.')

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
            <span class="pill">Trip History</span>
            <h2 style="margin-top:12px;">A list of journeys you have completed</h2>
        </div>

        <div class="panel-section">
            <div class="list">
                <div class="list-item">
                    <div>
                        <strong>Main Terminal → Cileungsi</strong>
                        <div><span>06:30 • 12 June 2026</span></div>
                    </div>
                    <span class="badge">Completed</span>
                </div>
                <div class="list-item">
                    <div>
                        <strong>Main Terminal → Bekasi</strong>
                        <div><span>05:00 • 11 June 2026</span></div>
                    </div>
                    <span class="badge">Completed</span>
                </div>
                <div class="list-item">
                    <div>
                        <strong>Main Terminal → Bogor</strong>
                        <div><span>06:10 • 10 June 2026</span></div>
                    </div>
                    <span class="badge warn">Completed</span>
                </div>
            </div>
        </div>
    </div>
@endsection
