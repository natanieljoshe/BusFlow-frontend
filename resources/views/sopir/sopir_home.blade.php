@extends('sopir.sopir_layout')

@section('title', 'BusFlow - Driver Home')
@section('page_title', 'Home')
@section('page_description', 'View today\'s assignment, your status, and current trip information.')

@section('content')
<style>
    .status-switcher button.active.aktif { background: #16a34a; color: white; border-color: #16a34a; }
    .status-switcher button.active.istirahat { background: #eab308; color: #111827; border-color: #eab308; }
    .status-switcher button.active.cuti { background: #dc2626; color: white; border-color: #dc2626; }
    .status-badge.aktif { background: rgba(34,197,94,.16); color: #4ade80; }
    .status-badge.istirahat { background: rgba(250,204,21,.16); color: #fde68a; }
    .status-badge.cuti { background: rgba(248,113,113,.16); color: #fda4af; }
</style>

    <div class="grid gap-4 p-[18px] border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl">
        <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-cyan-400/[0.12] text-[#22d3ee] text-xs font-bold uppercase tracking-wider">Today's Assignment</span>
            <h2 class="mt-3 text-lg font-bold">Morning trip • Main Terminal → Cileungsi</h2>
            <p class="text-[#b8c8e8] leading-relaxed">Departure time: <strong>06:30</strong> • Partner conductor: <strong>Rian</strong></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-[1.1fr_0.9fr] gap-[14px]">
            <div class="status-card p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-cyan-400/[0.12] text-[#22d3ee] text-xs font-bold uppercase tracking-wider">My Status</span>
                <span class="status-badge inline-flex items-center gap-1.5 px-[10px] py-1.5 rounded-full text-xs font-extrabold mt-2 w-fit aktif" id="driver-status-badge">Active</span>
                <p id="driver-status-desc" class="mt-2 text-[#b8c8e8] leading-relaxed">You are ready to work and receive today's assignments.</p>
                <div class="status-switcher flex flex-wrap gap-2 mt-3" role="group" aria-label="Change driver status">
                    <button type="button" class="border border-violet-500/25 bg-[rgba(4,5,15,0.8)] text-[#7a8aaa] px-3 py-2 rounded-full text-xs font-extrabold cursor-pointer transition-all duration-200 hover:border-violet-500/40 hover:text-[#eef4ff] active aktif" data-status="aktif">Active</button>
                    <button type="button" class="border border-violet-500/25 bg-[rgba(4,5,15,0.8)] text-[#7a8aaa] px-3 py-2 rounded-full text-xs font-extrabold cursor-pointer transition-all duration-200 hover:border-violet-500/40 hover:text-[#eef4ff]" data-status="istirahat">Rest</button>
                    <button type="button" class="border border-violet-500/25 bg-[rgba(4,5,15,0.8)] text-[#7a8aaa] px-3 py-2 rounded-full text-xs font-extrabold cursor-pointer transition-all duration-200 hover:border-violet-500/40 hover:text-[#eef4ff]" data-status="cuti">Day Off</button>
                </div>
            </div>
            <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-cyan-400/[0.12] text-[#22d3ee] text-xs font-bold uppercase tracking-wider">Fleet</span>
                <strong class="block mt-3 text-lg">Bus B-12</strong>
                <p class="mt-2 text-[#b8c8e8] leading-relaxed">Plate: <strong>DK 1234 AB</strong></p>
            </div>
        </div>

        <div class="p-[14px] border border-violet-500/[0.14] rounded-[10px] bg-[rgba(4,5,15,0.72)]">
            <h3 class="text-lg font-bold mb-3">Assignment Details</h3>
            <div class="grid gap-[12px]">
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Route</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]">Main Terminal → Cileungsi</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider">Depart 06:30</span>
                </div>
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Conductor</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]">Rian</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(244,191,85,0.16)] text-[#f4bf55] text-[11px] font-bold uppercase tracking-wider">Partner</span>
                </div>
                <div class="flex justify-between items-center gap-[10px] p-[12px_14px] rounded-xl bg-[rgba(4,5,15,0.65)] border border-[rgba(148,163,184,0.08)]">
                    <div>
                        <strong class="block">Status</strong>
                        <div><span class="text-[#7a8aaa] text-[13px]" id="driver-status-text">Ready to go</span></div>
                    </div>
                    <span class="inline-flex items-center px-[10px] py-1.5 rounded-full bg-[rgba(134,239,172,0.14)] text-[#86efac] text-[11px] font-bold uppercase tracking-wider" id="driver-status-pill">Active</span>
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
                badge.className = 'status-badge inline-flex items-center gap-1.5 px-[10px] py-1.5 rounded-full text-xs font-extrabold mt-2 w-fit ' + status;
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
