@extends('auth.layouts.auth')

@section('title', 'Passenger Registration')

@section('content')
<div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800 rounded-2xl p-8 shadow-[0_0_40px_rgba(0,0,0,0.5)] my-8">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-sky-600 mb-4 shadow-[0_0_20px_rgba(168,85,247,0.5)]">
            <i class="fa-solid fa-user-plus text-white text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-white tracking-tight">Join BusFlow</h2>
        <p class="text-sm text-slate-400 mt-1">Register for a passenger account</p>
    </div>

    <form id="register-form" class="space-y-5">
        <div id="error-message" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg p-3 flex items-start gap-2">
            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
            <span id="error-text">Failed to register. Please check your inputs.</span>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Full Name</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-slate-500"></i>
                </div>
                <input type="text" id="name" required class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 p-2.5 transition-colors" placeholder="John Doe">
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-slate-500"></i>
                </div>
                <input type="email" id="email" required class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 p-2.5 transition-colors" placeholder="user@busflow.com">
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-slate-500"></i>
                </div>
                <input type="password" id="password" required minlength="8" class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 p-2.5 transition-colors" placeholder="••••••••">
                <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300" data-target="password">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Confirm Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-check-double text-slate-500"></i>
                </div>
                <input type="password" id="password_confirmation" required minlength="8" class="bg-slate-800/50 border border-slate-700 text-slate-200 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 p-2.5 transition-colors" placeholder="••••••••">
                <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300" data-target="password_confirmation">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" id="submit-btn" class="w-full bg-gradient-to-r from-purple-600 to-sky-600 hover:from-purple-500 hover:to-sky-500 text-white rounded-lg px-4 py-2.5 text-sm font-semibold transition-all duration-300 shadow-[0_0_15px_rgba(168,85,247,0.3)] hover:shadow-[0_0_25px_rgba(168,85,247,0.5)] transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2">
            <span>Create Account</span>
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-slate-400">Already have an account? <a href="{{ route('login') }}" class="text-purple-400 font-medium hover:text-purple-300 transition-colors">Sign In</a></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle Password Visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';
            });
        });

        // Form Submit
        const registerForm = document.getElementById('register-form');
        const submitBtn = document.getElementById('submit-btn');
        const errorMsg = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');

        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            
            if (password !== password_confirmation) {
                errorText.innerText = 'Passwords do not match.';
                errorMsg.classList.remove('hidden');
                return;
            }

            // Reset state
            errorMsg.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Registering...';

            try {
                const response = await fetch(`${window.API_URL}/api/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name, email, password, password_confirmation })
                });

                const responseData = await response.json();
                const payload = responseData.data || {};

                if (response.ok && payload.token) {
                    // Save token
                    localStorage.setItem('token', payload.token);
                    // Redirect to passenger area (root for now)
                    window.location.href = "/";
                } else {
                    // Show error, handle validation errors from Laravel if present
                    let msg = responseData.message || 'Registration failed. Please check your inputs.';
                    if (responseData.errors) {
                        const firstErrorKey = Object.keys(responseData.errors)[0];
                        msg = responseData.errors[firstErrorKey][0];
                    }
                    errorText.innerText = msg;
                    errorMsg.classList.remove('hidden');
                }
            } catch (error) {
                console.error("Registration error:", error);
                errorText.innerText = 'Unable to connect to server. Please try again later.';
                errorMsg.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Create Account</span> <i class="fa-solid fa-paper-plane"></i>';
            }
        });
    });
</script>
@endpush
