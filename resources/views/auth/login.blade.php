@extends('auth.layouts.auth')

@section('title', 'Login')

@section('content')
<div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800 rounded-2xl p-8 shadow-[0_0_40px_rgba(0,0,0,0.5)]">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 mb-4 shadow-[0_0_20px_rgba(99,102,241,0.5)]">
            <i class="fa-solid fa-bus text-white text-2xl"></i>
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
                const response = await fetch(`${window.API_URL}/api/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const responseData = await response.json();
                const payload = responseData.data || {};

                if (response.ok && payload.token) {
                    // Save token
                    localStorage.setItem('token', payload.token);
                    
                    let role = payload.user?.role;
                    
                    // If login response doesn't include user details, fetch them
                    if (!role) {
                        try {
                            const userRes = await fetch(`${window.API_URL}/api/me`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': `Bearer ${payload.token}`
                                }
                            });
                            if (userRes.ok) {
                                const userData = await userRes.json();
                                const userObj = userData.data || userData;
                                role = userObj.role;
                            }
                        } catch (e) {
                            console.error("Failed to fetch user role", e);
                        }
                    }
                    
                    role = role || 'passenger'; // fallback default
                    
                    // Route based on role
                    switch (role) {
                        case 'admin':
                            window.location.href = "{{ route('admin.dashboard') }}";
                            break;
                        case 'operator':
                            window.location.href = "/operator";
                            break;
                        case 'driver':
                            window.location.href = "/driver";
                            break;
                        case 'conductor':
                            window.location.href = "/conductor";
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
