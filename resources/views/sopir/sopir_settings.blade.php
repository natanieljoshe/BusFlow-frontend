@extends('sopir.sopir_layout')

@section('title', 'Settings - BusFlow')
@section('page_title', 'Settings')
@section('page_description', 'Configure audio preferences, background effects, and display settings for the driver panel.')

@section('content')
<div class="grid gap-[18px]">
    <div class="grid gap-3 p-4 border border-violet-500/[0.16] rounded-[10px] bg-[rgba(4,5,15,0.92)]">
        <div class="flex items-center justify-between">
            <div class="grid gap-1">
                <strong class="text-sm" data-i18n="audio_title">Background Music</strong>
                <span class="text-xs text-[#7a8aaa]" data-i18n="audio_desc">Play background music while using BusFlow</span>
            </div>
            <input type="checkbox" id="audio-toggle"
                class="appearance-none w-6 h-6 border-2 border-violet-500/40 rounded-md bg-[rgba(4,5,15,0.8)] cursor-pointer relative transition-all duration-[250ms] shrink-0
                       hover:border-violet-500/60 hover:shadow-[0_0_8px_rgba(168,85,247,0.3)]
                       checked:bg-gradient-to-br checked:from-[#a855f7] checked:to-[#c084fc] checked:border-[#a855f7] checked:shadow-[0_0_12px_rgba(168,85,247,0.5)]">
        </div>

        <ul class="grid gap-2 list-none" id="track-list">
            <li class="track-item flex items-center gap-3 p-[10px_12px] border border-violet-500/[0.12] rounded-lg cursor-pointer transition-all duration-200 hover:border-violet-500/50 hover:bg-violet-500/[0.08] hover:shadow-[0_0_12px_rgba(168,85,247,0.15)]" data-src="" data-index="0">
                <span class="track-icon w-8 h-8 grid place-items-center rounded-lg bg-violet-500/[0.15] text-[#c084fc] shrink-0">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 11V4l9-2v7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><circle cx="3" cy="11" r="2" stroke="currentColor" stroke-width="1.3"/><circle cx="12" cy="9" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                </span>
                <span class="text-[13px] font-bold flex-1">Ambient City</span>
                <span class="text-[11px] text-[#7a8aaa]">Lo-fi</span>
            </li>
            <li class="track-item flex items-center gap-3 p-[10px_12px] border border-violet-500/[0.12] rounded-lg cursor-pointer transition-all duration-200 hover:border-violet-500/50 hover:bg-violet-500/[0.08] hover:shadow-[0_0_12px_rgba(168,85,247,0.15)]" data-src="" data-index="1">
                <span class="track-icon w-8 h-8 grid place-items-center rounded-lg bg-violet-500/[0.15] text-[#c084fc] shrink-0">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 11V4l9-2v7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><circle cx="3" cy="11" r="2" stroke="currentColor" stroke-width="1.3"/><circle cx="12" cy="9" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                </span>
                <span class="text-[13px] font-bold flex-1">Neon Drive</span>
                <span class="text-[11px] text-[#7a8aaa]">Synthwave</span>
            </li>
        </ul>

        <div class="text-[11px] text-[#c084fc] font-bold min-h-[16px]" id="now-playing">
            <span data-i18n="no_track">No track playing</span>
        </div>

        <div class="flex items-center gap-[10px]">
            <button type="button" id="btn-prev" title="Previous"
                class="ctrl-btn w-9 h-9 grid place-items-center border border-violet-500/30 rounded-lg bg-violet-500/[0.08] text-[#c084fc] cursor-pointer transition-all duration-200 shrink-0 hover:bg-violet-500/20 hover:shadow-[0_0_10px_rgba(168,85,247,0.35)]">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 3v8M12 3L5 7l7 4V3z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <button type="button" id="btn-play" title="Play/Pause"
                class="ctrl-btn w-9 h-9 grid place-items-center border border-violet-500/30 rounded-lg bg-violet-500/[0.08] text-[#c084fc] cursor-pointer transition-all duration-200 shrink-0 hover:bg-violet-500/20 hover:shadow-[0_0_10px_rgba(168,85,247,0.35)]">
                <svg id="icon-play" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M4 2.5l8 4.5-8 4.5V2.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                <svg id="icon-pause" width="14" height="14" viewBox="0 0 14 14" fill="none" style="display:none"><path d="M4 3v8M10 3v8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </button>
            <button type="button" id="btn-next" title="Next"
                class="ctrl-btn w-9 h-9 grid place-items-center border border-violet-500/30 rounded-lg bg-violet-500/[0.08] text-[#c084fc] cursor-pointer transition-all duration-200 shrink-0 hover:bg-violet-500/20 hover:shadow-[0_0_10px_rgba(168,85,247,0.35)]">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M12 3v8M2 3l7 4-7 4V3z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="flex items-center gap-2 flex-1">
                <svg class="text-[#7a8aaa] shrink-0" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 5h2l3-3v10L4 9H2V5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M10 4.5a4 4 0 010 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                <input type="range" id="vol-slider" min="0" max="100" value="70"
                    class="vol-slider flex-1 h-1 rounded-full appearance-none bg-violet-500/20 outline-none cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-3.5 [&::-webkit-slider-thumb]:h-3.5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-[#c084fc] [&::-webkit-slider-thumb]:shadow-[0_0_6px_rgba(168,85,247,0.7)]">
            </div>
        </div>

        <audio id="bg-audio" loop></audio>
    </div>

    <div class="flex items-center justify-between gap-4 p-4 border border-violet-500/[0.16] rounded-[10px] bg-[rgba(4,5,15,0.92)] transition-all duration-[220ms] hover:border-violet-500/40 hover:shadow-[0_0_18px_rgba(168,85,247,0.1)]">
        <div class="grid gap-1">
            <strong class="text-sm" data-i18n="bg_title">Background Effect</strong>
            <span class="text-xs text-[#7a8aaa]" data-i18n="bg_desc">Particle animation and color wave background effect</span>
        </div>
        <input type="checkbox" id="bg-toggle" checked
            class="appearance-none w-6 h-6 border-2 border-violet-500/40 rounded-md bg-[rgba(4,5,15,0.8)] cursor-pointer relative transition-all duration-[250ms] shrink-0
                   hover:border-violet-500/60 hover:shadow-[0_0_8px_rgba(168,85,247,0.3)]
                   checked:bg-gradient-to-br checked:from-[#a855f7] checked:to-[#c084fc] checked:border-[#a855f7] checked:shadow-[0_0_12px_rgba(168,85,247,0.5)]">
    </div>
</div>

<style>
    .custom-checkbox:checked::after,.vol-slider::-webkit-slider-thumb,input[type=checkbox]:checked::after {
        content:"";position:absolute;left:7px;top:3px;width:6px;height:10px;border:solid white;border-width:0 2px 2px 0;transform:rotate(45deg);
    }
    .eq-bars { display:inline-flex;align-items:flex-end;gap:2px;height:14px;vertical-align:middle;margin-right:6px; }
    .eq-bars span { display:block;width:3px;background:#c084fc;border-radius:1px;animation:eq-bounce 0.6s ease-in-out infinite alternate; }
    .eq-bars span:nth-child(1){height:6px;animation-delay:0s}
    .eq-bars span:nth-child(2){height:12px;animation-delay:.15s}
    .eq-bars span:nth-child(3){height:8px;animation-delay:.3s}
    .eq-bars span:nth-child(4){height:14px;animation-delay:.1s}
    @keyframes eq-bounce{to{height:3px}}
    .eq-bars.paused span{animation:none;height:4px}
    .track-item.is-playing .track-icon{background:rgba(168,85,247,.3);box-shadow:0 0 10px rgba(168,85,247,.4)}
    .track-item.is-playing{border-color:rgba(168,85,247,.5);background:rgba(168,85,247,.08);box-shadow:0 0 12px rgba(168,85,247,.15)}
    .ctrl-btn.is-active{background:rgba(168,85,247,.25);border-color:#a855f7}
    #audio-toggle:checked::after,#bg-toggle:checked::after{content:"";position:absolute;left:7px;top:3px;width:6px;height:10px;border:solid white;border-width:0 2px 2px 0;transform:rotate(45deg);}
</style>

<script>
(function () {
    const LANG = {
        id: {
            audio_title: 'Background Music', audio_desc: 'Play background music while using BusFlow',
            bg_title: 'Background Effect', bg_desc: 'Particle animation and color wave background effect',
            lang_title: 'Language / Bahasa', lang_desc: 'Change the interface display language',
            no_track: 'No track playing', now_playing: 'Now playing',
        },
        en: {
            audio_title: 'Background Music', audio_desc: 'Play background music while using BusFlow',
            bg_title: 'Background Effect', bg_desc: 'Particle animation and color wave background effect',
            lang_title: 'Language / Bahasa', lang_desc: 'Change the interface display language',
            no_track: 'No track playing', now_playing: 'Now playing',
        }
    };

    const TRACKS = [
        { name: 'Ambient City', genre: 'Lo-fi' },
        { name: 'Neon Drive',   genre: 'Synthwave' },
    ];

    const state = {
        lang:     localStorage.getItem('bf_lang')  || 'en',
        bgEffect: localStorage.getItem('bf_bg')    !== 'off',
        audioOn:  localStorage.getItem('bf_audio') === 'on',
        trackIdx: parseInt(localStorage.getItem('bf_track') || '0'),
        volume:   parseInt(localStorage.getItem('bf_vol')   || '70'),
    };

    const audio       = document.getElementById('bg-audio');
    const btnPlay     = document.getElementById('btn-play');
    const iconPlay    = document.getElementById('icon-play');
    const iconPause   = document.getElementById('icon-pause');
    const btnPrev     = document.getElementById('btn-prev');
    const btnNext     = document.getElementById('btn-next');
    const volSlider   = document.getElementById('vol-slider');
    const nowPlaying  = document.getElementById('now-playing');
    const audioToggle = document.getElementById('audio-toggle');
    const bgToggle    = document.getElementById('bg-toggle');
    const trackItems  = document.querySelectorAll('.track-item');
    const bgCanvas    = document.getElementById('bg-canvas');

    function applyLang(lang) {
        state.lang = lang;
        localStorage.setItem('bf_lang', lang);
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.dataset.i18n;
            if (LANG[lang][key]) el.textContent = LANG[lang][key];
        });
        if (!audio.paused) updateNowPlaying();
    }

    function applyBg(on) {
        state.bgEffect = on;
        localStorage.setItem('bf_bg', on ? 'on' : 'off');
        if (bgCanvas) bgCanvas.style.display = on ? '' : 'none';
        bgToggle.checked = on;
    }

    bgToggle.addEventListener('change', () => applyBg(bgToggle.checked));
    window.addEventListener('storage', (event) => {
        if (event.key === 'bf_bg') { const e = event.newValue !== 'off'; state.bgEffect = e; bgToggle.checked = e; if (bgCanvas) bgCanvas.style.display = e ? '' : 'none'; }
        if (event.key === 'bf_lang') applyLang(event.newValue || 'en');
    });

    function updateNowPlaying() {
        const t = TRACKS[state.trackIdx];
        const label = LANG[state.lang].now_playing;
        nowPlaying.innerHTML = audio.paused
            ? `<span data-i18n="no_track">${LANG[state.lang].no_track}</span>`
            : `<span class="eq-bars"><span></span><span></span><span></span><span></span></span>${label}: ${t.name} — ${t.genre}`;
    }

    function selectTrack(idx) {
        state.trackIdx = idx;
        localStorage.setItem('bf_track', idx);
        trackItems.forEach((el, i) => el.classList.toggle('is-playing', i === idx));
        audio.src = '';
        updateNowPlaying();
    }

    function togglePlayPause() {
        if (!state.audioOn) { audioToggle.checked = true; state.audioOn = true; localStorage.setItem('bf_audio', 'on'); }
        if (audio.paused) {
            audio.play().catch(() => {});
            iconPlay.style.display = 'none'; iconPause.style.display = '';
            btnPlay.classList.add('is-active');
        } else {
            audio.pause();
            iconPlay.style.display = ''; iconPause.style.display = 'none';
            btnPlay.classList.remove('is-active');
        }
        updateNowPlaying();
    }

    audioToggle.addEventListener('change', () => {
        state.audioOn = audioToggle.checked;
        localStorage.setItem('bf_audio', state.audioOn ? 'on' : 'off');
        if (!state.audioOn) { audio.pause(); iconPlay.style.display = ''; iconPause.style.display = 'none'; btnPlay.classList.remove('is-active'); updateNowPlaying(); }
    });

    btnPlay.addEventListener('click', togglePlayPause);
    btnPrev.addEventListener('click', () => selectTrack((state.trackIdx - 1 + TRACKS.length) % TRACKS.length));
    btnNext.addEventListener('click', () => selectTrack((state.trackIdx + 1) % TRACKS.length));

    trackItems.forEach((el, i) => {
        el.addEventListener('click', () => {
            selectTrack(i);
            if (state.audioOn) { audio.play().catch(() => {}); iconPlay.style.display = 'none'; iconPause.style.display = ''; btnPlay.classList.add('is-active'); updateNowPlaying(); }
        });
    });

    volSlider.addEventListener('input', () => { state.volume = volSlider.value; audio.volume = state.volume / 100; localStorage.setItem('bf_vol', state.volume); });

    applyLang(state.lang);
    applyBg(state.bgEffect);
    audioToggle.checked = state.audioOn;
    volSlider.value     = state.volume;
    audio.volume        = state.volume / 100;
    selectTrack(state.trackIdx);
    updateNowPlaying();
})();
</script>
@endsection
