@extends('user.layout')

@section('title', 'Payments & Digital Wallet - BusFlow')
@section('page_title', 'Payments & QR')
@section('page_description', 'Kelola saldo e-wallet, top up, riwayat pembayaran, dan QR boarding untuk tap-in serta tap-out.')

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
        #qr-status.ok  { color: var(--lime); }
        #qr-status.err { color: var(--rose); }
    </style>

    <section class="surface">
        <div class="surface-header">
            <div>
                <h2>Digital Wallet</h2>
                <p>Saldo, top up, dan riwayat pembayaran akan diambil dari database user.</p>
            </div>
            <span class="pill">{{ data_get($wallet, 'status', 'Waiting data') }}</span>
        </div>
        <div class="surface-body">
            <div class="wallet-layout">
                <div class="stack">
                    <div class="balance-card">
                        <div class="balance-row">
                            <div>
                                <span class="mini-label">Available Balance</span>
                                <strong>{{ is_numeric($balance) ? 'Rp ' . number_format($balance, 0, ',', '.') : 'Belum tersedia' }}</strong>
                                <span class="status-dot">{{ data_get($wallet, 'is_active') ? 'Ready to ride' : 'Need wallet data' }}</span>
                            </div>
                            <div class="button-row">
                                <a class="primary-btn" href="{{ $topUpUrl ?? '#' }}">Top Up</a>
                                <a class="ghost-btn"   href="{{ $walletCardUrl ?? '#' }}">E-Card</a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-row" style="margin-bottom:10px;">
                            <span class="mini-label">Recent Activity</span>
                            <a class="meta" href="{{ route('user.favourites') }}">View trip log</a>
                        </div>
                        <ul class="list">
                            @forelse ($walletActivities as $activity)
                                @php
                                    $amount   = data_get($activity, 'amount');
                                    $isCredit = is_numeric($amount) && $amount > 0;
                                @endphp
                                <li class="item-card">
                                    <div class="card-row">
                                        <div>
                                            <strong>{{ data_get($activity, 'title', data_get($activity, 'description', 'Aktivitas wallet')) }}</strong>
                                            <div class="meta">{{ data_get($activity, 'meta', data_get($activity, 'created_at', '-')) }}</div>
                                        </div>
                                        <span class="amount {{ $isCredit ? 'is-credit' : 'is-debit' }}">
                                            {{ is_numeric($amount) ? ($isCredit ? '+' : '-') . 'Rp ' . number_format(abs($amount), 0, ',', '.') : '-' }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="empty-state">
                                    <strong>Belum ada riwayat pembayaran.</strong>
                                    <span>Riwayat akan tampil dari tabel transaksi wallet ketika database sudah disambungkan.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- ── QR Panel ── --}}
                <div class="qr-panel">
                    <div>
                        <span class="mini-label">Scan to Board</span>
                        <h3 style="margin:6px 0 0;">QR Boarding</h3>
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
                                <img src="{{ $qrCodeUrl }}" alt="QR boarding BusFlow">
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

                    <div id="qr-status">Arahkan kamera ke QR code</div>

                    <button class="cam-btn" id="cam-toggle" type="button">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <rect x="1" y="3.5" width="12" height="8.5" rx="2" stroke="currentColor" stroke-width="1.3"/>
                            <circle cx="7" cy="7.75" r="2.2" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M4.5 3.5V3a1.5 1.5 0 013 0v.5" stroke="currentColor" stroke-width="1.2"/>
                        </svg>
                        <span id="cam-label">Buka Kamera</span>
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
                    setStatus('Meminta akses kamera…');
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 640 } }
                    });
                    video.srcObject = stream;
                    await video.play();
                    active = true;
                    video.style.display    = 'block';
                    staticEl.style.display = 'none';
                    toggleBtn.classList.add('is-active');
                    label.textContent = 'Tutup Kamera';
                    setStatus('Kamera aktif — arahkan ke QR');
                    lastCode = null;
                    scanFrame();
                } catch (err) { setStatus('Tidak dapat mengakses kamera: ' + err.message, 'err'); }
            }

            function stopCamera() {
                active = false;
                cancelAnimationFrame(rafId);
                if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
                video.srcObject        = null;
                video.style.display    = 'none';
                staticEl.style.display = 'grid';
                toggleBtn.classList.remove('is-active');
                label.textContent = 'Buka Kamera';
                setStatus('Arahkan kamera ke QR code');
                lastCode = null;
            }

            toggleBtn.addEventListener('click', () => active ? stopCamera() : startCamera());
            window.addEventListener('pagehide', stopCamera);
        })();
    </script>
@endsection
