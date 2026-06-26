@extends('auth.layouts.auth')

@section('title', 'Login')

@section('content')
<div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800 rounded-2xl p-8 shadow-[0_0_40px_rgba(0,0,0,0.5)]">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-24 h-24 mb-2">
            <img src="{{ asset('assets/logo/logo_fix.png') }}" alt="BusFlow Logo" class="w-full h-full object-contain drop-shadow-[0_0_15px_rgba(99,102,241,0.5)]">
        </div>
        <h2 class="text-2xl font-bold text-white tracking-tight">Welcome to BusFlow</h2>
        <p class="text-sm text-slate-400 mt-1">Sign in to your account</p>
    </div>

    <form id="login-form" class="space-y-5">
        <div id="error-message" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg p-3 flex items-start gap-2">
            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
            <span id="error-text">Invalid credentials. Please try again.</span>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-slate-500"></i>
                </div>
                <input type="email" id="email" required class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 transition-colors" placeholder="user@busflow.com">
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label class="text-xs font-medium text-slate-400">Password</label>
                <a href="#" class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-slate-500"></i>
                </div>
                <input type="password" id="password" required class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 transition-colors" placeholder="••••••••">
                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center">
            <input type="checkbox" id="remember" class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900">
            <label for="remember" class="ml-2 text-sm text-slate-400 cursor-pointer">Remember me</label>
        </div>

        <button type="submit" id="submit-btn" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-lg px-4 py-2.5 text-sm font-semibold transition-all duration-300 shadow-[0_0_15px_rgba(79,70,229,0.3)] hover:shadow-[0_0_25px_rgba(79,70,229,0.5)] transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
            <span>Sign In</span>
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-slate-400">New passenger? <a href="{{ route('register') }}" class="text-indigo-400 font-medium hover:text-indigo-300 transition-colors">Create an account</a></p>
    </div>

    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-slate-900/60 text-slate-400">Or continue with</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ env('API_URL', 'http://127.0.0.1:8010/api') }}/auth/google/redirect" class="w-full inline-flex justify-center items-center gap-2 py-2.5 px-4 border border-slate-700 rounded-lg bg-slate-800 hover:bg-slate-700 text-sm font-medium text-white transition-colors">
                <i class="fa-brands fa-google text-red-500"></i>
                Google
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle Password Visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';
        });

        // Form Submit
        const loginForm = document.getElementById('login-form');
        const submitBtn = document.getElementById('submit-btn');
        const errorMsg = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Reset state
            errorMsg.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Authenticating...';

            try {
                const response = await fetch("{{ route('local.login') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email, password })
                });

                const responseData = await response.json();

                if (response.ok && responseData.status === 'success') {
                    const role = responseData.role || 'passenger';
                    
                    // Route based on role
                    switch (role) {
                        case 'admin':
                            window.location.href = "{{ route('admin.dashboard') }}";
                            break;
                        case 'operator':
                            window.location.href = "{{ route('admin.routes') }}";
                            break;
                        case 'driver':
                            window.location.href = "{{ route('sopir.home') }}";
                            break;
                        case 'conductor':
                            window.location.href = "{{ route('sopir.home') }}";
                            break;
                        case 'passenger':
                        default:
                            window.location.href = "/user";
                            break;
                    }
                } else {
                    // Show error
                    errorText.innerText = responseData.message || 'Invalid credentials. Please try again.';
                    errorMsg.classList.remove('hidden');
                }
            } catch (error) {
                console.error("Login error:", error);
                errorText.innerText = 'Unable to connect to server. Please try again later.';
                errorMsg.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Sign In</span> <i class="fa-solid fa-arrow-right-to-bracket"></i>';
            }
        });
    });
</script>
@endpush
