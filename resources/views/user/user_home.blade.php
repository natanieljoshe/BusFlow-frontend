@extends('user.user_layout')

@section('title', 'BusFlow - User')
@section('page_title', 'BusFlow User')
@section('page_description', 'Halaman user BusFlow sudah dipisah menjadi Routes, Payments, My Trip, dan Favourites.')

@section('top_actions')
    <a class="inline-flex items-center gap-2 min-h-[34px] px-3 border border-violet-500/30 rounded-lg text-[#c084fc] bg-violet-500/10 text-xs font-extrabold" href="{{ route('user.routes') }}">Open Routes</a>
@endsection

@section('content')
    <section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden transition-shadow duration-300 hover:shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_28px_rgba(168,85,247,0.14)]">
        <div class="flex items-start justify-between gap-4 p-[18px] border-b border-violet-500/[0.12]">
            <div>
                <h2 class="text-xl font-bold">Dashboard User</h2>
                <p class="mt-1.5 text-[#7a8aaa] text-[13px] leading-relaxed">Pilih salah satu menu di navbar untuk membuka modul user yang terpisah.</p>
            </div>
        </div>
        <div class="p-[18px]">
            <div class="flex items-center gap-[10px] flex-wrap">
                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-cyan-400/[0.08] border border-cyan-400/50 text-[#22d3ee] hover:bg-cyan-400/[0.16] hover:border-cyan-400 hover:text-white hover:shadow-[0_0_18px_rgba(34,211,238,0.4),inset_0_0_12px_rgba(34,211,238,0.08)]" href="{{ route('user.routes') }}">Routes</a>
                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-violet-500/5 border border-violet-500/[0.28] text-[#b8c8e8] hover:bg-violet-500/[0.12] hover:border-violet-500/[0.65] hover:text-white hover:shadow-[0_0_14px_rgba(168,85,247,0.25)]" href="{{ route('user.payments') }}">Payments</a>
                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-violet-500/5 border border-violet-500/[0.28] text-[#b8c8e8] hover:bg-violet-500/[0.12] hover:border-violet-500/[0.65] hover:text-white hover:shadow-[0_0_14px_rgba(168,85,247,0.25)]" href="{{ route('user.my-trip') }}">My Trip</a>
                <a class="inline-flex items-center justify-center gap-[7px] min-h-[38px] px-4 rounded-lg text-xs font-extrabold cursor-pointer tracking-wider uppercase relative overflow-hidden transition-all duration-[250ms] no-underline bg-violet-500/5 border border-violet-500/[0.28] text-[#b8c8e8] hover:bg-violet-500/[0.12] hover:border-violet-500/[0.65] hover:text-white hover:shadow-[0_0_14px_rgba(168,85,247,0.25)]" href="{{ route('user.favourites') }}">Favourites</a>
            </div>
        </div>
    </section>
@endsection
