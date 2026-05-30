@extends('layouts.app')
@section('title', 'Incident Details - NMDPRA')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-blue-900">{{ $incident->incident_ref }}</h2>
            <p class="text-gray-600">{{ $incident->organisation->org_name ?? 'Unknown Organisation' }}</p>
        </div>
        <div class="text-right">
            <span class="px-3 py-1 rounded text-sm {{ $incident->severity === 'fatal' ? 'bg-red-100 text-red-800' : ($incident->severity === 'critical' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">{{ strtoupper($incident->severity) }}</span>
            <div class="mt-2 text-sm {{ $incident->is_compliant ? 'text-green-600' : 'text-red-600' }}">Compliance: {{ $incident->is_compliant ? 'COMPLIANT' : 'NON-COMPLIANT' }}</div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="font-bold mb-2">Incident Details</h3>
            <table class="w-full text-sm">
                <tr class="border-b"><td class="py-2 text-gray-600">Type</td><td class="py-2 font-medium">{{ $incident->incident_type }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-600">Status</td><td class="py-2 font-medium">{{ $incident->status }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-600">Location</td><td class="py-2 font-medium">{{ $incident->location_address }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-600">Occurred</td><td class="py-2 font-medium">{{ $incident->occurred_at->format('d M Y H:i') }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-600">Reported</td><td class="py-2 font-medium">{{ $incident->reported_at->format('d M Y H:i') }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-600">Deadline</td><td class="py-2 font-medium {{ $incident->is_compliant ? 'text-green-600' : 'text-red-600' }}">{{ $incident->compliance_deadline_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div>
        <div>
            <h3 class="font-bold mb-2">Casualties & Damages</h3>
            <table class="w-full text-sm">
                <tr class="border-b"><td class="py-2 text-gray-600">Fatalities</td><td class="py-2 font-medium text-red-600">{{ $incident->casualty->fatalities_count ?? 0 }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-600">Injuries</td><td class="py-2 font-medium">{{ $incident->casualty->injuries_count ?? 0 }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-600">Environmental Damage</td><td class="py-2 font-medium">${{ number_format($incident->casualty->environmental_damage_est_usd ?? 0, 2) }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-600">Property Damage</td><td class="py-2 font-medium">${{ number_format($incident->casualty->property_damage_est_usd ?? 0, 2) }}</td></tr>
            </table>
        </div>
    </div>
    
    <div class="mb-6">
        <h3 class="font-bold mb-2">Description</h3>
        <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $incident->description }}</p>
    </div>
    
    @if($incident->latitude && $incident->longitude)
    <div class="mb-6">
        <h3 class="font-bold mb-2">Geolocation</h3>
        <div id="map" style="height: 300px;"></div>
        <script>
            var map = L.map('map').setView([{{ $incident->latitude }}, {{ $incident->longitude }}], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            L.marker([{{ $incident->latitude }}, {{ $incident->longitude }}]).addTo(map).bindPopup('{{ $incident->incident_ref }}');
        </script>
    </div>
    @endif
    
    <a href="{{ route('incidents.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back to List</a>
</div>
@endsection
