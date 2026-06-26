@extends('user.user_layout')

@section('title', 'My Wallet')
@section('page_title', 'My Wallet')
@section('page_description', 'Kelola saldo BusFlow Anda, top up, dan lihat riwayat transaksi di sini.')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-[380px_1fr] gap-6 items-start">

        <!-- Balance Card -->
        <div
            class="bg-[rgba(4,5,15,0.6)] backdrop-blur-md border border-violet-500/20 rounded-2xl p-6 relative overflow-hidden shadow-[0_0_30px_rgba(168,85,247,0.05)]">
            <div
                class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-10">
            </div>
            <div class="relative z-10">
                <h3 class="text-[#7a8aaa] text-sm font-semibold uppercase tracking-wider mb-2">Available Balance</h3>
                <div class="text-4xl font-black text-white mb-6">
                    $ {{ number_format($wallet['balance'] ?? 0, 2, '.', ',') }}
                </div>

                <button onclick="openTopupModal()"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-[0_0_20px_rgba(79,70,229,0.3)] transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> Top Up Balance
                </button>
            </div>
        </div>

        <!-- Transaction History -->
        <div
            class="bg-[rgba(4,5,15,0.6)] backdrop-blur-md border border-violet-500/20 rounded-2xl p-6 relative shadow-[0_0_30px_rgba(168,85,247,0.05)]">
            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-400"></i> Transaction History
            </h3>

            @if (!empty($history))
                <div class="space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar pr-2">
                    @foreach ($history as $item)
                        <div
                            class="flex items-center justify-between p-4 rounded-xl border border-slate-800 bg-slate-800/30">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center {{ $item['type'] === 'top_up' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                                    <i
                                        class="fa-solid {{ $item['type'] === 'top_up' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $item['description'] ?? 'Transaction' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($item['created_at'])->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-sm font-bold {{ $item['type'] === 'top_up' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $item['type'] === 'top_up' ? '+' : '-' }}${{ number_format($item['amount'], 2, '.', ',') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-slate-500">
                    <i class="fa-solid fa-receipt text-3xl mb-3 opacity-50"></i>
                    <p>Belum ada riwayat transaksi</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Topup Modal -->
    <div id="topup-modal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-sm p-6 shadow-2xl transform scale-95 transition-transform duration-300"
            id="topup-modal-content">
            <h3 class="text-xl font-bold text-white mb-2">Top Up Balance</h3>
            <p class="text-sm text-slate-400 mb-6">Enter amount to add to your wallet.</p>

            <form id="topup-form" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Amount (USD)</label>
                    <input type="number" id="topup-amount" required min="5" step="5"
                        class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-indigo-500 text-lg font-bold"
                        placeholder="20">
                </div>

                <div class="grid grid-cols-3 gap-2 mt-2">
                    <button type="button" onclick="setAmount(10)"
                        class="py-2 text-sm border border-slate-700 rounded hover:bg-slate-800 text-slate-300 transition-colors">$10</button>
                    <button type="button" onclick="setAmount(20)"
                        class="py-2 text-sm border border-slate-700 rounded hover:bg-slate-800 text-slate-300 transition-colors">$20</button>
                    <button type="button" onclick="setAmount(50)"
                        class="py-2 text-sm border border-slate-700 rounded hover:bg-slate-800 text-slate-300 transition-colors">$50</button>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeTopupModal()"
                        class="px-4 py-2.5 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="submit-topup-btn"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openTopupModal() {
            document.getElementById('topup-amount').value = '';
            const modal = document.getElementById('topup-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => document.getElementById('topup-modal-content').classList.remove('scale-95'), 10);
        }

        function closeTopupModal() {
            document.getElementById('topup-modal-content').classList.add('scale-95');
            setTimeout(() => {
                const modal = document.getElementById('topup-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function setAmount(val) {
            document.getElementById('topup-amount').value = val;
        }

        document.getElementById('topup-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const amount = document.getElementById('topup-amount').value;
            const btn = document.getElementById('submit-topup-btn');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;

            try {
                const API_URL = "{{ env('API_URL') }}" || 'http://127.0.0.1:8010';
                const res = await fetch(`${API_URL}/api/wallet/topup`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        amount: amount
                    })
                });

                if (res.ok) {
                    window.location.reload();
                } else {
                    const data = await res.json();
                    alert('Top up failed: ' + (data.message || 'Error'));
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                alert('Failed to connect to server');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    </script>
@endsection
