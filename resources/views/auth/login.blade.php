@extends('layouts.app')
@section('title', 'Login - NMDPRA Tracker')
@section('content')

<style>
    /* ── Page-scoped overrides ──────────────────────────────────────────
       Creates a centered, focused environment specifically for the login card.
    ──────────────────────────────────────────────────────────────────── */
    .login-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 64px); /* Adjusts for global padding to keep it vertically centered */
        padding: 20px;
    }

    .login-card {
        width: 100%;
        max-width: 480px; /* Perfect width for a login form */
        background: var(--panel, #ffffff);
        border: 1px solid var(--rule, #e5e7eb);
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* slightly deeper shadow for a floating effect */
        overflow: hidden;
    }

    .login-header {
        padding: 32px 32px 24px;
        border-bottom: 1px solid var(--rule, #f3f4f6);
        text-align: center;
    }

    .login-title {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 700;
        color: var(--bright, #111827);
        letter-spacing: -0.01em;
    }

    .login-subtitle {
        color: var(--muted, #6b7280);
        font-size: 13px;
        line-height: 1.5;
    }

    .login-body {
        padding: 32px;
    }

    .field-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--body, #374151);
    }

    /* Standardizing Inputs to match the main app */
    .input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--rule, #d1d5db);
        border-radius: 8px;
        background-color: var(--surface, #f9fafb);
        color: var(--bright, #111827);
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .input:hover {
        border-color: #9ca3af;
    }
    .input:focus {
        outline: none;
        border-color: var(--accent, #3b82f6);
        background-color: var(--panel);
        box-shadow: 0 0 0 3px var(--accent-bg, rgba(59, 130, 246, 0.15));
    }

    .btn-submit {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        background-color: var(--accent, #2563eb);
        color: white;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
        margin-top: 8px;
    }
    .btn-submit:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }

    .security-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 24px;
        color: var(--muted);
        font-size: 12px;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        
        <div class="login-header">
            <div style="font-size:11px; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--muted); margin-bottom:12px;">
                Secure Login
            </div>
            <h2 class="login-title">NMDPRA Incident Registry</h2>
            <div class="login-subtitle">
                Authenticate to submit and review 24-hour incident reports.
            </div>
        </div>

        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 24px; border-radius: 8px;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input id="email" name="email" type="email" class="input" required autofocus value="{{ old('email') }}" placeholder="name@organisation.com" />
                </div>

                <div class="field-group">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="input" required placeholder="••••••••" />
                </div>

                <button type="submit" class="btn-submit">
                    Sign In
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>

                <div class="security-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Session-based access • RBAC enforced
                </div>
            </form>
        </div>

    </div>
</div>

@endsection