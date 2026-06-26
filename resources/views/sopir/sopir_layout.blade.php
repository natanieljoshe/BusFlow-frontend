<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BusFlow - Driver')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    <style>
        :root {
            --lime: #86efac;
            --violet: #a855f7;
            --violet-bright: #c084fc;
            --cyan: #22d3ee;
            --amber: #f4bf55;
            --soft: #b8c8e8;
            --muted: #7a8aaa;
            --text: #eef4ff;
            --panel: #090d1a;
            --shadow: 0 24px 80px rgba(0,0,0,.6);
        }
        @keyframes pulse-glow  { 0%,100%{opacity:.45} 50%{opacity:1} }
        @keyframes drift       { 0%{transform:translate(0,0) scale(1)} 33%{transform:translate(28px,-18px) scale(1.06)} 66%{transform:translate(-18px,16px) scale(.96)} 100%{transform:translate(0,0) scale(1)} }
        @keyframes scanline    { 0%{background-position:0 0} 100%{background-position:0 100vh} }
        @keyframes slide-left  { from{opacity:0;transform:translateX(-28px)} to{opacity:1;transform:translateX(0)} }
        @keyframes slide-up    { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
        @keyframes brand-pulse { 0%,100%{box-shadow:0 0 12px rgba(34,211,238,.4),0 0 28px rgba(168,85,247,.3)} 50%{box-shadow:0 0 22px rgba(34,211,238,.8),0 0 48px rgba(168,85,247,.6)} }

        body::after {
            content:"";position:fixed;inset:0;pointer-events:none;z-index:0;
            background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(168,85,247,.014) 2px,rgba(168,85,247,.014) 4px);
            animation:scanline 10s linear infinite;
        }
        .bg-orb-1 { width:520px;height:520px;top:-120px;left:-100px;background:radial-gradient(circle,rgba(168,85,247,.22) 0%,transparent 70%);animation:drift 18s ease-in-out infinite; }
        .bg-orb-2 { width:440px;height:440px;bottom:-80px;right:-80px;background:radial-gradient(circle,rgba(34,211,238,.16) 0%,transparent 70%);animation:drift 22s ease-in-out infinite reverse; }
        .bg-orb-3 { width:300px;height:300px;top:45%;left:55%;background:radial-gradient(circle,rgba(59,130,246,.14) 0%,transparent 70%);animation:drift 28s ease-in-out infinite 4s; }
        .brand-mark { animation:brand-pulse 2.8s ease-in-out infinite; }
        .sidebar    { animation:slide-left .55s ease both; }
        .topbar     { animation:slide-up .5s ease both; }
        .status-dot::before { content:"";width:7px;height:7px;border-radius:99px;background:var(--lime);box-shadow:0 0 18px var(--lime);animation:pulse-glow 1.8s ease-in-out infinite; }
        .nav-link:hover .nav-icon, .nav-link.is-active .nav-icon { background:rgba(168,85,247,.28);box-shadow:0 0 12px rgba(168,85,247,.5); }
        .nav-link.is-active,.nav-link:hover { border-color:rgba(168,85,247,.55)!important;background:linear-gradient(90deg,rgba(168,85,247,.15),rgba(34,211,238,.07))!important;color:#fff!important;box-shadow:0 0 18px rgba(168,85,247,.22),inset 0 0 14px rgba(168,85,247,.07); }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen text-[#eef4ff] font-[Instrument_Sans,ui-sans-serif,system-ui,sans-serif] bg-[#04050f] overflow-x-hidden relative">
    <div class="bg-orb bg-orb-1 fixed rounded-full blur-[90px] pointer-events-none z-0"></div>
    <div class="bg-orb bg-orb-2 fixed rounded-full blur-[90px] pointer-events-none z-0"></div>
    <div class="bg-orb bg-orb-3 fixed rounded-full blur-[90px] pointer-events-none z-0"></div>

    <div class="grid grid-cols-[264px_minmax(0,1fr)] min-h-screen relative z-[1] max-[1180px]:grid-cols-1">
        @include('components.navbar')

        <main class="min-w-0 px-7 py-6 pb-8 max-[760px]:p-4">
            <div class="topbar flex justify-between items-start gap-5 pb-6 pt-2.5 max-[760px]:flex-col max-[760px]:items-stretch">
                <div>
                    <h1 class="text-[30px] leading-[1.05] max-[760px]:text-[26px]">@yield('page_title', 'Driver Page')</h1>
                    <p class="text-[#7a8aaa] mt-2 max-w-[700px] leading-relaxed">@yield('page_description', 'Monitor assignments, trip details, and your driving history from the driver panel.')</p>
                </div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    @yield('top_actions')
                </div>
            </div>
            @yield('content')
        </main>
    </div>
</body>
</html>
