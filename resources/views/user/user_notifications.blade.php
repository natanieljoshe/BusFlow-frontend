@extends('user.user_layout')

@section('title', 'Notifications - BusFlow')
@section('page_title', 'Notifications')
@section('page_description', 'Your latest updates and alerts.')

@section('content')
<section class="border border-violet-500/[0.22] rounded-xl bg-[rgba(4,5,15,0.96)] shadow-[0_8px_40px_rgba(0,0,0,0.7),0_0_0_1px_rgba(168,85,247,0.08)] backdrop-blur-2xl overflow-hidden p-[20px]">
    <div class="flex items-center justify-between border-b border-violet-500/20 pb-4 mb-6">
        <h3 class="text-xl font-bold">Inbox</h3>
    </div>

    <div class="grid gap-4">
        @forelse($notifications as $notif)
        <div class="border {{ $notif['is_read'] ? 'border-violet-500/10 bg-transparent' : 'border-violet-500/30 bg-violet-500/5' }} rounded-[9px] p-4 transition-all hover:bg-violet-500/10 flex gap-4">
            <div class="w-10 h-10 rounded-full bg-cyan-400/10 flex items-center justify-center shrink-0 text-cyan-400">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            </div>
            <div>
                <strong class="text-[#eef4ff] text-[15px] block mb-1">{{ $notif['title'] ?? 'Notification' }}</strong>
                <p class="text-sm text-[#7a8aaa]">{{ $notif['body'] ?? $notif['message'] ?? '' }}</p>
                <span class="text-[10px] text-[#7a8aaa] mt-2 block">{{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="text-center text-[#7a8aaa] py-8 border border-violet-500/20 rounded-xl bg-violet-500/5">
            Belum ada notifikasi.
        </div>
        @endforelse
    </div>
</section>
@endsection
