@extends('layouts.app')
@section('title', 'Welcome - NMDPRA Incident Registry')
@section('content')

<style>
    /* ── Page-scoped overrides ──────────────────────────────────────────
       A clean, centered hero section for the application landing page.
    ──────────────────────────────────────────────────────────────────── */
    .hero-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        min-height: calc(100vh - 120px);
        padding: 40px 20px;
    }

    .hero-logo {
        width: 64px;
        height: 64px;
        background: var(--accent, #2563eb);
        border-radius: 12px;
        clip-path: polygon(0 0, 80% 0, 100% 20%, 100% 100%, 0 100%);
        margin-bottom: 24px;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
    }

    .hero-title {
        font-size: 36px;
        font-weight: 700;
        color: var(--bright, #ffffff);
        margin: 0 0 16px;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .hero-subtitle {
        font-size: 16px;
        color: var(--muted, #8b9eb0);
        max-width: 540px;
        margin: 0 auto 36px;
        line-height: 1.6;
    }

    .hero-actions {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn-large {
        padding: 14px 28px;
        font-size: 15px;
    }
</style>

<div class="hero-container">
    {{-- Decorative Brand Logo --}}
    <div class="hero-logo"></div>
    
    <h1 class="hero-title">National Incident Registry</h1>
    
    <p class="hero-subtitle">
        Centralised reporting, oversight, and compliance tracking platform for the National Midstream and Downstream Petroleum Regulatory Authority.
    </p>

    <div class="hero-actions">
        @auth
            {{-- If the user is already logged in, send them to their respective dashboard --}}
            @php
                $role = Auth::user()->role ?? '';
                $dashboardRoute = in_array($role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor'])
                    ? route('dashboard.admin')
                    : route('dashboard.operator');
            @endphp
            <a href="{{ $dashboardRoute }}" class="btn btn-primary btn-large">
                Go to Dashboard
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px; margin-left: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        @else
            {{-- If the user is a guest, show Login and Register options --}}
            <a href="{{ route('login') }}" class="btn btn-primary btn-large">
                Secure Login
            </a>
            
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-ghost btn-large" style="border-radius: var(--btn-radius);">
                    Register Organisation
                </a>
            @endif
        @endauth
    </div>
</div>

@endsection