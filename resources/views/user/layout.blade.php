<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BusFlow - User')</title>
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

        @keyframes pulse-glow    { 0%,100%{opacity:.45} 50%{opacity:1} }
        @keyframes drift         { 0%{transform:translate(0,0) scale(1)} 33%{transform:translate(28px,-18px) scale(1.06)} 66%{transform:translate(-18px,16px) scale(.96)} 100%{transform:translate(0,0) scale(1)} }
        @keyframes scanline      { 0%{background-position:0 0} 100%{background-position:0 100vh} }
        @keyframes slide-in-left { from{opacity:0;transform:translateX(-28px)} to{opacity:1;transform:translateX(0)} }
        @keyframes slide-in-up   { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
        @keyframes brand-pulse   { 0%,100%{box-shadow:0 0 12px rgba(34,211,238,.4),0 0 28px rgba(168,85,247,.3)} 50%{box-shadow:0 0 22px rgba(34,211,238,.8),0 0 48px rgba(168,85,247,.6)} }
        @keyframes float-node    { 0%,100%{transform:translate(-50%,-50%) scale(1)} 50%{transform:translate(-50%,-50%) scale(1.18)} }
        @keyframes ribbon-shift  { 0%{opacity:.7;filter:hue-rotate(0deg)} 100%{opacity:.95;filter:hue-rotate(30deg)} }

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

        .brand { display:flex;align-items:center;gap:12px;padding:0 8px; }

        .brand-mark {
            width: 42px; height: 42px;
            display: grid; place-items: center;
            border-radius: 10px;
            border: 1px solid rgba(34,211,238,.5);
            background: linear-gradient(135deg,rgba(34,211,238,.15),rgba(168,85,247,.15));
            animation: brand-pulse 2.8s ease-in-out infinite;
            flex-shrink: 0;
        }

        .brand strong { display:block;font-size:18px; }
        .brand > a > span:last-child > span { color:var(--muted);font-size:12px; }

        .operator-card {
            padding: 14px;
            border: 1px solid rgba(168,85,247,.2);
            border-radius: 10px;
            background: rgba(4,5,15,.95);
        }
        .operator-card span  { color:var(--muted);font-size:12px; }
        .operator-card strong{ display:block;margin-top:4px;font-size:14px; }

        .status-dot {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--lime);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-dot::before {
            content:"";
            width:7px;height:7px;
            border-radius:99px;
            background:var(--lime);
            box-shadow:0 0 18px var(--lime);
            animation: pulse-glow 1.8s ease-in-out infinite;
        }

        .nav-list, .utility-list { display:grid;gap:6px;list-style:none; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 42px;
            padding: 0 12px;
            border: 1px solid transparent;
            border-radius: 10px;
            color: var(--soft);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all .22s ease;
        }
        .nav-link:hover, .nav-link.is-active {
            border-color: rgba(168,85,247,.55);
            background: linear-gradient(90deg,rgba(168,85,247,.15),rgba(34,211,238,.07));
            color: #fff;
            box-shadow: 0 0 18px rgba(168,85,247,.22), inset 0 0 14px rgba(168,85,247,.07);
        }

        .nav-icon {
            width: 26px; height: 26px;
            display: grid; place-items: center;
            background: rgba(168,85,247,.1);
            border-radius: 7px;
            flex-shrink: 0;
            transition: all .22s ease;
            color: var(--violet-bright);
        }
        .nav-link:hover .nav-icon, .nav-link.is-active .nav-icon {
            background: rgba(168,85,247,.28);
            box-shadow: 0 0 12px rgba(168,85,247,.5);
        }

        .sidebar-footer { margin-top:auto;display:grid;gap:14px; }

        @keyframes btn-scan {
            0%   { transform: translateX(-100%) skewX(-12deg); }
            100% { transform: translateX(220%)  skewX(-12deg); }
        }
        @keyframes recharge-pulse {
            0%,100% { box-shadow: 0 0 8px rgba(168,85,247,.3), inset 0 0 8px rgba(168,85,247,.08); }
            50%     { box-shadow: 0 0 18px rgba(168,85,247,.55), inset 0 0 14px rgba(34,211,238,.1); }
        }

        .recharge-btn, .primary-btn, .ghost-btn, .danger-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 38px;
            padding: 0 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            font-family: inherit;
            letter-spacing: .03em;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
            transition: all .25s ease;
            text-decoration: none;
        }

        /* scan line sweep shared */
        .recharge-btn::before, .primary-btn::before {
            content: "";
            position: absolute;
            top: 0; bottom: 0;
            width: 40%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
            transform: translateX(-100%) skewX(-12deg);
            pointer-events: none;
        }
        .recharge-btn:hover::before, .primary-btn:hover::before {
            animation: btn-scan .55s ease forwards;
        }

        /* Recharge — menyatu dgn sidebar, transparan border neon */
        .recharge-btn {
            width: 100%;
            min-height: 40px;
            background: rgba(168,85,247,.08);
            border: 1px solid rgba(168,85,247,.45);
            color: var(--violet-bright);
            animation: recharge-pulse 3s ease-in-out infinite;
        }
        .recharge-btn:hover {
            background: rgba(168,85,247,.18);
            border-color: rgba(168,85,247,.9);
            color: #fff;
            box-shadow: 0 0 20px rgba(168,85,247,.4), inset 0 0 16px rgba(168,85,247,.1);
            animation: none;
        }

        /* Primary — aksi utama, sedikit lebih menonjol tapi tetap transparan */
        .primary-btn {
            background: rgba(34,211,238,.08);
            border: 1px solid rgba(34,211,238,.5);
            color: var(--cyan);
        }
        .primary-btn:hover {
            background: rgba(34,211,238,.16);
            border-color: var(--cyan);
            color: #fff;
            box-shadow: 0 0 18px rgba(34,211,238,.4), inset 0 0 12px rgba(34,211,238,.08);
        }

        /* Ghost — secondary action */
        .ghost-btn {
            background: rgba(168,85,247,.05);
            border: 1px solid rgba(168,85,247,.28);
            color: var(--soft);
        }
        .ghost-btn:hover {
            background: rgba(168,85,247,.12);
            border-color: rgba(168,85,247,.65);
            color: #fff;
            box-shadow: 0 0 14px rgba(168,85,247,.25);
        }

        /* Danger */
        .danger-btn {
            background: rgba(251,113,133,.06);
            border: 1px solid rgba(251,113,133,.32);
            color: #fda4af;
        }
        .danger-btn:hover {
            background: rgba(251,113,133,.14);
            border-color: rgba(251,113,133,.7);
            color: #fff;
            box-shadow: 0 0 14px rgba(251,113,133,.3);
        }

        .main { min-width:0;padding:24px;animation:slide-in-up .5s ease .15s both; }

        .topbar { display:flex;align-items:center;justify-content:space-between;gap:18px;margin-bottom:18px; }

        .page-title h1 { font-size:30px;line-height:1.1; }
        .page-title p  { max-width:760px;margin:8px 0 0;color:var(--muted);font-size:14px;line-height:1.6; }

        .top-actions, .button-row { display:flex;align-items:center;gap:10px;flex-wrap:wrap; }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 34px;
            padding: 0 12px;
            border: 1px solid rgba(168,85,247,.3);
            border-radius: 8px;
            color: var(--violet-bright);
            background: rgba(168,85,247,.1);
            font-size: 12px;
            font-weight: 800;
        }

        .surface {
            border: 1px solid rgba(168,85,247,.22);
            border-radius: 12px;
            background: rgba(4,5,15,.96);
            box-shadow: 0 8px 40px rgba(0,0,0,.7), 0 0 0 1px rgba(168,85,247,.08);
            backdrop-filter: blur(24px);
            overflow: hidden;
            transition: box-shadow .3s ease;
        }
        .surface:hover { box-shadow:0 8px 40px rgba(0,0,0,.7),0 0 28px rgba(168,85,247,.14); }
        .surface + .surface, .stack > .surface + .surface { margin-top:18px; }

        .surface-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
            border-bottom: 1px solid rgba(168,85,247,.12);
        }
        .surface-header h2 { font-size:20px; }
        .surface-header p  { margin:6px 0 0;color:var(--muted);font-size:13px;line-height:1.5; }

        .surface-body { padding:18px; }
        .stack { display:grid;gap:18px; }

        .content-grid { display:grid;grid-template-columns:minmax(0,1.25fr) minmax(340px,.75fr);gap:18px;align-items:start; }
        .route-layout  { display:grid;grid-template-columns:330px minmax(0,1fr);gap:18px; }
        .wallet-layout { display:grid;grid-template-columns:minmax(0,1.05fr) minmax(280px,.95fr);gap:18px; }

        .field { display:grid;gap:8px; }
        .field label { color:var(--soft);font-size:12px;font-weight:800;text-transform:uppercase; }

        .input-control, .textarea-control {
            width: 100%;
            border: 1px solid rgba(168,85,247,.25);
            border-radius: 8px;
            color: var(--text);
            background: rgba(4,5,15,.98);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-control { height:44px;padding:0 13px; }
        .textarea-control { min-height:92px;resize:vertical;padding:12px 13px; }
        .input-control:focus, .textarea-control:focus {
            border-color: rgba(168,85,247,.7);
            box-shadow: 0 0 0 3px rgba(168,85,247,.14);
        }

        .list, .route-cards { display:grid;gap:10px;list-style:none; }

        .item-card, .route-card, .stat-box, .empty-state {
            border: 1px solid rgba(168,85,247,.16);
            border-radius: 9px;
            background: rgba(4,5,15,.92);
            transition: border-color .22s, box-shadow .22s;
        }
        .item-card:hover, .route-card:hover { border-color:rgba(168,85,247,.4);box-shadow:0 0 18px rgba(168,85,247,.1); }
        .item-card, .route-card { display:grid;gap:10px;padding:13px; }
        .route-card { border-left:3px solid var(--violet); }

        .card-row { display:flex;align-items:flex-start;justify-content:space-between;gap:12px; }

        .route-code {
            display: inline-flex;
            align-items: center;
            min-height: 25px;
            padding: 0 8px;
            border-radius: 7px;
            color: #04050f;
            background: linear-gradient(90deg, var(--violet), var(--cyan));
            font-size: 12px;
            font-weight: 900;
        }

        .route-card h3, .item-card h3 { font-size:15px;margin-bottom:4px; }

        .eta, .amount { color:var(--cyan);font-size:18px;font-weight:900;text-align:right; }
        .amount.is-debit  { color:var(--rose); }
        .amount.is-credit { color:var(--lime); }
        .eta span { display:block;color:var(--muted);font-size:11px;font-weight:700; }

        .progress { height:6px;border-radius:99px;overflow:hidden;background:rgba(168,85,247,.12); }
        .progress > span { display:block;width:var(--level,0%);height:100%;border-radius:inherit;background:linear-gradient(90deg,var(--violet),var(--cyan)); }

        .map-wrap {
            position: relative;
            min-height: 520px;
            border: 1px solid rgba(168,85,247,.22);
            border-radius: 10px;
            overflow: hidden;
            background:
                linear-gradient(90deg,rgba(168,85,247,.06) 1px,transparent 1px),
                linear-gradient(0deg,rgba(168,85,247,.06) 1px,transparent 1px),
                #050818;
            background-size: 42px 42px, 42px 42px, auto;
        }

        .map-ribbon {
            position: absolute;
            inset: 58% -8% auto 18%;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(90deg,transparent,var(--violet),var(--cyan),var(--violet-bright),transparent);
            transform: rotate(-38deg);
            box-shadow: 0 0 18px rgba(168,85,247,.7),0 0 36px rgba(34,211,238,.4);
            animation: ribbon-shift 4s ease-in-out infinite alternate;
        }

        .map-node, .bus-pin { position:absolute;display:grid;place-items:center;border-radius:8px;transform:translate(-50%,-50%); }
        .map-node {
            width:28px;height:28px;
            border:1px solid rgba(168,85,247,.6);
            background:rgba(5,8,24,.92);
            color:var(--violet-bright);
            font-size:11px;font-weight:900;
            box-shadow:0 0 10px rgba(168,85,247,.4);
            animation:float-node 3s ease-in-out infinite;
        }
        .map-node:nth-child(2){ animation-delay:.6s; }
        .map-node:nth-child(3){ animation-delay:1.2s; }

        .bus-pin {
            width:38px;height:38px;
            color:#04050f;
            background:linear-gradient(135deg,var(--cyan),var(--violet));
            box-shadow:0 0 28px rgba(34,211,238,.7),0 0 56px rgba(168,85,247,.4);
            font-size:11px;font-weight:900;
            animation:pulse-glow 1.6s ease-in-out infinite;
        }

        .map-status {
            position: absolute;
            left:16px;right:16px;bottom:16px;
            display: grid;
            grid-template-columns: minmax(0,1fr) auto;
            gap: 12px;
            align-items: center;
            padding: 14px;
            border: 1px solid rgba(168,85,247,.25);
            border-radius: 9px;
            background: rgba(4,5,15,.88);
            backdrop-filter: blur(14px);
        }
        .map-status strong { display:block;font-size:14px; }

        .grid-2      { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
        .stat-grid   { display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px; }
        .driver-info { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px; }

        .stat-box { padding:14px; }
        .stat-box span   { display:block;color:var(--muted);font-size:11px;font-weight:800;text-transform:uppercase; }
        .stat-box strong { display:block;margin-top:6px;font-size:22px; }

        .empty-state { display:grid;gap:8px;place-items:start;padding:18px;color:var(--muted);min-height:120px; }
        .empty-state strong { color:var(--text); }

        .balance-card, .qr-panel, .bus-asset {
            border: 1px solid rgba(168,85,247,.28);
            border-radius: 9px;
            background: rgba(4,5,15,.94);
        }
        .balance-card { padding:16px; }
        .balance-row  { display:flex;align-items:flex-start;justify-content:space-between;gap:16px; }
        .balance-card strong { display:block;margin-top:4px;font-size:32px; }

        .qr-panel { display:grid;gap:14px;justify-items:center;padding:16px;border-color:rgba(168,85,247,.32); }

        .qr-frame {
            display: grid;
            width:180px;height:180px;
            place-items: center;
            padding: 18px;
            background: transparent;
            overflow: hidden;
        }
        .qr-frame img, .qr-frame svg { width:100%;height:100%;object-fit:contain; }

        .active-route { display:grid;grid-template-columns:180px minmax(0,1fr);gap:16px;align-items:center; }

        .bus-asset { position:relative;height:180px;overflow:hidden; }
        .bus-asset::before {
            content: attr(data-bus-code);
            position: absolute;
            left:50%;top:50%;
            transform: translate(-50%,-50%);
            display: grid;place-items:center;
            width:94px;height:56px;
            border:2px solid rgba(168,85,247,.72);
            border-radius:9px;
            color:var(--violet-bright);
            font-weight:900;
            box-shadow:0 0 32px rgba(168,85,247,.22);
        }

        .route-vector { display:grid;gap:14px;margin-top:12px;padding-left:7px;border-left:1px dashed rgba(168,85,247,.3); }
        .vector-step  { display:flex;gap:10px;align-items:flex-start; }
        .vector-step::before {
            content:"";
            flex:0 0 12px;width:12px;height:12px;
            margin-left:-13px;margin-top:4px;
            border:2px solid var(--violet);
            border-radius:99px;
            background:var(--panel);
        }

        .stars { display:flex;gap:6px; }
        .star-btn {
            display:grid;width:34px;height:34px;place-items:center;
            border:1px solid rgba(244,191,85,.26);border-radius:8px;
            color:rgba(244,191,85,.45);background:rgba(244,191,85,.06);
            font-size:20px;line-height:1;transition:all .18s ease;
        }
        .star-btn:hover { border-color:rgba(244,191,85,.6);box-shadow:0 0 12px rgba(244,191,85,.4); }
        .star-btn.is-selected { color:var(--amber);background:rgba(244,191,85,.18);box-shadow:0 0 10px rgba(244,191,85,.45); }

        .status-badge {
            display:inline-flex;align-items:center;justify-content:center;
            min-height:25px;padding:0 8px;border-radius:7px;
            color:var(--lime);background:rgba(134,239,172,.1);
            font-size:11px;font-weight:900;
        }

        .mini-label { color:var(--muted);font-size:12px; }
        .meta       { color:var(--muted);font-size:12px; }

        a { color:inherit;text-decoration:none; }
        button, input, textarea { font:inherit; }
        button { cursor:pointer; }

        @media (max-width:1180px) {
            .app-shell,.content-grid,.route-layout,.wallet-layout { grid-template-columns:1fr; }
            .sidebar { position:static;height:auto; }
            .sidebar-footer { margin-top:0; }
            .nav-list,.utility-list { grid-template-columns:repeat(4,minmax(0,1fr)); }
        }
        @media (max-width:760px) {
            .main,.sidebar { padding:16px; }
            .topbar,.surface-header,.balance-row { align-items:stretch;flex-direction:column; }
            .page-title h1 { font-size:26px; }
            .nav-list,.utility-list,.grid-2,.stat-grid,.driver-info,.active-route { grid-template-columns:1fr; }
            .nav-link { min-height:40px; }
            .map-wrap { min-height:420px; }
            .card-row { align-items:stretch;flex-direction:column; }
            .eta,.amount { text-align:left; }
        }
    </style>
</head>
<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    <canvas id="bg-canvas"></canvas>

    <div class="app-shell">
        <aside class="sidebar" aria-label="Navigasi user BusFlow">
            <a class="brand" href="{{ route('user.routes') }}">
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
                    <span>Transit deck user</span>
                </span>
            </a>

            <div class="operator-card">
                <span>Transit Deck</span>
                <strong>Terminal & Status</strong>
                <span class="status-dot" style="margin-top:10px;">Active</span>
            </div>

            <nav>
                <ul class="nav-list">
                    <li>
                        <a class="nav-link {{ request()->routeIs('user.routes') ? 'is-active' : '' }}" href="{{ route('user.routes') }}">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <circle cx="3" cy="12" r="1.8" stroke="currentColor" stroke-width="1.3"/>
                                    <circle cx="12" cy="3" r="1.8" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M3 10.2C3 7 6.5 5.5 7.5 5.5C8.5 5.5 12 4.5 12 4.8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                                    <path d="M4.5 7.5h6" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-dasharray="1.5 1.5"/>
                                </svg>
                            </span>
                            <span>Routes</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('user.payments') ? 'is-active' : '' }}" href="{{ route('user.payments') }}">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <rect x="1.5" y="3.5" width="12" height="8" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M1.5 6.5h12" stroke="currentColor" stroke-width="1.3"/>
                                    <rect x="3" y="8.5" width="3" height="1.5" rx=".5" fill="currentColor"/>
                                </svg>
                            </span>
                            <span>Payments</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('user.my-trip') ? 'is-active' : '' }}" href="{{ route('user.my-trip') }}">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <rect x="2" y="5" width="11" height="7" rx="2" stroke="currentColor" stroke-width="1.3"/>
                                    <circle cx="5" cy="12" r="1.4" stroke="currentColor" stroke-width="1.2"/>
                                    <circle cx="10" cy="12" r="1.4" stroke="currentColor" stroke-width="1.2"/>
                                    <path d="M5 5V3.5a2.5 2.5 0 015 0V5" stroke="currentColor" stroke-width="1.2"/>
                                    <path d="M2 8h11" stroke="currentColor" stroke-width="1" stroke-dasharray="1.5 1.5"/>
                                </svg>
                            </span>
                            <span>My Trip</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('user.favourites') ? 'is-active' : '' }}" href="{{ route('user.favourites') }}">
                            <span class="nav-icon">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <path d="M7.5 12.5L3.2 8.4a3 3 0 014.3-4.2 3 3 0 014.3 4.2z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span>Favourites</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <a class="recharge-btn" href="{{ route('user.payments') }}">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                        <path d="M7 1L2 7.5h4.5L5.5 12 11 5.5H6.5L7 1z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                    </svg>
                    Recharge Card
                </a>
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
                        <a class="nav-link {{ request()->routeIs('user.settings') ? 'is-active' : '' }}" href="{{ route('user.settings') }}">
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
                    <h1>@yield('page_title', 'BusFlow User')</h1>
                    <p>@yield('page_description', 'Kelola perjalanan, pembayaran, armada, dan favorit dari halaman user BusFlow.')</p>
                </div>
                <div class="top-actions">
                    @yield('top_actions')
                </div>
            </div>
            @yield('content')
        </main>
    </div>

    <script>
        /* ── Rating buttons ── */
        document.querySelectorAll('[data-rating-button]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const form = btn.closest('[data-rating-form]');
                const inp  = form?.querySelector('[data-rating-value]');
                const val  = Number(btn.dataset.ratingButton);
                if (inp) inp.value = val;
                form?.querySelectorAll('[data-rating-button]').forEach((b) => {
                    const sel = Number(b.dataset.ratingButton) <= val;
                    b.classList.toggle('is-selected', sel);
                    b.setAttribute('aria-pressed', sel ? 'true' : 'false');
                });
            });
        });

        /* ── Grid dot wave glow ── */
        (function () {
            const canvas = document.getElementById('bg-canvas');
            const ctx    = canvas.getContext('2d');
            const GAP    = 36;
            let W, H, cols, rows, dots = [];
            let mx = -9999, my = -9999;

            document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
            document.addEventListener('mouseleave', () => { mx = -9999; my = -9999; });

            /* white → violet-bright → violet → blue → cyan → white */
            const STOPS = [
                [255, 255, 255],
                [220, 190, 255],
                [192, 132, 252],
                [168,  85, 247],
                [ 99, 140, 255],
                [ 59, 130, 246],
                [ 34, 211, 238],
                [180, 230, 255],
                [255, 255, 255],
            ];

            function lerpC(a, b, t) {
                return [
                    Math.round(a[0] + (b[0]-a[0]) * t),
                    Math.round(a[1] + (b[1]-a[1]) * t),
                    Math.round(a[2] + (b[2]-a[2]) * t),
                ];
            }

            function palColor(t) {
                t = ((t % 1) + 1) % 1;
                const s = t * (STOPS.length - 1);
                const i = Math.floor(s);
                return lerpC(STOPS[i], STOPS[Math.min(i + 1, STOPS.length - 1)], s - i);
            }

            /*
             * Wave trains:
             * type  'h'  = horizontal  (front sweeps across columns)
             * type  'v'  = vertical    (front sweeps across rows)
             * type  'd1' = diagonal ↘  (front sweeps col+row)
             * type  'd2' = diagonal ↙  (front sweeps col-row)
             * speed = grid-cells per second (negative = reverse)
             * width = gaussian sigma in grid-cells
             * cOff  = color palette offset
             */
            const WAVES = [
                { type:'h',  speed:  9, width: 4.5, cOff: 0.00 },
                { type:'v',  speed:  6, width: 4.0, cOff: 0.28 },
                { type:'d1', speed:  7, width: 5.5, cOff: 0.55 },
                { type:'d2', speed:  5, width: 5.0, cOff: 0.75 },
                { type:'h',  speed: -8, width: 3.5, cOff: 0.40 },
                { type:'v',  speed: -5, width: 4.0, cOff: 0.15 },
                { type:'d1', speed: -6, width: 4.5, cOff: 0.65 },
                { type:'d2', speed:  4, width: 3.8, cOff: 0.88 },
            ];

            const PERIOD = 90; /* grid-cells before wave repeats */

            function waveBright(dot, wave, t) {
                let idx;
                if      (wave.type === 'h')  idx = dot.c;
                else if (wave.type === 'v')  idx = dot.r;
                else if (wave.type === 'd1') idx = dot.c + dot.r;
                else                          idx = dot.c - dot.r;

                const front = (t * wave.speed) % PERIOD;
                let d = ((idx - front) % PERIOD + PERIOD) % PERIOD;
                if (d > PERIOD / 2) d = PERIOD - d;
                return Math.exp(-(d * d) / (2 * wave.width * wave.width));
            }

            function build() {
                W = canvas.width  = window.innerWidth;
                H = canvas.height = window.innerHeight;
                cols = Math.ceil(W / GAP) + 2;
                rows = Math.ceil(H / GAP) + 2;
                dots = [];
                for (let r = 0; r < rows; r++)
                    for (let c = 0; c < cols; c++)
                        dots.push({ x: c * GAP, y: r * GAP, c, r });
            }

            function frame(ts) {
                const t = ts * 0.001; /* seconds */
                ctx.clearRect(0, 0, W, H);

                dots.forEach(d => {
                    /* sum wave contributions */
                    let bright = 0, colorAcc = 0;
                    WAVES.forEach(w => {
                        const b = waveBright(d, w, t);
                        bright    += b;
                        colorAcc  += b * w.cOff;
                    });
                    bright = Math.min(bright, 1.4);

                    /* cursor proximity */
                    const dx   = d.x - mx, dy = d.y - my;
                    const dist = Math.sqrt(dx*dx + dy*dy);
                    const prox = Math.max(0, 1 - dist / 150);
                    bright += prox * 1.6;

                    /* always-on dim base flicker */
                    const base = 0.055 + 0.03 * Math.sin(ts * 0.0009 + d.c * 0.28 + d.r * 0.42);
                    bright = Math.max(bright, base);

                    /* color */
                    const colorT = bright > 0.15
                        ? ((colorAcc / Math.max(bright, 0.01)) + t * 0.06) % 1
                        : (t * 0.035 + d.c * 0.018 + d.r * 0.013) % 1;
                    const [r, g, b] = palColor(colorT);

                    const radius = 1.1 + bright * 2.6 + prox * 2.8;
                    const alpha  = Math.min(0.07 + bright * 0.78 + prox * 0.65, 1.0);

                    if (bright > 0.25 || prox > 0.08) {
                        ctx.shadowColor = `rgba(${r},${g},${b},${Math.min(alpha, 1)})`;
                        ctx.shadowBlur  = 3 + bright * 14 + prox * 18;
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
