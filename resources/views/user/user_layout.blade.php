<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BusFlow - User')</title>
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
            --rose: #fb7185;
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
        @keyframes btn-scan    { 0%{transform:translateX(-100%) skewX(-12deg)} 100%{transform:translateX(220%) skewX(-12deg)} }
        @keyframes recharge-pulse { 0%,100%{box-shadow:0 0 8px rgba(168,85,247,.3),inset 0 0 8px rgba(168,85,247,.08)} 50%{box-shadow:0 0 18px rgba(168,85,247,.55),inset 0 0 14px rgba(34,211,238,.1)} }
        @keyframes float-node  { 0%,100%{transform:translate(-50%,-50%) scale(1)} 50%{transform:translate(-50%,-50%) scale(1.18)} }
        @keyframes ribbon-shift{ 0%{opacity:.7;filter:hue-rotate(0deg)} 100%{opacity:.95;filter:hue-rotate(30deg)} }

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
        .main       { animation:slide-up .5s ease .15s both; }
        .status-dot::before { content:"";width:7px;height:7px;border-radius:99px;background:var(--lime);box-shadow:0 0 18px var(--lime);animation:pulse-glow 1.8s ease-in-out infinite; }
        .nav-link:hover .nav-icon,.nav-link.is-active .nav-icon { background:rgba(168,85,247,.28);box-shadow:0 0 12px rgba(168,85,247,.5); }
        .nav-link.is-active,.nav-link:hover { border-color:rgba(168,85,247,.55)!important;background:linear-gradient(90deg,rgba(168,85,247,.15),rgba(34,211,238,.07))!important;color:#fff!important;box-shadow:0 0 18px rgba(168,85,247,.22),inset 0 0 14px rgba(168,85,247,.07); }
        .recharge-btn { animation:recharge-pulse 3s ease-in-out infinite; }
        .recharge-btn::before,.primary-btn::before { content:"";position:absolute;top:0;bottom:0;width:40%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.18),transparent);transform:translateX(-100%) skewX(-12deg);pointer-events:none; }
        .recharge-btn:hover::before,.primary-btn:hover::before { animation:btn-scan .55s ease forwards; }
        .recharge-btn:hover { background:rgba(168,85,247,.18)!important;border-color:rgba(168,85,247,.9)!important;color:#fff!important;box-shadow:0 0 20px rgba(168,85,247,.4),inset 0 0 16px rgba(168,85,247,.1);animation:none; }
        .map-ribbon { animation:ribbon-shift 4s ease-in-out infinite alternate; }
        .map-node   { animation:float-node 3s ease-in-out infinite; }
        .map-node:nth-child(2){ animation-delay:.6s; }
        .map-node:nth-child(3){ animation-delay:1.2s; }
        /* progress bar, vector-step, bus-asset — dipakai di child views */
        .progress>span { display:block;width:var(--level,0%);height:100%;border-radius:inherit;background:linear-gradient(90deg,var(--violet),var(--cyan)); }
        .vector-step::before { content:"";flex:0 0 12px;width:12px;height:12px;margin-left:-13px;margin-top:4px;border:2px solid var(--violet);border-radius:99px;background:var(--panel); }
        .bus-asset::before { content:attr(data-bus-code);position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);display:grid;place-items:center;width:94px;height:56px;border:2px solid rgba(168,85,247,.72);border-radius:9px;color:var(--violet-bright);font-weight:900;box-shadow:0 0 32px rgba(168,85,247,.22); }
        .star-btn.is-selected { color:var(--amber);background:rgba(244,191,85,.18);box-shadow:0 0 10px rgba(244,191,85,.45); }
    </style>
</head>
<body class="min-h-screen text-[#eef4ff] font-[Instrument_Sans,ui-sans-serif,system-ui,sans-serif] bg-[#04050f] overflow-x-hidden relative">
    <div class="bg-orb-1 fixed rounded-full blur-[90px] pointer-events-none z-0"></div>
    <div class="bg-orb-2 fixed rounded-full blur-[90px] pointer-events-none z-0"></div>
    <div class="bg-orb-3 fixed rounded-full blur-[90px] pointer-events-none z-0"></div>
    <canvas id="bg-canvas" class="fixed inset-0 w-full h-full z-0 pointer-events-none"></canvas>

    <div class="grid grid-cols-[264px_minmax(0,1fr)] min-h-screen relative z-[1] max-[1180px]:grid-cols-1">
        <aside class="sidebar sticky top-0 h-screen flex flex-col gap-6 px-[18px] py-6 bg-[rgba(4,5,15,0.84)] border-r border-violet-500/[0.22] backdrop-blur-2xl shadow-[2px_0_40px_rgba(168,85,247,0.08)] max-[1180px]:static max-[1180px]:h-auto" aria-label="Navigasi user BusFlow">

            <a class="flex items-center gap-3 px-2 no-underline text-[#eef4ff]" href="{{ route('user.routes') }}">
                <span class="brand-mark w-[42px] h-[42px] grid place-items-center rounded-[10px] border border-cyan-400/50 bg-gradient-to-br from-cyan-400/15 to-violet-500/15 shrink-0">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                        <rect x="2" y="6" width="18" height="11" rx="3" stroke="#22d3ee" stroke-width="1.5"/>
                        <circle cx="6" cy="17" r="2" fill="#a855f7"/>
                        <circle cx="16" cy="17" r="2" fill="#a855f7"/>
                        <path d="M2 10h18" stroke="#22d3ee" stroke-width="1" stroke-dasharray="2 2"/>
                        <rect x="8" y="3" width="6" height="4" rx="1.5" stroke="#c084fc" stroke-width="1.2"/>
                    </svg>
                </span>
                <span>
                    <strong class="block text-lg">BusFlow</strong>
                    <span class="text-[#7a8aaa] text-xs">Transit deck user</span>
                </span>
            </a>

            <div class="p-[14px] border border-violet-500/20 rounded-[10px] bg-[rgba(4,5,15,0.95)]">
                <span class="text-[#7a8aaa] text-xs">Transit Deck</span>
                <strong class="block mt-1 text-sm">Terminal & Status</strong>
                <span class="status-dot inline-flex items-center gap-[7px] text-[#86efac] text-xs font-bold uppercase mt-2.5">Active</span>
            </div>

            <nav>
                <ul class="grid gap-1.5 list-none">
                    <li>
                        <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                            {{ request()->routeIs('user.routes') ? 'is-active' : '' }}" href="{{ route('user.routes') }}">
                            <span class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><circle cx="3" cy="12" r="1.8" stroke="currentColor" stroke-width="1.3"/><circle cx="12" cy="3" r="1.8" stroke="currentColor" stroke-width="1.3"/><path d="M3 10.2C3 7 6.5 5.5 7.5 5.5C8.5 5.5 12 4.5 12 4.8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M4.5 7.5h6" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-dasharray="1.5 1.5"/></svg>
                            </span>
                            <span>Routes</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                            {{ request()->routeIs('user.payments') ? 'is-active' : '' }}" href="{{ route('user.payments') }}">
                            <span class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><rect x="1.5" y="3.5" width="12" height="8" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M1.5 6.5h12" stroke="currentColor" stroke-width="1.3"/><rect x="3" y="8.5" width="3" height="1.5" rx=".5" fill="currentColor"/></svg>
                            </span>
                            <span>Payments</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                            {{ request()->routeIs('user.my-trip') ? 'is-active' : '' }}" href="{{ route('user.my-trip') }}">
                            <span class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><rect x="2" y="5" width="11" height="7" rx="2" stroke="currentColor" stroke-width="1.3"/><circle cx="5" cy="12" r="1.4" stroke="currentColor" stroke-width="1.2"/><circle cx="10" cy="12" r="1.4" stroke="currentColor" stroke-width="1.2"/><path d="M5 5V3.5a2.5 2.5 0 015 0V5" stroke="currentColor" stroke-width="1.2"/><path d="M2 8h11" stroke="currentColor" stroke-width="1" stroke-dasharray="1.5 1.5"/></svg>
                            </span>
                            <span>My Trip</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                            {{ request()->routeIs('user.favourites') ? 'is-active' : '' }}" href="{{ route('user.favourites') }}">
                            <span class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M7.5 12.5L3.2 8.4a3 3 0 014.3-4.2 3 3 0 014.3 4.2z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                            </span>
                            <span>Favourites</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="mt-auto grid gap-3.5">
                <a class="recharge-btn relative inline-flex items-center justify-center gap-[7px] w-full min-h-[40px] px-4 rounded-lg text-xs font-extrabold uppercase tracking-wider overflow-hidden transition-all duration-[250ms] no-underline bg-violet-500/[0.08] border border-violet-500/[0.45] text-[#c084fc]" href="{{ route('user.payments') }}">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M7 1L2 7.5h4.5L5.5 12 11 5.5H6.5L7 1z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>
                    Recharge Card
                </a>
                <ul class="grid gap-1.5 list-none">
                    <li>
                        <a class="nav-link flex items-center gap-2.5 min-h-[42px] px-3 border border-transparent rounded-[10px] text-[#b8c8e8] text-sm font-bold no-underline transition-all duration-[220ms]
                            {{ request()->routeIs('user.settings') ? 'is-active' : '' }}" href="{{ route('user.settings') }}">
                            <span class="nav-icon w-[26px] h-[26px] grid place-items-center bg-violet-500/10 rounded-[7px] shrink-0 transition-all duration-[220ms] text-[#c084fc]">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><circle cx="7.5" cy="7.5" r="2" stroke="currentColor" stroke-width="1.3"/><path d="M7.5 1.5v1.2M7.5 12.3v1.2M1.5 7.5h1.2M12.3 7.5h1.2M3.4 3.4l.85.85M10.75 10.75l.85.85M3.4 11.6l.85-.85M10.75 4.25l.85-.85" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                            </span>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="main min-w-0 p-6 max-[760px]:p-4">
            <div class="flex items-center justify-between gap-[18px] mb-[18px] max-[760px]:flex-col max-[760px]:items-stretch">
                <div>
                    <h1 class="text-[30px] leading-[1.1] max-[760px]:text-[26px]">@yield('page_title', 'BusFlow User')</h1>
                    <p class="max-w-[760px] mt-2 text-[#7a8aaa] text-sm leading-relaxed">@yield('page_description', 'Kelola perjalanan, pembayaran, armada, dan favorit dari halaman user BusFlow.')</p>
                </div>
                <div class="flex items-center gap-2.5 flex-wrap">
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
                canvas.style.display = (enabled !== false && localStorage.getItem('bf_bg') !== 'off') ? '' : 'none';
            }
            applyBgEffect(localStorage.getItem('bf_bg') !== 'off');
            window.addEventListener('storage', e => { if (e.key === 'bf_bg') applyBgEffect(e.newValue !== 'off'); });
        })();

        document.querySelectorAll('[data-rating-button]').forEach(btn => {
            btn.addEventListener('click', () => {
                const form = btn.closest('[data-rating-form]');
                const inp  = form?.querySelector('[data-rating-value]');
                const val  = Number(btn.dataset.ratingButton);
                if (inp) inp.value = val;
                form?.querySelectorAll('[data-rating-button]').forEach(b => {
                    const sel = Number(b.dataset.ratingButton) <= val;
                    b.classList.toggle('is-selected', sel);
                    b.setAttribute('aria-pressed', sel ? 'true' : 'false');
                });
            });
        });

        (function () {
            const canvas = document.getElementById('bg-canvas');
            const ctx    = canvas.getContext('2d');
            const GAP    = 36;
            let W, H, cols, rows, dots = [], mx = -9999, my = -9999;

            document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
            document.addEventListener('mouseleave', () => { mx = -9999; my = -9999; });

            const STOPS = [[255,255,255],[220,190,255],[192,132,252],[168,85,247],[99,140,255],[59,130,246],[34,211,238],[180,230,255],[255,255,255]];
            const WAVES = [
                {type:'h',speed:9,width:4.5,cOff:0.00},{type:'v',speed:6,width:4.0,cOff:0.28},
                {type:'d1',speed:7,width:5.5,cOff:0.55},{type:'d2',speed:5,width:5.0,cOff:0.75},
                {type:'h',speed:-8,width:3.5,cOff:0.40},{type:'v',speed:-5,width:4.0,cOff:0.15},
                {type:'d1',speed:-6,width:4.5,cOff:0.65},{type:'d2',speed:4,width:3.8,cOff:0.88},
            ];
            const PERIOD = 90;

            function lerpC(a,b,t){return[Math.round(a[0]+(b[0]-a[0])*t),Math.round(a[1]+(b[1]-a[1])*t),Math.round(a[2]+(b[2]-a[2])*t)];}
            function palColor(t){t=((t%1)+1)%1;const s=t*(STOPS.length-1),i=Math.floor(s);return lerpC(STOPS[i],STOPS[Math.min(i+1,STOPS.length-1)],s-i);}
            function waveBright(dot,wave,t){
                let idx=wave.type==='h'?dot.c:wave.type==='v'?dot.r:wave.type==='d1'?dot.c+dot.r:dot.c-dot.r;
                const front=(t*wave.speed)%PERIOD;let d=((idx-front)%PERIOD+PERIOD)%PERIOD;
                if(d>PERIOD/2)d=PERIOD-d;return Math.exp(-(d*d)/(2*wave.width*wave.width));
            }
            function build(){
                W=canvas.width=window.innerWidth;H=canvas.height=window.innerHeight;
                cols=Math.ceil(W/GAP)+2;rows=Math.ceil(H/GAP)+2;dots=[];
                for(let r=0;r<rows;r++)for(let c=0;c<cols;c++)dots.push({x:c*GAP,y:r*GAP,c,r});
            }
            function frame(ts){
                const t=ts*0.001;ctx.clearRect(0,0,W,H);
                dots.forEach(d=>{
                    let bright=0,colorAcc=0;
                    WAVES.forEach(w=>{const b=waveBright(d,w,t);bright+=b;colorAcc+=b*w.cOff;});
                    bright=Math.min(bright,1.4);
                    const dx=d.x-mx,dy=d.y-my,prox=Math.max(0,1-Math.sqrt(dx*dx+dy*dy)/150);
                    bright+=prox*1.6;
                    bright=Math.max(bright,0.055+0.03*Math.sin(ts*0.0009+d.c*0.28+d.r*0.42));
                    const colorT=bright>0.15?((colorAcc/Math.max(bright,0.01))+t*0.06)%1:(t*0.035+d.c*0.018+d.r*0.013)%1;
                    const[r,g,b]=palColor(colorT);
                    const radius=1.1+bright*2.6+prox*2.8,alpha=Math.min(0.07+bright*0.78+prox*0.65,1.0);
                    if(bright>0.25||prox>0.08){ctx.shadowColor=`rgba(${r},${g},${b},${Math.min(alpha,1)})`;ctx.shadowBlur=3+bright*14+prox*18;}
                    else ctx.shadowBlur=0;
                    ctx.beginPath();ctx.arc(d.x,d.y,radius,0,Math.PI*2);ctx.fillStyle=`rgba(${r},${g},${b},${alpha})`;ctx.fill();ctx.shadowBlur=0;
                });
                requestAnimationFrame(frame);
            }
            build();window.addEventListener('resize',build);requestAnimationFrame(frame);
        })();
    </script>
</body>
</html>
