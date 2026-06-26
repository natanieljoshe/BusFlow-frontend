@extends('sopir.sopir_layout')

@section('title', 'Boarding Scanner')
@section('page_title', 'Boarding Scanner')
@section('page_description', 'Scan passenger QR codes to check-in or check-out')

@section('content')
<div class="flex flex-col gap-6 max-w-3xl mx-auto w-full">
    
    <!-- Scanner Card -->
    <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-2xl p-8 relative overflow-hidden shadow-2xl">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-10"></div>
        
        <style>
            /* Custom styles for html5-qrcode generated elements */
            #reader select {
                background-color: #1e293b;
                color: #e2e8f0;
                border: 1px solid #334155;
                padding: 8px 12px;
                border-radius: 8px;
                margin-bottom: 10px;
                width: 100%;
                max-width: 300px;
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
            #reader button {
                background-color: #4f46e5;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.875rem;
                margin: 5px;
                transition: all 0.2s;
            }
            #reader button:hover {
                background-color: #4338ca;
            }
            #reader a {
                color: #818cf8;
                text-decoration: none;
                font-size: 0.875rem;
                margin-top: 10px;
                display: inline-block;
            }
            #reader a:hover {
                color: #a5b4fc;
            }
            #reader__dashboard_section_csr span {
                color: #94a3b8 !important;
            }
            #reader img {
                display: none !important;
            }
        </style>

        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-indigo-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-indigo-500/20">
                <i class="fa-solid fa-qrcode text-3xl text-indigo-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">QR Token Scanner</h2>
            <p class="text-slate-400 text-sm">Scan the passenger's booking QR code or enter token manually to process their boarding.</p>
        </div>

        <!-- QR Scanner Area -->
        <div class="mb-6 bg-slate-800/50 p-4 rounded-xl border border-slate-700">
            <div id="reader" class="w-full mx-auto overflow-hidden rounded-lg"></div>
            <p id="scan-status" class="text-center text-xs text-slate-400 mt-2">Camera initializing...</p>
        </div>

        <div class="flex items-center gap-4 mb-6">
            <div class="h-px bg-slate-700 flex-1"></div>
            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">OR ENTER MANUALLY</span>
            <div class="h-px bg-slate-700 flex-1"></div>
        </div>

        <form id="scanner-form" class="space-y-6 relative z-10">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Booking Token</label>
                <div class="relative">
                    <input type="text" id="token-input" required class="w-full bg-slate-800/80 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-mono text-center tracking-widest text-lg" placeholder="e.g. 1a2b3c4d...">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fa-solid fa-barcode text-slate-500"></i>
                    </div>
                </div>
            </div>

            <button type="submit" id="scan-btn" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-[0_0_20px_rgba(79,70,229,0.3)] transition-all flex items-center justify-center gap-2 text-lg">
                <i class="fa-solid fa-expand"></i> Scan & Verify
            </button>
        </form>

        <!-- Result Box -->
        <div id="result-box" class="mt-8 hidden p-6 rounded-xl border">
            <div class="flex items-start gap-4">
                <div id="result-icon" class="mt-1"></div>
                <div>
                    <h3 id="result-title" class="text-lg font-bold mb-1"></h3>
                    <p id="result-message" class="text-sm text-slate-300"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('scanner-form');
    const input = document.getElementById('token-input');
    const btn = document.getElementById('scan-btn');
    const resultBox = document.getElementById('result-box');
    const resultIcon = document.getElementById('result-icon');
    const resultTitle = document.getElementById('result-title');
    const resultMessage = document.getElementById('result-message');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const token = input.value.trim();
        if(!token) return;

        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;
        resultBox.classList.add('hidden');

        try {
            // Kita gunakan API admin untuk tap-in mock
            // Karena tidak ada endpoint khusus token, mari asumsikan kita punya endpoint /api/admin/scanner/tap-in
            // Namun untuk mock ini, kita cukup panggil /api/admin/scanner/tap-in
            const baseApiUrl = '{{ rtrim(env('API_URL', 'http://127.0.0.1:8010/api'), '/api') }}';
            const response = await fetch(`${baseApiUrl}/api/admin/scanner/tap-in`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ token: token })
            });

            const data = await response.json();

            resultBox.classList.remove('hidden', 'bg-emerald-500/10', 'border-emerald-500/20', 'bg-red-500/10', 'border-red-500/20');
            
            if(response.ok) {
                resultBox.classList.add('bg-emerald-500/10', 'border-emerald-500/20');
                resultIcon.innerHTML = '<i class="fa-solid fa-circle-check text-2xl text-emerald-400"></i>';
                resultTitle.className = 'text-lg font-bold mb-1 text-emerald-400';
                resultTitle.innerText = data.action === 'checkout' ? 'Check Out Successful' : 'Check In Successful';
                resultMessage.innerText = data.message || 'Passenger verified and checked in.';
                input.value = '';
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Scan Gagal',
                        text: data.message || 'Terjadi kesalahan.',
                        background: '#1e293b',
                        color: '#f8fafc'
                    });
                } else {
                    alert(data.message || 'Terjadi kesalahan.');
                }
                resultBox.classList.add('bg-red-500/10', 'border-red-500/20');
                resultIcon.innerHTML = '<i class="fa-solid fa-circle-xmark text-2xl text-red-400"></i>';
                resultTitle.className = 'text-lg font-bold mb-1 text-red-400';
                resultTitle.innerText = 'Scan Failed';
                resultMessage.innerText = data.message || 'Error occurred';
            }
        } catch (err) {
            resultBox.classList.remove('hidden');
            resultBox.classList.add('bg-red-500/10', 'border-red-500/20');
            resultIcon.innerHTML = '<i class="fa-solid fa-circle-xmark text-2xl text-red-400"></i>';
            resultTitle.className = 'text-lg font-bold mb-1 text-red-400';
            resultTitle.innerText = 'Connection Error';
            resultMessage.innerText = 'Failed to reach the server. Please try again.';
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-expand"></i> Scan & Verify';
            btn.disabled = false;
        }
    });

    // Initialize HTML5 QR Code Scanner
    function onScanSuccess(decodedText, decodedResult) {
        // Handle on success condition with the decoded message.
        input.value = decodedText;
        document.getElementById('scan-status').innerText = 'QR Code Scanned!';
        document.getElementById('scan-status').className = 'text-center text-xs text-emerald-400 mt-2 font-bold';
        
        // Auto submit form
        form.dispatchEvent(new Event('submit'));
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: {width: 250, height: 250} },
        /* verbose= */ false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    
    // Style adjustments for scanner
    setTimeout(() => {
        const readerEl = document.getElementById('reader');
        if (readerEl) {
            readerEl.style.border = 'none';
            const btn = document.getElementById('html5-qrcode-button-camera-permission');
            if (btn) {
                btn.className = 'px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded text-sm mb-2';
            }
        }
    }, 1000);

});
</script>
@endpush
@endsection
