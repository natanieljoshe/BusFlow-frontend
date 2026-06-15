@extends('user.user_layout')

@section('title', 'BusFlow - User')
@section('page_title', 'BusFlow User')
@section('page_description', 'Halaman user BusFlow sudah dipisah menjadi Routes, Payments, My Trip, dan Favourites.')

@section('top_actions')
    <a class="pill" href="{{ route('user.routes') }}">Open Routes</a>
@endsection

@section('content')
    <section class="surface">
        <div class="surface-header">
            <div>
                <h2>Dashboard User</h2>
                <p>Pilih salah satu menu di navbar untuk membuka modul user yang terpisah.</p>
            </div>
        </div>
        <div class="surface-body">
            <div class="button-row">
                <a class="primary-btn" href="{{ route('user.routes') }}">Routes</a>
                <a class="ghost-btn" href="{{ route('user.payments') }}">Payments</a>
                <a class="ghost-btn" href="{{ route('user.my-trip') }}">My Trip</a>
                <a class="ghost-btn" href="{{ route('user.favourites') }}">Favourites</a>
            </div>
        </div>
    </section>
@endsection
