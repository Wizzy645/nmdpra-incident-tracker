@extends('layouts.app')
@section('title', 'Admin Dashboard - NMDPRA')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-900">National Regulatory Dashboard</h2>
    <p class="text-gray-600">Real-time oversight of downstream petroleum incidents</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow"><div class="text-3xl font-bold text-blue-900">{{ $stats['total_incidents'] }}</div><div class="text-gray-600">Total Incidents</div></div>
    <div class="bg-white p-4 rounded-lg shadow"><div class="text-3xl font-bold text-red-600">{{ $stats['fatal_incidents'] }}</div><div class="text-gray-600">Fatal Incidents</div></div>
    <div class="bg-white p-4 rounded-lg shadow"><div class="text-3xl font-bold text-yellow-600">{{ $stats['pending_investigation'] }}</div><div class="text-gray-600">Under Investigation</div></div>
    <div class="bg-white p-4 rounded-lg shadow"><div class="text-3xl font-bold text-orange-600">{{ $stats['non_compliant'] }}</div><div class="text-gray-600">Non-Compliant (24h)</div></div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-bold mb-4">Incident Heatmap</h3>
        <div id="map" style="height: 400px;"></div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-bold mb-4">Recent Incidents</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="p-2 text-left">Ref</th><th class="p-2 text-left">Type</th><th class="p-2 text-left">Severity</th><th class="p-2 text-left">Status</th><th class="p-2 text-left">Reported</th></tr></thead>
            <tbody>
                @foreach($recent_incidents as $incident)
                <tr class="border-t">
                    <td class="p-2 font-mono text-xs">{{ $incident->incident_ref }}</td>
                    <td class="p-2">{{ $incident->incident_type }}</td>
                    <td class="p-2"><span class="px-2 py-1 rounded text-xs {{ $incident->severity === 'fatal' ? 'bg-red-100 text-red-800' : ($incident->severity === 'critical' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">{{ $incident->severity }}</span></td>
                    <td class="p-2">{{ $incident->status }}</td>
                    <td class="p-2 text-xs">{{ $incident->reported_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    var map = L.map('map').setView([9.082, 8.675], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    @foreach($recent_incidents as $incident)
        @if($incident->latitude && $incident->longitude)
            L.marker([{{ $incident->latitude }}, {{ $incident->longitude }}]).addTo(map).bindPopup('<b>{{ $incident->incident_ref }}</b><br>{{ $incident->incident_type }}');
        @endif
    @endforeach
</script>
@endsection
