@extends('layouts.app')
@section('title', 'Incident Registry - NMDPRA')
@section('content')

<style>
    /* ── Page-scoped styles ─────────────────────────────────────────────
       All tokens (--panel, --rule, --muted, etc.) come from app.blade.php.
    ──────────────────────────────────────────────────────────────────── */

    /* Page Header */
    .page-header-container {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap; /* Allows the button to wrap on very small screens */
        gap: 16px;
    }
    .page-subtitle {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 8px;
    }
    .page-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--bright);
        letter-spacing: -0.01em;
        margin-bottom: 8px;
    }
    .page-description {
        font-size: 13px;
        color: var(--muted);
        line-height: 1.5;
    }

    /* Table Panel & Responsive Wrapper */
    .table-panel {
        background: var(--panel);
        border: 1px solid var(--rule);
        border-radius: 6px;
        overflow: hidden;
    }
    .table-responsive {
        width: 100%;
        overflow-x: auto; /* Enables horizontal scrolling on small devices */
        -webkit-overflow-scrolling: touch;
    }

    .registry-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px; /* Ensures columns don't crush together before scrolling kicks in */
    }

    .registry-table th {
        padding: 12px 18px;
        font-size: 11px; /* Increased for readability */
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--muted);
        border-bottom: 1px solid var(--rule);
        text-align: left;
        white-space: nowrap;
        background: var(--surface);
    }

    .registry-table td {
        padding: 14px 18px;
        border-bottom: 1px solid var(--rule-dim);
        font-size: 13px; /* Increased for readability */
        color: var(--body);
        vertical-align: middle;
    }

    .registry-table tbody tr:hover td {
        background: rgba(255, 255, 255, 0.03); /* Slightly more visible hover state */
    }

    .registry-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Specific Column Utilities */
    .col-ref {
        font-family: 'IBM Plex Mono', monospace, ui-monospace, SFMono-Regular;
        font-size: 12px;
        font-weight: 500;
        color: var(--accent);
        text-decoration: none;
        letter-spacing: -0.01em;
    }
    .col-ref:hover {
        text-decoration: underline;
    }
    .col-org {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .col-date {
        font-family: 'IBM Plex Mono', monospace, ui-monospace, SFMono-Regular;
        font-size: 11px;
        color: var(--muted);
        white-space: nowrap;
    }

    /* Footer & Pagination */
    .table-foot {
        padding: 14px 18px;
        border-top: 1px solid var(--rule);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--surface);
        flex-wrap: wrap;
        gap: 12px;
    }

    .record-count {
        font-size: 12px;
        color: var(--muted);
        font-family: 'IBM Plex Mono', monospace, ui-monospace, SFMono-Regular;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        font-size: 14px;
        color: var(--muted);
    }
</style>

{{-- ── Page header ─────────────────────────────────────────── --}}
<div class="page-header-container">
    <div>
        <div class="page-subtitle">National Registry</div>
        <div class="page-title">Incident Registry</div>
        <div class="page-description">
            Centralised database of reported incidents and compliance status.
        </div>
    </div>

    @if(!in_array(Auth::user()->role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor']))
<a href="{{ route('incidents.create') }}" class="btn-primary" style="
    display: inline-flex; 
    align-items: center; 
    gap: 8px; 
    white-space: nowrap; 
    padding: 10px 20px; 
    border-radius: 8px; 
    font-weight: 600; 
    text-decoration: none; 
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    transition: all 0.2s ease-in-out;
">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
    </svg>
    Report Incident
</a>
    @endif
</div>

{{-- ── Table panel ─────────────────────────────────────────── --}}
<div class="table-panel">
    
    <div class="table-responsive">
        <table class="registry-table">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Organisation</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Reported</th>
                    <th>Compliance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $incident)
                <tr>

                    {{-- Reference --}}
                    <td>
                        <a href="{{ route('incidents.show', $incident) }}" class="col-ref">
                            {{ $incident->incident_ref }}
                        </a>
                    </td>

                    {{-- Organisation --}}
                    <td class="col-org" title="{{ $incident->organisation->org_name ?? 'N/A' }}">
                        {{ $incident->organisation->org_name ?? 'N/A' }}
                    </td>

                    {{-- Type --}}
                    <td>{{ ucwords(str_replace('_', ' ', $incident->incident_type)) }}</td>

                    {{-- Severity --}}
                    <td>
                        @php
                            $sevClass = match($incident->severity) {
                                'fatal'    => 'badge-fatal',
                                'critical' => 'badge-critical',
                                'high'     => 'badge-high',
                                'medium'   => 'badge-medium',
                                default    => 'badge-low',
                            };
                        @endphp
                        <span class="badge {{ $sevClass }}">{{ $incident->severity }}</span>
                    </td>

                    {{-- Status --}}
                    <td>
                        @php
                            $statusClass = match($incident->status) {
                                'resolved'            => 'badge-resolved',
                                'under_investigation' => 'badge-investigating',
                                'closed'              => 'badge-closed',
                                default               => 'badge-pending',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">
                            {{ str_replace('_', ' ', $incident->status) }}
                        </span>
                    </td>

                    {{-- Reported --}}
                    <td class="col-date">
                        {{ $incident->reported_at->format('d M Y · H:i') }}
                    </td>

                    {{-- Compliance --}}
                    <td>
                        <span class="badge {{ $incident->is_compliant ? 'badge-compliant' : 'badge-non-compliant' }}">
                            {{ $incident->is_compliant ? 'Compliant' : 'Overdue' }}
                        </span>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        No incidents have been registered in the system.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-foot">
        <span class="record-count">
            {{ $incidents->firstItem() ?? 0 }}–{{ $incidents->lastItem() ?? 0 }} of {{ $incidents->total() }}
        </span>
        <div class="pagination-wrapper">
            {{ $incidents->links() }}
        </div>
    </div>

</div>

@endsection