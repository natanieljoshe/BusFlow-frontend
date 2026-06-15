<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusFlow Admin - @yield('title', 'Global Intelligence Hub')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- tailwind css cdn -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b0f19; /* Deep dark blue */
            color: #e2e8f0;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a; 
        }
        ::-webkit-scrollbar-thumb {
            background: #334155; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569; 
        }
    </style>
    @stack('styles')
    
    <!-- Global Route Guard -->
    <script>
        if (!localStorage.getItem('token')) {
            window.location.href = "{{ route('login') }}";
        }
    </script>
</head>
<body class="antialiased overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Sidebar -->
        @include('admin.components.admin-sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <!-- Header -->
            @include('admin.components.admin-header')

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#0b0f19] p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        window.API_URL = "{{ env('API_URL') }}";

        // Mobile sidebar toggle logic
        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            if (menuBtn && sidebar && overlay) {
                const toggleSidebar = () => {
                    sidebar.classList.toggle('-translate-x-full');
                    
                    if (overlay.classList.contains('hidden')) {
                        overlay.classList.remove('hidden');
                        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                    } else {
                        overlay.classList.add('opacity-0');
                        setTimeout(() => overlay.classList.add('hidden'), 300); // Wait for transition
                    }
                };

                menuBtn.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
