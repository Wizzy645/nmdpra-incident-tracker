@extends('layouts.app')
@section('title', 'Report Incident - NMDPRA')
@section('content')

<style>
    /* ── Page-scoped overrides ──────────────────────────────────────────
       Modern, modular card layout with strict grid alignment.
    ──────────────────────────────────────────────────────────────────── */

    .form-wrap {
        max-width: 760px; 
        margin: 0 auto; 
        padding: 40px 20px;
    }

    /* Page Header */
    .page-header { margin-bottom: 32px; }
    .page-subtitle {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--muted, #6b7280);
        margin-bottom: 8px;
    }
    .page-title {
        font-size: 24px; 
        font-weight: 700;
        color: var(--bright, #111827);
        letter-spacing: -0.01em;
        margin-bottom: 8px;
    }
    .page-description {
        font-size: 14px;
        color: var(--muted, #4b5563);
        line-height: 1.5;
    }

    /* Form Container & Discrete Cards */
    .form-container {
        display: flex;
        flex-direction: column;
        gap: 24px; /* Space between the separate cards */
    }
    .form-card {
        background: var(--panel, #ffffff);
        border: 1px solid var(--rule, #e5e7eb);
        border-radius: 12px; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); 
        padding: 32px 30px; 
    }

    .section-label {
        font-size: 12px; 
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--muted, #6b7280);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid var(--rule, #f3f4f6);
        padding-bottom: 12px;
    }

    /* Responsive Grid Layouts */
    .field-row {
        display: grid;
        gap: 20px; 
        /* Ensures row items stretch to match the tallest item in the row */
        align-items: stretch; 
    }
    .field-row-2 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
    .field-row-3 { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }

    /* Individual field & Input overrides */
    .field { 
        display: flex; 
        flex-direction: column; 
        height: 100%;
        /* Pushes the input to the bottom so boxes perfectly align even if labels vary in height */
        justify-content: flex-end; 
    }
    .form-label {
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--body, #374151);
    }

    /* Standardizing Inputs */
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--rule, #d1d5db);
        border-radius: 8px; 
        background-color: var(--surface, #f9fafb);
        color: var(--body, #1f2937);
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s ease;
        box-sizing: border-box;
        margin-top: auto; /* Forces input to the bottom of the flex container */
    }
    .form-input:hover, .form-select:hover, .form-textarea:hover {
        border-color: #9ca3af;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--accent, #3b82f6);
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); 
    }
    .form-textarea {
        resize: vertical;
    }

    .field + .field-row,
    .field-row + .field,
    .field + .field {
        margin-top: 20px;
    }

    /* Validation error block */
    .error-block {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        padding: 16px;
        margin-bottom: 24px;
        font-size: 13px;
        color: #b91c1c;
        border-radius: 0 8px 8px 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .error-block svg {
        width: 18px; height: 18px;
        flex-shrink: 0; margin-top: 1px;
    }
    .error-block ul { 
        list-style: none; 
        margin: 0; 
        padding: 0; 
    }
    .error-block ul li + li { margin-top: 6px; }

    /* Action row & Buttons */
    .form-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 24px 0; /* Removed background to blend with page */
    }
    
    .btn-submit {
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        padding: 10px 20px; 
        border-radius: 8px; 
        background-color: var(--accent, #2563eb);
        color: white;
        font-weight: 600; 
        font-size: 14px;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2); 
        transition: all 0.2s ease-in-out;
    }
    .btn-submit:hover {
        background-color: #1d4ed8;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
    }
    
    .btn-cancel {
        padding: 10px 20px;
        border-radius: 8px;
        color: var(--muted, #4b5563);
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    .btn-cancel:hover {
        background-color: #e5e7eb;
        color: #111827;
    }
</style>

<div class="form-wrap">

    {{-- Page header --}}
    <div class="page-header">
        <div class="page-subtitle">Incident Registry</div>
        <div class="page-title">Report an Incident</div>
        <div class="page-description">
            Submit accurate incident details to comply with 24-hour major-accident reporting requirements.
        </div>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="error-block">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Container --}}
    <form method="POST" action="{{ route('incidents.store') }}" class="form-container">
        @csrf

        {{-- Card 1: Classification --}}
        <div class="form-card">
            <div class="section-label">01 &nbsp;— &nbsp;Classification</div>

            <div class="field-row field-row-2">
                <div class="field">
                    <label class="form-label" for="incident_type">
                        Incident type <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="incident_type" name="incident_type" class="form-select" required>
                        <option value="">Select type…</option>
                        <option value="vandalism"          {{ old('incident_type') === 'vandalism'          ? 'selected' : '' }}>Vandalism</option>
                        <option value="sabotage"           {{ old('incident_type') === 'sabotage'           ? 'selected' : '' }}>Sabotage</option>
                        <option value="mechanical_failure" {{ old('incident_type') === 'mechanical_failure' ? 'selected' : '' }}>Mechanical Failure</option>
                        <option value="fire"               {{ old('incident_type') === 'fire'               ? 'selected' : '' }}>Fire</option>
                        <option value="explosion"          {{ old('incident_type') === 'explosion'          ? 'selected' : '' }}>Explosion</option>
                        <option value="spill"              {{ old('incident_type') === 'spill'              ? 'selected' : '' }}>Oil Spill</option>
                        <option value="theft"              {{ old('incident_type') === 'theft'              ? 'selected' : '' }}>Theft</option>
                        <option value="other"              {{ old('incident_type') === 'other'              ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="field">
                    <label class="form-label" for="severity">
                        Severity <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="severity" name="severity" class="form-select" required>
                        <option value="">Select severity…</option>
                        <option value="low"      {{ old('severity') === 'low'      ? 'selected' : '' }}>Low</option>
                        <option value="medium"   {{ old('severity') === 'medium'   ? 'selected' : '' }}>Medium</option>
                        <option value="high"     {{ old('severity') === 'high'     ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical (24h)</option>
                        <option value="fatal"    {{ old('severity') === 'fatal'    ? 'selected' : '' }}>Fatal (24h)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Card 2: Description --}}
        <div class="form-card">
            <div class="section-label">02 &nbsp;— &nbsp;Description</div>

            <div class="field">
                <label class="form-label" for="description">
                    Narrative account <span style="color: #ef4444;">*</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    class="form-textarea"
                    rows="5"
                    required
                    minlength="20"
                    placeholder="Detailed description of what happened, including affected systems and immediate response…"
                >{{ old('description') }}</textarea>
                <div style="margin-top: 8px; font-size: 12px; color: var(--muted, #6b7280);">Minimum 20 characters.</div>
            </div>
        </div>

        {{-- Card 3: Location --}}
        <div class="form-card">
            <div class="section-label">03 &nbsp;— &nbsp;Location</div>

            <div class="field">
                <label class="form-label" for="location_address">
                    Site address <span style="color: #ef4444;">*</span>
                </label>
                <input
                    id="location_address"
                    type="text"
                    name="location_address"
                    class="form-input"
                    required
                    value="{{ old('location_address') }}"
                    placeholder="e.g. Port Harcourt Refinery, Rivers State"
                >
            </div>

            <div class="field-row field-row-2">
                <div class="field">
                    <label class="form-label" for="latitude">Latitude</label>
                    <input
                        id="latitude"
                        type="number"
                        step="any"
                        name="latitude"
                        class="form-input"
                        style="font-family: monospace;"
                        value="{{ old('latitude') }}"
                        placeholder="e.g. 4.8156"
                    >
                </div>

                <div class="field">
                    <label class="form-label" for="longitude">Longitude</label>
                    <input
                        id="longitude"
                        type="number"
                        step="any"
                        name="longitude"
                        class="form-input"
                        style="font-family: monospace;"
                        value="{{ old('longitude') }}"
                        placeholder="e.g. 7.0498"
                    >
                </div>
            </div>
        </div>

        {{-- Card 4: Date and time --}}
        <div class="form-card">
            <div class="section-label">04 &nbsp;— &nbsp;Date &amp; Time</div>

            <div class="field">
                <label class="form-label" for="occurred_at">
                    Date / time of occurrence <span style="color: #ef4444;">*</span>
                </label>
                <div style="max-width: 320px;">
                    <input
                        id="occurred_at"
                        type="datetime-local"
                        name="occurred_at"
                        class="form-input"
                        required
                        max="{{ now()->format('Y-m-d\TH:i') }}"
                        value="{{ old('occurred_at') }}"
                    >
                </div>
                <div style="margin-top: 8px; font-size: 12px; color: var(--muted, #6b7280);">West Africa Time (WAT, UTC+1).</div>
            </div>
        </div>

        {{-- Card 5: Impact --}}
        <div class="form-card">
            <div class="section-label">05 &nbsp;— &nbsp;Impact Assessment</div>

            <div class="field-row field-row-3">
                <div class="field">
                    <label class="form-label" for="fatalities_count">Fatalities</label>
                    <input
                        id="fatalities_count"
                        type="number"
                        name="fatalities_count"
                        class="form-input"
                        min="0"
                        value="{{ old('fatalities_count', 0) }}"
                    >
                </div>

                <div class="field">
                    <label class="form-label" for="injuries_count">Injuries</label>
                    <input
                        id="injuries_count"
                        type="number"
                        name="injuries_count"
                        class="form-input"
                        min="0"
                        value="{{ old('injuries_count', 0) }}"
                    >
                </div>

                <div class="field">
                    <label class="form-label" for="environmental_damage_est_usd">Environmental Damage (USD)</label>
                    <input
                        id="environmental_damage_est_usd"
                        type="number"
                        step="0.01"
                        name="environmental_damage_est_usd"
                        class="form-input"
                        min="0"
                        value="{{ old('environmental_damage_est_usd', 0) }}"
                    >
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Submit Incident
            </button>
            <a href="{{ route('incidents.index') }}" class="btn-cancel">Cancel</a>
        </div>

    </form>

</div>

@endsection