<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusFlow | Smart Transportation. Seamless Journeys.</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-950 text-slate-200 font-sans antialiased selection:bg-indigo-500/30">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 top-0 border-b border-slate-800/50 bg-slate-950/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center shrink-0">
                        <img src="{{ asset('assets/logo/logo_fix.png') }}" alt="BusFlow Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">BusFlow</span>
                </a>
                
                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Features</a>
                    <a href="#testimonials" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Testimonials</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if(session()->has('api_token'))
                        <a href="{{ route('user.home') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all border border-slate-700">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Sign in</a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-[0_0_15px_rgba(99,102,241,0.3)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5">Get Started</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Abstract Background -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8">
                Move Smarter.<br class="hidden md:block" />
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-sky-400">Travel Better.</span>
            </h1>
            
            <p class="mt-4 text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10">
                Reliable bus transportation with real-time schedules, easy booking, and a seamless travel experience.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                @if(session()->has('api_token'))
                    <a href="{{ route('user.routes') }}" class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-lg font-semibold px-8 py-3.5 rounded-xl transition-all shadow-[0_0_20px_rgba(99,102,241,0.4)] hover:shadow-[0_0_30px_rgba(99,102,241,0.6)] transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        Book a Ticket <i class="fa-solid fa-ticket"></i>
                    </a>
                    <a href="{{ route('user.routes') }}" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-700 text-white text-lg font-medium px-8 py-3.5 rounded-xl transition-all border border-slate-700 hover:border-slate-600 flex items-center justify-center gap-2">
                        View Schedules <i class="fa-solid fa-calendar-alt"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-lg font-semibold px-8 py-3.5 rounded-xl transition-all shadow-[0_0_20px_rgba(99,102,241,0.4)] hover:shadow-[0_0_30px_rgba(99,102,241,0.6)] transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        Book a Ticket <i class="fa-solid fa-ticket"></i>
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-700 text-white text-lg font-medium px-8 py-3.5 rounded-xl transition-all border border-slate-700 hover:border-slate-600 flex items-center justify-center gap-2">
                        View Schedules <i class="fa-solid fa-calendar-alt"></i>
                    </a>
                @endif
            </div>

            <p class="text-sm font-medium text-slate-500 uppercase tracking-widest">
                Trusted transportation for your daily commute and long-distance journeys.
            </p>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-slate-900 border-y border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-indigo-400 font-medium tracking-wider uppercase text-sm mb-2 block">Why Choose BusFlow?</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Everything You Need for a Better Journey</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-8 rounded-2xl hover:bg-slate-800 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-location-crosshairs text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Real-Time Bus Tracking</h3>
                    <p class="text-slate-400">Know exactly where your bus is and when it will arrive.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-8 rounded-2xl hover:bg-slate-800 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-regular fa-clock text-purple-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Up-to-Date Schedules</h3>
                    <p class="text-slate-400">Access accurate schedules and route information anytime.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-8 rounded-2xl hover:bg-slate-800 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-sky-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-ticket-simple text-sky-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Easy Ticket Booking</h3>
                    <p class="text-slate-400">Book your trip in just a few clicks from any device.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-8 rounded-2xl hover:bg-slate-800 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-regular fa-bell text-amber-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Instant Notifications</h3>
                    <p class="text-slate-400">Receive updates about delays, arrivals, and important travel information.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-8 rounded-2xl hover:bg-slate-800 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-shield-halved text-emerald-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Safe & Reliable</h3>
                    <p class="text-slate-400">Travel with confidence through a dependable transportation network.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonial Section -->
    <div id="testimonials" class="py-24 bg-slate-950 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">What Our Passengers Say</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl relative">
                    <i class="fa-solid fa-quote-left text-4xl text-indigo-500/20 absolute top-6 right-6"></i>
                    <p class="text-slate-300 text-lg mb-6 italic">"Booking tickets has never been easier. The real-time tracking feature is incredibly helpful."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold">
                            S
                        </div>
                        <div>
                            <p class="text-white font-medium">— Sarah M.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl relative">
                    <i class="fa-solid fa-quote-left text-4xl text-indigo-500/20 absolute top-6 right-6"></i>
                    <p class="text-slate-300 text-lg mb-6 italic">"I always know exactly when my bus will arrive. Very reliable and convenient."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center text-white font-bold">
                            D
                        </div>
                        <div>
                            <p class="text-white font-medium">— David L.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/50 to-purple-900/50 z-0"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready for a Better Travel Experience?</h2>
            <p class="text-xl text-indigo-200 mb-10 max-w-2xl mx-auto">Book your next trip with BusFlow and enjoy smarter, more reliable transportation.</p>
            
            <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-900 hover:bg-indigo-50 text-lg font-bold px-10 py-4 rounded-xl transition-all shadow-xl transform hover:-translate-y-1">
                Get Started
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center">
                <div class="flex items-center gap-3 mb-6 md:mb-0">
                    <div class="w-10 h-10 flex items-center justify-center shrink-0">
                        <img src="{{ asset('assets/logo/logo_fix.png') }}" alt="BusFlow Logo" class="w-full h-full object-contain grayscale opacity-70">
                    </div>
                    <span class="text-xl font-bold text-white">BusFlow</span>
                </div>
                <p class="text-slate-400 text-center font-medium tracking-wide">
                    Smart Transportation. Seamless Journeys.
                </p>
                <div class="mt-8 text-sm text-slate-600 flex items-center gap-4">
                    <span>&copy; {{ date('Y') }} BusFlow Inc. All rights reserved.</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
