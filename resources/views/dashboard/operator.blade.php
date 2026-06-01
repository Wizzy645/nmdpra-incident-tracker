@extends('layouts.app')
@section('title', 'Operator Dashboard - NMDPRA')
@section('content')

<style>
    /* ── Page-scoped overrides ──────────────────────────────────────────
       Modern grid layouts and soft-card styling for the operator dashboard.
    ──────────────────────────────────────────────────────────────────── */
    .dashboard-header {
        margin-bottom: 24px;
    }

    /* Stat Cards Grid */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--surface, #141719);
        border: 1px solid var(--rule, #252c33);
        border-radius: 12px;
        padding: 24px;
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
        font-size: 36px; /* Slightly larger since there are only two cards here */
        font-weight: 700;
        font-family: 'IBM Plex Mono', monospace;
        line-height: 1;
    }

    .table-container {
        overflow-x: auto;
    }
    
    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--rule);
        background: var(--surface);
    }
</style>

{{-- Header Section --}}
<div class="dashboard-header">
    <div class="panel" style="padding: 24px 30px;">
        <p class="panel-title" style="margin-bottom: 8px;">Operator Dashboard</p>
        <h2 style="margin: 0 0 8px; font-size: 24px; color: var(--bright); font-weight: 700;">
            Your Organisation • {{ Auth::user()->organisation?->org_name ?? '—' }}
        </h2>
        <div style="color: var(--muted); font-size: 14px;">
            Licence: <span style="font-family: 'IBM Plex Mono', monospace;">{{ Auth::user()->organisation?->licence_number ?? '—' }}</span>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Incidents</div>
        <div class="stat-value" style="color: var(--bright);">
            {{ $stats['total_incidents'] }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending Reports</div>
        <div class="stat-value" style="color: var(--warn);">
            {{ $stats['pending'] }}
        </div>
    </div>
</div>

{{-- Main Table Panel --}}
<div class="panel">
    <div class="panel-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <p class="panel-title" style="margin: 0 0 4px;">Your Incidents</p>
            <div style="color: var(--muted); font-size: 12px;">
                Track compliance and status updates.
            </div>
        </div>

        <a href="{{ route('incidents.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; white-space: nowrap;">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Report New Incident
        </a>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Compliance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $incident)
                    <tr>
                        <td>
                            <a class="ref-link" href="{{ route('incidents.show', $incident) }}">{{ $incident->incident_ref }}</a>
                        </td>
                        <td style="color: var(--bright); font-weight: 500;">{{ ucwords(str_replace('_', ' ', $incident->incident_type)) }}</td>
                        <td>
                            <span class="badge
                                {{ $incident->severity === 'fatal' ? 'badge-fatal' : '' }}
                                {{ $incident->severity === 'critical' ? 'badge-critical' : '' }}
                                {{ $incident->severity === 'high' ? 'badge-high' : '' }}
                                {{ in_array($incident->severity, ['low','medium']) ? 'badge-status' : '' }}
                            ">
                                {{ $incident->severity }}
                            </span>
                        </td>
                        <td style="color: var(--body);">{{ str_replace('_', ' ', $incident->status) }}</td>
                        <td>
                            @if($incident->compliance_deadline_at)
                                {{-- Updated to use the global badge-compliant / badge-non-compliant classes --}}
                                <span class="badge {{ $incident->is_compliant ? 'badge-compliant' : 'badge-non-compliant' }}">
                                    {{ $incident->is_compliant ? 'COMPLIANT' : 'OVERDUE' }}
                                </span>
                                <div style="color: var(--muted); font-family: 'IBM Plex Mono', monospace; font-size: 11px; margin-top: 6px;">
                                    {{ $incident->compliance_deadline_at->diffForHumans() }}
                                </div>
                            @else
                                <span class="badge badge-status">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="color: var(--muted); padding: 32px 16px; text-align: center;">
                            No incidents reported yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Wrapper --}}
    @if($incidents->hasPages())
        <div class="pagination-wrapper">
            {{ $incidents->links() }}
        </div>
    @endif
</div>

@endsection