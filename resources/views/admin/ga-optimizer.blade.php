@extends('admin.layouts.admin')

@section('title', 'AI Schedule Optimizer')
@section('header_title', 'AI Schedule Optimizer')
@section('header_subtitle', 'Konfigurasi Parameter GA')

@push('styles')
<style>
    .glass-panel {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .input-glow:focus {
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
        border-color: #6366f1;
    }
</style>
@endpush

@section('content')
<div class="flex flex-col items-center justify-center w-full max-w-4xl mx-auto py-8">
    <div class="text-center mb-10 w-full">
        <h2 class="text-3xl font-bold mb-4 text-white">
            Konfigurasi Parameter Optimasi
        </h2>
        <p class="text-slate-400 text-sm max-w-2xl mx-auto">
            Atur parameter untuk model AI guna memprediksi dan menghasilkan jadwal keberangkatan bus yang optimal.
        </p>
    </div>

    <div class="glass-panel w-full rounded-2xl p-8 shadow-2xl">
        <form action="{{ route('admin.ga-results') }}" method="GET" class="space-y-6">
            
            <!-- Route & Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 relative">
                    <label for="route" class="text-sm font-medium text-slate-300">Pilih Rute Bus</label>
                    <select id="route" name="route" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300 appearance-none">
                        <option value="M15+">M15+ (Manhattan Select Bus)</option>
                        <option value="Q52+">Q52+ (Queens Select Bus)</option>
                        <option value="BX18B">BX18B (Bronx Local)</option>
                        <option value="Q114">Q114 (Queens Local)</option>
                        <option value="M3">M3 (Manhattan Local)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400" style="margin-top: 28px;">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="date" class="text-sm font-medium text-slate-300">Pilih Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300">
                </div>
            </div>

            <!-- Operating Hours -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="start_time" class="text-sm font-medium text-slate-300">Jam Operasional Mulai</label>
                    <input type="time" id="start_time" name="start_time" value="05:00" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300">
                </div>
                
                <div class="space-y-2">
                    <label for="end_time" class="text-sm font-medium text-slate-300">Jam Operasional Selesai</label>
                    <input type="time" id="end_time" name="end_time" value="23:00" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 outline-none input-glow transition-all duration-300">
                </div>
            </div>

            <!-- Fleet & Capacity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="fleet_count" class="text-sm font-medium text-slate-300">Jumlah Armada Tersedia</label>
                    <div class="relative">
                        <input type="number" id="fleet_count" name="fleet_count" value="10" min="1" max="50" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-200 outline-none input-glow transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-bus-simple text-slate-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="bus_capacity" class="text-sm font-medium text-slate-300">Kapasitas Bus (Penumpang/Bus)</label>
                    <div class="relative">
                        <input type="number" id="bus_capacity" name="bus_capacity" value="80" min="10" max="150" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-slate-200 outline-none input-glow transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-users text-slate-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-6 py-4 transition-all duration-300 flex items-center justify-center gap-2 group">
                    <span>Jalankan Optimasi AI</span>
                    <i class="fa-solid fa-wand-magic-sparkles group-hover:rotate-12 transition-transform duration-300"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
