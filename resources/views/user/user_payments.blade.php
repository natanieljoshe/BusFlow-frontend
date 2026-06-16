@extends('user.user_layout')

@section('title', 'Payments & Digital Wallet - BusFlow')
@section('page_title', 'Payments & QR')
@section('page_description', 'Manage e-wallet balance, top up, payment history, and QR boarding for tap-in and tap-out.')

@section('content')
    @php
        $wallet           = $wallet ?? null;
        $walletActivities = $walletActivities ?? [];
        $balance          = data_get($wallet, 'balance');
    @endphp

    <style>
        .qr-scanner {
            position: relative;
            width: 200px;
            height: 200px;
            overflow: hidden;
            flex-shrink: 0;
            background: rgba(4,5,15,.97);
        }

        /* ── Corner brackets — sharp siku-siku neon ── */
        .qr-corner {
            position: absolute;
            width: 22px;
            height: 22px;
            z-index: 6;
            pointer-events: none;
        }
        /* vertical bar */
        .qr-corner::before {
            content: "";
            position: absolute;
            width: 3px;
            height: 22px;
            background: currentColor;
        }
        /* horizontal bar */
        .qr-corner::after {
            content: "";
            position: absolute;
            width: 22px;
            height: 3px;
            background: currentColor;
        }

        /* top-left */
        .qr-corner.tl { top: 0; left: 0; color: #a855f7;
            filter: drop-shadow(0 0 4px #a855f7) drop-shadow(0 0 10px #a855f7); }
        .qr-corner.tl::before { top: 0; left: 0; }
        .qr-corner.tl::after  { top: 0; left: 0; }

        /* top-right */
        .qr-corner.tr { top: 0; right: 0; color: #22d3ee;
            filter: drop-shadow(0 0 4px #22d3ee) drop-shadow(0 0 10px #22d3ee); }
        .qr-corner.tr::before { top: 0; right: 0; left: auto; }
        .qr-corner.tr::after  { top: 0; right: 0; left: auto; }

        /* bottom-left */
        .qr-corner.bl { bottom: 0; left: 0; color: #a855f7;
            filter: drop-shadow(0 0 4px #a855f7) drop-shadow(0 0 10px #a855f7); }
        .qr-corner.bl::before { bottom: 0; top: auto; left: 0; }
        .qr-corner.bl::after  { bottom: 0; top: auto; left: 0; }

        /* bottom-right */
        .qr-corner.br { bottom: 0; right: 0; color: #22d3ee;
            filter: drop-shadow(0 0 4px #22d3ee) drop-shadow(0 0 10px #22d3ee); }
        .qr-corner.br::before { bottom: 0; top: auto; right: 0; left: auto; }
        .qr-corner.br::after  { bottom: 0; top: auto; right: 0; left: auto; }

        /* ── QR content ── */
        .qr-frame {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            padding: 18px;
            position: relative;
            z-index: 1;
            background: transparent;
        }
        .qr-frame img, .qr-frame svg { width: 100%; height: 100%; object-fit: contain; background: transparent; }

        /* camera feed */
        #qr-video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
            display: none;
        }

        /* ── Scan beam ── */
        .qr-beam {
            position: absolute;
            left: 0; right: 0;
            height: 2px;
            z-index: 5;
            pointer-events: none;
            background: #a855f7;
            box-shadow:
                0 0 6px 3px rgba(168,85,247,.9),
                0 0 18px 6px rgba(168,85,247,.5);
            animation: qr-bounce 2.2s ease-in-out infinite;
        }

        /* glow trail */
        .qr-beam::after {
            content: "";
            position: absolute;
            left: 0; right: 0;
            top: 0;
            height: 40px;
            background: linear-gradient(to bottom, rgba(168,85,247,.18), transparent);
            pointer-events: none;
        }

        /* naik-turun terus menerus */
        @keyframes qr-bounce {
            0%   { top: 4px; }
            50%  { top: 182px; }
            100% { top: 4px; }
        }

        /* detected flash */
        .qr-scanner.is-detected {
            animation: qr-flash .4s ease;
        }
        @keyframes qr-flash {
            0%,100% { box-shadow: none; }
            50%     { box-shadow: 0 0 0 3px #86efac, 0 0 28px rgba(134,239,172,.5); }
        }

        /* camera button */
        .cam-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 34px;
            padding: 0 14px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            cursor: pointer;
            font-family: inherit;
            transition: all .22s ease;
            background: rgba(34,211,238,.07);
            border: 1px solid rgba(34,211,238,.4);
            color: var(--cyan);
            overflow: hidden;
        }
        .cam-btn:hover { background: rgba(34,211,238,.15); border-color: var(--cyan); box-shadow: 0 0 16px rgba(34,211,238,.35); color:#fff; }
        .cam-btn.is-active { background: rgba(134,239,172,.1); border-color: rgba(134,239,172,.6); color: var(--lime); }

        #qr-status { font-size:11px; font-weight:700; text-align:center; min-height:16px; color:var(--muted); transition:color .2s; }
        #qr-status.ok  { color: #86efac; }
        #qr-status.err { color: #fb7185; }
        #cam-toggle.is-active { background: rgba(134,239,172,.1); border-color: rgba(134,239,172,.6); color: #86efac; }
    </style>

    <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden transition-shadow duration-300 hover:shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_28px_rgba(168,85,247,0.14)]">
        <div class="flex items-start justify-between gap-4 p-[18px] border-b border-violet-500/[0.12]">
            <div>
                <h2 class="text-xl font-bold">Digital Wallet</h2>
                <p class="mt-1.5 text-[#7a8aaa] text-[13px] leading-relaxed">Balance, top-up, and payment history will be retrieved from the user database.</p>
            </div>
            <span class="inline-flex items-center gap-2 min-h-[34px] px-3 border border-violet-500/30 rounded-lg text-[#c084fc] bg-violet-500/10 text-xs font-extrabold">{{ data_get($wallet, 'status', 'Waiting data') }}</span>
        </div>
        <div class="p-[18px]">
            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.05fr)_minmax(280px,0.95fr)] gap-[18px]">
                <div class="grid gap-[18px]">
                    <div class="border border-violet-500/[0.28] rounded-[9px] bg-[rgba(4,5,15,0.94)] p-4">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div>
                                <span class="text-[#7a8aaa] text-xs block">Available Balance</span>
                                <strong class="block mt-1 text-[32px]">{{ is_numeric($balance) ? 'Rp ' . number_format($balance, 0, ',', '.') : 'Unavailable' }}</strong>
                                <span class="inline-flex items-center gap-[7px] text-[#86efac] text-xs font-bold uppercase mt-2 before:content-[''] before:w-[7px] before:h-[7px] before:rounded-full before:bg-[#86efac] before:shadow-[0_0_18px_#86efac] before:animate-pulse">{{ data_get($wallet, 'is_active') ? 'Ready to ride' : 'Need wallet data' }}</span>
                            </div>
                            <div class="flex items-center gap-[10px] flex-wrap">
                                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-cyan-400/[0.08] border border-cyan-400/50 text-[#22d3ee] hover:bg-cyan-400/[0.16] hover:border-cyan-400 hover:text-white hover:shadow-[0_0_18px_rgba(34,211,238,0.4),inset_0_0_12px_rgba(34,211,238,0.08)]" href="{{ $topUpUrl ?? '#' }}">Top Up</a>
                                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-violet-500/5 border border-violet-500/[0.28] text-[#b8c8e8] hover:bg-violet-500/[0.12] hover:border-violet-500/[0.65] hover:text-white hover:shadow-[0_0_14px_rgba(168,85,247,0.25)]" href="{{ $walletCardUrl ?? '#' }}">E-Card</a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-start justify-between gap-3 mb-[10px]">
                            <span class="text-[#7a8aaa] text-xs">Recent Activity</span>
                            <a class="text-[#7a8aaa] text-xs" href="{{ route('user.favourites') }}">View trip log</a>
                        </div>
                        <ul class="grid gap-[10px] list-none">
                            @forelse ($walletActivities as $activity)
                                @php
                                    $amount   = data_get($activity, 'amount');
                                    $isCredit = is_numeric($amount) && $amount > 0;
                                @endphp
                                <li class="border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] hover:border-violet-500/40 hover:shadow-[0_0_18px_rgba(168,85,247,0.1)] grid gap-[10px] p-[13px]">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <strong class="text-[15px] mb-1 block">{{ data_get($activity, 'title', data_get($activity, 'description', 'Wallet activity')) }}</strong>
                                            <div class="text-[#7a8aaa] text-xs">{{ data_get($activity, 'meta', data_get($activity, 'created_at', '-')) }}</div>
                                        </div>
                                        <span class="{{ $isCredit ? 'text-[#86efac]' : 'text-[#fb7185]' }} text-lg font-black text-right">
                                            {{ is_numeric($amount) ? ($isCredit ? '+' : '-') . 'Rp ' . number_format(abs($amount), 0, ',', '.') : '-' }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="grid gap-2 place-items-start p-[18px] text-[#7a8aaa] min-h-[120px] border border-violet-500/[0.16] rounded-[9px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms]">
                                    <strong class="text-[#eef4ff]">No payment history yet.</strong>
                                    <span>History will appear from the wallet transaction table once the database is connected.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- ── QR Panel ── --}}
                <div class="border border-violet-500/[0.28] rounded-[9px] bg-[rgba(4,5,15,0.94)] grid gap-[14px] justify-items-center p-4 border-violet-500/[0.32]">
                    <div>
                        <span class="text-[#7a8aaa] text-xs block">Scan to Board</span>
                        <h3 class="mt-1.5 mb-0">QR Boarding</h3>
                    </div>

                    <div class="qr-scanner" id="qr-scanner">
                        {{-- corner brackets --}}
                        <div class="qr-corner tl"></div>
                        <div class="qr-corner tr"></div>
                        <div class="qr-corner bl"></div>
                        <div class="qr-corner br"></div>

                        {{-- scan beam --}}
                        <div class="qr-beam"></div>

                        {{-- camera feed --}}
                        <video id="qr-video" playsinline muted></video>

                        {{-- static QR --}}
                        <div class="qr-frame" id="qr-static">
                            @if (!empty($qrCodeSvg))
                                {!! $qrCodeSvg !!}
                            @elseif (!empty($qrCodeUrl))
                                <img src="{{ $qrCodeUrl }}" alt="BusFlow QR boarding">
                            @else
                                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="background:transparent">
                                    <rect width="100" height="100" fill="transparent"/>
                                    <rect x="8"  y="8"  width="30" height="30" rx="3" stroke="#c084fc" stroke-width="3" fill="none"/>
                                    <rect x="14" y="14" width="18" height="18" rx="1.5" fill="#c084fc"/>
                                    <rect x="62" y="8"  width="30" height="30" rx="3" stroke="#c084fc" stroke-width="3" fill="none"/>
                                    <rect x="68" y="14" width="18" height="18" rx="1.5" fill="#c084fc"/>
                                    <rect x="8"  y="62" width="30" height="30" rx="3" stroke="#c084fc" stroke-width="3" fill="none"/>
                                    <rect x="14" y="68" width="18" height="18" rx="1.5" fill="#c084fc"/>
                                    <rect x="48" y="8"  width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="56" y="8"  width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="48" y="16" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="8"  y="48" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="16" y="48" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="8"  y="56" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="48" y="48" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="56" y="48" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="64" y="48" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="72" y="48" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="80" y="48" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="48" y="56" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="64" y="56" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="80" y="56" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="48" y="64" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="56" y="64" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="72" y="64" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="48" y="72" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="64" y="72" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="72" y="72" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="80" y="72" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="48" y="80" width="6" height="6" rx="1" fill="#22d3ee"/>
                                    <rect x="56" y="80" width="6" height="6" rx="1" fill="#c084fc"/>
                                    <rect x="80" y="80" width="6" height="6" rx="1" fill="#22d3ee"/>
                                </svg>
                            @endif
                        </div>

                        <canvas id="qr-canvas" style="display:none;"></canvas>
                    </div>

                    <div class="text-[11px] font-bold text-center min-h-[16px] text-[#7a8aaa] transition-colors duration-200" id="qr-status">Point camera at QR code</div>

                    <button class="inline-flex items-center gap-[7px] min-h-[34px] px-[14px] rounded-lg text-[11px] font-extrabold tracking-wider uppercase cursor-pointer transition-all duration-[220ms] overflow-hidden bg-cyan-400/[0.07] border border-cyan-400/40 text-[#22d3ee] hover:bg-cyan-400/[0.15] hover:border-cyan-400 hover:shadow-[0_0_16px_rgba(34,211,238,0.35)] hover:text-white" id="cam-toggle" type="button">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <rect x="1" y="3.5" width="12" height="8.5" rx="2" stroke="currentColor" stroke-width="1.3"/>
                            <circle cx="7" cy="7.75" r="2.2" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M4.5 3.5V3a1.5 1.5 0 013 0v.5" stroke="currentColor" stroke-width="1.2"/>
                        </svg>
                        <span id="cam-label">Open Camera</span>
                    </button>


                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        (function () {
            const video     = document.getElementById('qr-video');
            const canvas    = document.getElementById('qr-canvas');
            const staticEl  = document.getElementById('qr-static');
            const scanner   = document.getElementById('qr-scanner');
            const toggleBtn = document.getElementById('cam-toggle');
            const label     = document.getElementById('cam-label');
            const status    = document.getElementById('qr-status');
            const ctx       = canvas.getContext('2d');
            let stream = null, rafId = null, active = false, lastCode = null;

            function setStatus(msg, type = '') { status.textContent = msg; status.className = type; }

            function scanFrame() {
                if (!active || video.readyState < video.HAVE_ENOUGH_DATA) {
                    rafId = requestAnimationFrame(scanFrame); return;
                }
                canvas.width = video.videoWidth; canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code    = jsQR(imgData.data, imgData.width, imgData.height, { inversionAttempts: 'dontInvert' });
                if (code && code.data !== lastCode) {
                    lastCode = code.data;
                    scanner.classList.add('is-detected');
                    setTimeout(() => scanner.classList.remove('is-detected'), 600);
                    setStatus('✓ ' + code.data.slice(0, 36) + (code.data.length > 36 ? '…' : ''), 'ok');
                }
                rafId = requestAnimationFrame(scanFrame);
            }

            async function startCamera() {
                try {
                    setStatus('Requesting camera access…');
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 640 } }
                    });
                    video.srcObject = stream;
                    await video.play();
                    active = true;
                    video.style.display    = 'block';
                    staticEl.style.display = 'none';
                    toggleBtn.classList.add('is-active');
                    label.textContent = 'Close Camera';
                    setStatus('Camera active — point at QR');
                    lastCode = null;
                    scanFrame();
                } catch (err) { setStatus('Unable to access camera: ' + err.message, 'err'); }
            }

            function stopCamera() {
                active = false;
                cancelAnimationFrame(rafId);
                if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
                video.srcObject        = null;
                video.style.display    = 'none';
                staticEl.style.display = 'grid';
                toggleBtn.classList.remove('is-active');
                label.textContent = 'Open Camera';
                setStatus('Point camera at QR code');
                lastCode = null;
            }

            toggleBtn.addEventListener('click', () => active ? stopCamera() : startCamera());
            window.addEventListener('pagehide', stopCamera);
        })();
    </script>
@endsection