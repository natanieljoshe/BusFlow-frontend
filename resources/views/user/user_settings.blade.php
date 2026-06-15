@extends('user.user_layout')

@section('title', 'Settings - BusFlow')
@section('page_title', 'Settings')
@section('page_description', 'Manage user-specific audio preferences, background effects, and BusFlow display language.')

@section('content')
<style>
    .settings-grid {
        display: grid;
        gap: 18px;
    }

    .setting-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border: 1px solid rgba(168,85,247,.16);
        border-radius: 10px;
        background: rgba(4,5,15,.92);
        transition: border-color .22s, box-shadow .22s;
    }
    .setting-row:hover { border-color: rgba(168,85,247,.4); box-shadow: 0 0 18px rgba(168,85,247,.1); }

    .setting-info { display: grid; gap: 4px; }
    .setting-info strong { font-size: 14px; }
    .setting-info span { font-size: 12px; color: var(--muted); }

    .toggle {
        position: relative;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
    }
    .toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
    .toggle-track {
        position: absolute;
        inset: 0;
        border-radius: 99px;
        background: rgba(168,85,247,.15);
        border: 1px solid rgba(168,85,247,.3);
        cursor: pointer;
        transition: all .25s ease;
    }
    .toggle-track::after {
        content: "";
        position: absolute;
        top: 3px; left: 3px;
        width: 18px; height: 18px;
        border-radius: 50%;
        background: var(--muted);
        transition: all .25s ease;
    }
    .toggle input:checked ~ .toggle-track {
        background: rgba(168,85,247,.35);
        border-color: var(--violet);
        box-shadow: 0 0 10px rgba(168,85,247,.5);
    }
    .toggle input:checked ~ .toggle-track::after {
        transform: translateX(22px);
        background: var(--violet-bright);
    }

    .lang-toggle {
        display: flex;
        border: 1px solid rgba(168,85,247,.3);
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .lang-btn {
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .04em;
        cursor: pointer;
        border: none;
        background: transparent;
        color: var(--muted);
        transition: all .2s ease;
        font-family: inherit;
    }
    .lang-btn.active {
        background: rgba(168,85,247,.3);
        color: var(--violet-bright);
        box-shadow: 0 0 10px rgba(168,85,247,.3);
    }

    .audio-player {
        display: grid;
        gap: 12px;
        padding: 16px 18px;
        border: 1px solid rgba(168,85,247,.16);
        border-radius: 10px;
        background: rgba(4,5,15,.92);
    }
    .audio-player-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .track-list {
        display: grid;
        gap: 8px;
        list-style: none;
    }
    .track-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border: 1px solid rgba(168,85,247,.12);
        border-radius: 8px;
        cursor: pointer;
        transition: all .2s ease;
    }
    .track-item:hover, .track-item.is-playing {
        border-color: rgba(168,85,247,.5);
        background: rgba(168,85,247,.08);
        box-shadow: 0 0 12px rgba(168,85,247,.15);
    }
    .track-icon {
        width: 32px; height: 32px;
        display: grid; place-items: center;
        border-radius: 8px;
        background: rgba(168,85,247,.15);
        color: var(--violet-bright);
        flex-shrink: 0;
    }
    .track-item.is-playing .track-icon {
        background: rgba(168,85,247,.3);
        box-shadow: 0 0 10px rgba(168,85,247,.4);
    }
    .track-name { font-size: 13px; font-weight: 700; flex: 1; }
    .track-dur  { font-size: 11px; color: var(--muted); }

    .audio-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ctrl-btn {
        width: 36px; height: 36px;
        display: grid; place-items: center;
        border: 1px solid rgba(168,85,247,.3);
        border-radius: 8px;
        background: rgba(168,85,247,.08);
        color: var(--violet-bright);
        cursor: pointer;
        transition: all .2s ease;
        flex-shrink: 0;
    }
    .ctrl-btn:hover { background: rgba(168,85,247,.2); box-shadow: 0 0 10px rgba(168,85,247,.35); }
    .ctrl-btn.is-active { background: rgba(168,85,247,.25); border-color: var(--violet); }

    .volume-wrap { display: flex; align-items: center; gap: 8px; flex: 1; }
    .volume-wrap svg { color: var(--muted); flex-shrink: 0; }
    input[type=range].vol-slider {
        flex: 1;
        height: 4px;
        border-radius: 99px;
        appearance: none;
        background: rgba(168,85,247,.2);
        outline: none;
        cursor: pointer;
    }
    input[type=range].vol-slider::-webkit-slider-thumb {
        appearance: none;
        width: 14px; height: 14px;
        border-radius: 50%;
        background: var(--violet-bright);
        box-shadow: 0 0 6px rgba(168,85,247,.7);
    }

    .now-playing {
        font-size: 11px;
        color: var(--violet-bright);
        font-weight: 700;
        min-height: 16px;
    }
    .eq-bars {
        display: inline-flex;
        align-items: flex-end;
        gap: 2px;
        height: 14px;
        vertical-align: middle;
        margin-right: 6px;
    }
    .eq-bars span {
        display: block;
        width: 3px;
        background: var(--violet-bright);
        border-radius: 1px;
        animation: eq-bounce 0.6s ease-in-out infinite alternate;
    }
    .eq-bars span:nth-child(1) { height: 6px;  animation-delay: 0s; }
    .eq-bars span:nth-child(2) { height: 12px; animation-delay: .15s; }
    .eq-bars span:nth-child(3) { height: 8px;  animation-delay: .3s; }
    .eq-bars span:nth-child(4) { height: 14px; animation-delay: .1s; }
    @keyframes eq-bounce { to { height: 3px; } }
    .eq-bars.paused span { animation: none; height: 4px; }
</style>

<div class="settings-grid">
    <div class="audio-player">
        <div class="audio-player-header">
            <div class="setting-info">
                <strong data-i18n="audio_title">Background Music</strong>
                <span data-i18n="audio_desc">Play background music while using BusFlow</span>
            </div>
            <label class="toggle">
                <input type="checkbox" id="audio-toggle">
                <span class="toggle-track"></span>
            </label>
        </div>

        <ul class="track-list" id="track-list">
            <li class="track-item" data-src="" data-index="0">
                <span class="track-icon">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 11V4l9-2v7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><circle cx="3" cy="11" r="2" stroke="currentColor" stroke-width="1.3"/><circle cx="12" cy="9" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                </span>
                <span class="track-name">Ambient City</span>
                <span class="track-dur">Lo-fi</span>
            </li>
            <li class="track-item" data-src="" data-index="1">
                <span class="track-icon">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 11V4l9-2v7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><circle cx="3" cy="11" r="2" stroke="currentColor" stroke-width="1.3"/><circle cx="12" cy="9" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                </span>
                <span class="track-name">Neon Drive</span>
                <span class="track-dur">Synthwave</span>
            </li>
            <li class="track-item" data-src="" data-index="2">
                <span class="track-icon">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 11V4l9-2v7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><circle cx="3" cy="11" r="2" stroke="currentColor" stroke-width="1.3"/><circle cx="12" cy="9" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                </span>
                <span class="track-name">Transit Pulse</span>
                <span class="track-dur">Chillhop</span>
            </li>
        </ul>

        <div class="now-playing" id="now-playing">
            <span data-i18n="no_track">No track playing</span>
        </div>

        <div class="audio-controls">
            <button class="ctrl-btn" id="btn-prev" title="Previous">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 3v8M12 3L5 7l7 4V3z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <button class="ctrl-btn" id="btn-play" title="Play/Pause">
                <svg id="icon-play" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M4 2.5l8 4.5-8 4.5V2.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                <svg id="icon-pause" width="14" height="14" viewBox="0 0 14 14" fill="none" style="display:none"><path d="M4 3v8M10 3v8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </button>
            <button class="ctrl-btn" id="btn-next" title="Next">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M12 3v8M2 3l7 4-7 4V3z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="volume-wrap">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 5h2l3-3v10L4 9H2V5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M10 4.5a4 4 0 010 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                <input type="range" class="vol-slider" id="vol-slider" min="0" max="100" value="70">
            </div>
        </div>

        <audio id="bg-audio" loop></audio>
    </div>

    <div class="setting-row">
        <div class="setting-info">
            <strong data-i18n="bg_title">Background Effect</strong>
            <span data-i18n="bg_desc">Particle animation and color wave background effect</span>
        </div>
        <label class="toggle">
            <input type="checkbox" id="bg-toggle" checked>
            <span class="toggle-track"></span>
        </label>
    </div>

</div>

<script>
(function () {
    const LANG = {
        id: {
            audio_title: 'Background Music',
            audio_desc:  'Play background music while using BusFlow',
            bg_title:    'Background Effect',
            bg_desc:     'Particle animation and color wave background effect',
            lang_title:  'Language',
            lang_desc:   'Change the interface display language',
            no_track:    'No track playing',
            now_playing: 'Now playing',
        },
        en: {
            audio_title: 'Background Music',
            audio_desc:  'Play background music while using BusFlow',
            bg_title:    'Background Effect',
            bg_desc:     'Particle animation and color wave background effect',
            lang_title:  'Language',
            lang_desc:   'Change the interface display language',
            no_track:    'No track playing',
            now_playing: 'Now playing',
        }
    };

    const TRACKS = [
        { name: 'Ambient City',   genre: 'Lo-fi' },
        { name: 'Neon Drive',     genre: 'Synthwave' },
        { name: 'Transit Pulse',  genre: 'Chillhop' },
    ];

    const state = {
        lang:      localStorage.getItem('bf_lang')    || 'id',
        bgEffect:  localStorage.getItem('bf_bg')      !== 'off',
        audioOn:   localStorage.getItem('bf_audio')   === 'on',
        trackIdx:  parseInt(localStorage.getItem('bf_track') || '0'),
        volume:    parseInt(localStorage.getItem('bf_vol')   || '70'),
    };

    const audio      = document.getElementById('bg-audio');
    const btnPlay    = document.getElementById('btn-play');
    const iconPlay   = document.getElementById('icon-play');
    const iconPause  = document.getElementById('icon-pause');
    const btnPrev    = document.getElementById('btn-prev');
    const btnNext    = document.getElementById('btn-next');
    const volSlider  = document.getElementById('vol-slider');
    const nowPlaying = document.getElementById('now-playing');
    const audioToggle= document.getElementById('audio-toggle');
    const bgToggle   = document.getElementById('bg-toggle');
    const trackItems = document.querySelectorAll('.track-item');
    const bgCanvas   = document.getElementById('bg-canvas');

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
        if (event.key === 'bf_bg') {
            const enabled = event.newValue !== 'off';
            state.bgEffect = enabled;
            bgToggle.checked = enabled;
            if (bgCanvas) bgCanvas.style.display = enabled ? '' : 'none';
        }

        if (event.key === 'bf_lang') {
            const lang = event.newValue || 'id';
            applyLang(lang);
        }
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
        if (!state.audioOn) {
            audioToggle.checked = true;
            state.audioOn = true;
            localStorage.setItem('bf_audio', 'on');
        }
        if (audio.paused) {
            audio.play().catch(() => {});
            iconPlay.style.display  = 'none';
            iconPause.style.display = '';
            btnPlay.classList.add('is-active');
        } else {
            audio.pause();
            iconPlay.style.display  = '';
            iconPause.style.display = 'none';
            btnPlay.classList.remove('is-active');
        }
        updateNowPlaying();
    }

    audioToggle.addEventListener('change', () => {
        state.audioOn = audioToggle.checked;
        localStorage.setItem('bf_audio', state.audioOn ? 'on' : 'off');
        if (!state.audioOn) {
            audio.pause();
            iconPlay.style.display  = '';
            iconPause.style.display = 'none';
            btnPlay.classList.remove('is-active');
            updateNowPlaying();
        }
    });

    btnPlay.addEventListener('click', togglePlayPause);
    btnPrev.addEventListener('click', () => {
        selectTrack((state.trackIdx - 1 + TRACKS.length) % TRACKS.length);
    });
    btnNext.addEventListener('click', () => {
        selectTrack((state.trackIdx + 1) % TRACKS.length);
    });

    trackItems.forEach((el, i) => {
        el.addEventListener('click', () => {
            selectTrack(i);
            if (state.audioOn) {
                audio.play().catch(() => {});
                iconPlay.style.display  = 'none';
                iconPause.style.display = '';
                btnPlay.classList.add('is-active');
                updateNowPlaying();
            }
        });
    });

    volSlider.addEventListener('input', () => {
        state.volume = volSlider.value;
        audio.volume = state.volume / 100;
        localStorage.setItem('bf_vol', state.volume);
    });

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