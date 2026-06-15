@extends('sopir.sopir_layout')

@section('title', 'BusFlow - Driver Home')
@section('page_title', 'Home')
@section('page_description', 'View today\'s assignment, your status, and current trip information.')

@section('content')
<style>
    .status-card .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        margin-top: 8px;
        width: fit-content;
    }
    .status-card .status-badge.aktif { background: rgba(34,197,94,.16); color: #4ade80; }
    .status-card .status-badge.istirahat { background: rgba(250,204,21,.16); color: #fde68a; }
    .status-card .status-badge.cuti { background: rgba(248,113,113,.16); color: #fda4af; }

    .status-switcher {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }
    .status-switcher button {
        border: 1px solid rgba(168,85,247,.25);
        background: rgba(4,5,15,.8);
        color: var(--muted);
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: all .2s ease;
    }
    .status-switcher button:hover { border-color: rgba(168,85,247,.4); color: var(--text); }
    .status-switcher button.active.aktif { background: #16a34a; color: white; border-color: #16a34a; }
    .status-switcher button.active.istirahat { background: #eab308; color: #111827; border-color: #eab308; }
    .status-switcher button.active.cuti { background: #dc2626; color: white; border-color: #dc2626; }

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
    .panel-row {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 14px;
    }
    @media (max-width: 768px) {
        .panel-row { grid-template-columns: 1fr; }
    }
</style>

    <div class="single-panel">
        <div class="panel-section">
            <span class="pill">Today's Assignment</span>
            <h2 style="margin-top:12px;">Morning trip • Main Terminal → Cileungsi</h2>
            <p>Departure time: <strong>06:30</strong> • Partner conductor: <strong>Rian</strong></p>
        </div>

        <div class="panel-row">
            <div class="panel-section status-card">
                <span class="pill">My Status</span>
                <span class="status-badge aktif" id="driver-status-badge">Active</span>
                <p id="driver-status-desc" style="margin-top:8px;">You are ready to work and receive today's assignments.</p>
                <div class="status-switcher" role="group" aria-label="Change driver status">
                    <button type="button" class="active aktif" data-status="aktif">Active</button>
                    <button type="button" data-status="istirahat">Rest</button>
                    <button type="button" data-status="cuti">Day Off</button>
                </div>
            </div>
            <div class="panel-section">
                <span class="pill">Fleet</span>
                <strong>Bus B-12</strong>
                <p style="margin-top:8px;">Plate: <strong>DK 1234 AB</strong></p>
            </div>
        </div>

        <div class="panel-section">
            <h3>Assignment Details</h3>
            <div class="list">
                <div class="list-item">
                    <div>
                        <strong>Route</strong>
                        <div><span>Main Terminal → Cileungsi</span></div>
                    </div>
                    <span class="badge">Depart 06:30</span>
                </div>
                <div class="list-item">
                    <div>
                        <strong>Conductor</strong>
                        <div><span>Rian</span></div>
                    </div>
                    <span class="badge warn">Partner</span>
                </div>
                <div class="list-item">
                    <div>
                        <strong>Status</strong>
                        <div><span id="driver-status-text">Ready to go</span></div>
                    </div>
                    <span class="badge" id="driver-status-pill">Active</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const buttons = document.querySelectorAll('.status-switcher button');
            const badge = document.getElementById('driver-status-badge');
            const desc = document.getElementById('driver-status-desc');
            const text = document.getElementById('driver-status-text');
            const pill = document.getElementById('driver-status-pill');

            const meta = {
                aktif: {
                    label: 'Active',
                    badge: 'Active',
                    desc: 'You are ready to work and receive today\'s assignments.',
                    text: 'Ready to go',
                    pill: 'Active'
                },
                istirahat: {
                    label: 'Rest',
                    badge: 'Rest',
                    desc: 'You are resting and are not receiving assignments right now.',
                    text: 'On break',
                    pill: 'Rest'
                },
                cuti: {
                    label: 'Day Off',
                    badge: 'Day Off',
                    desc: 'You are on day off and not active for today\'s tasks.',
                    text: 'On day off',
                    pill: 'Day Off'
                }
            };

            function applyStatus(status) {
                const data = meta[status] || meta.aktif;
                localStorage.setItem('bf_driver_status', status);
                buttons.forEach((btn) => {
                    const isActive = btn.dataset.status === status;
                    btn.classList.toggle('active', isActive);
                    btn.classList.toggle('aktif', btn.dataset.status === 'aktif' && isActive);
                    btn.classList.toggle('istirahat', btn.dataset.status === 'istirahat' && isActive);
                    btn.classList.toggle('cuti', btn.dataset.status === 'cuti' && isActive);
                });
                badge.textContent = data.badge;
                badge.className = 'status-badge ' + status;
                desc.textContent = data.desc;
                text.textContent = data.text;
                pill.textContent = data.pill;
            }

            const saved = localStorage.getItem('bf_driver_status') || 'aktif';
            applyStatus(saved);

            buttons.forEach((btn) => {
                btn.addEventListener('click', () => applyStatus(btn.dataset.status));
            });
        })();
    </script>
@endsection
