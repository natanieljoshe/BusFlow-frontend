<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BusFlow - Driver')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    <style>
        :root {
            --bg: #04050f;
            --panel: #090d1a;
            --panel-2: #0e1226;
            --panel-3: #070b18;
            --line: rgba(148, 163, 184, 0.12);
            --text: #eef4ff;
            --muted: #7a8aaa;
            --soft: #b8c8e8;
            --cyan: #22d3ee;
            --violet: #a855f7;
            --violet-bright: #c084fc;
            --blue: #3b82f6;
            --lime: #86efac;
            --amber: #f4bf55;
            --rose: #fb7185;
            --shadow: 0 24px 80px rgba(0,0,0,.6);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        @keyframes pulse-glow { 0%,100%{opacity:.45} 50%{opacity:1} }
        @keyframes drift { 0%{transform:translate(0,0) scale(1)} 33%{transform:translate(28px,-18px) scale(1.06)} 66%{transform:translate(-18px,16px) scale(.96)} 100%{transform:translate(0,0) scale(1)} }
        @keyframes scanline { 0%{background-position:0 0} 100%{background-position:0 100vh} }
        @keyframes slide-in-left { from{opacity:0;transform:translateX(-28px)} to{opacity:1;transform:translateX(0)} }
        @keyframes slide-in-up { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
        @keyframes brand-pulse { 0%,100%{box-shadow:0 0 12px rgba(34,211,238,.4),0 0 28px rgba(168,85,247,.3)} 50%{box-shadow:0 0 22px rgba(34,211,238,.8),0 0 48px rgba(168,85,247,.6)} }

        body {
            min-height: 100vh;
            color: var(--text);
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            background: #04050f;
            overflow-x: hidden;
            position: relative;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(168,85,247,.014) 2px,
                rgba(168,85,247,.014) 4px
            );
            animation: scanline 10s linear infinite;
        }

        #bg-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }
        .bg-orb-1 { width:520px;height:520px;top:-120px;left:-100px;background:radial-gradient(circle,rgba(168,85,247,.22) 0%,transparent 70%);animation:drift 18s ease-in-out infinite; }
        .bg-orb-2 { width:440px;height:440px;bottom:-80px;right:-80px;background:radial-gradient(circle,rgba(34,211,238,.16) 0%,transparent 70%);animation:drift 22s ease-in-out infinite reverse; }
        .bg-orb-3 { width:300px;height:300px;top:45%;left:55%;background:radial-gradient(circle,rgba(59,130,246,.14) 0%,transparent 70%);animation:drift 28s ease-in-out infinite 4s; }

        .app-shell {
            display: grid;
            grid-template-columns: 264px minmax(0,1fr);
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding: 24px 18px;
            background: rgba(4,5,15,.84);
            border-right: 1px solid rgba(168,85,247,.22);
            backdrop-filter: blur(24px);
            box-shadow: 2px 0 40px rgba(168,85,247,.08);
            animation: slide-in-left .55s ease both;
        }

        .brand { display:flex;align-items:center;gap:12px;padding:0 8px;text-decoration:none;color:var(--text); }
        .brand-mark {
            width:42px;height:42px;display:grid;place-items:center;border-radius:10px;
            border:1px solid rgba(34,211,238,.5);
            background: linear-gradient(135deg,rgba(34,211,238,.15),rgba(168,85,247,.15));
            animation: brand-pulse 2.8s ease-in-out infinite;
            flex-shrink:0;
        }
        .brand strong { display:block;font-size:18px; }
        .brand > span:last-child > span { color:var(--muted);font-size:12px; }

        .operator-card {
            padding:14px;border:1px solid rgba(168,85,247,.2);border-radius:10px;background:rgba(4,5,15,.95);
        }
        .operator-card span { color:var(--muted);font-size:12px; }
        .operator-card strong { display:block;margin-top:4px;font-size:14px; }

        .status-dot {
            display:inline-flex;align-items:center;gap:7px;color:var(--lime);font-size:12px;font-weight:700;text-transform:uppercase;
            margin-top:10px;
        }
        .status-dot::before {
            content:"";width:7px;height:7px;border-radius:99px;background:var(--lime);box-shadow:0 0 18px var(--lime);animation:pulse-glow 1.8s ease-in-out infinite;
        }

        .nav-list, .utility-list { display:grid;gap:6px;list-style:none; }
        .nav-link {
            display:flex;align-items:center;gap:10px;min-height:42px;padding:0 12px;border:1px solid transparent;border-radius:10px;
            color:var(--soft);font-size:14px;font-weight:700;text-decoration:none;transition:all .22s ease;
        }
        .nav-link:hover, .nav-link.is-active {
            border-color:rgba(168,85,247,.55);background:linear-gradient(90deg,rgba(168,85,247,.15),rgba(34,211,238,.07));color:#fff;
            box-shadow:0 0 18px rgba(168,85,247,.22), inset 0 0 14px rgba(168,85,247,.07);
        }
        .nav-icon {
            width:26px;height:26px;display:grid;place-items:center;background:rgba(168,85,247,.1);border-radius:7px;flex-shrink:0;transition:all .22s ease;color:var(--violet-bright);
        }
        .nav-link:hover .nav-icon, .nav-link.is-active .nav-icon {
            background:rgba(168,85,247,.28);box-shadow:0 0 12px rgba(168,85,247,.5);
        }

        .sidebar-footer { margin-top:auto;display:grid;gap:14px; }

        .recharge-btn, .primary-btn, .ghost-btn, .danger-btn {
            display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:38px;padding:0 16px;border-radius:8px;font-size:12px;font-weight:800;cursor:pointer;font-family:inherit;letter-spacing:.03em;text-transform:uppercase;position:relative;overflow:hidden;transition:all .25s ease;text-decoration:none;
        }
        .recharge-btn {
            background: linear-gradient(90deg,var(--violet),var(--cyan));color:#fff;box-shadow:0 12px 28px rgba(34,211,238,.16);
        }

        .main { padding:24px 28px 32px; }
        .topbar {
            display:flex;justify-content:space-between;align-items:flex-start;gap:20px;padding:10px 0 24px;
            animation: slide-in-up .5s ease both;
        }
        .page-title h1 { font-size:30px;line-height:1.05; }
        .page-title p { color:var(--muted);margin-top:8px;max-width:700px;line-height:1.6; }
        .content-grid { display:grid;gap:18px; }
        .card { background:rgba(9,13,26,.9);border:1px solid rgba(148,163,184,.12);border-radius:18px;padding:20px;box-shadow:var(--shadow); }
        .card h2,.card h3 { font-size:18px;margin-bottom:10px; }
        .card p,.card li { color:var(--soft);line-height:1.7; }
        .pill { display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:rgba(34,211,238,.12);color:var(--cyan);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.04em; }
        .stat-grid { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px; }
        .mini-card { background:rgba(14,18,38,.95);border:1px solid rgba(148,163,184,.12);border-radius:14px;padding:16px; }
        .mini-card strong { display:block;font-size:22px;margin-top:6px; }
        .list { display:grid;gap:10px;margin-top:12px; }
        .list-item { display:flex;justify-content:space-between;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;background:rgba(4,5,15,.65);border:1px solid rgba(148,163,184,.08); }
        .list-item span { color:var(--muted);font-size:13px; }
        .badge { display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;background:rgba(134,239,172,.14);color:var(--lime);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.04em; }
        .badge.warn { background:rgba(244,191,85,.16);color:var(--amber); }

        @media (max-width:1180px) {
            .app-shell { grid-template-columns:1fr; }
            .sidebar { position:static;height:auto; }
            .nav-list, .utility-list { grid-template-columns:repeat(4,minmax(0,1fr)); }
        }
        @media (max-width:760px) {
            .main, .sidebar { padding:16px; }
            .topbar { align-items:stretch;flex-direction:column; }
            .page-title h1 { font-size:26px; }
            .nav-list, .utility-list, .stat-grid { grid-template-columns:1fr; }
            .nav-link { min-height:40px; }
        }
    </style>
</head>
<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    <canvas id="bg-canvas"></canvas>

    <div class="app-shell">
        <aside class="sidebar" aria-label="BusFlow driver navigation">
            <a class="brand" href="{{ route('sopir.home') }}">
                <span class="brand-mark">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                        <rect x="2" y="6" width="18" height="11" rx="3" stroke="#22d3ee" stroke-width="1.5"/>
                        <circle cx="6" cy="17" r="2" fill="#a855f7"/>
                        <circle cx="16" cy="17" r="2" fill="#a855f7"/>
                        <path d="M2 10h18" stroke="#22d3ee" stroke-width="1" stroke-dasharray="2 2"/>
                        <rect x="8" y="3" width="6" height="4" rx="1.5" stroke="#c084fc" stroke-width="1.2"/>
                    </svg>
                </span>
                <span>
                    <strong>BusFlow</strong>
                    <span>Driver panel</span>
                </span>
            </a>

            <div class="operator-card">
                <span>Driver Status</span>
                <strong>Budi - Driver</strong>
                <span class="status-dot">Active</span>
            </div>

            <nav>
                <ul class="nav-list">
                    <li>
                        <a class="nav-link {{ request()->routeIs('sopir.home') ? 'is-active' : '' }}" href="{{ route('sopir.home') }}">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <path d="M2 6.5L7.5 2l5.5 4.5v5a1 1 0 01-1 1h-3v-4H6v4H3a1 1 0 01-1-1z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('sopir.trip-details') ? 'is-active' : '' }}" href="{{ route('sopir.trip-details') }}">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <rect x="2" y="3" width="11" height="9" rx="2" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M4 6h7M4 8.5h4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span>Trip Details</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('sopir.history') ? 'is-active' : '' }}" href="{{ route('sopir.history') }}">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <circle cx="7.5" cy="7.5" r="5.5" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M7.5 7.5v-3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                                    <path d="M7.5 7.5l2 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span>History</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <ul class="utility-list">
                    <li>
                        <a class="nav-link" href="#">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <circle cx="7.5" cy="7.5" r="5.5" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M7.5 5v3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                    <circle cx="7.5" cy="10" r=".8" fill="currentColor"/>
                                </svg>
                            </span>
                            <span>Support</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('sopir.settings') ? 'is-active' : '' }}" href="{{ route('sopir.settings') }}">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <circle cx="7.5" cy="7.5" r="2" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M7.5 1.5v1.2M7.5 12.3v1.2M1.5 7.5h1.2M12.3 7.5h1.2M3.4 3.4l.85.85M10.75 10.75l.85.85M3.4 11.6l.85-.85M10.75 4.25l.85-.85" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <div class="page-title">
                    <h1>@yield('page_title', 'Driver Page')</h1>
                    <p>@yield('page_description', 'Monitor assignments, trip details, and your driving history from the driver panel.')</p>
                </div>
                <div class="top-actions">
                    @yield('top_actions')
                </div>
            </div>
            @yield('content')
        </main>
    </div>

    <script>
        (function () {
            const canvas = document.getElementById('bg-canvas');
            if (!canvas) return;

            function applyBgEffect(enabled) {
                const shouldShow = enabled !== false && localStorage.getItem('bf_bg') !== 'off';
                canvas.style.display = shouldShow ? '' : 'none';
            }

            applyBgEffect(localStorage.getItem('bf_bg') !== 'off');
            window.addEventListener('storage', (event) => {
                if (event.key === 'bf_bg') {
                    applyBgEffect(event.newValue !== 'off');
                }
            });

            const ctx = canvas.getContext('2d');
            const GAP = 36;
            let W, H, cols, rows, dots = [];
            let mx = -9999, my = -9999;

            document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
            document.addEventListener('mouseleave', () => { mx = -9999; my = -9999; });

            const STOPS = [
                [255, 255, 255],
                [220, 190, 255],
                [192, 132, 252],
                [168, 85, 247],
                [99, 140, 255],
                [59, 130, 246],
                [34, 211, 238],
                [180, 230, 255],
                [255, 255, 255],
            ];

            function lerpC(a, b, t) {
                return [
                    Math.round(a[0] + (b[0] - a[0]) * t),
                    Math.round(a[1] + (b[1] - a[1]) * t),
                    Math.round(a[2] + (b[2] - a[2]) * t),
                ];
            }

            function palColor(t) {
                t = ((t % 1) + 1) % 1;
                const s = t * (STOPS.length - 1);
                const i = Math.floor(s);
                return lerpC(STOPS[i], STOPS[Math.min(i + 1, STOPS.length - 1)], s - i);
            }

            const WAVES = [
                { type: 'h', speed: 9, width: 4.5, cOff: 0.00 },
                { type: 'v', speed: 6, width: 4.0, cOff: 0.28 },
                { type: 'd1', speed: 7, width: 5.5, cOff: 0.55 },
                { type: 'd2', speed: 5, width: 5.0, cOff: 0.75 },
                { type: 'h', speed: -8, width: 3.5, cOff: 0.40 },
                { type: 'v', speed: -5, width: 4.0, cOff: 0.15 },
                { type: 'd1', speed: -6, width: 4.5, cOff: 0.65 },
                { type: 'd2', speed: 4, width: 3.8, cOff: 0.88 },
            ];

            const PERIOD = 90;

            function waveBright(dot, wave, t) {
                let idx;
                if (wave.type === 'h') idx = dot.c;
                else if (wave.type === 'v') idx = dot.r;
                else if (wave.type === 'd1') idx = dot.c + dot.r;
                else idx = dot.c - dot.r;

                const front = (t * wave.speed) % PERIOD;
                let d = ((idx - front) % PERIOD + PERIOD) % PERIOD;
                if (d > PERIOD / 2) d = PERIOD - d;
                return Math.exp(-(d * d) / (2 * wave.width * wave.width));
            }

            function build() {
                W = canvas.width = window.innerWidth;
                H = canvas.height = window.innerHeight;
                cols = Math.ceil(W / GAP) + 2;
                rows = Math.ceil(H / GAP) + 2;
                dots = [];
                for (let r = 0; r < rows; r++) {
                    for (let c = 0; c < cols; c++) {
                        dots.push({ x: c * GAP, y: r * GAP, c, r });
                    }
                }
            }

            function frame(ts) {
                const t = ts * 0.001;
                ctx.clearRect(0, 0, W, H);

                dots.forEach(d => {
                    let bright = 0;
                    let colorAcc = 0;
                    WAVES.forEach(w => {
                        const b = waveBright(d, w, t);
                        bright += b;
                        colorAcc += b * w.cOff;
                    });
                    bright = Math.min(bright, 1.4);

                    const dx = d.x - mx;
                    const dy = d.y - my;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    const prox = Math.max(0, 1 - dist / 150);
                    bright += prox * 1.6;

                    const base = 0.055 + 0.03 * Math.sin(ts * 0.0009 + d.c * 0.28 + d.r * 0.42);
                    bright = Math.max(bright, base);

                    const colorT = bright > 0.15
                        ? ((colorAcc / Math.max(bright, 0.01)) + t * 0.06) % 1
                        : (t * 0.035 + d.c * 0.018 + d.r * 0.013) % 1;
                    const [r, g, b] = palColor(colorT);

                    const radius = 1.1 + bright * 2.6 + prox * 2.8;
                    const alpha = Math.min(0.07 + bright * 0.78 + prox * 0.65, 1.0);

                    if (bright > 0.25 || prox > 0.08) {
                        ctx.shadowColor = `rgba(${r},${g},${b},${Math.min(alpha, 1)})`;
                        ctx.shadowBlur = 3 + bright * 14 + prox * 18;
                    } else {
                        ctx.shadowBlur = 0;
                    }

                    ctx.beginPath();
                    ctx.arc(d.x, d.y, radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${r},${g},${b},${alpha})`;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                });

                requestAnimationFrame(frame);
            }

            build();
            window.addEventListener('resize', build);
            requestAnimationFrame(frame);
        })();
    </script>
</body>
</html>
