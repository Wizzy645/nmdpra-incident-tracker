@extends('layouts.app')
@section('title', 'National Regulatory Dashboard - NMDPRA')
@section('content')

<style>
    /* ── Page-scoped overrides ──────────────────────────────────────────
       Modern grid layouts and soft-card styling for the dashboard.
    ──────────────────────────────────────────────────────────────────── */
    .dashboard-header {
        margin-bottom: 24px;
    }

    /* Stat Cards Grid */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--surface, #141719);
        border: 1px solid var(--rule, #252c33);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.15);
    }

    .stat-label {
        color: var(--muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        font-family: 'IBM Plex Mono', monospace;
        line-height: 1;
    }

    /* Main Content Grid (Map + Table) */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr; /* Stacks the table under the map on smaller screens */
        }
    }

    /* Map container softening */
    .map-container {
        height: 460px; 
        width: 100%; 
        border-radius: 8px; 
        overflow: hidden; /* Prevents map tiles from breaking the rounded corners */
        border: 1px solid var(--rule);
    }
    
    .table-container {
        overflow-x: auto;
    }
</style>

<div class="dashboard-header">
    <div class="panel" style="padding: 24px 30px; border-radius: 12px;">
        <p class="panel-title" style="margin-bottom: 8px;">National Regulatory Dashboard</p>
        <h2 style="margin: 0 0 8px; font-size: 24px; color: var(--bright); font-weight: 700;">
            Incident Oversight • {{ date('Y-m-d') }}
        </h2>
        <div style="color: var(--muted); font-size: 14px; line-height: 1.5;">
            Downstream incident reporting status, compliance flags, and geospatial distribution.
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Incidents</div>
        <div class="stat-value" style="color: var(--bright);">
            {{ $stats['total_incidents'] }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Fatal Incidents</div>
        <div class="stat-value" style="color: var(--danger);">
            {{ $stats['fatal_incidents'] }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Under Investigation</div>
        <div class="stat-value" style="color: var(--warn);">
            {{ $stats['pending_investigation'] }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Non-Compliant (24h)</div>
        <div class="stat-value" style="color: var(--danger);">
            {{ $stats['non_compliant'] }}
        </div>
    </div>
</div>

<div class="content-grid">
    <div class="panel">
        <div class="panel-header">
            <p class="panel-title">Geospatial Distribution</p>
            <span style="color: var(--muted); font-size: 12px;">CartoDB dark tiles • Severity markers</span>
        </div>
        <div style="padding: 16px;">
            <div id="map" class="map-container"></div>
        </div>
    </div>

    <div class="panel" style="display: flex; flex-direction: column;">
        <div class="panel-header">
            <p class="panel-title">Recent Incidents</p>
            <span style="color: var(--muted); font-size: 12px;">Latest 10</span>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Type</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Reported</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_incidents as $incident)
                        <tr>
                            <td>
                                <a class="ref-link" href="{{ route('incidents.show', $incident) }}">{{ $incident->incident_ref }}</a>
                            </td>
                            <td style="color: var(--bright); font-weight: 500;">{{ ucwords(str_replace('_', ' ', $incident->incident_type)) }}</td>
                            <td>
                                @php
                                    $sev = $incident->severity;
                                @endphp
                                <span class="badge
                                    {{ $sev === 'fatal' ? 'badge-fatal' : '' }}
                                    {{ $sev === 'critical' ? 'badge-critical' : '' }}
                                    {{ $sev === 'high' ? 'badge-high' : '' }}
                                    {{ in_array($sev, ['low','medium']) ? 'badge-status' : '' }}
                                ">
                                    {{ $sev }}
                                </span>
                            </td>
                            <td style="color: var(--body);">{{ str_replace('_', ' ', $incident->status) }}</td>
                            <td style="color: var(--muted); font-size: 12px;">
                                {{ optional($incident->reported_at)->diffForHumans() ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="color: var(--muted); padding: 32px 16px; text-align: center;">
                                No incidents recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function() {
        // Adjusted the map initialization to slightly zoom out or adjust position if necessary
        var map = L.map('map', { zoomControl: true }).setView([9.082, 8.675], 6);

        // CartoDB dark tiles for publication-quality dark UI
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; Carto'
        }).addTo(map);

        @foreach($recent_incidents as $incident)
            @if($incident->latitude && $incident->longitude)
                (function(){
                    var lat = {{ $incident->latitude }};
                    var lng = {{ $incident->longitude }};
                    var sev = '{{ $incident->severity }}';

                    // Updated hex colors to match the new modern variables in app.blade.php
                    var color = '#8b9eb0'; // Default muted blue-grey
                    if (sev === 'fatal') color = '#ef4444'; // Modern Danger
                    else if (sev === 'critical') color = '#f59e0b'; // Modern Warn
                    else if (sev === 'high') color = '#f59e0b'; // Modern Warn

                    L.circleMarker([lat, lng], {
                        radius: 8, // Slightly larger for better visibility
                        fillColor: color,
                        color: '#141719', // Match the surface color for a nice border punch-out effect
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.85
                    }).addTo(map).bindPopup(
                        '<div style="min-width:180px; padding: 4px;">' +
                        '<div style="color: var(--bright); font-size:14px; font-weight:700; font-family:\'IBM Plex Mono\', monospace; margin-bottom: 4px;">{{ $incident->incident_ref }}</div>' +
                        '<div style="color: var(--body); font-size:13px; font-weight:500;">{{ ucwords(str_replace("_", " ", $incident->incident_type)) }}</div>' +
                        '<div style="color: var(--muted); margin-top:8px; font-size:12px; line-height: 1.4;">{{ $incident->location_address }}</div>' +
                        '</div>'
                    );
                })();
            @endif
        @endforeach
    })();
</script>

@endsection