@extends('layouts.app')
@section('title', 'Incident Details - NMDPRA')
@section('content')

@php
    $casualty = $incident->casualty;
    $fatalities    = $casualty->fatalities_count          ?? 0;
    $injuries      = $casualty->injuries_count            ?? 0;
    $environmental = $casualty->environmental_damage_est_usd ?? 0;
    $propertyDamage = $casualty->property_damage_est_usd  ?? 0;
    $evidence = is_array($incident->media_evidence)
        ? $incident->media_evidence
        : (($incident->media_evidence && is_string($incident->media_evidence))
            ? [$incident->media_evidence]
            : null);
@endphp

<style>
    .show-wrap { max-width: 860px; }

    /* ── Header strip ─────────────────────────────── */
    .incident-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        padding-bottom: 18px;
        border-bottom: 1px solid var(--rule);
        margin-bottom: 22px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        font-size: 10px;
        color: var(--muted);
        letter-spacing: 0.04em;
    }
    .breadcrumb a { color: var(--muted); text-decoration: none; }
    .breadcrumb a:hover { color: var(--body); }
    .breadcrumb-sep { color: var(--rule); }

    .incident-ref {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 22px;
        font-weight: 600;
        color: var(--bright);
        letter-spacing: -0.02em;
        margin-bottom: 4px;
    }

    .incident-org {
        font-size: 12px;
        color: var(--muted);
    }

    .header-badges {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 7px;
        flex-shrink: 0;
    }

    /* ── Non-compliance alert ─────────────────────── */
    .violation-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: var(--danger-bg);
        border-left: 4px solid var(--danger);
        padding: 14px 16px;
        margin-bottom: 22px;
    }
    .violation-alert svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; stroke: var(--danger); }
    .violation-title {
        font-size: 11px; font-weight: 600;
        color: #e07878; letter-spacing: 0.03em; margin-bottom: 3px;
    }
    .violation-body { font-size: 11px; color: var(--muted); }
    .violation-body .mono { color: #e07878; }

    /* ── Two-column body layout ───────────────────── */
    .body-grid {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 16px;
        align-items: start;
    }

    /* ── Panel ────────────────────────────────────── */
    .panel { background: var(--panel); border: 1px solid var(--rule); }
    .panel-head {
        padding: 11px 16px;
        border-bottom: 1px solid var(--rule);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .panel-title {
        font-size: 9px; font-weight: 600;
        letter-spacing: 0.10em; text-transform: uppercase; color: var(--muted);
    }

    /* ── Key-value grid ───────────────────────────── */
    .kv-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1px;
        background: var(--rule);
        border: 1px solid var(--rule);
        margin-bottom: 16px;
    }
    .kv-cell { background: var(--panel); padding: 11px 14px; }
    .kv-cell.span-2 { grid-column: 1 / -1; }
    .kv-key {
        font-size: 9px; font-weight: 600;
        letter-spacing: 0.10em; text-transform: uppercase;
        color: var(--muted); margin-bottom: 4px;
    }
    .kv-val {
        font-size: 13px; color: var(--bright); font-weight: 400;
    }

    /* ── Description block ────────────────────────── */
    .description-block {
        padding: 16px;
        font-size: 13px;
        color: var(--body);
        line-height: 1.7;
        white-space: pre-wrap;
    }

    /* ── Impact column ────────────────────────────── */
    .impact-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        padding: 13px 16px;
        border-bottom: 1px solid var(--rule-dim);
    }
    .impact-row:last-child { border-bottom: none; }
    .impact-label { font-size: 11px; color: var(--muted); }
    .impact-val {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 18px; font-weight: 600; line-height: 1;
    }

    /* ── Evidence block ───────────────────────────── */
    .evidence-empty {
        padding: 14px 16px;
        font-size: 12px; color: var(--muted);
    }
    .evidence-list {
        list-style: none;
        padding: 0;
    }
    .evidence-item {
        padding: 10px 16px;
        border-bottom: 1px solid var(--rule-dim);
        font-size: 11px; color: var(--body);
        word-break: break-all;
        font-family: 'IBM Plex Mono', monospace;
    }
    .evidence-item:last-child { border-bottom: none; }

    /* ── Map ──────────────────────────────────────── */
    #map { height: 260px; width: 100%; display: block; }

    /* ── Footer ───────────────────────────────────── */
    .page-foot {
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid var(--rule);
    }
</style>

<div class="show-wrap">

    {{-- ── Header ──────────────────────────────────── --}}
    <div class="incident-header">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('incidents.index') }}">Incident Registry</a>
                <span class="breadcrumb-sep">/</span>
                <span class="mono" style="color: var(--accent);">{{ $incident->incident_ref }}</span>
            </div>
            <div class="incident-ref">{{ $incident->incident_ref }}</div>
            <div class="incident-org">
                {{ $incident->organisation->org_name ?? 'Unknown Organisation' }}
            </div>
        </div>

        <div class="header-badges">
            {{-- Severity --}}
            @php
                $sevClass = match($incident->severity) {
                    'fatal'    => 'badge-fatal',
                    'critical' => 'badge-critical',
                    'high'     => 'badge-high',
                    'medium'   => 'badge-medium',
                    default    => 'badge-low',
                };
            @endphp
            <span class="badge {{ $sevClass }}" style="padding: 4px 10px; font-size: 10px;">
                {{ strtoupper($incident->severity) }}
            </span>

            {{-- Compliance --}}
            <span class="badge {{ $incident->is_compliant ? 'badge-compliant' : 'badge-non-compliant' }}"
                  style="padding: 4px 10px; font-size: 10px;">
                {{ $incident->is_compliant ? 'COMPLIANT' : 'NON-COMPLIANT' }}
            </span>

            {{-- Status --}}
            @php
                $statusClass = match($incident->status) {
                    'resolved'            => 'badge-resolved',
                    'under_investigation' => 'badge-investigating',
                    'pending'             => 'badge-pending',
                    default               => 'badge-closed',
                };
            @endphp
            <span class="badge {{ $statusClass }}" style="padding: 4px 10px; font-size: 10px;">
                {{ strtoupper(str_replace('_', ' ', $incident->status)) }}
            </span>
        </div>
    </div>

    {{-- ── Non-compliance notice ───────────────────── --}}
    @if(!$incident->is_compliant)
    <div class="violation-alert">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <div class="violation-title">Regulatory violation — reporting deadline exceeded</div>
            <div class="violation-body">
                Deadline was
                <span class="mono">{{ $incident->compliance_deadline_at->format('d M Y, H:i') }}</span>
                (24 h after occurrence). This incident is subject to regulatory sanction under the Safety Regulations 2023.
            </div>
        </div>
    </div>
    @endif

    {{-- ── Body grid ───────────────────────────────── --}}
    <div class="body-grid">

        {{-- LEFT COLUMN --}}
        <div style="display: flex; flex-direction: column; gap: 16px;">

            {{-- Incident record --}}
            <div class="kv-grid">
                <div class="kv-cell">
                    <div class="kv-key">Type</div>
                    <div class="kv-val">{{ ucwords(str_replace('_', ' ', $incident->incident_type)) }}</div>
                </div>
                <div class="kv-cell">
                    <div class="kv-key">Status</div>
                    <div class="kv-val">{{ ucwords(str_replace('_', ' ', $incident->status)) }}</div>
                </div>
                <div class="kv-cell">
                    <div class="kv-key">Occurred</div>
                    <div class="kv-val mono" style="font-size: 12px;">{{ $incident->occurred_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="kv-cell">
                    <div class="kv-key">Reported</div>
                    <div class="kv-val mono" style="font-size: 12px;">{{ $incident->reported_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="kv-cell">
                    <div class="kv-key">Compliance deadline</div>
                    <div class="kv-val mono" style="font-size: 12px; color: {{ $incident->is_compliant ? '#4ec88a' : '#e07878' }};">
                        {{ $incident->compliance_deadline_at->format('d M Y, H:i') }}
                    </div>
                </div>
                <div class="kv-cell">
                    <div class="kv-key">Reporter</div>
                    <div class="kv-val">{{ $incident->reporter->name ?? 'Unknown' }}</div>
                </div>
                <div class="kv-cell span-2">
                    <div class="kv-key">Site address</div>
                    <div class="kv-val">{{ $incident->location_address }}</div>
                </div>
            </div>

            {{-- Description --}}
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-title">Narrative account</span>
                </div>
                <div class="description-block">{{ $incident->description }}</div>
            </div>

            {{-- Map --}}
            @if($incident->latitude && $incident->longitude)
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-title">Site location</span>
                    <span class="mono" style="font-size: 10px; color: var(--muted);">
                        {{ $incident->latitude }}, {{ $incident->longitude }}
                    </span>
                </div>
                <div id="map"></div>
            </div>

            <script>
                var map = L.map('map').setView([{{ $incident->latitude }}, {{ $incident->longitude }}], 12);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
                }).addTo(map);

                var markerColor = '{{ $incident->severity === "fatal" ? "#bf3535" : ($incident->severity === "critical" ? "#d95f1a" : ($incident->severity === "high" ? "#b88218" : "#56636f")) }}';

                L.circleMarker([{{ $incident->latitude }}, {{ $incident->longitude }}], {
                    radius: 10,
                    fillColor: markerColor,
                    color: '#0d0f10',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.9
                }).addTo(map).bindPopup('<div style="font-family:IBM Plex Sans,sans-serif;font-size:12px;color:#e8edf2;background:#1c2025;padding:10px 12px;"><div style="font-family:IBM Plex Mono,monospace;font-size:11px;color:#d95f1a;font-weight:600;margin-bottom:4px;">{{ $incident->incident_ref }}</div><div>{{ $incident->incident_type }}</div></div>');

                var s = document.createElement('style');
                s.textContent = '.leaflet-popup-content-wrapper{background:#1c2025;border:1px solid #252c33;border-radius:0;box-shadow:0 4px 16px rgba(0,0,0,0.5);padding:0}.leaflet-popup-content{margin:0}.leaflet-popup-tip{background:#1c2025}';
                document.head.appendChild(s);
            </script>
            @endif

            {{-- Evidence --}}
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-title">Evidence / Media</span>
                </div>
                @if(!empty($evidence))
                    <ul class="evidence-list">
                        @foreach($evidence as $item)
                            <li class="evidence-item">{{ $item }}</li>
                        @endforeach
                    </ul>
                @else
                    <div class="evidence-empty">No evidence uploaded for this incident.</div>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div style="display: flex; flex-direction: column; gap: 16px;">

            {{-- Impact assessment --}}
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-title">Impact assessment</span>
                </div>
                <div class="impact-row">
                    <span class="impact-label">Fatalities</span>
                    <span class="impact-val" style="color: {{ $fatalities > 0 ? '#e07878' : 'var(--muted)' }};">
                        {{ $fatalities }}
                    </span>
                </div>
                <div class="impact-row">
                    <span class="impact-label">Injuries</span>
                    <span class="impact-val" style="color: {{ $injuries > 0 ? '#d4a040' : 'var(--muted)' }};">
                        {{ $injuries }}
                    </span>
                </div>
                <div class="impact-row">
                    <span class="impact-label">Environmental (USD)</span>
                    <span class="impact-val" style="font-size: 13px; color: var(--body);">
                        ${{ number_format($environmental, 2) }}
                    </span>
                </div>
                <div class="impact-row">
                    <span class="impact-label">Property (USD)</span>
                    <span class="impact-val" style="font-size: 13px; color: var(--body);">
                        ${{ number_format($propertyDamage, 2) }}
                    </span>
                </div>
            </div>

        </div>

    </div>

    {{-- ── Footer nav ───────────────────────────────── --}}
    <div class="page-foot">
        <a href="{{ route('incidents.index') }}" class="btn-ghost">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:13px;height:13px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to List
        </a>
    </div>

</div>

@endsection